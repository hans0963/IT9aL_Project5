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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('purchase_order_id'); // Foreign key to purchase orders table
            $table->unsignedBigInteger('product_id'); // Foreign key to products table
            $table->integer('quantity'); // Quantity of the product
            $table->decimal('price', 10, 2); // Price of the product
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('purchase_order_details');
    }
};
