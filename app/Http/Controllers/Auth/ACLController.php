<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\ParentPermission;

use App\Models\User;
use Auth;
use DB;

class ACLController extends Controller
{
 

    public function manageUserRole(){
 		$data['roles'] = Role::where('id','!=',1)->paginate(25);
        
 
        $data['parentPermissions'] = ParentPermission::with('permissions')->where('status', 1)->paginate(25);
  
    	return view('auth.roles.index', $data);
    }

    public function storeRole(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:roles'
        ]);

        $allPermissions = $request->permissionId;
        if($allPermissions == null){
            return back()->with('error','No permissions selected');
        }
        
        $role = Role::create([
            'name' => $request->name,
            'status' => 1,
            'guard_name' => 'web'
        ]);

        if($role){ 
            foreach ($allPermissions as $key => $permission_id) {
                $permission = Permission::find($permission_id);
                
                $role->givePermissionTo($permission);
            }
        }


        return back()->with('success','Permission saving completed successfully');
    }


    public function updateRoleItem(Request $request){
         

        Role::where('id', $request->role_id)->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

         return back()->with('success','Corrected successfully');
    }

    public function roleItemDelete(Request $request, $role_id){
        Role::where('id', $role_id)->delete();

        return back()->with('success','Deleted successfully');
    }

    public function permissionItemDelete(Request $request, $id){
        Permission::where('id', $id)->delete();

        return back()->with('success','Deleted successfully');
    }

    public function roleUpdateToUser(Request $request){

        User::where('id', $request->user_id)->update([
            'role_id' => $request->role_id,
        ]);

        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);

        DB::table('model_has_roles')->where('model_id', $request->user_id)->delete();

        $user->assignRole($role);

         return back()->with('success','Role modified successfully');
    }


    public function manageUserPermissions(Request $request){
        $data['permissions'] = Permission::with('parent')->paginate(25);
        $data['parentPermissions'] = ParentPermission::where('status', 1)->paginate(25);

        return view('auth.permissions.index', $data);
    }
     
    /**
    *  store permission
    *  @return void
    */

    public function storePermission(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:permissions',
        ]);

        Permission::create([
            'parent_permission_id' => $request->parent_permission_id,
            'name' => $request->name,
            'guard_name' => 'web',
            'status' => 1,
        ]);

         return back()->with('success','Save completed successfully');
    }     
    /**
    *  update permission item
    *  @return void
    */

    public function updatePermission(Request $request){
        $this->validate($request, [
            'name' => 'required',
        ]);

        Permission::where('id', $request->permission_id)->update([
            'parent_permission_id' => $request->parent_permission_id,
            'name' => $request->name,
            'status' => $request->status,
        ]);

         return back()->with('success','Correction completed successfully');
    }

    /**
    *  store parent permission
    *  @return void
    */

    public function storePatentPermission(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:parent_permissions'
        ]);

        ParentPermission::create([
            'name' => $request->name,
            'status' => 1,
        ]);

         return back()->with('success','Save completed successfully');
    }

 

    /**
    *  manage user permissions
    *  @return void
    */


    /**
    *  load role permissions with ajax request
    *  @return void
    */

    public function ajaxRolePermissionLoad(Request $request){
        $data['role_id'] = $request->id;

        $data['parentPermissions'] = ParentPermission::with('permissions')->where('status', 1)->get();
         

        return view('auth.permissions.permission_parts', $data);
    }



    /**
    *  manage permissions page
    *  @return void
    */
    public function updateRolePermissions(Request $request, $id){
        $data['role'] = Role::find($id);

        $data['permissions'] = Permission::with('parent')->paginate(25);
        $data['parentPermissions'] = ParentPermission::with('permissions')->where('status', 1)->paginate(25);
  
        return view('auth.roles.manage_permissions', $data);
    }



    /**
    *  manage user permissions all
    *  @return void
    */
    public function storeUpdateUserPermissionAll(Request $request){
        
        $role = Role::find($request->role_id);

        $rolePermissions = RoleHasPermission::where('role_id', $request->role_id)->get();
       
        if(!empty($rolePermissions)){
            foreach($rolePermissions as $rolePermissions) {
                $permission = Permission::find($rolePermissions->permission_id);
               
                $role->revokePermissionTo($permission);
                $permission->removeRole($role);
            }
        }

        RoleHasPermission::where('role_id', $request->role_id)->delete();

        $allPermissions = $request->permissionId;
        if(!empty($allPermissions)){
            foreach ($allPermissions as $key => $permission_id) {
                $permission = Permission::find($permission_id);
                
                $role->givePermissionTo($permission);
            }
        }
        return back()->with('success','Modification of permission allocation completed successfully');
   

        // $data['parentPermissions'] = ParentPermission::with('permissions')->where('status', 1)->get();
       

        // return view('auth.permissions.manage_permissions', $data);
    }




}
