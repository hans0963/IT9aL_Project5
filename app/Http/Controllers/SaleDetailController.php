<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    /**
     * Display a listing of the sale details.
     */
    public function index()
    {
        $saleDetails = SaleDetail::with(['sale', 'product'])->get();
        return response()->json($saleDetails);
    }

    /**
     * Store a newly created sale detail in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $saleDetail = SaleDetail::create($validated);
        return response()->json($saleDetail, 201);
    }

    /**
     * Display the specified sale detail.
     */
    public function show(SaleDetail $saleDetail)
    {
        $saleDetail->load(['sale', 'product']);
        return response()->json($saleDetail);
    }

    /**
     * Update the specified sale detail in storage.
     */
    public function update(Request $request, SaleDetail $saleDetail)
    {
        $validated = $request->validate([
            'sale_id' => 'sometimes|required|exists:sales,id',
            'product_id' => 'sometimes|required|exists:products,id',
            'quantity' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
        ]);

        $saleDetail->update($validated);
        return response()->json($saleDetail);
    }

    /**
     * Remove the specified sale detail from storage.
     */
    public function destroy(SaleDetail $saleDetail)
    {
        $saleDetail->delete();
        return response()->json(null, 204);
    }
}