<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ReceiveType;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportController extends Controller
{
    private function createMpdf($orientation = 'P'): Mpdf
    {
        return new Mpdf([
            'default_font' => 'garuda',
            'default_font_size' => 12,
            'orientation' => $orientation,
        ]);
    }

    private function exportPdf($viewName, $data, $filename, $orientation = 'P')
    {
        $mpdf = $this->createMpdf($orientation);
        $html = view($viewName, $data)->render();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '_' . now()->format('Ymd_His') . '.pdf"',
        ]);
    }

    private function exportExcel(array $headers, array $rows, string $filename, string $title = '')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $startRow = 1;

        // Title row
        if ($title) {
            $lastCol = chr(64 + count($headers)); // A, B, C...
            $sheet->mergeCells("A1:{$lastCol}1");
            $sheet->setCellValue('A1', $title);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $startRow = 3;
        }

        // Headers
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

        // Data rows
        $dataStart = $startRow + 1;
        foreach ($rows as $ri => $row) {
            foreach ($row as $ci => $value) {
                $col = chr(65 + $ci);
                $sheet->setCellValue("{$col}" . ($dataStart + $ri), $value);
            }
        }

        // Borders + auto-width
        $lastRow = $dataStart + count($rows) - 1;
        if (count($rows) > 0) {
            $sheet->getStyle("A{$startRow}:{$lastCol}{$lastRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
        }
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output
        $writer = new Xlsx($spreadsheet);
        $fullFilename = $filename . '_' . now()->format('Ymd_His') . '.xlsx';

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fullFilename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // =============================================
    // 1. เมนูหลัก
    // =============================================

    // 1a. ประเภทจัดเก็บ
    public function mainReceiveTypes(Request $request)
    {
        $receiveTypes = ReceiveType::withCount('transactions')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.main.receive_types_pdf', compact('receiveTypes'), 'ประเภทจัดเก็บ');
        }

        if ($request->has('export_excel')) {
            $rows = $receiveTypes->map(fn($t, $i) => [$i+1, $t->receive_type_id, $t->name, $t->transactions_count])->toArray();
            return $this->exportExcel(['ลำดับ', 'รหัส', 'ชื่อประเภท', 'จำนวนรายการ'], $rows, 'ประเภทจัดเก็บ', 'รายงานประเภทจัดเก็บ');
        }

        return view('reports.main.receive_types', compact('receiveTypes'));
    }

    // 1b. หมวดสินค้า
    public function mainCategories(Request $request)
    {
        $categories = Category::withCount('products')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.main.categories_pdf', compact('categories'), 'หมวดสินค้า');
        }

        if ($request->has('export_excel')) {
            $rows = $categories->map(fn($c, $i) => [$i+1, $c->category_id, $c->category_name, $c->products_count])->toArray();
            return $this->exportExcel(['ลำดับ', 'รหัสหมวด', 'ชื่อหมวด', 'จำนวนสินค้า'], $rows, 'หมวดสินค้า', 'รายงานหมวดสินค้า');
        }

        return view('reports.main.categories', compact('categories'));
    }

    // 1c. รายละเอียดสินค้า
    public function mainProducts(Request $request)
    {
        $products = Product::with('category')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.main.products_pdf', compact('products'), 'รายละเอียดสินค้า', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = $products->map(fn($p, $i) => [$i+1, $p->product_id, $p->name, $p->category->category_name ?? '-', $p->size ?? '-', $p->pack ?? '-', $p->weight_per_kg ?? '-', $p->weight_total ?? '-', $p->stock_min, $p->stock_max, $p->current_stock])->toArray();
            return $this->exportExcel(['ลำดับ', 'รหัสสินค้า', 'ชื่อสินค้า', 'หมวด', 'Size', 'Pack', 'น้ำหนัก/กก.', 'น้ำหนักรวม', 'สต็อกต่ำสุด', 'สต็อกสูงสุด', 'คงเหลือ'], $rows, 'รายละเอียดสินค้า', 'รายงานรายละเอียดสินค้า');
        }

        return view('reports.main.products', compact('products'));
    }

    // =============================================
    // 2. รายละเอียดสินค้า
    // =============================================

    // 2a. แสดงสินค้าแยกตามหมวด
    public function productsByCategory(Request $request)
    {
        $categories = Category::with('products')->get();
        $selectedCategory = $request->input('category_id');

        if ($selectedCategory) {
            $categories = Category::with('products')->where('category_id', $selectedCategory)->get();
        }

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.products.by_category_pdf', compact('categories', 'selectedCategory'), 'สินค้าแยกตามหมวด', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = []; $i = 1;
            foreach ($categories as $cat) {
                foreach ($cat->products as $p) {
                    $rows[] = [$i++, $cat->category_name, $p->product_id, $p->name, $p->size ?? '-', $p->pack ?? '-', $p->weight_per_kg ?? '-', $p->current_stock];
                }
            }
            return $this->exportExcel(['ลำดับ', 'หมวด', 'รหัสสินค้า', 'ชื่อสินค้า', 'Size', 'Pack', 'น้ำหนัก/กก.', 'คงเหลือ'], $rows, 'สินค้าแยกตามหมวด', 'รายงานสินค้าแยกตามหมวด');
        }

        $allCategories = Category::all();
        return view('reports.products.by_category', compact('categories', 'allCategories', 'selectedCategory'));
    }

    // 2b. แสดงสินค้าตามวันเข้าคลัง
    public function productsByDate(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $query = Transaction::with(['items.product.category', 'receiveType']);

        if ($start) $query->where('trans_date', '>=', $start);
        if ($end) $query->where('trans_date', '<=', $end);

        $transactions = $query->orderBy('trans_date', 'desc')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.products.by_date_pdf', compact('transactions', 'start', 'end'), 'สินค้าตามวันเข้าคลัง', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = []; $i = 1;
            foreach ($transactions as $t) {
                foreach ($t->items as $item) {
                    $rows[] = [$i++, $t->trans_date, $t->trans_id, $t->receiveType->name ?? '-', $item->product->product_id ?? '-', $item->product->name ?? '-', $item->product->category->category_name ?? '-', $item->full_qty, $item->fraction_qty];
                }
            }
            return $this->exportExcel(['ลำดับ', 'วันที่เข้าคลัง', 'เลขที่เอกสาร', 'ประเภทการรับ', 'รหัสสินค้า', 'ชื่อสินค้า', 'หมวด', 'Kg/ctn', 'Kg/Inner'], $rows, 'สินค้าตามวันเข้าคลัง', 'รายงานสินค้าตามวันเข้าคลัง');
        }

        return view('reports.products.by_date', compact('transactions', 'start', 'end'));
    }

    // 2c. แสดงรายละเอียดสินค้าทั้งหมด
    public function productsAll(Request $request)
    {
        $products = Product::with('category')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.products.all_pdf', compact('products'), 'รายละเอียดสินค้าทั้งหมด', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = $products->map(fn($p, $i) => [$i+1, $p->product_id, $p->name, $p->category->category_name ?? '-', $p->size ?? '-', $p->pack ?? '-', $p->weight_per_kg ?? '-', $p->weight_total ?? '-', $p->stock_min, $p->stock_max, $p->current_stock])->toArray();
            return $this->exportExcel(['ลำดับ', 'รหัสสินค้า', 'ชื่อสินค้า', 'หมวด', 'Size', 'Pack', 'น้ำหนัก/กก.', 'น้ำหนักรวม', 'สต็อกต่ำสุด', 'สต็อกสูงสุด', 'คงเหลือ'], $rows, 'รายละเอียดสินค้าทั้งหมด', 'รายงานรายละเอียดสินค้าทั้งหมด');
        }

        return view('reports.products.all', compact('products'));
    }

    // 2d. แสดงรายละเอียดตาม Size
    public function productsBySize(Request $request)
    {
        $size = $request->input('size');
        $query = Product::with('category');

        if ($size) {
            $query->where('size', $size);
        }

        $products = $query->orderBy('size')->orderBy('name')->get();
        $grouped = $products->groupBy('size');
        $sizes = Product::select('size')->distinct()->whereNotNull('size')->orderBy('size')->pluck('size');

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.products.by_size_pdf', compact('grouped', 'size'), 'รายละเอียดตาม_Size', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = [];
            foreach ($grouped as $sizeKey => $items) {
                $rows[] = ["Size: " . ($sizeKey ?: '-'), '', '', '', '', '', '', ''];
                foreach ($items as $i => $p) {
                    $rows[] = [$i+1, $p->product_id, $p->name, $p->category->category_name ?? '-', $p->size ?? '-', $p->pack ?? '-', $p->weight_per_kg ?? '-', $p->current_stock];
                }
                $rows[] = ['', '', '', '', '', '', '', ''];
            }
            return $this->exportExcel(['ลำดับ', 'รหัสสินค้า', 'ชื่อสินค้า', 'หมวด', 'Size', 'Pack', 'น้ำหนัก/กก.', 'คงเหลือ'], $rows, 'รายละเอียดตาม_Size', 'รายงานรายละเอียดสินค้าตาม Size');
        }

        return view('reports.products.by_size', compact('grouped', 'sizes', 'size'));
    }

    // 2e. แสดงรายละเอียดตาม Pack
    public function productsByPack(Request $request)
    {
        $pack = $request->input('pack');
        $query = Product::with('category');

        if ($pack) {
            $query->where('pack', $pack);
        }

        $products = $query->orderBy('pack')->orderBy('name')->get();
        $grouped = $products->groupBy('pack');
        $packs = Product::select('pack')->distinct()->whereNotNull('pack')->orderBy('pack')->pluck('pack');

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.products.by_pack_pdf', compact('grouped', 'pack'), 'รายละเอียดตาม_Pack', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = [];
            foreach ($grouped as $packKey => $items) {
                $rows[] = ["Pack: " . ($packKey ?: '-'), '', '', '', '', '', '', ''];
                foreach ($items as $i => $p) {
                    $rows[] = [$i+1, $p->product_id, $p->name, $p->category->category_name ?? '-', $p->size ?? '-', $p->pack ?? '-', $p->weight_per_kg ?? '-', $p->current_stock];
                }
                $rows[] = ['', '', '', '', '', '', '', ''];
            }
            return $this->exportExcel(['ลำดับ', 'รหัสสินค้า', 'ชื่อสินค้า', 'หมวด', 'Size', 'Pack', 'น้ำหนัก/กก.', 'คงเหลือ'], $rows, 'รายละเอียดตาม_Pack', 'รายงานรายละเอียดสินค้าตาม Pack');
        }

        return view('reports.products.by_pack', compact('grouped', 'packs', 'pack'));
    }

    // =============================================
    // 3. รายงานรับสินค้าเข้าคลัง
    // =============================================

    // 3a. รายงานตรวจสอบใบรับเบิก
    public function receivedCheck(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $referenceNo = $request->input('reference_no');

        $query = Transaction::with(['items.product', 'receiveType']);

        if ($start) $query->where('trans_date', '>=', $start);
        if ($end) $query->where('trans_date', '<=', $end);
        if ($referenceNo) $query->where('reference_no', 'like', "%{$referenceNo}%");

        $transactions = $query->orderBy('trans_date', 'desc')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.received.check_pdf', compact('transactions', 'start', 'end', 'referenceNo'), 'ตรวจสอบใบรับเบิก', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = []; $i = 1;
            foreach ($transactions as $t) {
                foreach ($t->items as $item) {
                    $rows[] = [$i++, $t->trans_date, $t->trans_id, $t->reference_doc ?? '-', $t->reference_no ?? '-', $t->receiveType->name ?? '-', $item->product->name ?? '-', $item->item_code ?? '-', $item->full_qty, $item->fraction_qty, $t->note ?? '-'];
                }
            }
            return $this->exportExcel(['ลำดับ', 'วันที่', 'เลขที่เอกสาร', 'เอกสารอ้างอิง', 'เลขอ้างอิง', 'ประเภทการรับ', 'สินค้า', 'Code', 'Kg/ctn', 'Kg/Inner', 'หมายเหตุ'], $rows, 'ตรวจสอบใบรับเบิก', 'รายงานตรวจสอบใบรับเบิก');
        }

        return view('reports.received.check', compact('transactions', 'start', 'end', 'referenceNo'));
    }

    // 3b. รายงานตรวจสอบในรับ/รหัสสินค้า
    public function receivedByProduct(Request $request)
    {
        $transId = $request->input('trans_id');
        $productId = $request->input('product_id');

        $query = Transaction::with(['items.product', 'receiveType']);

        if ($transId) $query->where('trans_id', 'like', "%{$transId}%");
        if ($productId) {
            $query->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', 'like', "%{$productId}%");
            });
        }

        $transactions = $query->orderBy('trans_date', 'desc')->get();
        $products = Product::orderBy('product_id')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.received.by_product_pdf', compact('transactions', 'transId', 'productId'), 'ตรวจสอบใบรับ_รหัสสินค้า', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = []; $i = 1;
            foreach ($transactions as $t) {
                foreach ($t->items as $item) {
                    $rows[] = [$i++, $t->trans_date, $t->trans_id, $t->receiveType->name ?? '-', $item->product->product_id ?? '-', $item->product->name ?? '-', $item->full_qty, $item->fraction_qty, $item->net_weight];
                }
            }
            return $this->exportExcel(['ลำดับ', 'วันที่', 'เลขที่', 'ประเภท', 'รหัสสินค้า', 'ชื่อสินค้า', 'Kg/ctn', 'Kg/Inner', 'น้ำหนัก'], $rows, 'ตรวจสอบใบรับ_รหัสสินค้า', 'รายงานตรวจสอบใบรับ/รหัสสินค้า');
        }

        return view('reports.received.by_product', compact('transactions', 'products', 'transId', 'productId'));
    }

    // 3c. ใบรับสินค้าตามประเภท
    public function receivedByType(Request $request)
    {
        $receiveTypeId = $request->input('receive_type_id');

        $query = Transaction::with(['items.product', 'receiveType']);

        if ($receiveTypeId) {
            $query->where('receive_type_id', $receiveTypeId);
        }

        $transactions = $query->orderBy('trans_date', 'desc')->get();
        $receiveTypes = ReceiveType::all();

        if ($request->has('export_pdf')) {
            $typeName = $receiveTypeId ? ReceiveType::find($receiveTypeId)->name ?? '' : 'ทั้งหมด';
            return $this->exportPdf('reports.received.by_type_pdf', compact('transactions', 'receiveTypeId', 'typeName'), 'ใบรับสินค้าตามประเภท', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = []; $i = 1;
            foreach ($transactions as $t) {
                foreach ($t->items as $item) {
                    $rows[] = [$i++, $t->trans_date, $t->trans_id, $t->receiveType->name ?? '-', $item->product->product_id ?? '-', $item->product->name ?? '-', $item->full_qty, $item->fraction_qty, $item->net_weight];
                }
            }
            $typeName = $receiveTypeId ? ReceiveType::find($receiveTypeId)->name ?? '' : 'ทั้งหมด';
            return $this->exportExcel(['ลำดับ', 'วันที่', 'เลขที่', 'ประเภท', 'รหัสสินค้า', 'ชื่อสินค้า', 'Kg/ctn', 'Kg/Inner', 'น้ำหนัก'], $rows, 'ใบรับสินค้าตามประเภท', 'รายงานใบรับสินค้าตามประเภท: ' . $typeName);
        }

        return view('reports.received.by_type', compact('transactions', 'receiveTypes', 'receiveTypeId'));
    }

    // =============================================
    // 4. รายงานเบิกสินค้าจากคลัง
    // =============================================

    // 4a. รายงานตรวจสอบใบรับเบิก
    public function issuedCheck(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $query = StockOut::with(['product', 'user', 'transaction']);

        if ($start) $query->where('issued_date', '>=', $start);
        if ($end) $query->where('issued_date', '<=', $end);

        $stockOuts = $query->orderBy('issued_date', 'desc')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.issued.check_pdf', compact('stockOuts', 'start', 'end'), 'ตรวจสอบใบเบิก', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = $stockOuts->map(fn($s, $i) => [$i+1, $s->issued_date, $s->stock_out_id ?? '-', $s->product->product_id ?? '-', $s->product->name ?? '-', $s->quantity, $s->user->fname ?? '-', $s->remark ?? '-'])->toArray();
            return $this->exportExcel(['ลำดับ', 'วันที่', 'เลขที่', 'รหัสสินค้า', 'ชื่อสินค้า', 'จำนวน', 'ผู้เบิก', 'หมายเหตุ'], $rows, 'ตรวจสอบใบเบิก', 'รายงานตรวจสอบใบเบิก');
        }

        return view('reports.issued.check', compact('stockOuts', 'start', 'end'));
    }

    // 4b. รายงานตรวจสอบในรับ/รหัสสินค้า
    public function issuedByProduct(Request $request)
    {
        $transId = $request->input('trans_id');
        $productId = $request->input('product_id');

        $query = StockOut::with(['product', 'user', 'transaction']);

        if ($transId) $query->where('trans_id', 'like', "%{$transId}%");
        if ($productId) $query->where('product_id', 'like', "%{$productId}%");

        $stockOuts = $query->orderBy('issued_date', 'desc')->get();
        $products = Product::orderBy('product_id')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.issued.by_product_pdf', compact('stockOuts', 'transId', 'productId'), 'ตรวจสอบเบิก_รหัสสินค้า', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = $stockOuts->map(fn($s, $i) => [$i+1, $s->issued_date, $s->stock_out_id ?? '-', $s->product->product_id ?? '-', $s->product->name ?? '-', $s->quantity, $s->user->fname ?? '-', $s->remark ?? '-'])->toArray();
            return $this->exportExcel(['ลำดับ', 'วันที่', 'เลขที่', 'รหัสสินค้า', 'ชื่อสินค้า', 'จำนวน', 'ผู้เบิก', 'หมายเหตุ'], $rows, 'ตรวจสอบเบิก_รหัสสินค้า', 'รายงานตรวจสอบเบิก/รหัสสินค้า');
        }

        return view('reports.issued.by_product', compact('stockOuts', 'products', 'transId', 'productId'));
    }

    // 4c. ใบเบิกสินค้าตามประเภท
    public function issuedByType(Request $request)
    {
        $receiveTypeId = $request->input('receive_type_id');

        $query = StockOut::with(['product', 'user', 'transaction.receiveType']);

        if ($receiveTypeId) {
            $query->whereHas('transaction', function ($q) use ($receiveTypeId) {
                $q->where('receive_type_id', $receiveTypeId);
            });
        }

        $stockOuts = $query->orderBy('issued_date', 'desc')->get();
        $receiveTypes = ReceiveType::all();

        if ($request->has('export_pdf')) {
            $typeName = $receiveTypeId ? ReceiveType::find($receiveTypeId)->name ?? '' : 'ทั้งหมด';
            return $this->exportPdf('reports.issued.by_type_pdf', compact('stockOuts', 'receiveTypeId', 'typeName'), 'ใบเบิกสินค้าตามประเภท', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = $stockOuts->map(fn($s, $i) => [$i+1, $s->issued_date, $s->stock_out_id ?? '-', $s->transaction->receiveType->name ?? '-', $s->product->product_id ?? '-', $s->product->name ?? '-', $s->quantity, $s->user->fname ?? '-'])->toArray();
            $typeName = $receiveTypeId ? ReceiveType::find($receiveTypeId)->name ?? '' : 'ทั้งหมด';
            return $this->exportExcel(['ลำดับ', 'วันที่', 'เลขที่', 'ประเภท', 'รหัสสินค้า', 'ชื่อสินค้า', 'จำนวน', 'ผู้เบิก'], $rows, 'ใบเบิกสินค้าตามประเภท', 'รายงานใบเบิกสินค้าตามประเภท: ' . $typeName);
        }

        return view('reports.issued.by_type', compact('stockOuts', 'receiveTypes', 'receiveTypeId'));
    }

    // =============================================
    // 5. รายงานสินค้าคงเหลือ ณ ปัจจุบัน/Size
    // =============================================
    public function stockRemainingSize(Request $request)
    {
        $products = Product::with('category')->where('current_stock', '>', 0)->orderBy('size')->get();
        $grouped = $products->groupBy('size');

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.stock.remaining_size_pdf', compact('grouped'), 'สินค้าคงเหลือตาม_Size', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = []; $i = 1;
            foreach ($grouped as $size => $items) {
                foreach ($items as $p) {
                    $rows[] = [$i++, $size ?? '-', $p->product_id, $p->name, $p->category->category_name ?? '-', $p->current_stock, $p->weight_per_kg, $p->weight_total];
                }
            }
            return $this->exportExcel(['ลำดับ', 'Size', 'รหัส', 'ชื่อสินค้า', 'หมวด', 'คงเหลือ', 'น้ำหนัก/kg', 'น้ำหนักรวม'], $rows, 'สินค้าคงเหลือตาม_Size', 'รายงานสินค้าคงเหลือตาม Size');
        }

        return view('reports.stock.remaining_size', compact('grouped'));
    }

    // =============================================
    // 6. รายงานสินค้าคงเหลือ ณ ปัจจุบัน/Pack
    // =============================================
    public function stockRemainingPack(Request $request)
    {
        $products = Product::with('category')->where('current_stock', '>', 0)->orderBy('pack')->get();
        $grouped = $products->groupBy('pack');

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.stock.remaining_pack_pdf', compact('grouped'), 'สินค้าคงเหลือตาม_Pack', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = []; $i = 1;
            foreach ($grouped as $pack => $items) {
                foreach ($items as $p) {
                    $rows[] = [$i++, $pack ?? '-', $p->product_id, $p->name, $p->category->category_name ?? '-', $p->current_stock, $p->weight_per_kg, $p->weight_total];
                }
            }
            return $this->exportExcel(['ลำดับ', 'Pack', 'รหัส', 'ชื่อสินค้า', 'หมวด', 'คงเหลือ', 'น้ำหนัก/kg', 'น้ำหนักรวม'], $rows, 'สินค้าคงเหลือตาม_Pack', 'รายงานสินค้าคงเหลือตาม Pack');
        }

        return view('reports.stock.remaining_pack', compact('grouped'));
    }

    // =============================================
    // 7. รายงานสต็อกการ์ด_ตามรหัส
    // =============================================
    public function stockCardById(Request $request)
    {
        $productId = $request->input('product_id');
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $products = Product::orderBy('product_id')->get();
        $product = null;
        $receivedItems = collect();
        $issuedItems = collect();

        if ($productId) {
            $product = Product::find($productId);

            $recvQuery = TransactionItem::with('transaction.receiveType')
                ->where('product_id', $productId);
            if ($start) $recvQuery->whereHas('transaction', fn($q) => $q->where('trans_date', '>=', $start));
            if ($end) $recvQuery->whereHas('transaction', fn($q) => $q->where('trans_date', '<=', $end));
            $receivedItems = $recvQuery->get();

            $issQuery = StockOut::where('product_id', $productId);
            if ($start) $issQuery->where('issued_date', '>=', $start);
            if ($end) $issQuery->where('issued_date', '<=', $end);
            $issuedItems = $issQuery->get();
        }

        if ($request->has('export_pdf') && $productId) {
            return $this->exportPdf('reports.stock.card_by_id_pdf', compact('product', 'receivedItems', 'issuedItems', 'start', 'end'), 'สต็อกการ์ด_ตามรหัส', 'L');
        }

        if ($request->has('export_excel') && $productId) {
            $rows = []; $i = 1;
            foreach ($receivedItems as $item) {
                $rows[] = [$i++, 'รับเข้า', $item->transaction->trans_date ?? '-', $item->transaction->trans_id ?? '-', $item->transaction->receiveType->name ?? '-', $item->full_qty, $item->fraction_qty, $item->net_weight];
            }
            foreach ($issuedItems as $item) {
                $rows[] = [$i++, 'เบิกออก', $item->issued_date ?? '-', $item->stock_out_id ?? '-', '-', $item->quantity, '-', '-'];
            }
            return $this->exportExcel(['ลำดับ', 'ประเภท', 'วันที่', 'เลขที่', 'ประเภทรับ', 'Kg/ctn', 'Kg/Inner', 'น้ำหนัก'], $rows, 'สต็อกการ์ด_' . $productId, 'สต็อกการ์ด: ' . ($product->name ?? $productId));
        }

        return view('reports.stock.card_by_id', compact('products', 'product', 'receivedItems', 'issuedItems', 'productId', 'start', 'end'));
    }

    // =============================================
    // 8. รายงานสต็อกการ์ดแยกตาม Code
    // =============================================
    public function stockCardByCode(Request $request)
    {
        $itemCode = $request->input('item_code');
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $items = collect();

        if ($itemCode) {
            $query = TransactionItem::with(['transaction.receiveType', 'product'])
                ->where('item_code', 'like', "%{$itemCode}%");
            if ($start) $query->whereHas('transaction', fn($q) => $q->where('trans_date', '>=', $start));
            if ($end) $query->whereHas('transaction', fn($q) => $q->where('trans_date', '<=', $end));
            $items = $query->get();
        }

        if ($request->has('export_pdf') && $itemCode) {
            return $this->exportPdf('reports.stock.card_by_code_pdf', compact('items', 'itemCode', 'start', 'end'), 'สต็อกการ์ดตาม_Code', 'L');
        }

        if ($request->has('export_excel') && $itemCode) {
            $rows = $items->map(fn($item, $i) => [$i+1, $item->item_code, $item->transaction->trans_date ?? '-', $item->transaction->trans_id ?? '-', $item->transaction->receiveType->name ?? '-', $item->product->product_id ?? '-', $item->product->name ?? '-', $item->full_qty, $item->fraction_qty, $item->net_weight])->toArray();
            return $this->exportExcel(['ลำดับ', 'Code', 'วันที่', 'เลขที่', 'ประเภท', 'รหัสสินค้า', 'ชื่อสินค้า', 'Kg/ctn', 'Kg/Inner', 'น้ำหนัก'], $rows, 'สต็อกการ์ดตาม_Code', 'สต็อกการ์ดตาม Code: ' . $itemCode);
        }

        return view('reports.stock.card_by_code', compact('items', 'itemCode', 'start', 'end'));
    }

    // =============================================
    // 9. รายงานสินค้าคงเหลือตาม Product
    // =============================================
    public function stockByProduct(Request $request)
    {
        $products = Product::with('category')->orderBy('product_id')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.stock.by_product_pdf', compact('products'), 'สินค้าคงเหลือตาม_Product', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = $products->map(fn($p, $i) => [$i+1, $p->product_id, $p->name, $p->category->category_name ?? '-', $p->stock_min, $p->stock_max, $p->current_stock, $p->size, $p->pack, $p->weight_per_kg, $p->weight_total])->toArray();
            return $this->exportExcel(['ลำดับ', 'รหัส', 'ชื่อสินค้า', 'หมวด', 'Min', 'Max', 'คงเหลือ', 'Size', 'Pack', 'น้ำหนัก/kg', 'น้ำหนักรวม'], $rows, 'สินค้าคงเหลือตาม_Product', 'รายงานสินค้าคงเหลือตาม Product');
        }

        return view('reports.stock.by_product', compact('products'));
    }

    // =============================================
    // 10. รายงานสินค้าคงเหลือปริมาณ
    // =============================================
    public function stockQuantity(Request $request)
    {
        $products = Product::with('category')->orderBy('product_id')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.stock.quantity_pdf', compact('products'), 'สินค้าคงเหลือปริมาณ');
        }

        if ($request->has('export_excel')) {
            $rows = $products->map(fn($p, $i) => [$i+1, $p->product_id, $p->name, $p->category->category_name ?? '-', $p->current_stock, $p->weight_per_kg, $p->weight_total])->toArray();
            return $this->exportExcel(['ลำดับ', 'รหัส', 'ชื่อสินค้า', 'หมวด', 'คงเหลือ', 'น้ำหนัก/kg', 'น้ำหนักรวม'], $rows, 'สินค้าคงเหลือปริมาณ', 'รายงานสินค้าคงเหลือปริมาณ');
        }

        return view('reports.stock.quantity', compact('products'));
    }

    // =============================================
    // 11. รายงานสินค้าคงเหลือตาม Product ไม่โชว์ Code
    // =============================================
    public function stockByProductNoCode(Request $request)
    {
        $products = Product::with('category')->orderBy('product_id')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.stock.by_product_no_code_pdf', compact('products'), 'สินค้าคงเหลือตาม_Product_ไม่โชว์Code', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = $products->map(fn($p, $i) => [$i+1, $p->product_id, $p->name, $p->category->category_name ?? '-', $p->stock_min, $p->stock_max, $p->current_stock, $p->size, $p->pack])->toArray();
            return $this->exportExcel(['ลำดับ', 'รหัส', 'ชื่อสินค้า', 'หมวด', 'Min', 'Max', 'คงเหลือ', 'Size', 'Pack'], $rows, 'สินค้าคงเหลือตาม_Product_ไม่โชว์Code', 'รายงานสินค้าคงเหลือตาม Product (ไม่โชว์ Code)');
        }

        return view('reports.stock.by_product_no_code', compact('products'));
    }

    // =============================================
    // 12. รายงานสรุปสินค้า (รับ/เบิก)
    // =============================================
    public function productSummary(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $productId = $request->input('product_id');

        $products = Product::orderBy('product_id')->get();

        // Query รับเข้า
        $receivedQuery = TransactionItem::with(['product', 'transaction'])
            ->select('product_id')
            ->selectRaw('SUM(full_qty) as total_full_qty')
            ->selectRaw('SUM(fraction_qty) as total_fraction_qty')
            ->selectRaw('SUM(net_weight) as total_weight');

        if ($start) {
            $receivedQuery->whereHas('transaction', fn($q) => $q->where('trans_date', '>=', $start));
        }
        if ($end) {
            $receivedQuery->whereHas('transaction', fn($q) => $q->where('trans_date', '<=', $end));
        }
        if ($productId) {
            $receivedQuery->where('product_id', $productId);
        }

        $received = $receivedQuery->groupBy('product_id')->get()->keyBy('product_id');

        // Query เบิกออก
        $issuedQuery = StockOut::select('product_id')
            ->selectRaw('SUM(quantity) as total_qty')
            ->selectRaw('SUM(fraction_qty) as total_fraction_qty');

        if ($start) {
            $issuedQuery->where('issued_date', '>=', $start);
        }
        if ($end) {
            $issuedQuery->where('issued_date', '<=', $end);
        }
        if ($productId) {
            $issuedQuery->where('product_id', $productId);
        }

        $issued = $issuedQuery->groupBy('product_id')->get()->keyBy('product_id');

        // รวมข้อมูล
        $summaryProducts = $productId
            ? Product::with('category')->where('product_id', $productId)->get()
            : Product::with('category')->get();

        $summary = $summaryProducts->map(function ($product) use ($received, $issued) {
            $recv = $received->get($product->product_id);
            $iss = $issued->get($product->product_id);
            return [
                'product_id' => $product->product_id,
                'name' => $product->name,
                'category' => $product->category->category_name ?? '-',
                'received_full' => $recv->total_full_qty ?? 0,
                'received_fraction' => $recv->total_fraction_qty ?? 0,
                'received_weight' => $recv->total_weight ?? 0,
                'issued_full' => $iss->total_qty ?? 0,
                'issued_fraction' => $iss->total_fraction_qty ?? 0,
                'current_stock' => $product->current_stock,
            ];
        })->filter(function ($item) {
            return $item['received_full'] > 0 || $item['issued_full'] > 0 || $item['current_stock'] > 0;
        });

        if ($request->has('export_pdf')) {
            return $this->exportPdf('reports.summary.product_pdf', compact('summary', 'start', 'end', 'productId'), 'สรุปสินค้า_รับเบิก', 'L');
        }

        if ($request->has('export_excel')) {
            $rows = []; $i = 1;
            foreach ($summary as $item) {
                $rows[] = [
                    $i++,
                    $item['product_id'],
                    $item['name'],
                    $item['category'],
                    $item['received_full'],
                    $item['received_fraction'],
                    $item['received_weight'],
                    $item['issued_full'],
                    $item['issued_fraction'],
                    $item['current_stock'],
                ];
            }
            return $this->exportExcel(
                ['ลำดับ', 'รหัสสินค้า', 'ชื่อสินค้า', 'หมวด', 'รับ Kg/ctn', 'รับ Kg/Inner', 'รับ น้ำหนัก', 'เบิก Kg/ctn', 'เบิก Kg/Inner', 'คงเหลือ'],
                $rows,
                'สรุปสินค้า_รับเบิก',
                'รายงานสรุปสินค้า (รับ/เบิก)' . ($start ? " ตั้งแต่ $start" : '') . ($end ? " ถึง $end" : '')
            );
        }

        return view('reports.summary.product', compact('summary', 'products', 'start', 'end', 'productId'));
    }

    // =============================================
    // Legacy methods (keep existing routes working)
    // =============================================
    public function receivedReport(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $query = Transaction::with(['items.product', 'receiveType']);
        if ($start) $query->where('trans_date', '>=', $start);
        if ($end) $query->where('trans_date', '<=', $end);
        $transactions = $query->orderBy('trans_date', 'desc')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('profile.reports.received_pdf', compact('transactions', 'start', 'end'), 'รายงานรับสินค้า');
        }

        if ($request->has('export_excel')) {
            $rows = []; $i = 1;
            foreach ($transactions as $t) {
                foreach ($t->items as $item) {
                    $rows[] = [$i++, $t->trans_date, $t->trans_id, $t->receiveType->name ?? '-', $item->product->product_id ?? '-', $item->product->name ?? '-', $item->full_qty, $item->fraction_qty, $item->net_weight];
                }
            }
            return $this->exportExcel(['ลำดับ', 'วันที่', 'เลขที่', 'ประเภท', 'รหัสสินค้า', 'ชื่อสินค้า', 'Kg/ctn', 'Kg/Inner', 'น้ำหนัก'], $rows, 'รายงานรับสินค้า', 'รายงานรับสินค้า');
        }

        return view('profile.reports.received', compact('transactions', 'start', 'end'));
    }

    public function issuedReport(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $query = StockOut::with(['product', 'user', 'transaction']);
        if ($start) $query->where('issued_date', '>=', $start);
        if ($end) $query->where('issued_date', '<=', $end);
        $stockOuts = $query->orderBy('issued_date', 'desc')->get();

        if ($request->has('export_pdf')) {
            return $this->exportPdf('profile.reports.issued_pdf', compact('stockOuts', 'start', 'end'), 'รายงานเบิกสินค้า');
        }

        if ($request->has('export_excel')) {
            $rows = $stockOuts->map(fn($s, $i) => [$i+1, $s->issued_date, $s->stock_out_id ?? '-', $s->product->product_id ?? '-', $s->product->name ?? '-', $s->quantity, $s->user->fname ?? '-', $s->remark ?? '-'])->toArray();
            return $this->exportExcel(['ลำดับ', 'วันที่', 'เลขที่', 'รหัสสินค้า', 'ชื่อสินค้า', 'จำนวน', 'ผู้เบิก', 'หมายเหตุ'], $rows, 'รายงานเบิกสินค้า', 'รายงานเบิกสินค้า');
        }

        return view('profile.reports.issued', compact('stockOuts', 'start', 'end'));
    }

    public function transactionsForm()
    {
        return view('profile.reports.transactions_form');
    }

    public function transactionsPdf(Request $request)
    {
        $request->validate(['start_date' => 'required|date', 'end_date' => 'required|date']);

        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $transactions = Transaction::with(['items.product', 'receiveType'])
            ->whereBetween('trans_date', [$start, $end])
            ->orderBy('trans_date', 'desc')
            ->get();

        return $this->exportPdf('profile.reports.transactions_pdf', compact('transactions', 'start', 'end'), 'รายงานธุรกรรม');
    }
}
