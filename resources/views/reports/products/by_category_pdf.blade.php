<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>สินค้าแยกตามหมวด</title>
    <style>
        body { font-family: 'garuda', sans-serif; font-size: 10pt; }
        h2 { text-align: center; margin-bottom: 10px; }
        h3 { margin-top: 15px; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <h2>รายงานสินค้าแยกตามหมวด</h2>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>
    @foreach($categories as $cat)
        <h3>หมวด: {{ $cat->category_name }} ({{ $cat->category_id }})</h3>
        @if($cat->products->count())
            <table>
                <thead>
                    <tr>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>Size</th>
                        <th>Pack</th>
                        <th>น้ำหนัก/กก.</th>
                        <th>คงเหลือ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cat->products as $p)
                        <tr>
                            <td>{{ $p->product_id }}</td>
                            <td class="text-left">{{ $p->name }}</td>
                            <td>{{ $p->size ?? '-' }}</td>
                            <td>{{ $p->pack ?? '-' }}</td>
                            <td>{{ $p->weight_per_kg ?? '-' }}</td>
                            <td>{{ $p->current_stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>ไม่มีสินค้าในหมวดนี้</p>
        @endif
    @endforeach
</body>
</html>
