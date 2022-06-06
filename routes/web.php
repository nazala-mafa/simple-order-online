<?php

use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Buyer\CartController;
use App\Http\Controllers\Buyer\PaymentController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\Seller\ProductCategoryController;
use App\Http\Controllers\Seller\ProductDisplayStatusController;
use App\Http\Controllers\Seller\ProductStockController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomepageController::class, 'index']);
Route::get('/products', [HomepageController::class, 'products']);

Route::get('/login', [AuthController::class, 'login']);
Route::post('/loginAttempt', [AuthController::class, 'loginAttempt']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/registerAttempt', [AuthController::class, 'registerAttempt']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware('auth.role:admin')->prefix('admin')->group(function() {
  Route::get('/', function () { return view('admin.dashboard.index', ['title'=>'Dashboard Admin']); });
});

Route::middleware('auth.role:seller')->prefix('seller')->group(function() {
  Route::get('/', function () { return view('seller.dashboard.index', ['title'=>'Dashboard Seller']); });
  Route::post('products-stock', ProductStockController::class);
  Route::post('products-display-status', ProductDisplayStatusController::class);
  Route::get('products-image/{imageId}', [ProductController::class, 'destroyImage']);
  Route::resource('products-categories', ProductCategoryController::class)->except(['show']);
  Route::resource('products', ProductController::class)->except(['show']);
});

Route::middleware('auth.role:buyer')->prefix('buyer')->group(function() {
  Route::get('/', function () { return view('buyer.dashboard.index', ['title'=>'Dashboard Buyer']); });
  Route::resource('payments', PaymentController::class);
  Route::resource('carts', CartController::class);
});