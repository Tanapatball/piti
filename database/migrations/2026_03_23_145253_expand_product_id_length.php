<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop foreign keys first
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('stock_outs', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('issue_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // Expand product_id length
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_id', 30)->change();
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->string('product_id', 30)->change();
        });

        Schema::table('stock_outs', function (Blueprint $table) {
            $table->string('product_id', 30)->change();
        });

        Schema::table('issue_items', function (Blueprint $table) {
            $table->string('product_id', 30)->change();
        });

        // Recreate foreign keys
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });

        Schema::table('stock_outs', function (Blueprint $table) {
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });

        Schema::table('issue_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Revert to original length (20)
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('stock_outs', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('issue_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('product_id', 20)->change();
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->string('product_id', 20)->change();
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });

        Schema::table('stock_outs', function (Blueprint $table) {
            $table->string('product_id', 20)->change();
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });

        Schema::table('issue_items', function (Blueprint $table) {
            $table->string('product_id', 20)->change();
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }
};
