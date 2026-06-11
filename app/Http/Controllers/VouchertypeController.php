<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\VoucherType;

class VouchertypeController extends Controller
{
  public function index()
  {
    $vouchers = new Voucher();
    $voucherTypes = new VoucherType();
    $vouchers = $vouchers->getVouchers();
    $getVoucherType = $voucherTypes->getVoucherType();
    return view('application.vouchertype.index', compact('vouchers','getVoucherType'));
  }

  public function viewType()
  {
    $voucherTypes = new VoucherType();
    $voucherTypes = $voucherTypes->getAllVoucherTypes();
    return view('application.vouchertype.type', compact('voucherTypes'));
  }

  public function updateVoucherType(Request $request)
  {
    $VoucherType=VoucherType::where('id', $request->id)->first();
    $VoucherType->voucher_prefix=$request->voucher_prefix;
    $VoucherType->start_number=$request->start_number;
    $VoucherType->goddown=$request->goddown;
    $VoucherType->sales_account=$request->sales_account;
    $VoucherType->save();

    return back()->with('success','Update successfully');
  }

}
