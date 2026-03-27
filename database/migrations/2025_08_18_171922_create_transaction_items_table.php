<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->string('trans_id', 50);
            $table->string('product_id', 20);
            $table->string('item_code', 50)->nullable();
            $table->integer('full_qty')->default(0);
            $table->integer('fraction_qty')->default(0);
            $table->decimal('net_weight', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('trans_id')->references('trans_id')->on('transactions')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
