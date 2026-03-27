<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->unsignedBigInteger('issue_type_id')->nullable()->after('product_id');
            $table->foreign('issue_type_id')->references('issue_type_id')->on('issue_types')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->dropForeign(['issue_type_id']);
            $table->dropColumn('issue_type_id');
        });
    }
};
