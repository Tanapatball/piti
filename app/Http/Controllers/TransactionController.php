<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\ReceiveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['items.product', 'receiveType'])
            ->orderBy('trans_date', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('trans_id', 'like', "%{$s}%")
                  ->orWhere('reference_no', 'like', "%{$s}%")
                  ->orWhere('reference_doc', 'like', "%{$s}%")
                  ->orWhere('note', 'like', "%{$s}%");
            });
        }

        if ($request->filled('receive_type')) {
            $query->where('receive_type_id', $request->receive_type);
        }

        if ($request->filled('date_from')) {
            $query->where('trans_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('trans_date', '<=', $request->date_to);
        }

        // Filter ช่วงสินค้า
        if ($request->filled('product_from') || $request->filled('product_to')) {
            $productFrom = $request->product_from;
            $productTo = $request->product_to;

            $query->whereHas('items', function ($q) use ($productFrom, $productTo) {
                if ($productFrom && $productTo) {
                    $q->whereBetween('product_id', [$productFrom, $productTo]);
                } elseif ($productFrom) {
                    $q->where('product_id', '>=', $productFrom);
                } elseif ($productTo) {
                    $q->where('product_id', '<=', $productTo);
                }
            });
        }

        $transactions = $query->get();

        // Filter items ตามช่วงสินค้า (สำหรับแสดงผลและ export)
        if ($request->filled('product_from') || $request->filled('product_to')) {
            $productFrom = $request->product_from;
            $productTo = $request->product_to;

            $transactions = $transactions->map(function ($transaction) use ($productFrom, $productTo) {
                $filteredItems = $transaction->items->filter(function ($item) use ($productFrom, $productTo) {
                    if ($productFrom && $productTo) {
                        return $item->product_id >= $productFrom && $item->product_id <= $productTo;
                    } elseif ($productFrom) {
                        return $item->product_id >= $productFrom;
                    } elseif ($productTo) {
                        return $item->product_id <= $productTo;
                    }
                    return true;
                });
                $transaction->setRelation('items', $filteredItems);
                return $transaction;
            });
        }

        // Export PDF
        if ($request->has('export_pdf')) {
            return $this->exportPdf($transactions, $request);
        }

        // Export Excel
        if ($request->has('export_excel')) {
            return $this->exportExcel($transactions, $request);
        }

        $receiveTypes = ReceiveType::all();
        $products = Product::orderBy('product_id')->get();
        return view('transactions.index', compact('transactions', 'receiveTypes', 'products'));
    }

    private function exportPdf($transactions, $request)
    {
        $mpdf = new Mpdf([
            'default_font' => 'garuda',
            'default_font_size' => 10,
            'orientation' => 'L',
        ]);

        $reportType = $request->report_type ?? 'normal';

        $html = view('transactions.report_pdf', [
            'transactions' => $transactions,
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
            'productFrom' => $request->product_from,
            'productTo' => $request->product_to,
            'reportType' => $reportType,
        ])->render();

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="รายงานรับสินค้า_' . now()->format('Ymd_His') . '.pdf"',
        ]);
    }

    private function exportExcel($transactions, $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $reportType = $request->report_type ?? 'normal';

        // รวบรวม items ทั้งหมด
        $allItems = collect();
        foreach ($transactions as $t) {
            foreach ($t->items as $item) {
                $allItems->push([
                    'trans_date' => $t->trans_date,
                    'trans_id' => $t->trans_id,
                    'receive_type' => $t->receiveType->name ?? '-',
                    'product_id' => $item->product->product_id ?? '-',
                    'product_name' => $item->product->name ?? '-',
                    'size' => $item->product->size ?? '-',
                    'pack' => $item->product->pack ?? '-',
                    'full_qty' => $item->full_qty,
                    'fraction_qty' => $item->fraction_qty,
                    'net_weight' => $item->net_weight,
                ]);
            }
        }

        // Title
        $titleText = 'รายงานรับสินค้า';
        if ($reportType == 'by_size') $titleText .= ' (จัดกลุ่มตาม Size)';
        elseif ($reportType == 'by_pack') $titleText .= ' (จัดกลุ่มตาม Pack)';

        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', $titleText);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Filter info
        $filterInfo = '';
        if ($request->date_from) $filterInfo .= "ตั้งแต่วันที่: {$request->date_from} ";
        if ($request->date_to) $filterInfo .= "ถึงวันที่: {$request->date_to} ";
        if ($request->product_from) $filterInfo .= "สินค้าเริ่ม: {$request->product_from} ";
        if ($request->product_to) $filterInfo .= "สินค้าสิ้นสุด: {$request->product_to} ";

        if ($filterInfo) {
            $sheet->mergeCells('A2:J2');
            $sheet->setCellValue('A2', $filterInfo);
        }

        $row = 4;

        if ($reportType == 'by_size' || $reportType == 'by_pack') {
            // จัดกลุ่มตาม Size หรือ Pack
            $groupField = $reportType == 'by_size' ? 'size' : 'pack';
            $otherField = $reportType == 'by_size' ? 'pack' : 'size';
            $groupLabel = $reportType == 'by_size' ? 'Size' : 'Pack';
            $otherLabel = $reportType == 'by_size' ? 'Pack' : 'Size';

            $groupedItems = $allItems->groupBy($groupField)->sortKeys();

            foreach ($groupedItems as $groupKey => $items) {
                // Group header
                $sheet->mergeCells("A{$row}:J{$row}");
                $sheet->setCellValue("A{$row}", "{$groupLabel}: {$groupKey} ({$items->count()} รายการ)");
                $sheet->getStyle("A{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']],
                ]);
                $row++;

                // Headers
                $headers = ['ลำดับ', 'วันที่', 'เลขที่เอกสาร', 'รหัสสินค้า', 'ชื่อสินค้า', $otherLabel, 'Kg/ctn', 'Kg/Inner', 'น้ำหนัก(kg)'];
                foreach ($headers as $i => $header) {
                    $col = chr(65 + $i);
                    $sheet->setCellValue("{$col}{$row}", $header);
                }
                $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);
                $row++;

                // Data rows
                $startDataRow = $row;
                $i = 1;
                foreach ($items as $item) {
                    $sheet->setCellValue("A{$row}", $i++);
                    $sheet->setCellValue("B{$row}", $item['trans_date']);
                    $sheet->setCellValue("C{$row}", $item['trans_id']);
                    $sheet->setCellValue("D{$row}", $item['product_id']);
                    $sheet->setCellValue("E{$row}", $item['product_name']);
                    $sheet->setCellValue("F{$row}", $item[$otherField]);
                    $sheet->setCellValue("G{$row}", $item['full_qty']);
                    $sheet->setCellValue("H{$row}", $item['fraction_qty']);
                    $sheet->setCellValue("I{$row}", $item['net_weight']);
                    $row++;
                }

                // Group summary
                $sheet->mergeCells("A{$row}:F{$row}");
                $sheet->setCellValue("A{$row}", "รวม {$groupLabel} {$groupKey}:");
                $sheet->setCellValue("G{$row}", $items->sum('full_qty'));
                $sheet->setCellValue("H{$row}", $items->sum('fraction_qty'));
                $sheet->setCellValue("I{$row}", $items->sum('net_weight'));
                $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF3C7']],
                ]);

                // Borders for data section
                $sheet->getStyle("A{$startDataRow}:I{$row}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                $row += 2; // Space between groups
            }

            // Grand total
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->setCellValue("A{$row}", "รวมทั้งหมด ({$allItems->count()} รายการ):");
            $sheet->setCellValue("G{$row}", $allItems->sum('full_qty'));
            $sheet->setCellValue("H{$row}", $allItems->sum('fraction_qty'));
            $sheet->setCellValue("I{$row}", $allItems->sum('net_weight'));
            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);

        } else {
            // Normal format
            $headers = ['ลำดับ', 'วันที่', 'เลขที่เอกสาร', 'ประเภท', 'รหัสสินค้า', 'ชื่อสินค้า', 'Size', 'Pack', 'Kg/ctn', 'Kg/Inner', 'น้ำหนัก(kg)'];
            $startRow = $row;
            foreach ($headers as $i => $header) {
                $col = chr(65 + $i);
                $sheet->setCellValue("{$col}{$startRow}", $header);
            }
            $lastCol = chr(64 + count($headers));
            $sheet->getStyle("A{$startRow}:{$lastCol}{$startRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);

            $row = $startRow + 1;
            $i = 1;
            foreach ($transactions as $t) {
                foreach ($t->items as $item) {
                    $sheet->setCellValue("A{$row}", $i++);
                    $sheet->setCellValue("B{$row}", $t->trans_date);
                    $sheet->setCellValue("C{$row}", $t->trans_id);
                    $sheet->setCellValue("D{$row}", $t->receiveType->name ?? '-');
                    $sheet->setCellValue("E{$row}", $item->product->product_id ?? '-');
                    $sheet->setCellValue("F{$row}", $item->product->name ?? '-');
                    $sheet->setCellValue("G{$row}", $item->product->size ?? '-');
                    $sheet->setCellValue("H{$row}", $item->product->pack ?? '-');
                    $sheet->setCellValue("I{$row}", $item->full_qty);
                    $sheet->setCellValue("J{$row}", $item->fraction_qty);
                    $sheet->setCellValue("K{$row}", $item->net_weight);
                    $row++;
                }
            }

            $lastRow = $row - 1;
            if ($lastRow >= $startRow) {
                $sheet->getStyle("A{$startRow}:{$lastCol}{$lastRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);
            }
        }

        // Auto width
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'รายงานรับสินค้า_' . now()->format('Ymd_His') . '.xlsx';

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function create()
    {
        $products = Product::all();
        $receiveTypes = ReceiveType::all();
        return view('transactions.create', compact('products', 'receiveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trans_id' => 'required|unique:transactions,trans_id',
            'trans_date' => 'required|date',
            'receive_type_id' => 'nullable|exists:receive_types,receive_type_id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.full_qty' => 'nullable|integer|min:0',
            'items.*.fraction_qty' => 'nullable|integer|min:0',
            'items.*.net_weight' => 'nullable|numeric|min:0',
        ]);

        $transaction = Transaction::create([
            'trans_id' => $request->trans_id,
            'trans_date' => $request->trans_date,
            'reference_doc' => $request->doc_ref,
            'reference_no' => $request->ref_no,
            'receive_type_id' => $request->receive_type_id,
            'note' => $request->note,
        ]);

        foreach ($request->items as $item) {
            TransactionItem::create([
                'trans_id' => $transaction->trans_id,
                'product_id' => $item['product_id'],
                'code' => $item['code'] ?? null,
                'item_code' => $item['item_code'] ?? null,
                'full_qty' => $item['full_qty'] ?? 0,
                'fraction_qty' => $item['fraction_qty'] ?? 0,
                'net_weight' => $item['net_weight'] ?? 0,
            ]);

            // อัปเดต current_stock ของสินค้า
            $product = Product::find($item['product_id']);
            if ($product) {
                $fullQty = (int)($item['full_qty'] ?? 0);
                $product->current_stock += $fullQty;
                $product->save();
            }
        }

        return redirect()->route('transactions.index')->with('success', 'รับสินค้าสำเร็จ');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['items.product', 'receiveType']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $transaction->load('items');
        $products = Product::all();
        $receiveTypes = ReceiveType::all();
        return view('transactions.edit', compact('transaction', 'products', 'receiveTypes'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $oldTransId = $transaction->trans_id;
        $newTransId = $request->trans_id ?? $oldTransId;

        // Validate trans_id uniqueness only if changed
        $rules = [
            'trans_date' => 'required|date',
            'receive_type_id' => 'nullable|exists:receive_types,receive_type_id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.full_qty' => 'nullable|integer|min:0',
            'items.*.fraction_qty' => 'nullable|integer|min:0',
            'items.*.net_weight' => 'nullable|numeric|min:0',
        ];

        if ($newTransId !== $oldTransId) {
            $rules['trans_id'] = 'required|unique:transactions,trans_id';
        }

        $request->validate($rules);

        // คืน current_stock จากรายการเดิมก่อน
        foreach ($transaction->items as $oldItem) {
            $product = Product::find($oldItem->product_id);
            if ($product) {
                $product->current_stock -= $oldItem->full_qty;
                $product->save();
            }
        }

        // ลบ items เดิม
        $transaction->items()->delete();

        // อัปเดตข้อมูล transaction (รวม trans_id ถ้าเปลี่ยน)
        if ($newTransId !== $oldTransId) {
            // ต้อง update ผ่าน DB query เพราะเป็น primary key
            DB::table('transactions')->where('trans_id', $oldTransId)->update([
                'trans_id' => $newTransId,
                'trans_date' => $request->trans_date,
                'reference_doc' => $request->doc_ref,
                'reference_no' => $request->ref_no,
                'receive_type_id' => $request->receive_type_id,
                'note' => $request->note,
                'updated_at' => now(),
            ]);
        } else {
            $transaction->update([
                'trans_date' => $request->trans_date,
                'reference_doc' => $request->doc_ref,
                'reference_no' => $request->ref_no,
                'receive_type_id' => $request->receive_type_id,
                'note' => $request->note,
            ]);
        }

        $finalTransId = $newTransId;

        foreach ($request->items as $item) {
            TransactionItem::create([
                'trans_id' => $finalTransId,
                'product_id' => $item['product_id'],
                'code' => $item['code'] ?? null,
                'item_code' => $item['item_code'] ?? null,
                'full_qty' => $item['full_qty'] ?? 0,
                'fraction_qty' => $item['fraction_qty'] ?? 0,
                'net_weight' => $item['net_weight'] ?? 0,
            ]);

            // เพิ่ม current_stock จากรายการใหม่
            $product = Product::find($item['product_id']);
            if ($product) {
                $fullQty = (int)($item['full_qty'] ?? 0);
                $product->current_stock += $fullQty;
                $product->save();
            }
        }

        return redirect()->route('transactions.index')->with('success', 'อัปเดตรายการรับสินค้าสำเร็จ');
    }

    public function destroy(Transaction $transaction)
    {
        // คืน current_stock ก่อนลบ
        foreach ($transaction->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->current_stock -= $item->full_qty;
                $product->save();
            }
        }

        $transaction->items()->delete();
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'ลบรายการรับสินค้าสำเร็จ');
    }
}
