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
    public function index()
    {
        $roles = Role::where('name', '!=', 'admin')->get();
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
    public function show(Role $role)
    {

        $users = User::with('roles')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name',  'admin');
            })

            ->paginate(9);

        return view('roles.show',compact('role','users'));
    }
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index');
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
