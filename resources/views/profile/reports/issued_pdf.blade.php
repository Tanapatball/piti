<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานเบิกสินค้าออก</title>
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
    <h2>รายงานเบิกสินค้าออก</h2>
    <p>ช่วงวันที่: {{ $start ?? '-' }} ถึง {{ $end ?? '-' }}</p>
    <table>
        <thead>
            <tr>
                <th>วันที่เบิก</th>
                <th>เลขที่อ้างอิง</th>
                <th>สินค้า</th>
                <th>Kg/ctn</th>
                <th>Kg/Inner</th>
                <th>ผู้เบิก</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockOuts as $s)
                <tr>
                    <td>{{ $s->issued_date }}</td>
                    <td>{{ $s->trans_id ?? '-' }}</td>
                    <td>{{ $s->product->name ?? '-' }}</td>
                    <td>{{ $s->quantity }}</td>
                    <td>{{ $s->fraction_qty ?? 0 }}</td>
                    <td>{{ $s->issued_to }}</td>
                    <td>{{ $s->note ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
