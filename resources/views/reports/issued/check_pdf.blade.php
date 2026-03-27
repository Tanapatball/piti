<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานตรวจสอบใบเบิก</title>
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
    <h2>รายงานตรวจสอบใบเบิก</h2>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>
    @if($start && $end)
    <p>ช่วงวันที่: {{ $start }} - {{ $end }}</p>
    @endif

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
            @foreach($stockOuts as $stockOut)
            <tr>
                <td>{{ $stockOut->issued_date }}</td>
                <td>{{ $stockOut->trans_id }}</td>
                <td class="text-left">{{ $stockOut->product->name ?? '-' }}</td>
                <td>{{ $stockOut->quantity }}</td>
                <td>{{ $stockOut->fraction_qty }}</td>
                <td>{{ $stockOut->issued_to }}</td>
                <td class="text-left">{{ $stockOut->note }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
