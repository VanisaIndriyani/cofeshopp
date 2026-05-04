<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPosController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminStockController;
use App\Http\Controllers\Admin\AdminTableController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\TableMenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/table/{code}', [TableMenuController::class, 'show'])->name('table.menu');

Route::get('/qr/table/{code}.png', [AdminTableController::class, 'qrPublic'])
    ->where('code', '[A-Za-z0-9_-]+')
    ->name('table.qr.public');

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/order/{invoice}', [OrderTrackingController::class, 'show'])->name('order.show');
Route::get('/order/{invoice}/status', [OrderTrackingController::class, 'status'])->name('order.status');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [AdminDashboardController::class, 'data'])->name('dashboard.data');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/pending-count', [AdminOrderController::class, 'pendingCount'])->name('orders.pending-count');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{order}/print', [AdminOrderController::class, 'print'])->name('orders.print');

    Route::get('/pos', [AdminPosController::class, 'index'])->name('pos');
    Route::post('/pos', [AdminPosController::class, 'store'])->name('pos.store');

    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/stocks', [AdminStockController::class, 'index'])->name('stocks.index');
    Route::post('/stocks', [AdminStockController::class, 'store'])->name('stocks.store');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-excel', [AdminReportController::class, 'exportExcel'])->name('reports.excel');
    Route::get('/reports/export-pdf', [AdminReportController::class, 'exportPdf'])->name('reports.pdf');

    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('/tables', [AdminTableController::class, 'index'])->name('tables.index');
    Route::post('/tables', [AdminTableController::class, 'store'])->name('tables.store');
    Route::put('/tables/{table}', [AdminTableController::class, 'update'])->name('tables.update');
    Route::delete('/tables/{table}', [AdminTableController::class, 'destroy'])->name('tables.destroy');
    Route::get('/tables/{table}/qr', [AdminTableController::class, 'qr'])->name('tables.qr');
});

require __DIR__.'/auth.php';
