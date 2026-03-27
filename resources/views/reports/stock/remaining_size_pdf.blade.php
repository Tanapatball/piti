<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานสินค้าคงเหลือ ณ ปัจจุบัน/Size</title>
    <style>
        body { font-family: 'garuda', sans-serif; font-size: 10pt; }
        h2 { text-align: center; margin-bottom: 10px; }
        h3 { margin-top: 15px; margin-bottom: 5px; background-color: #e5e5e5; padding: 4px 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <h2>รายงานสินค้าคงเหลือ ณ ปัจจุบัน/Size</h2>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>

    @foreach($grouped as $size => $products)
    <h3>Size: {{ $size }}</h3>
    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>หมวด</th>
                <th>Size</th>
                <th>Pack</th>
                <th>คงเหลือ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->product_id }}</td>
                <td class="text-left">{{ $product->name }}</td>
                <td>{{ $product->category->category_name ?? '-' }}</td>
                <td>{{ $product->size }}</td>
                <td>{{ $product->pack }}</td>
                <td>{{ $product->current_stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
</body>
</html>
