<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/fetch-stocks', [ApiDataController::class, 'fetchStocks']);
Route::get('/fetch-incomes', [ApiDataController::class, 'fetchIncomes']);
Route::get('/fetch-sales', [ApiDataController::class, 'fetchSales']);
Route::get('/fetch-orders', [ApiDataController::class, 'fetchOrders']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
