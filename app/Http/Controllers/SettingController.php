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
use App\Models\ItemSubCategory;
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
                'name.unique' => 'This name already exists.',
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
                'name.unique' => 'This name already exists.',
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
        $subCategory = new ItemSubCategory();
        $allSubCategory = $subCategory->getAll();
        $manufacturer = new Manufacturer();
        $allManufacturer = $manufacturer->getAll();
        $stockItem = StockItem::orderBy('name', 'ASC')->orderBy('alias', 'ASC')->paginate(10);
        // dd($stockItem);
        return view('application.setting.item', compact('stockItem', 'allUnits','allGroup','allSubCategory','allManufacturer'));
    }

    public function itemUpdate(Request $request, $id=null){
        if ($request->isMethod('post')){
            $request->validate(
                [
                    'name' => 'required|string|max:255',
                    'unit' => 'required|integer|exists:units,id',
                    'category' => 'nullable|integer|exists:category,id',
                    'sub_category' => [
                        'nullable',
                        'integer',
                        Rule::exists('item_sub_categories', 'id')->where(function ($query) use ($request) {
                            if ($request->filled('category')) {
                                $query->where('category_id', $request->category);
                            }
                        }),
                    ],
                    'manufacturer' => 'nullable|integer|exists:manufacturer,id',
                    'barcode' => 'nullable|string|max:255|unique:stock_items,barcode,' . $id,
                    'salesRate' => 'nullable|numeric|min:0',
                ],
                [
                    'sub_category.exists' => 'Selected sub category does not belong to the selected category.',
                ]
            );

            $data = $request->only([
                'name',
                'name_bn',
                'manufacturer',
                'category',
                'sub_category',
                'barcode',
                'part_no',
                'unit',
                'salesRate',
            ]);
            $data['name_bn'] = $request->filled('name_bn') ? trim($request->name_bn) : null;
            $data['manufacturer'] = $request->filled('manufacturer') ? $request->manufacturer : null;
            $data['category'] = $request->filled('category') ? $request->category : null;
            $data['sub_category'] = ($data['category'] && $request->filled('sub_category')) ? $request->sub_category : null;
            $data['barcode'] = $request->filled('barcode') ? trim($request->barcode) : null;
            $data['part_no'] = $request->filled('part_no') ? trim($request->part_no) : null;
            $data['salesRate'] = $request->filled('salesRate') ? $request->salesRate : 0;

            $result = StockItem::where('id', $id)->update($data);

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

        $itemId = $id ?? $request->id;
        $item = StockItem::where('id', $itemId)->firstOrFail();
        $subCategories = $item->category
            ? ItemSubCategory::where('category_id', $item->category)->where('is_active', 1)->orderBy('name', 'ASC')->pluck('name', 'id')
            : collect();

        return view('application.setting.item-update', compact('item', 'allUnits','allGroup','subCategories','allManufacturer'));
    }

    // Items Category & Items Sub Category
    public function itemCategory(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $query = Category::withCount(['subCategories', 'stockItems'])->orderBy('name', 'ASC');
        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }
        $categories = $query->paginate(50);

        return view('application.setting.itemCategoryList', compact('categories', 'search'));
    }

    public function itemCategoryCreate(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);
        $request->validate(
            [
                'name' => 'required|string|max:255|unique:category,name',
            ],
            [
                'name.required' => 'Category name is required.',
                'name.unique' => 'This category name already exists.',
            ]
        );

        $category = Category::create([
            'name' => $request->name,
        ]);

        session()->flash('success', 'Items Category created successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Items Category created successfully.',
            'data' => $category,
        ]);
    }

    public function itemCategoryUpdate(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);
        $request->validate(
            [
                'id' => 'required|integer|exists:category,id',
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('category', 'name')->ignore($request->id),
                ],
            ],
            [
                'name.required' => 'Category name is required.',
                'name.unique' => 'This category name already exists.',
            ]
        );

        $category = Category::findOrFail($request->id);
        $category->update([
            'name' => $request->name,
        ]);

        session()->flash('success', 'Items Category updated successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Items Category updated successfully.',
            'data' => $category,
        ]);
    }

    public function itemCategoryDelete($id)
    {
        $category = Category::findOrFail($id);

        if ($category->subCategories()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete this category because it has sub categories.');
        }

        if ($category->stockItems()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete this category because it is assigned to stock items.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Items Category deleted successfully.');
    }

    public function itemSubCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        $search = trim((string) $request->input('search', ''));
        $categories = Category::orderBy('name', 'ASC')->pluck('name', 'id');

        $query = ItemSubCategory::with('category')->withCount('stockItems')->orderBy('name', 'ASC');
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }
        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $subCategories = $query->paginate(50);

        return view('application.setting.itemSubCategoryList', compact('subCategories', 'categories', 'categoryId', 'search'));
    }

    public function itemSubCategoryCreate(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);
        $request->validate(
            [
                'category_id' => 'required|integer|exists:category,id',
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('item_sub_categories', 'name')->where(function ($query) use ($request) {
                        return $query->where('category_id', $request->category_id);
                    }),
                ],
                'is_active' => 'nullable|in:0,1',
            ],
            [
                'category_id.required' => 'Items Category is required.',
                'category_id.exists' => 'Selected Items Category is invalid.',
                'name.required' => 'Sub Category name is required.',
                'name.unique' => 'This Sub Category already exists under the selected Items Category.',
            ]
        );

        $subCategory = ItemSubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'is_active' => $request->input('is_active', 1),
        ]);

        session()->flash('success', 'Items Sub Category created successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Items Sub Category created successfully.',
            'data' => $subCategory,
        ]);
    }

    public function itemSubCategoryUpdate(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);
        $request->validate(
            [
                'id' => 'required|integer|exists:item_sub_categories,id',
                'category_id' => 'required|integer|exists:category,id',
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('item_sub_categories', 'name')
                        ->where(function ($query) use ($request) {
                            return $query->where('category_id', $request->category_id);
                        })
                        ->ignore($request->id),
                ],
                'is_active' => 'required|in:0,1',
            ],
            [
                'category_id.required' => 'Items Category is required.',
                'category_id.exists' => 'Selected Items Category is invalid.',
                'name.required' => 'Sub Category name is required.',
                'name.unique' => 'This Sub Category already exists under the selected Items Category.',
            ]
        );

        $subCategory = ItemSubCategory::findOrFail($request->id);
        $subCategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'is_active' => $request->is_active,
        ]);

        session()->flash('success', 'Items Sub Category updated successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Items Sub Category updated successfully.',
            'data' => $subCategory,
        ]);
    }

    public function itemSubCategoryDelete($id)
    {
        $subCategory = ItemSubCategory::findOrFail($id);

        if ($subCategory->stockItems()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete this sub category because it is assigned to stock items.');
        }

        $subCategory->delete();

        return redirect()->back()->with('success', 'Items Sub Category deleted successfully.');
    }

    // Manufacturer
    public function manufacturer(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $query = Manufacturer::withCount('stockItems')->orderBy('name', 'ASC');
        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }
        $manufacturers = $query->paginate(50);

        return view('application.setting.manufacturerList', compact('manufacturers', 'search'));
    }

    public function manufacturerCreate(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);
        $request->validate(
            [
                'name' => 'required|string|max:255|unique:manufacturer,name',
            ],
            [
                'name.required' => 'Manufacturer name is required.',
                'name.unique' => 'This manufacturer name already exists.',
            ]
        );

        $manufacturer = Manufacturer::create([
            'name' => $request->name,
        ]);

        session()->flash('success', 'Manufacturer created successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Manufacturer created successfully.',
            'data' => $manufacturer,
        ]);
    }

    public function manufacturerUpdate(Request $request)
    {
        $request->merge(['name' => trim((string) $request->input('name'))]);
        $request->validate(
            [
                'id' => 'required|integer|exists:manufacturer,id',
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('manufacturer', 'name')->ignore($request->id),
                ],
            ],
            [
                'name.required' => 'Manufacturer name is required.',
                'name.unique' => 'This manufacturer name already exists.',
            ]
        );

        $manufacturer = Manufacturer::findOrFail($request->id);
        $manufacturer->update([
            'name' => $request->name,
        ]);

        session()->flash('success', 'Manufacturer updated successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Manufacturer updated successfully.',
            'data' => $manufacturer,
        ]);
    }

    public function manufacturerDelete($id)
    {
        $manufacturer = Manufacturer::findOrFail($id);

        if ($manufacturer->stockItems()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete this manufacturer because it is assigned to stock items.');
        }

        $manufacturer->delete();

        return redirect()->back()->with('success', 'Manufacturer deleted successfully.');
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
                'name.unique' => 'This name already exists.',
            ]
        );

        // dd($request->all());
        WorkName::create($request->all());

        return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');
    }

    public function workNameInstant(Request $request){
        $request->validate(
            [
                'debit_head' => 'required',
                'name' => 'required|string|max:255|unique:work_names',
            ],
            [
                'debit_head.required' => 'Ledger is required',
                'name.required' => 'Name is required',
                'name.unique' => 'This name already exists.',
            ]
        );

        // dd($request->all());
        $data = WorkName::create([
            'name' => $request->name,
            'debit_head' => $request->debit_head
        ]);

        return response()->json([
            'id' => $data->id,
            'name' => $data->name
        ]);
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
