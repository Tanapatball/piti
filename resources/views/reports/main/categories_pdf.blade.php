<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานหมวดสินค้า</title>
    <style>
        body { font-family: 'garuda', sans-serif; font-size: 12pt; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <h2>รายงานหมวดสินค้า</h2>
    <p>วันที่พิมพ์: {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รหัสหมวด</th>
                <th>ชื่อหมวด</th>
                <th>จำนวนสินค้า</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $index => $cat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $cat->category_id }}</td>
                    <td class="text-left">{{ $cat->category_name }}</td>
                    <td>{{ $cat->products_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
