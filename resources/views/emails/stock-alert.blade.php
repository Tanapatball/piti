<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 650px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #4338ca, #3730a3); padding: 24px 30px; color: #fff; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 6px 0 0; opacity: 0.85; font-size: 13px; }
        .body { padding: 24px 30px; }
        .section-title { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 15px; font-weight: 600; }
        .dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
        .dot-red { background: #ef4444; }
        .dot-orange { background: #f97316; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 13px; }
        th { padding: 8px 10px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 8px 10px; border-bottom: 1px solid #f3f4f6; }
        .th-red { background: #fef2f2; color: #991b1b; }
        .th-orange { background: #fff7ed; color: #9a3412; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 700; }
        .badge-red { background: #fee2e2; color: #dc2626; }
        .badge-orange { background: #ffedd5; color: #ea580c; }
        .text-center { text-align: center; }
        .text-bold { font-weight: 700; }
        .text-red { color: #dc2626; }
        .text-orange { color: #ea580c; }
        .footer { padding: 16px 30px; background: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center; font-size: 12px; color: #9ca3af; }
        .summary-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: 14px; color: #92400e; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>แจ้งเตือนสต็อกสินค้า</h1>
            <p>ระบบ WMS ตรวจพบสินค้าที่ต้องดำเนินการ</p>
        </div>
        <div class="body">
            <div class="summary-box">
                พบสินค้าที่ต้องดำเนินการ <strong>{{ $lowStock->count() + $overStock->count() }}</strong> รายการ
                @if($lowStock->count() > 0) | ต่ำกว่าขั้นต่ำ <strong>{{ $lowStock->count() }}</strong> @endif
                @if($overStock->count() > 0) | เกินสูงสุด <strong>{{ $overStock->count() }}</strong> @endif
            </div>

            @if($lowStock->count() > 0)
            <div class="section-title">
                <span class="dot dot-red"></span>
                <span style="color: #991b1b;">สต็อกต่ำกว่าขั้นต่ำ ({{ $lowStock->count() }} รายการ)</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="th-red">รหัส</th>
                        <th class="th-red">ชื่อสินค้า</th>
                        <th class="th-red text-center">คงเหลือ</th>
                        <th class="th-red text-center">Min</th>
                        <th class="th-red text-center">ขาด</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStock as $p)
                    <tr>
                        <td>{{ $p->product_id }}</td>
                        <td>{{ $p->name }}</td>
                        <td class="text-center text-bold text-red">{{ $p->current_stock }}</td>
                        <td class="text-center">{{ $p->stock_min }}</td>
                        <td class="text-center"><span class="badge badge-red">-{{ $p->stock_min - $p->current_stock }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            @if($overStock->count() > 0)
            <div class="section-title">
                <span class="dot dot-orange"></span>
                <span style="color: #9a3412;">สต็อกเกินจำนวนสูงสุด ({{ $overStock->count() }} รายการ)</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="th-orange">รหัส</th>
                        <th class="th-orange">ชื่อสินค้า</th>
                        <th class="th-orange text-center">คงเหลือ</th>
                        <th class="th-orange text-center">Max</th>
                        <th class="th-orange text-center">เกิน</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overStock as $p)
                    <tr>
                        <td>{{ $p->product_id }}</td>
                        <td>{{ $p->name }}</td>
                        <td class="text-center text-bold text-orange">{{ $p->current_stock }}</td>
                        <td class="text-center">{{ $p->stock_max }}</td>
                        <td class="text-center"><span class="badge badge-orange">+{{ $p->current_stock - $p->stock_max }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        <div class="footer">
            ส่งจากระบบ WMS อัตโนมัติ | {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
