<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the sales.
     */
    public function index()
    {
        $sales = Sale::with('saleDetails')->get();
        return response()->json($sales);
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
        ]);

        $sale = Sale::create($validated);
        return response()->json($sale, 201);
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        $sale->load('saleDetails');
        return response()->json($sale);
    }

    /**
     * Update the specified sale in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'date' => 'sometimes|required|date',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'payment_method' => 'sometimes|required|string|max:255',
        ]);

        $sale->update($validated);
        return response()->json($sale);
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        return response()->json(null, 204);
    }
}