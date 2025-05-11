<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    
    public function index()
        {
            $purchaseOrders = PurchaseOrder::with('supplier')->get();
            return view('purchase-orders.index', compact('purchaseOrders')); // Return the index blade with all purchase orders
        }

   public function create()
        {
            $suppliers = Supplier::all();
            return view('purchase-orders.create', compact('suppliers'));
        }

    public function store(Request $request)
        {
             dd($request->all());
             
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'date' => 'required|date',
                'total_amount' => 'required|numeric|min:0',
            ]);

            $purchaseOrder = PurchaseOrder::create($validated);

            if ($request->has('orderDetails')) {
                foreach (json_decode($request->orderDetails, true) as $detail) {
                    $purchaseOrder->orderItems()->create([
                        'product_id' => $detail['productId'],
                        'product_name' => $detail['productName'],
                        'price' => $detail['price'],
                        'quantity' => $detail['quantity'],
                        'total_price' => $detail['totalPrice'],
                    ]);
                }
            }

            return redirect()->route('purchase-orders.receipt', $purchaseOrder->id);
        }

    public function show(PurchaseOrder $purchaseOrder)
        {
            $purchaseOrder->load('supplier');
            return view('purchase-orders.show', compact('purchaseOrder')); // Return the show blade with the purchase order details
        }

    
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

    public function edit(PurchaseOrder $purchaseOrder)
        {
            $suppliers = Supplier::all();
            return view('purchase-orders.edit', compact('purchaseOrder', 'suppliers')); // Return the edit blade with the purchase order and suppliers
        }

    public function destroy(PurchaseOrder $purchaseOrder)
        {
            $purchaseOrder->delete();
            return redirect()->route('purchase-orders.index')->with('success', 'Purchase order deleted successfully!'); // Redirect to index with success message
        }

    public function getProduct(Request $request)
        {
            $product = Product::where('barcode', $request->barcode)->first();

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            return response()->json($product);
        }

    public function receipt(PurchaseOrder $purchaseOrder)
        {
            $purchaseOrder->load('supplier', 'orderItems');
            return view('purchase-orders.receipt', compact('purchaseOrder'));
        }
}