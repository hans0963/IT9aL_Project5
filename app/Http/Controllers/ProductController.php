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
            $products = Product::all();
            return view('products.index', compact('products')); // Return the index blade with all products
        }

    /**
     * Show the form for creating a new product.
     */
    public function create()
        {
            $suppliers = \App\Models\Supplier::all();
            return view('products.create', compact('suppliers')); // Return the create blade
        }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'supplier_id' => 'required|exists:suppliers,id',
            ]);

            // Generate a unique numeric barcode
            $validated['barcode'] = $this->generateBarcode();

            Product::create($validated);
            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        }

    public function show(Product $product)
        {
            return view('products.show', compact('product')); // Return a show blade (if needed)
        }

    public function edit(Product $product)
        {
            $products = Product::all();
            $suppliers = \App\Models\Supplier::all();
            return view('products.edit', compact('product', 'suppliers')); 
        }


    public function update(Request $request, Product $product)
        {
            $validated = $request->validate([
                'barcode' => 'sometimes|required|unique:products,barcode,' . $product->id . '|regex:/^\d+$/', // Ensure barcode is numeric
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'stock_quantity' => 'sometimes|required|integer|min:0',
                'supplier_id' => 'sometimes|required|exists:suppliers,id',
            ]);

            $product->update($validated);
            return redirect()->route('products.index')->with('success', 'Product updated successfully!');
        }

    public function destroy(Product $product)
        {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully!'); // Redirect to index with success message
        }

    private function generateBarcode()
        {
            do {
                $barcode = str_pad(mt_rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
            } while (Product::where('barcode', $barcode)->exists());

            return $barcode;
        }

    public function getProductByBarcode(Request $request)
        {
            // Validate that the barcode is provided
            $request->validate([
                'barcode' => 'required|string',
            ]);

            // Find the product by barcode
            $product = Product::where('barcode', $request->barcode)->first();

            // If the product is not found, return a 404 response
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Return the product details as JSON
            return response()->json($product);
        }
}