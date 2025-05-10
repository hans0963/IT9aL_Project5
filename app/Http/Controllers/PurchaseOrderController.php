<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the purchase orders.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->get();
        return response()->json($purchaseOrders);
    }

    /**
     * Store a newly created purchase order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = PurchaseOrder::create($validated);
        return response()->json($purchaseOrder, 201);
    }

    /**
     * Display the specified purchase order.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier');
        return response()->json($purchaseOrder);
    }

    /**
     * Update the specified purchase order in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'supplier_id' => 'sometimes|required|exists:suppliers,id',
            'date' => 'sometimes|required|date',
            'total_amount' => 'sometimes|required|numeric|min:0',
        ]);

        $purchaseOrder->update($validated);
        return response()->json($purchaseOrder);
    }

    /**
     * Remove the specified purchase order from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return response()->json(null, 204);
    }
}