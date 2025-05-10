<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryTransactionController extends Controller
{
    /**
     * Display a listing of the inventory transactions.
     */
    public function index()
    {
        $transactions = InventoryTransaction::with('product')->get();
        return response()->json($transactions);
    }

    /**
     * Store a newly created inventory transaction in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'transaction_type' => 'required|string|in:stock-in,stock-out',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        $transaction = InventoryTransaction::create($validated);
        return response()->json($transaction, 201);
    }

    /**
     * Display the specified inventory transaction.
     */
    public function show(InventoryTransaction $inventoryTransaction)
    {
        $inventoryTransaction->load('product');
        return response()->json($inventoryTransaction);
    }

    /**
     * Update the specified inventory transaction in storage.
     */
    public function update(Request $request, InventoryTransaction $inventoryTransaction)
    {
        $validated = $request->validate([
            'product_id' => 'sometimes|required|exists:products,id',
            'transaction_type' => 'sometimes|required|string|in:stock-in,stock-out',
            'quantity' => 'sometimes|required|integer|min:1',
            'date' => 'sometimes|required|date',
        ]);

        $inventoryTransaction->update($validated);
        return response()->json($inventoryTransaction);
    }

    /**
     * Remove the specified inventory transaction from storage.
     */
    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        $inventoryTransaction->delete();
        return response()->json(null, 204);
    }
}