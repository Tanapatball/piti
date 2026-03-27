<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานรับสินค้าเข้าคลัง</title>
    <style>
        body {
            font-family: 'garuda', sans-serif;
            font-size: 12pt;
        }

        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>รายงานรับสินค้าเข้าคลัง</h2>
    <p>ช่วงวันที่: {{ $start ?? '-' }} ถึง {{ $end ?? '-' }}</p>
    <table>
        <thead>
            <tr>
                <th>วันที่</th>
                <th>เลขที่เอกสาร</th>
                <th>ประเภทการรับ</th>
                <th>สินค้า</th>
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
                        <td>{{ $i->product->name ?? '-' }}</td>
                        <td>{{ $i->full_qty }}</td>
                        <td>{{ $i->fraction_qty }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
