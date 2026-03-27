<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();
            $table->string('product_id', 20);
            $table->string('trans_id', 50)->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('fraction_qty')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('issued_to', 150);
            $table->date('issued_date');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('trans_id')->references('trans_id')->on('transactions')->onDelete('set null');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_outs');
    }
};
