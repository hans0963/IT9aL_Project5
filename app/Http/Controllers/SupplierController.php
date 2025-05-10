<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return response()->json($suppliers);
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier = Supplier::create($validated);
        return response()->json($supplier, 201);
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'contact_info' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier->update($validated);
        return response()->json($supplier);
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(null, 204);
    }
}