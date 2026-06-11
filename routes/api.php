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

Route::post('login', [ApiController::class, 'login']);

Route::post('user-dashboard',[ApiController::class,'userDashboard']);
Route::post('change-password', [ApiController::class, 'changePassword']);
Route::post('sale-delete', [ApiController::class, 'saleDelete']);
Route::post('receipt-delete', [ApiController::class, 'receiptDelete']);
Route::post('change-status', [ApiController::class, 'changeStatus']);

Route::post('items',[ApiController::class,'itemsCreateOrUpdate']);

Route::get('ledgers',[ApiController::class,'ledgers']);
Route::post('ledger-report',[ApiController::class,'ledgerReport']);
Route::post('day-book-report',[ApiController::class,'dayBookReport']);
Route::get('receipt-voucher-types',[ApiController::class,'receiptVoucherTypes']);
Route::get('units',[ApiController::class,'units']);
Route::get('stock-items',[ApiController::class,'stockItems']);

Route::get('cost-centers',[ApiController::class,'costCenters']);
Route::get('ledgers-bank-cash',[ApiController::class,'ledgerBankCash']);

Route::post('voucher_no',[ApiController::class,'voucherNo']);

Route::post('receipt-post',[ApiController::class,'receiptPost']);
Route::any('receipt-list',[ApiController::class,'receiptList']);
Route::post('receipt-details',[ApiController::class,'receiptDetails']);

Route::post('sales-post',[ApiController::class,'salesPost']);
Route::post('sales-list',[ApiController::class,'salesList']);
Route::post('sales-details',[ApiController::class,'salesDetails']);//post

Route::get('receipt/unpostedlist',[ReceiptController::class,'unpostedlist']);
Route::post('receipt/posted2tally',[ReceiptController::class,'posted2tally']);


Route::get('sales-voucher-types',[ApiController::class,'salesVoucherTypes']);

// Route::get('sales/unpostedlist',[SalesController::class,'allSaleOrder']);
Route::get('sales/unpostedlisttest',[SalesController::class,'unpostedlist']);
Route::post('sales/posted2tally',[SalesController::class,'postedSalesOrder2tally']);
Route::post('sales/update',[ApiController::class,'updateSalesAPI']);
Route::post('receipt/update',[ApiController::class,'updateReceiptAPI']);


Route::group(['prefix' => 'v2'], function () {
	Route::middleware('auth:api')->group(function () {

		Route::post('user-dashboard',[ApiController::class,'userDashboard']);
		Route::post('change-password', [ApiController::class, 'changePassword']);
		Route::post('sale-delete', [ApiController::class, 'saleDelete']);
		Route::post('receipt-delete', [ApiController::class, 'receiptDelete']);
		Route::post('change-status', [ApiController::class, 'changeStatus']);

		Route::post('items',[ApiController::class,'itemsCreateOrUpdate']);

		Route::get('ledgers',[ApiController::class,'ledgers']);
		Route::post('ledger-report',[ApiController::class,'ledgerReport']);
		Route::post('day-book-report',[ApiController::class,'dayBookReport']);
		Route::get('receipt-voucher-types',[ApiController::class,'receiptVoucherTypes']);
		Route::get('units',[ApiController::class,'units']);
		Route::get('stock-items',[ApiController::class,'stockItems']);

		Route::get('cost-centers',[ApiController::class,'costCenters']);
		Route::get('ledgers-bank-cash',[ApiController::class,'ledgerBankCash']);

		Route::post('voucher_no',[ApiController::class,'voucherNo']);

		Route::post('receipt-post',[ApiController::class,'receiptPost']);
		Route::any('receipt-list',[ApiController::class,'receiptList']);
		Route::post('receipt-details',[ApiController::class,'receiptDetails']);

		Route::post('sales-post',[ApiController::class,'salesPost']);
		Route::post('sales-list',[ApiController::class,'salesList']);
		Route::post('sales-details',[ApiController::class,'salesDetails']);//post

		Route::get('receipt/unpostedlist',[ReceiptController::class,'unpostedlist']);
		Route::post('receipt/posted2tally',[ReceiptController::class,'posted2tally']);


		Route::get('sales-voucher-types',[ApiController::class,'salesVoucherTypes']);

		Route::get('sales/unpostedlist',[SalesController::class,'allSaleOrder']);
		Route::get('sales/unpostedlisttest',[SalesController::class,'unpostedlist']);
		Route::post('sales/posted2tally',[SalesController::class,'postedSalesOrder2tally']);
		Route::post('sales/update',[ApiController::class,'updateSalesAPI']);
		Route::post('receipt/update',[ApiController::class,'updateReceiptAPI']);

	});
});