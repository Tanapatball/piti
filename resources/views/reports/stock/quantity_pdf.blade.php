<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานสินค้าคงเหลือปริมาณ</title>
    <style>
        body { font-family: 'garuda', sans-serif; font-size: 10pt; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
        .status-red { color: red; font-weight: bold; }
        .status-orange { color: orange; font-weight: bold; }
        .status-green { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h2>รายงานสินค้าคงเหลือปริมาณ</h2>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>

    @if($products && $products->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>หมวด</th>
                <th>คงเหลือ</th>
                <th>สต็อกต่ำสุด</th>
                <th>สต็อกสูงสุด</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->product_id }}</td>
                <td class="text-left">{{ $product->name }}</td>
                <td>{{ $product->category->category_name ?? '-' }}</td>
                <td>{{ $product->current_stock }}</td>
                <td>{{ $product->stock_min }}</td>
                <td>{{ $product->stock_max }}</td>
                <td>
                    @if($product->current_stock < $product->stock_min)
                        <span class="status-red">ต่ำกว่า Min</span>
                    @elseif($product->current_stock > $product->stock_max)
                        <span class="status-orange">เกิน Max</span>
                    @else
                        <span class="status-green">ปกติ</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>ไม่มีข้อมูล</p>
    @endif
</body>
</html>
