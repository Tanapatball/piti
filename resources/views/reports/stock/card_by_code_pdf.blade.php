<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานสต็อกการ์ดแยกตาม Code</title>
    <style>
        body { font-family: 'garuda', sans-serif; font-size: 10pt; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <h2>รายงานสต็อกการ์ดแยกตาม Code</h2>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>
    @if($itemCode)
    <p>Item Code: {{ $itemCode }}</p>
    @endif
    @if($start && $end)
    <p>ช่วงวันที่: {{ $start }} - {{ $end }}</p>
    @endif

    @if($items && $items->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>วันที่</th>
                <th>เลขที่เอกสาร</th>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>Code</th>
                <th>Kg/ctn</th>
                <th>Kg/Inner</th>
                <th>น้ำหนักสุทธิ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->transaction->trans_date ?? '-' }}</td>
                <td>{{ $item->transaction->trans_id ?? '-' }}</td>
                <td>{{ $item->product_id }}</td>
                <td class="text-left">{{ $item->product->name ?? '-' }}</td>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->full_qty }}</td>
                <td>{{ $item->fraction_qty }}</td>
                <td>{{ $item->net_weight }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>ไม่มีข้อมูล</p>
    @endif
</body>
</html>
