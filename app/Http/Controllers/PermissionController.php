<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $permissions = Permission::get()->groupBy('group');
        if ($request->role_id) {
            $roles = Role::where('id', $request->role_id)->get();
        } else {
            $roles = Role::where('name', '!=', 'admin')->get();
        }
//        dd($request->role_id);
        return view('admin.permissions', compact('permissions', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->permissions == null) {

            return back()->with('error','please select at least one role');
        }
        DB::beginTransaction();
        try {


//            dd($request->all());
            $allRoles = Role::all();
            foreach ($allRoles as $role) {
                $permissionId = $request->permissions[$role->id]??[];
                $role->permissions()->sync($permissionId);
            }


            DB::commit();
            return redirect()->back()->with('success', 'Permissions updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong while updating permissions.');
        }
    }
//    public function store(Request $request)
//    {
////        dd($request->all());
//        DB::beginTransaction();
//        try {
//            $allRoles = Role::all();
//
//            foreach ($allRoles as $role) {
//                $permissionIds = $request->permissions[$role->id] ?? [];
//
//                $role->permissions()->sync($permissionIds);
//            }
//
//            DB::commit();
//            return redirect()->back()->with('success', 'Permissions updated successfully!');
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return redirect()->back()->with('error', 'Something went wrong while updating permissions.');
//        }
//    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role,Permission $permission)
    {
        $role->permissions()->detach($permission->id);
        return redirect()->back()->with('success', 'Permission removed successfully');
    }
    public function search(Request $request)
    {
        $search = $request->inputText;
        $permissions = Permission::where('group', 'LIKE', "%{$search}%")->

        get()->groupBy('group');

        if ($request->role_id) {
            $roles = Role::where('id', $request->role_id)->get();
        } else {
            $roles = Role::where('name', '!=', 'admin')->get();
        }
        return view('admin.permissions', compact('permissions', 'roles'));
    }

}
