<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\VoucherType;
use App\Models\VoucherNumbers;
use App\Models\Receipt;
use App\Models\SalesOrder;
use App\Models\DeletedVoucher;
use App\Models\Subgroups;
use App\Models\Areas;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Terriitorys;
use App\Models\Group;
use App\Models\StockItem;
use App\Models\Ledger;
use App\Models\WorkName;
use App\Models\WorkType;
use App\Models\MasterSize;
use App\Models\MasterColor;
use App\Models\MasterWeight;
use App\Models\MasterPaper;
use App\Models\MasterLamination;

use Auth;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function group(){
        $groups = Group::paginate(60);
        // dd($groups);
        return view('application.setting.groupList', compact('groups'));
    }

    public function subGroup(){
        $subgroups = Subgroups::with('Group')->orderBy('alias','ASC')->paginate(50);
        // dd($subgroups);
        return view('application.setting.subGroupList', compact('subgroups'));
    }

    public function subGroupCreate(){
        $unit = new Group();
        $allGroup = $unit->getAll();
        return view('application.setting.subGroupCreate', compact('allGroup'));
    }

    public function subGroupCreateAction(Request $request){
        $request->validate(
            [
                'main_group_id' => 'required',
                'name' => 'required|string|max:255|unique:group_sub',
            ],
            [
                'main_group_id.required' => 'Group is required',
                'name.required' => 'Name is required',
            ]
        );

        $maxSub = Subgroups::where('main_group_id',$request->main_group_id)->max('alias');
        if($maxSub) {
            $maxSub = sprintf("%05d",($maxSub + 1));
        }else{
            $groupAlias = Group::select('alias')->where('id',$request->main_group_id)->first();
            $maxSub = sprintf("%02d",($groupAlias->alias)).'001';
        }
        $request->merge(['alias' => $maxSub]);
        // dd($request->all());

        Subgroups::create($request->all());

        return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');
    }

    public function subGroupUpdate(Request $request, $id=null){
        if ($request->isMethod('post')){
            // dd($request->all());
            $result=Subgroups::where('id',$id)->where('is_editable','!=', 0)->update($request->only(['main_group_id','name','is_active']));

            if($result){
                $msgtype='success';
                $msg='Update successfully.';
            }else{
                $msgtype='error';
                $msg='Update unsuccessfully.';
            }

            return redirect('setting/subgroup')->with($msgtype,$msg);
        }
        $unit = new Group();
        $allGroup = $unit->getAll();

        $subgroup = Subgroups::where('id',$request->id)->first();
        if($subgroup->is_editable === 0){
            exit('This "'.$subgroup->name.'" subgroup is not editable!');
        }
        // dd($subgroup);  
        return view('application.setting.subGroupUpdate', compact('allGroup','subgroup'));
    }

    public function subGroupUpdateAction(Request $request){
        $request->validate(
            [
                'main_group_id' => 'required',
                'name' => 'required|string|max:255|unique:group_sub',
            ],
            [
                'main_group_id.required' => 'Group is required',
                'name.required' => 'Name is required',
            ]
        );

        $maxSub = Subgroups::where('main_group_id',$request->main_group_id)->max('alias');
        if($maxSub) {
            $maxSub = sprintf("%05d",($maxSub + 1));
        }else{
            $groupAlias = Group::select('alias')->where('id',$request->main_group_id)->first();
            $maxSub = sprintf("%02d",($groupAlias->alias)).'001';
        }
        $request->merge(['alias' => $maxSub]);
        // dd($request->all());

        Subgroups::create($request->all());

        return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');
    }

    public function voucherType(){
        $voucherTypes = new VoucherType();
        $voucherTypes = $voucherTypes->getAllVoucherTypes();
        // dd($voucherTypes);
        return view('application.setting.vouchertype', compact('voucherTypes'));
    }

    public function item(){
        $unit = new Unit();
        $allUnits = $unit->getAllUnits();
        $group = new Category();
        $allGroup = $group->getAll();
        $manufacturer = new Manufacturer();
        $allManufacturer = $manufacturer->getAll();
        $stockItem = StockItem::orderBy('name', 'ASC')->orderBy('alias', 'ASC')->paginate(10);
        // dd($stockItem);
        return view('application.setting.item', compact('stockItem', 'allUnits','allGroup','allManufacturer'));
    }

    public function itemUpdate(Request $request, $id=null){
        if ($request->isMethod('post')){
          $result=StockItem::where('id',$id)
              ->update($request->except(['_token']));

          if($result){
              $msgtype='success';
              $msg='Update successfully.';
          }else{
              $msgtype='error';
              $msg='Update unsuccessfully.';
          }

          return redirect('setting/item')->with($msgtype,$msg);
        }

        $unit = new Unit();
        $allUnits = $unit->getAllUnits();
        $group = new Category();
        $allGroup = $group->getAll();
        $manufacturer = new Manufacturer();
        $allManufacturer = $manufacturer->getAll();

        $item = StockItem::where('id',$request->id)->first();
        // dd($item);

        return view('application.setting.item-update', compact('item', 'allUnits','allGroup','allManufacturer'));
    }



    // work-name

    public function workName(){
        $data = WorkName::with('Ledger')->orderBy('name','ASC')->paginate(50);
        // dd($data);
        return view('application.setting.workname.list', compact('data'));
    }

    public function workNameCreate(){
        // $unit = new Ledger();
        $ledgers = Ledger::orderBy('name',"ASC")->get()->pluck('name','id');
        // $allGroup = $unit->getAll();
        return view('application.setting.workname.create', compact('ledgers'));
    }

    public function workNameCreateAction(Request $request){
        $request->validate(
            [
                'debit_head' => 'required',
                'name' => 'required|string|max:255|unique:work_names',
            ],
            [
                'debit_head.required' => 'Ledger is required',
                'name.required' => 'Name is required',
            ]
        );

        // dd($request->all());
        WorkName::create($request->all());

        return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');
    }

    public function workNameUpdate(Request $request, $id=null){
        if ($request->isMethod('post')){
            // dd($request->all());
            $result=WorkName::where('id',$id)
              ->update($request->except(['_token']));

            if($result){
                $msgtype='success';
                $msg='Update successfully.';
            }else{
                $msgtype='error';
                $msg='Update unsuccessfully.';
            }

            return redirect('setting/work-name')->with($msgtype,$msg);
        }

        $ledgers = Ledger::orderBy('name',"ASC")->get()->pluck('name','id');

        $data = WorkName::where('id',$request->id)->first();

        // dd($data);  
        return view('application.setting.workname.update', compact('ledgers','data'));
    }

    public function workNameUpdateAction(Request $request){
        $request->validate(
            [
                'main_group_id' => 'required',
                'name' => 'required|string|max:255|unique:group_sub',
            ],
            [
                'main_group_id.required' => 'Group is required',
                'name.required' => 'Name is required',
            ]
        );

        $maxSub = Subgroups::where('main_group_id',$request->main_group_id)->max('alias');
        if($maxSub) {
            $maxSub = sprintf("%05d",($maxSub + 1));
        }else{
            $groupAlias = Group::select('alias')->where('id',$request->main_group_id)->first();
            $maxSub = sprintf("%02d",($groupAlias->alias)).'001';
        }
        $request->merge(['alias' => $maxSub]);
        // dd($request->all());

        Subgroups::create($request->all());

        return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');
    }
    // work-name--/--

    // /master_size

    public function masterSize(){
        $data = MasterSize::orderBy('name','ASC')->paginate(50);
        // dd($data);
        return view('application.setting.masterSizeList', compact('data'));
    }

    public function masterSizeCreate(Request $request){
        $request->validate(
            [
                'name' => 'required|unique:master_sizes,name',
                'name_bn' => 'required|string|max:255',
            ],
            [
                'name.required' => 'Group is required',
                'name.unique' => 'This name already exists.',
                'name_bn.required' => 'Name Bn is required',
            ]
        );
        MasterSize::create($request->all());

        return response()->json([
            'message' => 'Successfully updated!'
        ]);
        /*
        return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');*/
    }

    public function masterSizeUpdate(Request $request){
        // dd($request->all());
        $request->validate(
            [
                'name' => 'required|unique:master_sizes,name,'.$request->id,
                'name_bn' => 'required|string|max:255',
            ],
            [
                'name.required' => 'Group is required',
                'name.unique' => 'This name already exists.',
                'name_bn.required' => 'Name Bn is required',
            ]
        );
       
        MasterSize::where('id',$request->id)->update([
            'name'=>$request->name,
            'name_bn'=>$request->name_bn
        ]);
        return response()->json([
            'message' => 'Successfully updated!'
        ]);
        /*return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');*/
    }
    // /master_size--/--
    // basicColor

    public function masterColor(){
        $data = MasterColor::orderBy('name','ASC')->paginate(50);
        // dd($data);
        return view('application.setting.masterColorList', compact('data'));
    }

    public function masterColorCreate(Request $request){
        $request->validate(
            [
                'name' => 'required|unique:master_colors,name',
                'name_bn' => 'required|string|max:255',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'This name already exists.',
                'name_bn.required' => 'Name Bn is required',
            ]
        );
        MasterColor::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Updated successfully.'
        ]);
        /*
        return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');*/
    }
    // work-name--/--


    // /master_weight
    public function masterWeight(){
        $data = MasterWeight::orderBy('name','ASC')->paginate(50);
        // dd($data);
        return view('application.setting.masterWeightList', compact('data'));
    }

    public function masterWeightCreate(Request $request){
        $request->validate(
            [
                'name' => 'required|unique:master_weights,name',
                'name_bn' => 'required|string|max:255',
            ],
            [
                'name.required' => 'Group is required',
                'name.unique' => 'This name already exists.',
                'name_bn.required' => 'Name is required',
            ]
        );
        MasterWeight::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Updated successfully.'
        ]);
        /*return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');*/
    }
    // /master_weight--/--

    // /master_paper
    public function masterPaper(){
        $data = MasterPaper::orderBy('name','ASC')->paginate(50);
        // dd($data);
        return view('application.setting.masterPaperList', compact('data'));
    }

    public function masterPaperCreate(Request $request){
        $request->validate(
            [
                'name' => 'required|unique:master_papers,name',
                'name_bn' => 'required|string|max:255',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'This name already exists.',
                'name_bn.required' => 'Name is required',
            ]
        );
        MasterPaper::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Updated successfully.'
        ]);
        /*return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');*/
    }
    // /master_paper--/--

    // /master_paper
    public function masterLamination(){
        $data = MasterLamination::orderBy('name','ASC')->paginate(50);
        // dd($data);
        return view('application.setting.masterLaminationList', compact('data'));
    }

    public function masterLaminationCreate(Request $request){
        $request->validate(
            [
                'name' => 'required|unique:master_laminations,name',
                'name_bn' => 'required|string|max:255',
            ],
            [
                'name.required' => 'Group is required',
                'name.unique' => 'This name already exists.',
                'name_bn.required' => 'Name is required',
            ]
        );
        MasterLamination::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Updated successfully.'
        ]);
    }
    // /master_paper--/--

    // Common update
    public function update(Request $request){
        $modelClass = "App\\Models\\Master" . ucfirst($request->type);
        $model = new $modelClass;
        return $this->commonUpdate($request, $model);
    }
    protected function commonUpdate(Request $request, $model){
        $id = $request->id;
        $request->validate([
            'name' => [
                'required',
                Rule::unique($model->getTable(), 'name')->ignore($id),
            ],
            'name_bn' => 'required|string|max:255',
        ]);

        $record = $model::findOrFail($id);
        // dd($record);
        $record->update([
            'name'    => $request->name,
            'name_bn' => $request->name_bn,
            'status'  => $request->status ?? 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Updated successfully.'
        ]);
    }
    
}
