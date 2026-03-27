<?php
require_once 'vendor/autoload.php';

use XBase\TableReader;

$table = new TableReader(__DIR__ . '/rcv_dt.dbf', ['encoding' => 'CP874']);

$products = [];
while ($record = $table->nextRecord()) {
    $prCode = trim($record->get('pr_code'));
    if ($prCode && !isset($products[$prCode])) {
        $products[$prCode] = true;
    }
}

echo "จำนวนสินค้า (pr_code) ที่ไม่ซ้ำ: " . count($products) . " รายการ\n\n";
echo "ตัวอย่าง 20 รายการแรก:\n";
$i = 0;
foreach ($products as $code => $v) {
    if ($i++ >= 20) break;
    echo "  - $code\n";
}
