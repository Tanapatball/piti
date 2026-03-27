<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายละเอียดตาม Size</title>
    <style>
        body { font-family: 'garuda', sans-serif; font-size: 10pt; }
        h2 { text-align: center; margin-bottom: 10px; }
        h3 { background-color: #e0e7ff; color: #3730a3; padding: 6px 10px; margin: 15px 0 5px 0; font-size: 11pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <h2>รายงานรายละเอียดสินค้าตาม Size{{ $size ? ': ' . $size : ' (ทั้งหมด)' }}</h2>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>

    @foreach($grouped as $sizeKey => $products)
        <h3>Size: {{ $sizeKey ?: '-' }}</h3>
        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รหัสสินค้า</th>
                    <th>ชื่อสินค้า</th>
                    <th>หมวด</th>
                    <th>Size</th>
                    <th>Pack</th>
                    <th>น้ำหนัก/กก.</th>
                    <th>คงเหลือ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->product_id }}</td>
                        <td class="text-left">{{ $p->name }}</td>
                        <td>{{ $p->category->category_name ?? '-' }}</td>
                        <td>{{ $p->size ?? '-' }}</td>
                        <td>{{ $p->pack ?? '-' }}</td>
                        <td>{{ $p->weight_per_kg ?? '-' }}</td>
                        <td>{{ $p->current_stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
