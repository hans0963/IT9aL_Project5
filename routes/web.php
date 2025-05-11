<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryTransactionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product routes
    Route::resource('products', ProductController::class);
    Route::get('/get-product-by-barcode', [ProductController::class, 'getProductByBarcode']); // Renamed route for barcode lookup

    // Supplier routes
    Route::resource('suppliers', SupplierController::class);

    // Sale routes
    Route::resource('sales', SaleController::class);

    // SaleDetail routes
    Route::prefix('sale-details')->group(function () {
        Route::get('/', [SaleDetailController::class, 'index'])->name('sale-details.index');
        Route::post('/', [SaleDetailController::class, 'store'])->name('sale-details.store');
        Route::get('/{saleDetail}', [SaleDetailController::class, 'show'])->name('sale-details.show');
        Route::put('/{saleDetail}', [SaleDetailController::class, 'update'])->name('sale-details.update');
        Route::delete('/{saleDetail}', [SaleDetailController::class, 'destroy'])->name('sale-details.destroy');
    });

    // PurchaseOrder routes
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::get('/purchase-orders/{purchaseOrder}/receipt', [PurchaseOrderController::class, 'receipt'])->name('purchase-orders.receipt');
    Route::post('/purchase-orders/store', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');

    // OrderDetail routes
    Route::prefix('order-details')->group(function () {
        Route::get('/', [OrderDetailController::class, 'index'])->name('order-details.index');
        Route::post('/', [OrderDetailController::class, 'store'])->name('order-details.store');
        Route::get('/{purchaseOrderDetail}', [OrderDetailController::class, 'show'])->name('order-details.show');
        Route::put('/{purchaseOrderDetail}', [OrderDetailController::class, 'update'])->name('order-details.update');
        Route::delete('/{purchaseOrderDetail}', [OrderDetailController::class, 'destroy'])->name('order-details.destroy');
    });

    // InventoryTransaction routes
    Route::prefix('inventory-transactions')->group(function () {
        Route::get('/', [InventoryTransactionController::class, 'index'])->name('inventory-transactions.index');
        Route::post('/', [InventoryTransactionController::class, 'store'])->name('inventory-transactions.store');
        Route::get('/{inventoryTransaction}', [InventoryTransactionController::class, 'show'])->name('inventory-transactions.show');
        Route::put('/{inventoryTransaction}', [InventoryTransactionController::class, 'update'])->name('inventory-transactions.update');
        Route::delete('/{inventoryTransaction}', [InventoryTransactionController::class, 'destroy'])->name('inventory-transactions.destroy');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/qr-scanner', function () {
        return Inertia::render('QRScanner', [
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    })->name('qr-scanner');
});

require __DIR__.'/auth.php';