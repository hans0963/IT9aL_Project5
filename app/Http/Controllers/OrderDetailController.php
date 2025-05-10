<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the purchase order details.
     */
    public function index()
    {
        $details = OrderDetail::with(['purchaseOrder', 'product'])->get();
        return response()->json($details);
    }

    /**
     * Store a newly created purchase order detail in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $detail = OrderDetail::create($validated);
        return response()->json($detail, 201);
    }

    /**
     * Display the specified purchase order detail.
     */
    public function show(OrderDetail $purchaseOrderDetail)
    {
        $purchaseOrderDetail->load(['purchaseOrder', 'product']);
        return response()->json($purchaseOrderDetail);
    }

    /**
     * Update the specified purchase order detail in storage.
     */
    public function update(Request $request, OrderDetail $purchaseOrderDetail)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'sometimes|required|exists:purchase_orders,id',
            'product_id' => 'sometimes|required|exists:products,id',
            'quantity' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
        ]);

        $purchaseOrderDetail->update($validated);
        return response()->json($purchaseOrderDetail);
    }

    /**
     * Remove the specified purchase order detail from storage.
     */
    public function destroy(OrderDetail $purchaseOrderDetail)
    {
        $purchaseOrderDetail->delete();
        return response()->json(null, 204);
    }
}