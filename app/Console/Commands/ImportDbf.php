<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use XBase\TableReader;

class ImportDbf extends Command
{
    protected $signature = 'import:dbf {--fresh : Clear existing data before import}';
    protected $description = 'Import data from DBF files (product, RCV_HD, rcv_dt, isu_hd, isu_dt)';

    private $basePath;
    private $autoRunNo = 1;
    private $issueHeaders = [];

    public function handle()
    {
        $this->basePath = base_path();

        if ($this->option('fresh')) {
            $this->warn('Clearing existing data...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('issue_items')->truncate();
            DB::table('stock_outs')->truncate();
            DB::table('transaction_items')->truncate();
            DB::table('transactions')->truncate();
            DB::table('products')->truncate();
            DB::table('categories')->truncate();
            DB::table('issue_types')->truncate();
            DB::table('receive_types')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->importReceiveTypes();
        $this->createDefaultUser();
        $this->importIssueTypes();
        $this->importCategories();
        $this->importProducts();
        $this->importReceiveHeaders();
        $this->importReceiveDetails();
        $this->importIssueHeaders();
        $this->importIssueDetails();
        $this->calculateStockBalances();
        $this->generateStockMinMax();

        $this->info("\n" . str_repeat('=', 50));
        $this->info('Import completed!');
        $this->showSummary();
    }

    private function importReceiveTypes()
    {
        $this->info('Importing receive types...');

        $receiveTypes = [
            ['receive_type_id' => 1, 'name' => 'รับจากซัพพลายเออร์', 'created_at' => now(), 'updated_at' => now()],
            ['receive_type_id' => 2, 'name' => 'รับคืนจากลูกค้า', 'created_at' => now(), 'updated_at' => now()],
            ['receive_type_id' => 3, 'name' => 'รับโอนย้าย', 'created_at' => now(), 'updated_at' => now()],
            ['receive_type_id' => 4, 'name' => 'รับอื่นๆ', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($receiveTypes as $type) {
            DB::table('receive_types')->updateOrInsert(
                ['receive_type_id' => $type['receive_type_id']],
                $type
            );
        }

        $this->info('  -> ' . count($receiveTypes) . ' receive types imported');
    }

    private function createDefaultUser()
    {
        $this->info('Creating default user...');

        // Create default role first
        $roleExists = DB::table('roles')->where('role_id', 1)->exists();
        if (!$roleExists) {
            DB::table('roles')->insert([
                'role_id'    => 1,
                'role_name'  => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->info('  -> Default role created');
        }

        $userExists = DB::table('users')->where('user_id', 1)->exists();
        if (!$userExists) {
            DB::table('users')->insert([
                'user_id'    => 1,
                'fname'      => 'System',
                'lname'      => 'Import',
                'email'      => 'system@import.local',
                'role_id'    => 1,
                'password'   => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->info('  -> Default user created');
        } else {
            $this->info('  -> Default user already exists');
        }
    }

    private function importIssueTypes()
    {
        $this->info('Importing issue types...');

        // อ่าน ot_code ที่มีจาก isu_hd.dbf
        $table = new TableReader($this->basePath . '/isu_hd.dbf', ['encoding' => 'CP874']);
        $otCodes = [];

        while ($record = $table->nextRecord()) {
            $otCode = trim($record->get('ot_code'));
            if ($otCode && !isset($otCodes[$otCode])) {
                $otCodes[$otCode] = true;
            }
        }

        // สร้าง issue_types
        $issueTypeNames = [
            '04' => 'เบิกผลิต',
            '05' => 'เบิกทดสอบ',
            '08' => 'เบิกจ่ายลูกค้า',
            '11' => 'เบิกใช้ภายใน',
            '14' => 'เบิกอื่นๆ',
        ];

        foreach ($otCodes as $code => $v) {
            $name = $issueTypeNames[$code] ?? "ประเภท $code";
            DB::table('issue_types')->updateOrInsert(
                ['code' => $code],
                ['name' => $name, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $this->info('  -> ' . count($otCodes) . ' issue types imported');
    }

    private function importCategories()
    {
        $this->info('Importing categories from product.dbf...');

        $table = new TableReader($this->basePath . '/product.dbf', ['encoding' => 'CP874']);
        $categories = [];

        while ($record = $table->nextRecord()) {
            $caCode = trim($record->get('ca_code'));
            if ($caCode && !isset($categories[$caCode])) {
                $categories[$caCode] = $caCode;
            }
        }

        foreach ($categories as $code) {
            DB::table('categories')->updateOrInsert(
                ['category_id' => $code],
                ['category_name' => $code, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $this->info('  -> ' . count($categories) . ' categories imported');
    }

    private function importProducts()
    {
        $this->info('Importing products from product.dbf...');

        $table = new TableReader($this->basePath . '/product.dbf', ['encoding' => 'CP874']);
        $count = 0;
        $bar = $this->output->createProgressBar($table->getRecordCount());

        while ($record = $table->nextRecord()) {
            $prCode = trim($record->get('pr_code'));
            if (!$prCode) {
                $bar->advance();
                continue;
            }

            DB::table('products')->updateOrInsert(
                ['product_id' => $prCode],
                [
                    'name'          => trim($record->get('pr_desc')) ?: $prCode,
                    'category_id'   => trim($record->get('ca_code')) ?: null,
                    'size'          => trim($record->get('pr_size')) ?: null,
                    'pack'          => trim($record->get('pr_pack')) ?: null,
                    'weight_per_kg' => $record->get('u_weight') ?: null,
                    'current_stock' => 0,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            );
            $count++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("  -> $count products imported");
    }

    private function importReceiveHeaders()
    {
        $this->info('Importing receive headers from RCV_HD.DBF...');

        $table = new TableReader($this->basePath . '/RCV_HD.DBF', ['encoding' => 'CP874']);
        $count = 0;
        $created = 0;
        $bar = $this->output->createProgressBar($table->getRecordCount());

        while ($record = $table->nextRecord()) {
            $runNo = trim($record->get('run_no'));

            // ถ้าไม่มี run_no ให้สร้างใหม่
            if (!$runNo) {
                $runNo = 'AUTO-RCV-' . str_pad($this->autoRunNo++, 6, '0', STR_PAD_LEFT);
                $created++;
            }

            $rcvDt = $record->get('rcv_dt');
            $transDate = $this->parseDate($rcvDt) ?? now()->toDateString();

            DB::table('transactions')->updateOrInsert(
                ['trans_id' => $runNo],
                [
                    'trans_date'      => $transDate,
                    'reference_doc'   => trim($record->get('doc_no')) ?: null,
                    'reference_no'    => trim($record->get('ref_no')) ?: null,
                    'receive_type_id' => 1,
                    'note'            => trim($record->get('remark')) ?: null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );
            $count++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("  -> $count receive headers imported ($created auto-generated)");
    }

    private function importReceiveDetails()
    {
        $this->info('Importing receive details from rcv_dt.dbf...');

        $table = new TableReader($this->basePath . '/rcv_dt.dbf', ['encoding' => 'CP874']);
        $count = 0;
        $transCreated = 0;
        $prodCreated = 0;
        $bar = $this->output->createProgressBar($table->getRecordCount());

        while ($record = $table->nextRecord()) {
            $runNo = trim($record->get('run_no'));
            $prCode = trim($record->get('pr_code'));

            // ถ้าไม่มี run_no หรือ pr_code ให้ข้าม
            if (!$prCode) {
                $bar->advance();
                continue;
            }

            // ถ้าไม่มี run_no ให้สร้างใหม่
            if (!$runNo) {
                $runNo = 'AUTO-RCV-' . str_pad($this->autoRunNo++, 6, '0', STR_PAD_LEFT);
            }

            // Check if product exists, create if not
            $productExists = DB::table('products')->where('product_id', $prCode)->exists();
            if (!$productExists) {
                DB::table('products')->insert([
                    'product_id'    => $prCode,
                    'name'          => $prCode,
                    'current_stock' => 0,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                $prodCreated++;
            }

            // Check if transaction exists, create if not
            $transExists = DB::table('transactions')->where('trans_id', $runNo)->exists();
            if (!$transExists) {
                DB::table('transactions')->insert([
                    'trans_id'        => $runNo,
                    'trans_date'      => now()->toDateString(),
                    'receive_type_id' => 1,
                    'note'            => 'Auto-created from rcv_dt',
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
                $transCreated++;
            }

            $fullQty = (int) $record->get('qty');
            $fractionQty = (int) $record->get('qty_inner');
            $code = trim($record->get('code')) ?: null;

            DB::table('transaction_items')->insert([
                'trans_id'      => $runNo,
                'product_id'    => $prCode,
                'item_code'     => $code,
                'code'          => $code,
                'full_qty'      => $fullQty,
                'fraction_qty'  => $fractionQty,
                'net_weight'    => 0,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $count++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("  -> $count receive details imported");
        $this->info("     ($transCreated transactions created, $prodCreated products created)");
    }

    private function importIssueHeaders()
    {
        $this->info('Importing issue headers from isu_hd.dbf...');

        $table = new TableReader($this->basePath . '/isu_hd.dbf', ['encoding' => 'CP874']);
        $count = 0;
        $created = 0;
        $bar = $this->output->createProgressBar($table->getRecordCount());

        while ($record = $table->nextRecord()) {
            $runNo = trim($record->get('run_no'));

            // ถ้าไม่มี run_no ให้สร้างใหม่
            if (!$runNo) {
                $runNo = 'AUTO-ISU-' . str_pad($this->autoRunNo++, 6, '0', STR_PAD_LEFT);
                $created++;
            }

            $isuDt = $record->get('isu_dt');
            $issuedDate = $this->parseDate($isuDt) ?? now()->toDateString();
            $otCode = trim($record->get('ot_code'));

            // Get issue_type_id from code
            $issueType = DB::table('issue_types')->where('code', $otCode)->first();
            $issueTypeId = $issueType ? $issueType->issue_type_id : null;

            $this->issueHeaders[$runNo] = [
                'run_no'        => $runNo,
                'issued_date'   => $issuedDate,
                'doc_no'        => trim($record->get('doc_no')),
                'ref_no'        => trim($record->get('ref_no')),
                'remark'        => trim($record->get('remark')),
                'ot_code'       => $otCode,
                'issue_type_id' => $issueTypeId,
            ];

            $count++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("  -> $count issue headers cached ($created auto-generated)");
    }

    private function importIssueDetails()
    {
        $this->info('Importing issue details from isu_dt.dbf...');

        $table = new TableReader($this->basePath . '/isu_dt.dbf', ['encoding' => 'CP874']);
        $count = 0;
        $headerCreated = 0;
        $prodCreated = 0;
        $bar = $this->output->createProgressBar($table->getRecordCount());

        while ($record = $table->nextRecord()) {
            $runNo = trim($record->get('run_no'));
            $prCode = trim($record->get('pr_code'));

            // ถ้าไม่มี pr_code ให้ข้าม
            if (!$prCode) {
                $bar->advance();
                continue;
            }

            // ถ้าไม่มี run_no ให้สร้างใหม่
            if (!$runNo) {
                $runNo = 'AUTO-ISU-' . str_pad($this->autoRunNo++, 6, '0', STR_PAD_LEFT);
            }

            // Get header info
            $header = $this->issueHeaders[$runNo] ?? null;

            if (!$header) {
                // สร้าง header ใหม่
                $header = [
                    'run_no'        => $runNo,
                    'issued_date'   => now()->toDateString(),
                    'doc_no'        => null,
                    'ref_no'        => null,
                    'remark'        => 'Auto-created from isu_dt',
                    'ot_code'       => null,
                    'issue_type_id' => null,
                ];
                $this->issueHeaders[$runNo] = $header;
                $headerCreated++;
            }

            // Check if product exists
            $productExists = DB::table('products')->where('product_id', $prCode)->exists();
            if (!$productExists) {
                DB::table('products')->insert([
                    'product_id'    => $prCode,
                    'name'          => $prCode,
                    'current_stock' => 0,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                $prodCreated++;
            }

            $fullQty = (int) $record->get('qty');
            $fractionQty = (int) $record->get('qty_inner');

            DB::table('stock_outs')->insert([
                'product_id'    => $prCode,
                'issue_type_id' => $header['issue_type_id'],
                'code'          => trim($record->get('code')) ?: null,
                'trans_id'      => null,
                'reference_doc' => $header['doc_no'] ?: null,
                'reference_no'  => $header['ref_no'] ?: null,
                'quantity'      => $fullQty,
                'fraction_qty'  => $fractionQty,
                'user_id'       => 1,
                'issued_to'     => $header['remark'] ?: null,
                'issued_date'   => $header['issued_date'] ?: now()->toDateString(),
                'note'          => $runNo,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $count++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("  -> $count issue details imported");
        $this->info("     ($headerCreated headers created, $prodCreated products created)");
    }

    private function calculateStockBalances()
    {
        $this->info('Calculating stock balances...');

        // Reset all current_stock to 0
        DB::table('products')->update(['current_stock' => 0]);

        // Calculate received quantity per product
        $received = DB::table('transaction_items')
            ->select('product_id', DB::raw('SUM(full_qty) as total_received'))
            ->groupBy('product_id')
            ->get();

        foreach ($received as $r) {
            DB::table('products')
                ->where('product_id', $r->product_id)
                ->increment('current_stock', $r->total_received);
        }

        // Calculate issued quantity per product
        $issued = DB::table('stock_outs')
            ->select('product_id', DB::raw('SUM(quantity) as total_issued'))
            ->groupBy('product_id')
            ->get();

        foreach ($issued as $i) {
            DB::table('products')
                ->where('product_id', $i->product_id)
                ->decrement('current_stock', $i->total_issued);
        }

        $this->info('  -> Stock balances calculated');
    }

    private function generateStockMinMax()
    {
        $this->info('Generating stock min/max values...');

        $products = DB::table('products')->get();
        $updated = 0;

        foreach ($products as $product) {
            // คำนวณ stock_min และ stock_max ตามจำนวน current_stock
            $currentStock = $product->current_stock ?? 0;

            if ($currentStock > 0) {
                // มี stock - กำหนด min เป็น 10-30% ของ current, max เป็น 150-200%
                $stockMin = max(1, (int)($currentStock * (rand(10, 30) / 100)));
                $stockMax = max($stockMin + 10, (int)($currentStock * (rand(150, 200) / 100)));
            } else {
                // ไม่มี stock - กำหนดค่า default
                $stockMin = rand(5, 20);
                $stockMax = rand(50, 100);
            }

            DB::table('products')
                ->where('product_id', $product->product_id)
                ->update([
                    'stock_min' => $stockMin,
                    'stock_max' => $stockMax,
                    'updated_at' => now(),
                ]);

            $updated++;
        }

        $this->info("  -> $updated products updated with stock min/max");
    }

    private function parseDate($dateValue)
    {
        if (!$dateValue) return null;

        if ($dateValue instanceof \DateTime) {
            return $dateValue->format('Y-m-d');
        }

        $dateStr = trim($dateValue);
        if (strlen($dateStr) == 8 && is_numeric($dateStr)) {
            return substr($dateStr, 0, 4) . '-' . substr($dateStr, 4, 2) . '-' . substr($dateStr, 6, 2);
        }

        return null;
    }

    private function showSummary()
    {
        $this->newLine();
        $this->info('Summary:');
        $this->table(
            ['Table', 'Count'],
            [
                ['Categories', DB::table('categories')->count()],
                ['Products', DB::table('products')->count()],
                ['Issue Types', DB::table('issue_types')->count()],
                ['Transactions (RCV)', DB::table('transactions')->count()],
                ['Transaction Items', DB::table('transaction_items')->count()],
                ['Stock Outs (ISU)', DB::table('stock_outs')->count()],
            ]
        );

        // Show stock summary
        $this->newLine();
        $totalReceived = DB::table('transaction_items')->sum('full_qty');
        $totalIssued = DB::table('stock_outs')->sum('quantity');
        $totalBalance = DB::table('products')->sum('current_stock');

        $this->info("Stock Summary:");
        $this->info("  Total Received: $totalReceived");
        $this->info("  Total Issued: $totalIssued");
        $this->info("  Total Balance: $totalBalance");
    }
}
