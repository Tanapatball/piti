<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('issue_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_out_id');
            $table->string('product_id', 20);
            $table->integer('quantity')->default(0);
            $table->integer('fraction_qty')->default(0);
            $table->decimal('net_weight', 10, 2)->default(0);
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('stock_out_id')->references('id')->on('stock_outs')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_items');
    }
};
