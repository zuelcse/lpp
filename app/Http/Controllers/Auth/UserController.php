<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Ledger;
use App\Models\StockItem;
use App\Models\LedgerPermission;
use App\Models\StockItemPermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Auth;
use DB;

class UserController extends Controller
{
 

    public function index(){
 		$data['users'] = User::where('id','!=',1)->paginate(200);
  		$data['roles'] = Role::where('id','!=',1)->paginate(25);
    	return view('auth.user.index', $data);
    }


    public function updateUser(Request $request): RedirectResponse{
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20', Rule::unique('users', 'mobile')->ignore($request->id)],
            'role' => ['required']
        ]); 

        User::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'supervisors' => $request->supervisor,
        ]);

        $user=User::find($request->id);
        $user->syncRoles(Role::find($request->role)->name);
        if(!empty($request->password)){
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return back()->with('success','Update successfully');
    }

    public function userDelete(Request $request, $id){
        
        $user=User::find($id);
        // $user->syncRoles([]);
        if ($user) {
            $user->delete();
        }
        

        return back()->with('success','Deleted successfully');
    }
    
    public function userLedger(Request $request, $id){
        $data['user'] = User::find($id);
        $data['ledger'] = Ledger::where('parent','!=',NULL)->where('parent','!=','')->groupBy('parent')->get();
        $data['ledgerPermission'] = LedgerPermission::where('user_id', $id)->pluck('ledger_group')->toArray();
        // dd($data['ledgerPermission']);
        return view('auth.user.ledger', $data);
    }
    
    public function storeUpdateUserLadger(Request $request){
        $LedgerPermission=LedgerPermission::where('user_id',$request->user_id);
        $LedgerPermission->delete();
        
        $data=[];
        if(!empty($request->ledger_group)){
            foreach ($request->ledger_group as $key => $ledger_group){
                $data[$key]['user_id']=$request->user_id;
                $data[$key]['ledger_group']=$ledger_group;
            }
            LedgerPermission::insert($data);
        }
        
        return redirect('acl/user/list')->with('success','Modification of ledger group allocation completed successfully');
   
    }
    
    public function userItem(Request $request, $id){
        $data['user'] = User::find($id);
        $data['ledger'] = StockItem::where('parent','!=',NULL)->where('parent','!=','')->groupBy('parent')->get();
        $data['ledgerPermission'] = StockItemPermission::where('user_id', $id)->pluck('stock_item_group')->toArray();
        // dd($data['ledgerPermission']);
        return view('auth.user.item', $data);
    }
    
    public function storeUpdateUserItem(Request $request){
        $LedgerPermission=StockItemPermission::where('user_id',$request->user_id);
        $LedgerPermission->delete();
        
        $data=[];
        if(!empty($request->stock_item_group)){
            foreach ($request->stock_item_group as $key => $stock_item_group){
                $data[$key]['user_id']=$request->user_id;
                $data[$key]['stock_item_group']=$stock_item_group;
            }
            StockItemPermission::insert($data);
        }
        
        return redirect('acl/user/list')->with('success','Modification of Stock item group allocation completed successfully');
   
    }
}
