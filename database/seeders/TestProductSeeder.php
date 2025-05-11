<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Supplier;

class TestProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test supplier if none exists
        $supplier = Supplier::firstOrCreate(
            ['name' => 'Test Supplier'],
            [
                'contact_info' => 'test@supplier.com',
                'address' => '123 Test Street'
            ]
        );

        // Create test products with new barcodes
        Product::create([
            'barcode' => '987654321',
            'name' => 'Test Product 1',
            'description' => 'A test product for QR scanning',
            'price' => 9.99,
            'stock_quantity' => 100,
            'supplier_id' => $supplier->id
        ]);

        Product::create([
            'barcode' => '456789123',
            'name' => 'Test Product 2',
            'description' => 'Another test product for QR scanning',
            'price' => 19.99,
            'stock_quantity' => 50,
            'supplier_id' => $supplier->id
        ]);
    }
} 