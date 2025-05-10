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
         Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('supplier_id'); // Foreign key to suppliers table
            $table->dateTime('date'); // Date of the purchase order
            $table->decimal('total_amount', 10, 2); // Total amount of the purchase order
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('purchase_orders');
    }
};
