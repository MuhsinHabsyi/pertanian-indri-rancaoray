<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Landing Page (Public)
Route::get('/', function () {
    return view('landing');
});

// Dashboard & Internal Pages Grouped under auth:internal guard
Route::middleware(['auth:internal'])->group(function () {
    // Dashboard (Internal)
    Route::get('/dashboard', function () {
        $activeBatches = 0;
        $monthlyExpense = 0;
        $totalHarvest = 0;

        if (\Illuminate\Support\Facades\Schema::hasTable('production_schedules')) {
            $activeBatches = \App\Models\ProductionSchedule::where('status', 'Ongoing')->count();
            $totalHarvest = \App\Models\ProductionSchedule::where('status', 'Completed')->sum('total_harvest');
        }
        
        if (\Illuminate\Support\Facades\Schema::hasTable('purchases')) {
            $monthlyExpense = \App\Models\Purchase::where('validation_status', 'Approved')
                ->whereYear('submission_date', date('Y'))
                ->whereMonth('submission_date', date('m'))
                ->sum('total_cost');
        }

        $latitude = -7.0345;
        $longitude = 107.6186;
        $weather = null;

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current_weather' => true,
                'daily' => 'temperature_2m_max,weather_code',
                'timezone' => 'Asia/Jakarta'
            ]);

            if ($response->successful()) {
                $weather = $response->json();
            }
        } catch (\Exception $e) {
            // Fallback handled by ?? in blade
        }

        return view('dashboard', compact('activeBatches', 'monthlyExpense', 'totalHarvest', 'weather'));
    });

    // Pembiayaan Produksi
    Route::get('/purchases', [PurchaseController::class, 'index']);
    Route::post('/purchases', [PurchaseController::class, 'store']); // Aksi Mengajukan
    Route::patch('/purchases/{purchase}/validate', [PurchaseController::class, 'validatePurchase']); // Aksi Validasi
    Route::post('/purchases/{purchase}/upload-receipt', [PurchaseController::class, 'uploadReceipt']); // Unggah Nota Gambar
    Route::get('/purchases/{purchase}/receipt', [PurchaseController::class, 'showReceipt']); // Tampilkan Gambar Nota langsung via Controller
    Route::patch('/purchases/{purchase}/update-cost', [PurchaseController::class, 'updateCost']); // Pemilik Update Biaya Realisasi Nota
    Route::patch('/purchases/{purchase}/realize', [PurchaseController::class, 'realize']); // Aksi Realisasi (Konfirmasi Barang Masuk oleh Pemilik)

    // Modul Penggunaan Stok Sarana (Halaman 1)
    Route::get('/production/usage', [ProductionController::class, 'indexUsage']);
    Route::post('/production/usage/seed', [ProductionController::class, 'storeSeedUsage']);
    Route::post('/production/usage/fertilizer', [ProductionController::class, 'storeFertilizerUsage']);

    // Modul Pencatatan Hasil Panen (Halaman 2)
    Route::get('/production/harvest', [ProductionController::class, 'indexHarvest']);
    Route::patch('/production/harvest/{schedule}', [ProductionController::class, 'storeHarvest']);

    // Penjualan Hasil Panen (Internal Actions)
    Route::get('/internal/orders', [OrderController::class, 'indexInternal']);
    Route::post('/internal/orders', [OrderController::class, 'store']);
    Route::get('/internal/orders/{order}', [OrderController::class, 'show']);
    Route::get('/internal/orders/{order}/nota', [OrderController::class, 'nota']);
    Route::patch('/internal/orders/{order}/confirm', [OrderController::class, 'confirm']);
    Route::patch('/internal/orders/{order}/complete', [OrderController::class, 'complete']);
    Route::patch('/internal/orders/{order}/courier-phone', [OrderController::class, 'updateCourierPhone']);

    // Laporan Operasional
    Route::get('/reports', [ReportController::class, 'index']);
    Route::post('/reports/generate', [ReportController::class, 'generate']);
    Route::match(['get', 'post'], '/reports/download', [ReportController::class, 'download']);

    // Pemantauan Informasi Cuaca
    Route::get('/weather', [WeatherController::class, 'index']);

    // Kelola Penjualan Online (Owner only)
    Route::get('/internal/online-products', [OrderController::class, 'indexOnlineProducts']);
    Route::post('/internal/online-products/{product}/package/{size}', [OrderController::class, 'updatePackageSettings']);

    // Kelola Data Pengguna Internal (Owner only)
    Route::get('/internal/users', [UserController::class, 'index']);
    Route::post('/internal/users', [UserController::class, 'store']);
    Route::put('/internal/users/{user}', [UserController::class, 'update']);
    Route::delete('/internal/users/{user}', [UserController::class, 'destroy']);
});

// Penjualan Hasil Panen (Customer catalog & history)
Route::get('/orders', [OrderController::class, 'indexCustomer']);
Route::get('/orders/{id}/success', [OrderController::class, 'paymentSuccess']);

// Fitur E-commerce Cart
Route::post('/cart/add', [OrderController::class, 'addToCart']);
Route::get('/cart', [OrderController::class, 'viewCart']);
Route::post('/cart/remove/{cartId}', [OrderController::class, 'removeFromCart']);
Route::post('/cart/checkout', [OrderController::class, 'checkout']);

// Halaman Autentikasi Pelanggan
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register']);

// Halaman Autentikasi Staf Internal (Owner & Operational)
Route::get('/internal/login', [AuthController::class, 'showInternalLoginForm'])->name('internal.login');
Route::post('/internal/login', [AuthController::class, 'internalLogin']);

// Logout routes
Route::any('/internal/logout', [AuthController::class, 'internalLogout']);
Route::any('/logout', [AuthController::class, 'logout']);

// Profil Akun & Pelanggan
Route::get('/profile', [AuthController::class, 'profile']);

// Storage fallback route for uploaded files
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');
