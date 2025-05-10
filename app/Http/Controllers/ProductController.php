<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|unique:products,barcode',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'barcode' => 'sometimes|required|unique:products,barcode,' . $product->id,
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'supplier_id' => 'sometimes|required|exists:suppliers,id',
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}