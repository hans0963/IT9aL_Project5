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
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('barcode')->unique(); 
            $table->string('name'); 
            $table->text('description')->nullable(); 
            $table->decimal('price', 10, 2); 
            $table->integer('stock_quantity')->default(0); 
            $table->unsignedBigInteger('supplier_id'); 
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
