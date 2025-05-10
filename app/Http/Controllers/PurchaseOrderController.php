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
        return view('purchase-orders.index', compact('purchaseOrders')); // Return the index blade with all purchase orders
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

        PurchaseOrder::create($validated);
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order created successfully!'); // Redirect to index with success message
    }

    /**
     * Display the specified purchase order.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier');
        return view('purchase-orders.show', compact('purchaseOrder')); // Return the show blade with the purchase order details
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
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order updated successfully!'); // Redirect to index with success message
    }

    /**
     * Remove the specified purchase order from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order deleted successfully!'); // Redirect to index with success message
    }
}