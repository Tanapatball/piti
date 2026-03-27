<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานรับสินค้า</title>
    <style>
        body { font-family: 'garuda', sans-serif; font-size: 10pt; }
        h2 { text-align: center; margin-bottom: 10px; }
        h3 { background-color: #e5e7eb; padding: 8px; margin: 15px 0 5px 0; font-size: 11pt; }
        .filter-info { margin-bottom: 15px; font-size: 9pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .print-date { text-align: right; font-size: 9pt; margin-bottom: 5px; }
        .group-summary { background-color: #fef3c7; font-weight: bold; }
        .grand-total { margin-top: 20px; font-size: 10pt; padding: 10px; background-color: #dbeafe; }
    </style>
</head>
<body>
    <div class="print-date">วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</div>
    <h2>รายงานรับสินค้า
        @if($reportType == 'by_size') (จัดกลุ่มตาม Size)
        @elseif($reportType == 'by_pack') (จัดกลุ่มตาม Pack)
        @endif
    </h2>

    <div class="filter-info">
        @if($dateFrom || $dateTo || $productFrom || $productTo)
        <strong>เงื่อนไขการกรอง:</strong>
        @if($dateFrom) ตั้งแต่วันที่: {{ $dateFrom }} @endif
        @if($dateTo) ถึงวันที่: {{ $dateTo }} @endif
        @if($productFrom) | สินค้าเริ่ม: {{ $productFrom }} @endif
        @if($productTo) สินค้าสิ้นสุด: {{ $productTo }} @endif
        @endif
    </div>

    @php
        // รวบรวม items ทั้งหมด
        $allItems = collect();
        foreach($transactions as $transaction) {
            foreach($transaction->items as $item) {
                $allItems->push([
                    'trans_date' => $transaction->trans_date,
                    'trans_id' => $transaction->trans_id,
                    'receive_type' => $transaction->receiveType->name ?? '-',
                    'product_id' => $item->product->product_id ?? '-',
                    'product_name' => $item->product->name ?? '-',
                    'size' => $item->product->size ?? '-',
                    'pack' => $item->product->pack ?? '-',
                    'full_qty' => $item->full_qty,
                    'fraction_qty' => $item->fraction_qty,
                    'net_weight' => $item->net_weight,
                ]);
            }
        }
    @endphp

    @if($reportType == 'by_size')
        {{-- จัดกลุ่มตาม Size --}}
        @php $groupedItems = $allItems->groupBy('size')->sortKeys(); @endphp

        @foreach($groupedItems as $size => $items)
            <h3>Size: {{ $size }}</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 30px;">ลำดับ</th>
                        <th style="width: 70px;">วันที่</th>
                        <th style="width: 80px;">เลขที่เอกสาร</th>
                        <th style="width: 60px;">รหัสสินค้า</th>
                        <th style="width: 150px;">ชื่อสินค้า</th>
                        <th style="width: 50px;">Pack</th>
                        <th style="width: 50px;">Kg/ctn</th>
                        <th style="width: 50px;">Kg/Inner</th>
                        <th style="width: 60px;">น้ำหนัก(kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $item['trans_date'] }}</td>
                        <td>{{ $item['trans_id'] }}</td>
                        <td>{{ $item['product_id'] }}</td>
                        <td class="text-left">{{ $item['product_name'] }}</td>
                        <td>{{ $item['pack'] }}</td>
                        <td class="text-right">{{ number_format($item['full_qty']) }}</td>
                        <td class="text-right">{{ number_format($item['fraction_qty']) }}</td>
                        <td class="text-right">{{ number_format($item['net_weight'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="group-summary">
                        <td colspan="6" class="text-right">รวม Size {{ $size }}:</td>
                        <td class="text-right">{{ number_format($items->sum('full_qty')) }}</td>
                        <td class="text-right">{{ number_format($items->sum('fraction_qty')) }}</td>
                        <td class="text-right">{{ number_format($items->sum('net_weight'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach

    @elseif($reportType == 'by_pack')
        {{-- จัดกลุ่มตาม Pack --}}
        @php $groupedItems = $allItems->groupBy('pack')->sortKeys(); @endphp

        @foreach($groupedItems as $pack => $items)
            <h3>Pack: {{ $pack }}</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 30px;">ลำดับ</th>
                        <th style="width: 70px;">วันที่</th>
                        <th style="width: 80px;">เลขที่เอกสาร</th>
                        <th style="width: 60px;">รหัสสินค้า</th>
                        <th style="width: 150px;">ชื่อสินค้า</th>
                        <th style="width: 50px;">Size</th>
                        <th style="width: 50px;">Kg/ctn</th>
                        <th style="width: 50px;">Kg/Inner</th>
                        <th style="width: 60px;">น้ำหนัก(kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $item['trans_date'] }}</td>
                        <td>{{ $item['trans_id'] }}</td>
                        <td>{{ $item['product_id'] }}</td>
                        <td class="text-left">{{ $item['product_name'] }}</td>
                        <td>{{ $item['size'] }}</td>
                        <td class="text-right">{{ number_format($item['full_qty']) }}</td>
                        <td class="text-right">{{ number_format($item['fraction_qty']) }}</td>
                        <td class="text-right">{{ number_format($item['net_weight'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="group-summary">
                        <td colspan="6" class="text-right">รวม Pack {{ $pack }}:</td>
                        <td class="text-right">{{ number_format($items->sum('full_qty')) }}</td>
                        <td class="text-right">{{ number_format($items->sum('fraction_qty')) }}</td>
                        <td class="text-right">{{ number_format($items->sum('net_weight'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach

    @else
        {{-- รูปแบบปกติ --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">ลำดับ</th>
                    <th style="width: 70px;">วันที่</th>
                    <th style="width: 80px;">เลขที่เอกสาร</th>
                    <th style="width: 80px;">ประเภท</th>
                    <th style="width: 60px;">รหัสสินค้า</th>
                    <th style="width: 120px;">ชื่อสินค้า</th>
                    <th style="width: 50px;">Size</th>
                    <th style="width: 50px;">Pack</th>
                    <th style="width: 50px;">Kg/ctn</th>
                    <th style="width: 50px;">Kg/Inner</th>
                    <th style="width: 60px;">น้ำหนัก(kg)</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($transactions as $transaction)
                    @foreach($transaction->items as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $transaction->trans_date }}</td>
                        <td>{{ $transaction->trans_id }}</td>
                        <td>{{ $transaction->receiveType->name ?? '-' }}</td>
                        <td>{{ $item->product->product_id ?? '-' }}</td>
                        <td class="text-left">{{ $item->product->name ?? '-' }}</td>
                        <td>{{ $item->product->size ?? '-' }}</td>
                        <td>{{ $item->product->pack ?? '-' }}</td>
                        <td class="text-right">{{ number_format($item->full_qty) }}</td>
                        <td class="text-right">{{ number_format($item->fraction_qty) }}</td>
                        <td class="text-right">{{ number_format($item->net_weight, 2) }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="grand-total">
        <strong>รวมทั้งหมด:</strong>
        {{ $allItems->count() }} รายการ |
        Kg/ctn: {{ number_format($allItems->sum('full_qty')) }} |
        Kg/Inner: {{ number_format($allItems->sum('fraction_qty')) }} |
        น้ำหนัก: {{ number_format($allItems->sum('net_weight'), 2) }} kg
    </div>
</body>
</html>
