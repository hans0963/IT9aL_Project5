Route::middleware('auth:sanctum')->group(function () {
    // ... existing routes ...

    // Get product by barcode
    Route::get('/products/barcode/{barcode}', function ($barcode) {
        $product = \App\Models\Product::where('barcode', $barcode)->first();
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        return response()->json($product);
    });

    // Process sale
    Route::post('/sales', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string'
        ]);

        try {
            \DB::beginTransaction();

            // Create sale record
            $sale = \App\Models\Sale::create([
                'date' => now(),
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method
            ]);

            // Create sale details and update inventory
            foreach ($request->items as $item) {
                // Create sale detail
                \App\Models\SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Update product stock
                $product = \App\Models\Product::find($item['product_id']);
                $product->stock_quantity -= $item['quantity'];
                $product->save();

                // Create inventory transaction
                \App\Models\InventoryTransaction::create([
                    'product_id' => $item['product_id'],
                    'transaction_type' => 'stock-out',
                    'quantity' => $item['quantity'],
                    'date' => now()
                ]);
            }

            \DB::commit();

            return response()->json(['success' => true, 'sale_id' => $sale->id]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Failed to process sale'], 500);
        }
    });
}); 