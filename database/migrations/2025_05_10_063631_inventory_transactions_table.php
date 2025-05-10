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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('product_id'); // Foreign key to products table
            $table->string('transaction_type'); // Transaction type (stock-in, stock-out)
            $table->integer('quantity'); // Quantity of the transaction
            $table->dateTime('date'); // Date and time of the transaction
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('inventory_transactions');
    }
};
