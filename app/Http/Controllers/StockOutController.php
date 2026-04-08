<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOut;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\IssueType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StockOutController extends Controller
{
    // แสดงรายการเบิกทั้งหมด
    public function index(Request $request)
    {
        $query = StockOut::with('product')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('trans_id', 'like', "%{$s}%")
                  ->orWhere('reference_doc', 'like', "%{$s}%")
                  ->orWhere('reference_no', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('note', 'like', "%{$s}%")
                  ->orWhereHas('product', function ($pq) use ($s) {
                      $pq->where('name', 'like', "%{$s}%")
                        ->orWhere('product_id', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->where('issued_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('issued_date', '<=', $request->date_to);
        }

        // Filter ช่วงสินค้า
        if ($request->filled('product_from') && $request->filled('product_to')) {
            $query->whereBetween('product_id', [$request->product_from, $request->product_to]);
        } elseif ($request->filled('product_from')) {
            $query->where('product_id', '>=', $request->product_from);
        } elseif ($request->filled('product_to')) {
            $query->where('product_id', '<=', $request->product_to);
        }

        // Export PDF
        if ($request->has('export_pdf')) {
            $stockOutsAll = $query->get();
            return $this->exportPdf($stockOutsAll, $request);
        }

        // Export Excel
        if ($request->has('export_excel')) {
            $stockOutsAll = $query->get();
            return $this->exportExcel($stockOutsAll, $request);
        }

        $stockOuts = $query->paginate(20);
        $products = Product::orderBy('product_id')->get();
        return view('stock_outs.index', compact('stockOuts', 'products'));
    }

    private function exportPdf($stockOuts, $request)
    {
        $mpdf = new Mpdf([
            'default_font' => 'garuda',
            'default_font_size' => 10,
            'orientation' => 'L',
        ]);

        $reportType = $request->report_type ?? 'normal';

        $html = view('stock_outs.report_pdf', [
            'stockOuts' => $stockOuts,
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
            'productFrom' => $request->product_from,
            'productTo' => $request->product_to,
            'reportType' => $reportType,
        ])->render();

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="รายงานเบิกสินค้า_' . now()->format('Ymd_His') . '.pdf"',
        ]);
    }

    private function exportExcel($stockOuts, $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $reportType = $request->report_type ?? 'normal';

        // รวบรวม items ทั้งหมด
        $allItems = collect();
        foreach ($stockOuts as $s) {
            $allItems->push([
                'issued_date' => $s->issued_date,
                'note' => $s->note ?? $s->id,
                'product_id' => $s->product->product_id ?? '-',
                'product_name' => $s->product->name ?? '-',
                'size' => $s->product->size ?? '-',
                'pack' => $s->product->pack ?? '-',
                'quantity' => $s->quantity,
                'fraction_qty' => $s->fraction_qty ?? 0,
            ]);
        }

        // Title
        $titleText = 'รายงานเบิกสินค้า';
        if ($reportType == 'by_size') $titleText .= ' (จัดกลุ่มตาม Size)';
        elseif ($reportType == 'by_pack') $titleText .= ' (จัดกลุ่มตาม Pack)';

        $sheet->mergeCells('A1:H1');
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
            $sheet->mergeCells('A2:H2');
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
                $sheet->mergeCells("A{$row}:H{$row}");
                $sheet->setCellValue("A{$row}", "{$groupLabel}: {$groupKey} ({$items->count()} รายการ)");
                $sheet->getStyle("A{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']],
                ]);
                $row++;

                // Headers
                $headers = ['ลำดับ', 'วันที่', 'รหัสสินค้า', 'ชื่อสินค้า', $otherLabel, 'Kg/ctn', 'Kg/Inner', 'หมายเหตุ'];
                foreach ($headers as $i => $header) {
                    $col = chr(65 + $i);
                    $sheet->setCellValue("{$col}{$row}", $header);
                }
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
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
                    $sheet->setCellValue("B{$row}", $item['issued_date']);
                    $sheet->setCellValue("C{$row}", $item['product_id']);
                    $sheet->setCellValue("D{$row}", $item['product_name']);
                    $sheet->setCellValue("E{$row}", $item[$otherField]);
                    $sheet->setCellValue("F{$row}", $item['quantity']);
                    $sheet->setCellValue("G{$row}", $item['fraction_qty']);
                    $sheet->setCellValue("H{$row}", $item['note'] ?? '-');
                    $row++;
                }

                // Group summary
                $sheet->mergeCells("A{$row}:E{$row}");
                $sheet->setCellValue("A{$row}", "รวม {$groupLabel} {$groupKey}:");
                $sheet->setCellValue("F{$row}", $items->sum('quantity'));
                $sheet->setCellValue("G{$row}", $items->sum('fraction_qty'));
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF3C7']],
                ]);

                // Borders for data section
                $sheet->getStyle("A{$startDataRow}:H{$row}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                $row += 2; // Space between groups
            }

            // Grand total
            $sheet->mergeCells("A{$row}:E{$row}");
            $sheet->setCellValue("A{$row}", "รวมทั้งหมด ({$allItems->count()} รายการ):");
            $sheet->setCellValue("F{$row}", $allItems->sum('quantity'));
            $sheet->setCellValue("G{$row}", $allItems->sum('fraction_qty'));
            $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);

        } else {
            // Normal format
            $headers = ['ลำดับ', 'วันที่', 'เลขที่เบิก', 'รหัสสินค้า', 'ชื่อสินค้า', 'Size', 'Pack', 'Kg/ctn', 'Kg/Inner', 'หมายเหตุ'];
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
            foreach ($stockOuts as $s) {
                $sheet->setCellValue("A{$row}", $i++);
                $sheet->setCellValue("B{$row}", $s->issued_date);
                $sheet->setCellValue("C{$row}", $s->note ?? $s->id);
                $sheet->setCellValue("D{$row}", $s->product->product_id ?? '-');
                $sheet->setCellValue("E{$row}", $s->product->name ?? '-');
                $sheet->setCellValue("F{$row}", $s->product->size ?? '-');
                $sheet->setCellValue("G{$row}", $s->product->pack ?? '-');
                $sheet->setCellValue("H{$row}", $s->quantity);
                $sheet->setCellValue("I{$row}", $s->fraction_qty ?? 0);
                $sheet->setCellValue("J{$row}", $s->note ?? '-');
                $row++;
            }

            $lastRow = $row - 1;
            if ($lastRow >= $startRow) {
                $sheet->getStyle("A{$startRow}:{$lastCol}{$lastRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);
            }
        }

        // Auto width
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'รายงานเบิกสินค้า_' . now()->format('Ymd_His') . '.xlsx';

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // ฟอร์มบันทึกการเบิก
    public function create()
    {
        $products = Product::all();
        $transactions = Transaction::with('items.product')->orderBy('trans_date', 'desc')->orderBy('trans_id', 'desc')->get();
        $issueTypes = IssueType::all();
        return view('stock_outs.create', compact('products', 'transactions', 'issueTypes'));
    }

    // บันทึกข้อมูลการเบิก
    public function store(Request $request)
    {

        // ✅ ตรวจสอบว่ามีสินค้าในฟอร์มหรือไม่
        if (!$request->has('items') || count($request->items) === 0) {
            return back()->withErrors(['msg' => 'กรุณาระบุจำนวนเบิกอย่างน้อย 1 รายการ'])->withInput();
        }

        // ✅ ตรวจสอบความถูกต้องของข้อมูลแต่ละแถว
        $request->validate([
            'issued_date' => 'required|date',
            'note'        => 'nullable|string',
            'items.*.product_id'   => 'required|exists:products,product_id',
            'items.*.quantity'     => 'nullable|integer|min:0',
            'items.*.fraction_qty' => 'nullable|integer|min:0',
        ]);

        // ✅ ตรวจสอบ code ว่ามีอยู่จริงใน TransactionItem หรือไม่
        foreach ($request->items as $index => $item) {
            $code = $item['code'] ?? null;
            $fullQtyReq = (int)($item['quantity'] ?? 0);
            $fractionReq = (int)($item['fraction_qty'] ?? 0);

            // ข้ามรายการที่ไม่ได้เบิก
            if ($fullQtyReq <= 0 && $fractionReq <= 0) {
                continue;
            }

            // ถ้ามีการระบุ code ต้องตรวจสอบว่ามีอยู่จริง
            if ($code) {
                $existingCode = TransactionItem::where('product_id', $item['product_id'])
                    ->where('code', $code)
                    ->first();

                if (!$existingCode) {
                    return back()->withErrors([
                        "รายการที่ " . ($index + 1) . ": Code '{$code}' ไม่มีอยู่ในระบบสำหรับสินค้ารหัส {$item['product_id']}"
                    ])->withInput();
                }

                // ตรวจสอบว่า code นั้นยังมีของคงเหลือหรือไม่
                if ($existingCode->full_qty < $fullQtyReq || $existingCode->fraction_qty < $fractionReq) {
                    return back()->withErrors([
                        "รายการที่ " . ($index + 1) . ": Code '{$code}' มีสินค้าคงเหลือไม่เพียงพอ (เต็ม: {$existingCode->full_qty}, เศษ: {$existingCode->fraction_qty})"
                    ])->withInput();
                }
            }
        }

        // ✅ วนลูปแต่ละรายการสินค้า
        foreach ($request->items as $item) {
            $fullQtyReq = (int)($item['quantity'] ?? 0);
            $fractionReq = (int)($item['fraction_qty'] ?? 0);

            // ข้ามรายการที่ไม่ได้เบิก
            if ($fullQtyReq <= 0 && $fractionReq <= 0) {
                continue;
            }

            // ✅ บันทึกลงตาราง stock_outs (ไม่ลด TransactionItem เพราะคำนวณจาก รับเข้า - เบิกออก)
            StockOut::create([
                'product_id'    => $item['product_id'],
                'issue_type_id' => $request->issue_type_id,
                'code'          => $item['code'] ?? null,
                'trans_id'      => $request->trans_id, // บันทึก Source Transaction ID
                'reference_doc' => $request->reference_doc,
                'reference_no'  => $request->reference_no,
                'quantity'      => $fullQtyReq,
                'fraction_qty'  => $fractionReq,
                'user_id'       => Auth::id(),
                'issued_to'     => $request->issued_to,
                'issued_date'   => $request->issued_date,
                'note'          => $request->note,
            ]);

            // ✅ ลด current_stock ของสินค้า (ภาพรวม)
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->current_stock -= $fullQtyReq;
                $product->save();
            }
        }

        return redirect()->route('stock-outs.index')->with('success', 'บันทึกการเบิกสินค้าเรียบร้อยแล้ว');
    }

    // ดูรายละเอียดการเบิก
    public function show(StockOut $stockOut)
    {
        $stockOut->load('product');
        return view('stock_outs.show', compact('stockOut'));
    }

    // แก้ไขข้อมูลการเบิก (แบบกลุ่มบิล)
    public function edit(StockOut $stockOut)
    {
        // ถ้ามี trans_id (ระบบใหม่) ให้ดึงเพื่อนมาด้วย
        if ($stockOut->trans_id) {
            $siblings = StockOut::with('product')
                ->where('trans_id', $stockOut->trans_id)
                ->where('note', $stockOut->note)
                ->whereDate('created_at', $stockOut->created_at->toDateString())
                ->get();
        } else {
            // ระบบเก่า หรือไม่มี trans_id -> แก้ไขรายการเดียว
            $stockOut->load('product');
            $siblings = collect([$stockOut]);
        }

        // กรณีหาไม่เจอ (เผื่อไว้)
        if ($siblings->isEmpty()) {
            $stockOut->load('product');
            $siblings = collect([$stockOut]);
        }

        $products = Product::all();
        $transactions = Transaction::with('items.product')->orderBy('trans_date', 'desc')->orderBy('trans_id', 'desc')->get(); // โหลดรายการเพื่อใช้ใน JS
        $issueTypes = IssueType::all();

        return view('stock_outs.edit', compact('stockOut', 'siblings', 'products', 'transactions', 'issueTypes'));
    }

    // อัปเดตข้อมูลการเบิก (แบบ Batch)
    public function update(Request $request, StockOut $stockOut)
    {
        $request->validate([
            'issued_date' => 'required|date',
            'note'        => 'nullable|string',
            'items'       => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity'   => 'nullable|integer|min:0',
            'items.*.fraction_qty'=> 'nullable|integer|min:0',
        ]);

        $oldTransId = $stockOut->trans_id;

        // 1. ค้นหารายการเดิมทั้งหมด (Siblings)
        $siblings = StockOut::where('trans_id', $oldTransId)
            ->where('note', $stockOut->note)
            ->where('reference_no', $stockOut->reference_no)
            ->where('reference_doc', $stockOut->reference_doc)
            ->whereDate('created_at', $stockOut->created_at->toDateString())
            ->get();
        // Map siblings by ID for easy lookup
        $existingItems = $siblings->keyBy('id');
        $processedIds = [];

        // 2. วนลูปรายการที่ส่งมา (New & Updated)
        foreach ($request->items as $itemData) {
            $fullQtyReq = (int)($itemData['quantity'] ?? 0);
            $fractionReq = (int)($itemData['fraction_qty'] ?? 0);

            // ข้ามถ้ายอดเป็น 0
            if ($fullQtyReq <= 0 && $fractionReq <= 0) continue;

            $existingId = $itemData['id'] ?? null;

            if ($existingId && isset($existingItems[$existingId])) {
                // --- CASE: Update Existing ---
                $record = $existingItems[$existingId];
                $processedIds[] = $existingId;

                $diffFull = $fullQtyReq - $record->quantity;

                // จัดการ Product Master Stock (ไม่ต้องจัดการ TransactionItem)
                if ($diffFull != 0) {
                    $prod = Product::find($record->product_id);
                    if ($prod) {
                        $prod->current_stock -= $diffFull;
                        $prod->save();
                    }
                }

                // Update record fields
                $record->update([
                    'issue_type_id' => $request->issue_type_id,
                    'reference_doc' => $request->reference_doc,
                    'reference_no'  => $request->reference_no,
                    'issued_date'   => $request->issued_date,
                    'note'          => $request->note,
                    'quantity'      => $fullQtyReq,
                    'fraction_qty'  => $fractionReq,
                ]);

            } else {
                // --- CASE: New Item (ไม่ต้องตัด TransactionItem) ---
                StockOut::create([
                    'product_id'    => $itemData['product_id'],
                    'issue_type_id' => $request->issue_type_id,
                    'code'          => $itemData['code'] ?? null,
                    'trans_id'      => $request->trans_id,
                    'reference_doc' => $request->reference_doc,
                    'reference_no'  => $request->reference_no,
                    'quantity'      => $fullQtyReq,
                    'fraction_qty'  => $fractionReq,
                    'user_id'       => Auth::id(),
                    'issued_date'   => $request->issued_date,
                    'note'          => $request->note,
                ]);

                // Deduct Master Stock
                $prod = Product::find($itemData['product_id']);
                if ($prod) {
                    $prod->current_stock -= $fullQtyReq;
                    $prod->save();
                }
            }
        }

        // 3. ลบรายการที่หายไป (Deleted) — คืนเฉพาะ current_stock (ไม่แตะ TransactionItem)
        foreach ($existingItems as $id => $record) {
            if (!in_array($id, $processedIds)) {
                $prod = Product::find($record->product_id);
                if ($prod) {
                    $prod->current_stock += $record->quantity;
                    $prod->save();
                }

                $record->delete();
            }
        }

        return redirect()->route('stock-outs.index')->with('success', 'แก้ไขข้อมูลการเบิกเรียบร้อยแล้ว');
    }

    // ลบการเบิก (คืนเฉพาะ current_stock — ไม่แตะ TransactionItem เพราะไม่ได้ลดตอนเบิก)
    public function destroy(StockOut $stockOut)
    {
        // คืน current_stock กลับ
        $product = Product::find($stockOut->product_id);
        if ($product) {
            $product->current_stock += $stockOut->quantity;
            $product->save();
        }

        $stockOut->delete();
        return redirect()->route('stock-outs.index')->with('success', 'ลบประวัติการเบิกและคืนสต๊อกสำเร็จ');
    }

    // ตรวจสอบ code ว่ามีอยู่จริงหรือไม่
    public function checkCode(Request $request)
    {
        $code = $request->input('code');
        $productId = $request->input('product_id');

        if (!$code || !$productId) {
            return response()->json(['exists' => false, 'message' => 'กรุณาระบุ code และ product_id']);
        }

        $item = TransactionItem::where('product_id', $productId)
            ->where('code', $code)
            ->first();

        if (!$item) {
            return response()->json([
                'exists' => false,
                'message' => "Code '{$code}' ไม่มีอยู่ในระบบสำหรับสินค้ารหัส {$productId}"
            ]);
        }

        return response()->json([
            'exists' => true,
            'full_qty' => $item->full_qty,
            'fraction_qty' => $item->fraction_qty,
            'message' => "Code '{$code}' พบในระบบ (คงเหลือ: เต็ม {$item->full_qty}, เศษ {$item->fraction_qty})"
        ]);
    }
}
