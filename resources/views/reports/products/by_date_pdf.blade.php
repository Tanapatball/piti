<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>สินค้าตามวันเข้าคลัง</title>
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
    <h2>รายงานสินค้าตามวันเข้าคลัง</h2>
    <p>ช่วงวันที่: {{ $start ?? '-' }} ถึง {{ $end ?? '-' }}</p>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>วันที่เข้าคลัง</th>
                <th>เลขที่เอกสาร</th>
                <th>ประเภทการรับ</th>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>หมวด</th>
                <th>Kg/ctn</th>
                <th>Kg/Inner</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
                @foreach($t->items as $i)
                    <tr>
                        <td>{{ $t->trans_date }}</td>
                        <td>{{ $t->trans_id }}</td>
                        <td>{{ $t->receiveType->name ?? '-' }}</td>
                        <td>{{ $i->product_id }}</td>
                        <td class="text-left">{{ $i->product->name ?? '-' }}</td>
                        <td>{{ $i->product->category->category_name ?? '-' }}</td>
                        <td>{{ $i->full_qty }}</td>
                        <td>{{ $i->fraction_qty }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
