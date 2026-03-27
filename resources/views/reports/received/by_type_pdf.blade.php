<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>ใบรับสินค้าตามประเภท</title>
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
    <h2>ใบรับสินค้าตามประเภท</h2>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>
    @if($receiveTypeId)
    <p>ประเภทการรับ: {{ $typeName }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>วันที่</th>
                <th>เลขที่เอกสาร</th>
                <th>ประเภทการรับ</th>
                <th>สินค้า</th>
                <th>Code</th>
                <th>Kg/ctn</th>
                <th>Kg/Inner</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $transaction->trans_date }}</td>
                    <td>{{ $transaction->trans_id }}</td>
                    <td>{{ $transaction->receiveType->name ?? '-' }}</td>
                    <td class="text-left">{{ $item->product->name ?? '-' }}</td>
                    <td>{{ $item->item_code }}</td>
                    <td>{{ $item->full_qty }}</td>
                    <td>{{ $item->fraction_qty }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
