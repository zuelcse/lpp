<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('items',[ApiController::class,'itemsCreateOrUpdate']);

Route::get('ledgers',[ApiController::class,'ledgers']);
Route::get('receipt-voucher-types',[ApiController::class,'receiptVoucherTypes']);
Route::get('cost-centers',[ApiController::class,'costCenters']);
Route::get('ledgers-bank-cash',[ApiController::class,'ledgerBankCash']);

Route::post('receipt-post',[ApiController::class,'receiptPost']);
Route::get('receipt-list',[ApiController::class,'receiptList']);

Route::get('receipt/unpostedlist',[ReceiptController::class,'unpostedlist']);
Route::post('receipt/posted2tally',[ReceiptController::class,'posted2tally']);


Route::get('sales/unpostedlist',[SalesController::class,'allSaleOrder']);
Route::get('sales/unpostedlisttest',[SalesController::class,'unpostedlist']);
Route::post('sales/posted2tally',[SalesController::class,'postedSalesOrder2tally']);