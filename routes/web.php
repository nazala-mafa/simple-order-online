<?php

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\OrderFromTableController;
use App\Http\Controllers\Seller\ProductCategoryController;
use App\Http\Controllers\Seller\ProductDisplayStatusController;
use App\Http\Controllers\Seller\ProductStockController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomepageController::class, 'index']);
Route::get('/display/{table}', [HomepageController::class, 'display'])->name('display');
Route::get('/products', [HomepageController::class, 'products']);

Route::get('/login', [AuthController::class, 'login']);
Route::post('/loginAttempt', [AuthController::class, 'loginAttempt']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/registerAttempt', [AuthController::class, 'registerAttempt']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/profile-setting', [AuthController::class, 'profile_setting'] );
Route::post('/profile-setting-attempt', [AuthController::class, 'profile_setting_attempt']);

Route::middleware('auth.role:admin')->prefix('admin')->group(function() {
  Route::get('/', function () { return view('admin.dashboard.index', ['title'=>'Dashboard Admin']); });
  Route::resource('table', TableController::class)->except(['create', 'show', 'edit']);
  Route::get('setting', [SettingController::class, 'index']);
  Route::put('setting/save', [SettingController::class, 'save'])->name('save_setting');
});

Route::middleware('auth.role:seller')->prefix('seller')->group(function() {
  Route::get('/', function () { return view('seller.dashboard.index', ['title'=>'Dashboard Seller']); });
  Route::post('products-stock', ProductStockController::class);
  Route::post('products-display-status', ProductDisplayStatusController::class);
  Route::get('products-image/{imageId}', [ProductController::class, 'destroyImage']);
  Route::resource('products-categories', ProductCategoryController::class)->except(['show']);
  Route::resource('products', ProductController::class)->except(['show']);
});

Route::middleware('auth.role:table')->prefix('table')->group(function() {
  Route::get('/', function () { return view('buyer.dashboard.index', ['title'=>'Dashboard Buyer']); });
});

Route::resource('table-order', OrderFromTableController::class)->except(['create', 'show', 'edit']);