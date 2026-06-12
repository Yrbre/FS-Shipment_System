<?php

use App\Http\Controllers\ImcVerifController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\ItemController;
use App\Http\Controllers\Master\StatusController;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\WarehouseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->prefix('master')->name('master.')->group(function () {
    Route::resource('departments', DepartmentController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('items', ItemController::class);
    Route::resource('statuses', StatusController::class);
    Route::resource('users', UserController::class);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/shipments', ShipmentController::class);
    Route::get('/shipments/{shipment}/history/{history}', [ShipmentController::class, 'history'])
    ->name('shipments.history');
    Route::get('/imc', [ImcVerifController::class, 'index'])->name('imc.index');
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
