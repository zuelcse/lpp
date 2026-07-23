<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\WorkType;
use App\Models\MasterSize;
use App\Models\MasterColor;
use App\Models\MasterWeight;
use App\Models\MasterPaper;
use App\Models\MasterLamination;

class WorkTypeController extends Controller
{
    public function workTypes()
    {
        // $workTypes = WorkType::with('sizes','colors','weights','papers','laminations')->get()->toArray();
        $data = WorkType::with('sizes','colors','weights','papers','laminations')->orderBy('name','ASC')->paginate(10);
        // dd($data);
        return view('application.setting.worktype.lists', compact('data'));
    }

    public function workTypeCreate(Request $request){
        // dd($request->all());
        $request->validate(
            [
                'name' => 'required|unique:work_types',
                'name_bn' => 'required|string|max:255',
            ],
            [
                'name.required' => 'Group is required',
                'name_bn.required' => 'Name is required',
            ]
        );
        WorkType::create($request->all());
        return response()->json([
            'message' => 'Successfully Stored!'
        ]);
        /*return redirect()
        ->back()
        ->with('success', 'Your message has been sent!');*/
    }

    public function workTypesEdit(Request $request)
    {
        // dd($request->all());
        $data = WorkType::where('id',$request->id)
                ->with('sizes','colors','weights','papers','laminations')
                ->orderBy('name','ASC')->first();
        // dd($data);

        return view('application.setting.worktype.edit', [
            'data'     => $data,
            'sizes'  => MasterSize::all(),
            'colors' => MasterColor::all(),
            'weights' => MasterWeight::all(),
            'papers' => MasterPaper::all(),
            'laminations' => MasterLamination::all()
        ]);
        // return view('application.setting.worktype.edit', compact('data'));
    }

    /*public function create()
    {
        return view('worktypes.create', [
            'colors' => Color::all(),
            'sizes' => Size::all(),
            'papers' => Paper::all()
        ]);
    }*/

   /* public function store(Request $request)
    {
        $wt = WorkType::create($request->only('name','name_bn'));

        $wt->colors()->sync($request->color_ids ?? []);
        $wt->sizes()->sync($request->size_ids ?? []);
        $wt->papers()->sync($request->paper_ids ?? []);

        return redirect()->route('worktypes.index');
    }*/

   /* public function edit($id)
    {
        $wt = WorkType::findOrFail($id);

        return view('worktypes.edit', [
            'wt' => $wt,
            'colors' => Color::all(),
            'sizes' => Size::all(),
            'papers' => Paper::all()
        ]);
    }*/

    public function workTypesUpdate(Request $request)
    {
        // dd($request->all());
        $wt = WorkType::with(['sizes','colors','weights','papers','laminations'])->findOrFail($request->id);
        // dd($wt);

        // $wt = WorkType::findOrFail($id);

        // $wt->update($request->only('name','name_bn'));

        $wt->colors()->sync($request->color ?? []);
        $wt->sizes()->sync($request->size ?? []);
        $wt->weights()->sync($request->weight ?? []);
        $wt->papers()->sync($request->paper ?? []);
        $wt->laminations()->sync($request->lamination ?? []);
        // dd('H');
        return back()->with('success','Updated');
    }

    public function workTypeUpdate(Request $request){
        // dd($request->all());
        $request->validate(
            [
                'name' => 'required|unique:work_types,name,'.$request->id,
                'name_bn' => 'required|string|max:255',
            ],
            [
                'name.required' => 'Group is required',
                'name.unique' => 'This name already exists.',
                'name_bn.required' => 'Name is required',
            ]
        );
       
        WorkType::where('id',$request->id)->update([
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
}