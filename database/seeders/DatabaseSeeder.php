<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            WarehouseSeeder::class,
            CategorySeeder::class,
            ReceiveTypeSeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
            StockOutSeeder::class,
        ]);
    }
}
