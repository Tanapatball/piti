<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานตรวจสอบใบรับ/รหัสสินค้า</title>
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
    <h2>รายงานตรวจสอบใบรับ/รหัสสินค้า</h2>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>
    @if($transId)
    <p>เลขที่เอกสาร: {{ $transId }}</p>
    @endif
    @if($productId)
    <p>รหัสสินค้า: {{ $productId }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>วันที่</th>
                <th>เลขที่เอกสาร</th>
                <th>เอกสารอ้างอิง</th>
                <th>เลขอ้างอิง</th>
                <th>ประเภทการรับ</th>
                <th>สินค้า</th>
                <th>Code</th>
                <th>Kg/ctn</th>
                <th>Kg/Inner</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $transaction->trans_date }}</td>
                    <td>{{ $transaction->trans_id }}</td>
                    <td>{{ $transaction->reference_doc }}</td>
                    <td>{{ $transaction->reference_no }}</td>
                    <td>{{ $transaction->receiveType->name ?? '-' }}</td>
                    <td class="text-left">{{ $item->product->name ?? '-' }}</td>
                    <td>{{ $item->item_code }}</td>
                    <td>{{ $item->full_qty }}</td>
                    <td>{{ $item->fraction_qty }}</td>
                    <td class="text-left">{{ $transaction->note }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
