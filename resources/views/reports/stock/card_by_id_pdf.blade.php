<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานสต็อกการ์ด ตามรหัส</title>
    <style>
        body { font-family: 'garuda', sans-serif; font-size: 10pt; }
        h2 { text-align: center; margin-bottom: 10px; }
        h3 { margin-top: 15px; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
        .product-info { margin-bottom: 15px; padding: 8px; background-color: #f9f9f9; border: 1px solid #ddd; }
        .product-info span { margin-right: 20px; }
    </style>
</head>
<body>
    <h2>รายงานสต็อกการ์ด ตามรหัส</h2>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>
    @if($start && $end)
    <p>ช่วงวันที่: {{ $start }} - {{ $end }}</p>
    @endif

    @if($product)
    <div class="product-info">
        <span><strong>รหัสสินค้า:</strong> {{ $product->product_id }}</span>
        <span><strong>ชื่อสินค้า:</strong> {{ $product->name }}</span>
        <span><strong>หมวด:</strong> {{ $product->category->category_name ?? '-' }}</span>
        <span><strong>Size:</strong> {{ $product->size }}</span>
        <span><strong>Pack:</strong> {{ $product->pack }}</span>
        <span><strong>คงเหลือ:</strong> {{ $product->current_stock }}</span>
    </div>

    <h3>รายการรับเข้า</h3>
    @if($receivedItems && $receivedItems->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>วันที่</th>
                <th>เลขที่เอกสาร</th>
                <th>Code</th>
                <th>Kg/ctn</th>
                <th>Kg/Inner</th>
                <th>น้ำหนักสุทธิ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receivedItems as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->transaction->trans_date ?? '-' }}</td>
                <td>{{ $item->transaction->trans_id ?? '-' }}</td>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->full_qty }}</td>
                <td>{{ $item->fraction_qty }}</td>
                <td>{{ $item->net_weight }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>ไม่มีรายการรับเข้า</p>
    @endif

    <h3>รายการเบิกออก</h3>
    @if($issuedItems && $issuedItems->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>วันที่</th>
                <th>เลขที่อ้างอิง</th>
                <th>จำนวน</th>
                <th>Kg/Inner</th>
                <th>ผู้เบิก</th>
            </tr>
        </thead>
        <tbody>
            @foreach($issuedItems as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->issued_date }}</td>
                <td>{{ $item->trans_id }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->fraction_qty }}</td>
                <td>{{ $item->issued_to }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>ไม่มีรายการเบิกออก</p>
    @endif
    @endif
</body>
</html>
