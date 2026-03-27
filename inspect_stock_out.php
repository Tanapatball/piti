<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$stockOuts = App\Models\StockOut::orderBy('id', 'desc')->take(10)->get();
foreach ($stockOuts as $so) {
    echo "ID: {$so->id}, Product: {$so->product_id}, Qty: {$so->quantity}, Note: {$so->note}, Ref: {$so->reference_no}, Date: {$so->stock_out_date}\n";
}
