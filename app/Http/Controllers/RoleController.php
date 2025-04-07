<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $search =$request->input('inputText');
        $roles = Role::where('name', '!=', 'admin')
            ->where('name','LIKE',"%{$search}%")->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        Role::create([
            'name'=>$request->name
        ]);
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,Role $role)
    {
        $search =$request->input('inputText');
        $users = User::with('roles')
            ->where('username','LIKE',"%{$search}%")
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name',  'admin');
            })

            ->paginate(7);

        return view('roles.show',compact('role','users'));
    }
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return redirect()->route('roles.index');
        }catch (\Exception $exception){
            return redirect()->route('roles.index')->with('error','cannot delete the role id it has any permission' );
        }

    }


    public function attachRole(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->roles()->attach($request->role_id);
        return redirect()->back();
    }

    public function detachRole(Request $request, Role $role, User $user)
    {
        $user->roles()->detach($role->id);
        return redirect()->back();
    }
}
