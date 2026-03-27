<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // stock_low, stock_over, system, etc.
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable(); // icon class or name
            $table->string('color')->default('blue'); // blue, red, yellow, green
            $table->string('link')->nullable(); // link to related page
            $table->string('product_id', 20)->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // specific user or null for all
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('set null');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->index(['user_id', 'read_at']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
