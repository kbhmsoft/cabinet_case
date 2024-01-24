<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\ParentPermissionName;
use App\Models\RolePermission;
use App\Models\ModelHasPermission;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\ParentPermission;


use Auth;

class AclController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         // $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:manage_role_menu', ['only' => ['roleManagement']]);
  
        View::share('notification_count', 0);
        View::share('case_status', array());
    }



    // for roles
    public function roleManagement(){

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $data['page_title'] = 'ভূমিকা পরিচালনা';

        $roles = Role::paginate(25);
        return view('gov_case.roles.create', compact('roles'))->with($data);
    }

    public function storeRole(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:roles'
        ]);

        Role::create([
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'created_by' => Auth::user()->id,
            'status' => 1,
            'in_action' => 1,
            'is_gov' => 1,
        ]);

         return back()->with('success','সাফল্যের সাথে সংরক্ষণ সম্পন্ন হয়েছে');
    }

    public function updateRole(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ]);

        Role::where('id', $request->role_id)->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

         return back()->with('success','সাফল্যের সাথে সংশোধন সম্পন্ন হয়েছে');
    }

    public function roleItemDelete(Request $request, $id){
        Role::where('id', $id)->delete();

         return back()->with('success','ভুমিকাটি মুছে ফেলা হয়েছে');
    }

    // end for roles



    // for permissions
    public function permissionManagement(){

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());


        $data['page_title'] = 'অনুমতি পরিচালনা';
        $permissions = Permission::orderBy('created_at', 'ASC')->paginate(25);
        $parentPermissions = ParentPermissionName::where('status', 1)->get();

        if(Auth::user()->role_id == 27 || Auth::user()->role->name == 'ডেভলপার'){
            return view('gov_case.permissions.create', compact('permissions', 'parentPermissions'))->with($data);
        }else{
            abort(403);
        }

    }

    public function storePermission(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:permissions'
        ]);

        $nameLower = str_replace(' ', '_', $request->name);
        $permissionName = strtolower($nameLower);

        Permission::create([
            'name' => $permissionName,
            'display_name' => $request->display_name,
            'user_id' => Auth::user()->id,
            'parent_permission_name_id' => $request->parent_permission_id,
            'status' => 1,
        ]);

        return back()->with('success','সাফল্যের সাথে সংরক্ষণ সম্পন্ন হয়েছে');
    }

    public function updatePermission(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required'
        ]);
        $nameLower = str_replace(' ', '_', $request->name);
        $permissionName = strtolower($nameLower);

        Permission::where('id', $request->permission_id)->update([
            'name' => $permissionName,
            'display_name' => $request->display_name,
            'status' => $request->status,
        ]);

         return back()->with('success','সাফল্যের সাথে সংশোধন সম্পন্ন হয়েছে');
    }

    public function permissionItemDelete(Request $request, $id){
        Permission::where('id', $id)->delete();

         return back()->with('success','ভুমিকাটি মুছে ফেলা হয়েছে');
    }
    // end for permissions



    // for parent permission name

    public function storePatentPermissionName(Request $request){
        ParentPermissionName::create([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
            'status' => 1,
        ]);

         return back()->with('success','সাফল্যের সাথে সংরক্ষণ সম্পন্ন হয়েছে');
    }



    // end parent permission name



    // for give user permissions

    public function permissionToUserManagement(Request $request){

        session()->forget('currentUrlPath');
        session()->put('currentUrlPath', request()->path());

        $data['page_title'] = 'অনুমতি প্রদান পরিচালনা';
        $data['roles'] = Role::where(['is_gov'=> 1, 'status' => 1])->get();
        // $users = User::where('is_gov', 1)->get();
        // $data['users'] = $query->paginate(10)->withQueryString();
        return view('gov_case.user_permissions.index', $data);
    }

 

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
        return back()->with('success','সফলতার সাথে অনুমতি বরাদ্দ সংশোধন সম্পন্ন হয়েছে');
   
    }



    /**
    *  manage permissions page
    *  @return void
    */
    public function updateRolePermissions(Request $request, $id){
        $data['role'] = Role::find($id);

        $data['permissions'] = Permission::with('parent')->paginate(25);
        $data['parentPermissions'] = ParentPermission::with('permissions')->where('status', 1)->paginate(25);
  
        return view('backend.roles.manage_permissions', $data);
    }


    public function userPermissionManage(Request $request, $role_id){
        $data['page_title'] = 'অনুমতি প্রদান পরিচালনা করুন';
        $data['parentPermissions'] = ParentPermissionName::with('permissions')->where('status', 1)->paginate(25);
        $data['role'] = Role::find($role_id);
        $data['permissions'] = Permission::with('parent')->paginate(25);

        // $data['notification_count'] = 0;
        // $data['case_status'] = [];
        return view('gov_case.user_permissions.manage_permissions', $data);
    }


    // end user permissions




}
