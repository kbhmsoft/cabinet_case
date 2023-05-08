<?php

namespace App\Http\Controllers\gov_case;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\ParentPermissionName;
use App\Models\RolePermission;
use App\Models\ModelHasPermission;
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
         $this->middleware('permission:create', ['only' => ['roleManagement']]);
  
    }


     
    // for roles 
    public function roleManagement(){
        $data['page_title'] = 'ভুমিকা পরিচালনা';

        $roles = Role::paginate(25);
        return view('gov_case.roles.create', compact('roles'))->with($data);
    }

    public function storeRole(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:roles'
        ]);

        Role::create([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'status' => 1,
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
        $data['page_title'] = 'অনুমতি পরিচালনা';
        $permissions = Permission::paginate(25);
        $parentPermissions = ParentPermissionName::where('status', 1)->get();

        return view('gov_case.permissions.create', compact('permissions', 'parentPermissions')) ;
    }

    public function storePermission(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:permissions'
        ]);
        $nameLower = str_replace('_', ' ', $request->name);

        Permission::create([
            'name' => $request->name,
            'display_name' => ucwords($nameLower),
            'user_id' => Auth::user()->id,
            'parent_permission_name_id' => $request->parent_permission_id,
            'status' => 1,
        ]);

         return back()->with('success','সাফল্যের সাথে সংরক্ষণ সম্পন্ন হয়েছে');
    }

    public function updatePermission(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ]);
        $nameLower = str_replace('_', ' ', $request->name);

        Permission::where('id', $request->permission_id)->update([
            'name' => $request->name,
            'display_name' => ucwords($nameLower),
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

        $data['page_title'] = 'অনুমতি প্রদান পরিচালনা';
        $users = User::where('is_gov', 1)->get();

        return view('gov_case.user_permissions.index', compact('users'))->with($data);
    }


    public function userPermissionManage(Request $request, $user_id){
        $data['page_title'] = 'অনুমতি প্রদান পরিচালনা করুন';
        $parentPermissions = ParentPermissionName::where('status', 1)->get();
        $rolePermissions = RolePermission::where('user_id', $user_id)->get();

        return view('gov_case.user_permissions.manage_permissions', compact('user_id', 'parentPermissions', 'rolePermissions'))->with($data);
    }


    public function storeUpdateUserPermissionAll(Request $request){
       RolePermission::where('user_id', $request->user_id)->delete();
       ModelHasPermission::where('model_id', $request->user_id)->delete();
 
       $user = User::find($request->user_id);
        foreach($request->permissionId as $index => $permission_id){
            RolePermission::create([
                'user_id' => $request->user_id,
                'role_id' => $user->role->id,
                'permission_id' => $permission_id,
                'created_by' => Auth::user()->id,
            ]);

            $permission = Permission::find($permission_id);
            // Adding permissions to a user
            $user->givePermissionTo($permission->name);
        }

        return back()->with('success','অনুমতি বরাদ্দ সংশোধন সম্পন্ন হয়েছে');
    }







    // end user permissions 




}
