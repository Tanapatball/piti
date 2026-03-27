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
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->string('reference_doc', 100)->nullable()->after('trans_id');
            $table->string('reference_no', 100)->nullable()->after('reference_doc');
            $table->string('code', 50)->nullable()->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            $table->dropColumn(['reference_doc', 'reference_no', 'code']);
        });
    }
};
