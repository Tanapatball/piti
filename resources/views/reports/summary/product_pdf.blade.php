<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>รายงานสรุปสินค้า (รับ/เบิก)</title>
    <style>
        body { font-family: garuda, sans-serif; font-size: 11px; }
        h2 { text-align: center; margin-bottom: 5px; }
        .info { text-align: center; margin-bottom: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 4px 6px; }
        th { background: #4338CA; color: #fff; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bg-green { background: #d1fae5; }
        .bg-red { background: #fee2e2; }
        .bg-blue { background: #dbeafe; }
        .bg-gray { background: #f3f4f6; }
        tfoot td { font-weight: bold; background: #e5e7eb; }
    </style>
</head>
<body>
    <h2>รายงานสรุปสินค้า (รับ/เบิก)</h2>
    <p class="info">
        @if($start || $end)
            ช่วงวันที่: {{ $start ?? 'ไม่ระบุ' }} ถึง {{ $end ?? 'ไม่ระบุ' }}
        @else
            ข้อมูลทั้งหมด
        @endif
        @if($productId)
            | สินค้า: {{ $productId }}
        @endif
        | พิมพ์เมื่อ: {{ now()->format('d/m/Y H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th rowspan="2">ลำดับ</th>
                <th rowspan="2">รหัสสินค้า</th>
                <th rowspan="2">ชื่อสินค้า</th>
                <th rowspan="2">หมวด</th>
                <th colspan="3" class="bg-green">รับเข้า</th>
                <th colspan="2" class="bg-red">เบิกออก</th>
                <th rowspan="2" class="bg-blue">คงเหลือ</th>
            </tr>
            <tr>
                <th class="bg-green">Kg/ctn</th>
                <th class="bg-green">Kg/Inner</th>
                <th class="bg-green">น้ำหนัก</th>
                <th class="bg-red">Kg/ctn</th>
                <th class="bg-red">Kg/Inner</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach($summary as $item)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $item['product_id'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['category'] }}</td>
                    <td class="text-center bg-green">{{ number_format($item['received_full']) }}</td>
                    <td class="text-center bg-green">{{ number_format($item['received_fraction']) }}</td>
                    <td class="text-right bg-green">{{ number_format($item['received_weight'], 2) }}</td>
                    <td class="text-center bg-red">{{ number_format($item['issued_full']) }}</td>
                    <td class="text-center bg-red">{{ number_format($item['issued_fraction']) }}</td>
                    <td class="text-center bg-blue">{{ number_format($item['current_stock']) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center">รวม</td>
                <td class="text-center">{{ number_format($summary->sum('received_full')) }}</td>
                <td class="text-center">{{ number_format($summary->sum('received_fraction')) }}</td>
                <td class="text-right">{{ number_format($summary->sum('received_weight'), 2) }}</td>
                <td class="text-center">{{ number_format($summary->sum('issued_full')) }}</td>
                <td class="text-center">{{ number_format($summary->sum('issued_fraction')) }}</td>
                <td class="text-center">{{ number_format($summary->sum('current_stock')) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
