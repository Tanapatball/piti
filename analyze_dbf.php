<?php
require 'vendor/autoload.php';
use XBase\TableReader;

$files = [
    'RCV_HD.DBF' => 'Receive Headers',
    'rcv_dt.dbf' => 'Receive Details',
    'isu_hd.dbf' => 'Issue Headers',
    'isu_dt.dbf' => 'Issue Details',
    'product.dbf' => 'Products'
];

echo "DBF File Analysis:\n";
echo str_repeat('-', 60) . "\n";

foreach ($files as $file => $name) {
    $path = __DIR__ . '/' . $file;
    if (!file_exists($path)) { echo "Not found: $file\n"; continue; }

    // Count with deleted
    $table = new TableReader($path, ['encoding' => 'CP874']);
    $total = $table->getRecordCount();

    // Count active only
    $active = 0;
    $table = new TableReader($path, ['encoding' => 'CP874']);
    while ($record = $table->nextRecord()) {
        $active++;
    }

    $deleted = $total - $active;
    printf("%-20s Total: %6d | Active: %6d | Deleted: %6d\n", $name, $total, $active, $deleted);
}
