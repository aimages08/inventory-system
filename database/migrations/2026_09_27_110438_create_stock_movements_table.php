<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            // in | out | adjustment
            $table->string('type', 20);

            // positive for in, negative for out (we also keep `quantity` as absolute)
            $table->integer('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');

            // reason: purchase, sale, adjustment, damaged, expired, transfer, opening
            $table->string('reason', 30)->nullable();

            // polymorphic-ish references (nullable)
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type', 100)->nullable();

            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['product_id', 'created_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};