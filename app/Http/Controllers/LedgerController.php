<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Models\Unit;


use App\Models\StockItem;
use App\Models\Group;
use App\Models\Subgroups;
use App\Models\Regions;
use App\Models\Areas;
use App\Models\Terriitorys;
use App\Models\Manufacturer;
use App\Models\WorkName;

use Illuminate\Support\Facades\DB;

class LedgerController extends Controller
{
  public function index()
  {
    // $ledgers = Ledger::getLedgers();
    $ledgers = Ledger::with("Group","Subgroups")->orderBy('name','ASC')
              ->paginate(60);
    // dd($ledgers);
    return view('application.ledger.index', compact('ledgers'));
  }

  public function create(){
    $allGroup = Group::getAll();
    $regions = Regions::pluck('name','id');
    return view('application.ledger.create', compact('allGroup','regions'));
  }

  public function createAction(Request $request){
    $request->validate(
      [
        'name' => 'required|string|max:255|unique:ledgers,name',
        'main_group_id' => 'required',
        'sub_group_id' => 'required',
        'opening_balance' => 'nullable|numeric'
      ],
      [
        'name.required' => 'Name is required',
        'name.unique' => 'This Ledger Name already exists',
        'main_group_id.required' => 'Main Group is required',
        'sub_group_id.required' => 'Sub Group is required',
        'opening_balance.nullable' => 'Opening Balance is only numeric'
      ]
    );
    // dd($request->all());
    $maxSub = Ledger::where('sub_group_id',$request->sub_group_id)->max('alias');
    if($maxSub) {
      $maxSub = sprintf("%010d",($maxSub + 1));
    }else{
      $groupAlias = Subgroups::select('alias')->where('id',$request->sub_group_id)->first();
      $maxSub = sprintf("%05d",($groupAlias->alias)).'00001';
    }

    $request->merge(['alias' => $maxSub]);
    // dd($request->all());
    $ledger = Ledger::create($request->all());

    return redirect()
      ->back()
      ->with('success', 'Successfully creted!');
  }


  public function update(Request $request, $id=null){
    if ($request->isMethod('post')){
      $ledgerInfo = Ledger::where('id',$id)->where('is_editable','!=', 0)->first();
      if($ledgerInfo->sub_group_id != $request->sub_group_id){
        $maxSub = Ledger::where('sub_group_id',$request->sub_group_id)->max('alias');
        if($maxSub) {
          $maxSub = sprintf("%010d",($maxSub + 1));
        }else{
          $groupAlias = Subgroups::select('alias')->where('id',$request->sub_group_id)->first();
          $maxSub = sprintf("%05d",($groupAlias->alias)).'00001';
        }

        $request->merge(['alias' => $maxSub]);
      }
      // dd($request->all());

      $result=Ledger::where('id',$id)->where('is_editable','!=', 0)
          ->update($request->except(['_token', '_url']));

      if($result){
          $msgtype='success';
          $msg='Update successfully.';
      }else{
          $msgtype='error';
          $msg='Update unsuccessfully.';
      }

      return redirect('setting/ledger')->with($msgtype,$msg);
    }
    
    $ledger = Ledger::where('id',$request->id)->first();
    
    $allGroup = Group::getAll();
    $allSubGroup = Subgroups::select('id', DB::raw("CONCAT(alias , ' - ', name) as name"))
                        ->where('main_group_id',$ledger->main_group_id)->pluck('name','id');
    $regions = Regions::pluck('name','id');
    $areas = Areas::pluck('name','id');
    $terriitorys = Terriitorys::pluck('name','id');

    
    // dd($allSubGroup);
    if($ledger->is_editable === 0){
        exit('This "'.$ledger->name.'" ledger is not editable!');
    }
    return view('application.ledger.update', compact('allGroup','allSubGroup','regions','areas','terriitorys','ledger'));
  }

  public function workNames($id){
    /*$work_names = WorkName::selectRaw("id, CONCAT(name, ' - ', name_bn) as name")->where('debit_head',$id)
                  ->orderBy('name','ASC')->pluck('name','id');*/

    $work_names = WorkName::selectRaw("id, name")->where('debit_head',$id)
                  ->orderBy('name','ASC')->pluck('name','id');
  
    // dd($work_names);
    $closing_balance = Ledger::select('closing_balance')->where('id',$id)->first()->closing_balance;
    return response()->json([
        'work_names' => $work_names,
        'closing_balance' => $closing_balance
    ]);

  }

}
