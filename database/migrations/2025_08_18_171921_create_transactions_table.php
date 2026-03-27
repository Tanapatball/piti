<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('trans_id', 50)->primary();
            $table->date('trans_date');
            $table->string('reference_doc', 100)->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->unsignedBigInteger('receive_type_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('receive_type_id')->references('receive_type_id')->on('receive_types')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
