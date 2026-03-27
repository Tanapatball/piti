<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class RecalculateStock extends Command
{
    protected $signature = 'stock:recalculate {--product= : Recalculate specific product_id}';
    protected $description = 'Recalculate current_stock from transaction_items and stock_outs';

    public function handle()
    {
        $productId = $this->option('product');

        if ($productId) {
            $this->recalculateProduct($productId);
        } else {
            $this->recalculateAll();
        }

        $this->info('Stock recalculation completed!');
    }

    private function recalculateProduct($productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            $this->error("Product {$productId} not found!");
            return;
        }

        $received = DB::table('transaction_items')
            ->where('product_id', $productId)
            ->sum('full_qty');

        $issued = DB::table('stock_outs')
            ->where('product_id', $productId)
            ->sum('quantity');

        $newStock = $received - $issued;
        $oldStock = $product->current_stock;

        $product->current_stock = $newStock;
        $product->save();

        $this->info("Product: {$productId}");
        $this->info("  Received: {$received}, Issued: {$issued}");
        $this->info("  Old stock: {$oldStock} -> New stock: {$newStock}");
    }

    private function recalculateAll()
    {
        $products = Product::all();
        $bar = $this->output->createProgressBar($products->count());

        $received = DB::table('transaction_items')
            ->select('product_id')
            ->selectRaw('SUM(full_qty) as total_received')
            ->groupBy('product_id')
            ->pluck('total_received', 'product_id');

        $issued = DB::table('stock_outs')
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_issued')
            ->groupBy('product_id')
            ->pluck('total_issued', 'product_id');

        $updated = 0;
        foreach ($products as $product) {
            $recv = $received->get($product->product_id, 0);
            $iss = $issued->get($product->product_id, 0);
            $newStock = $recv - $iss;

            if ($product->current_stock != $newStock) {
                $product->current_stock = $newStock;
                $product->save();
                $updated++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Updated {$updated} products.");
    }
}
