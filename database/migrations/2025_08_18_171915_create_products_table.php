<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('product_id', 20)->primary();
            $table->string('name', 150);
            $table->string('category_id')->nullable();
            $table->integer('stock_min')->nullable();
            $table->integer('stock_max')->nullable();
            $table->integer('current_stock')->default(0);
            $table->string('size', 50)->nullable();
            $table->string('pack', 50)->nullable();
            $table->decimal('weight_per_kg', 10, 2)->nullable();
            $table->decimal('weight_total', 10, 2)->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('set null');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
