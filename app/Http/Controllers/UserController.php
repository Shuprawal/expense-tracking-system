<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Forecastincome;
use App\Models\Income;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role','user')->with('categories')->get();

        return view('admin.index', compact('users'));
    }

//    public function category()
//    {
//
//    }

    public function dashboard()
    {

//        $routes = collect(Route::getRoutes())->map(function ($route) {
//            return $route->getName();
//        })->filter()->toArray();
//
//        dd($routes);

        $userNumber = User::where('role','user')->count();
        $categoryNumber = Category::count();
        $averageIncome = Forecastincome::avg('amount') ?? 0;
        $mostUsedCategories = Category::withSum('expenses', 'amount')->orderBy('expenses_sum_amount', 'desc')->limit(3)->get();
     return view('admin.dashboard', compact('categoryNumber', 'userNumber', 'averageIncome', 'mostUsedCategories') );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
    public function permission()
    {
        $permissions = Permission::get()->groupBy('group');

        $roles= Role::all();

        return view('admin.permissions', compact('permissions', 'roles'));
    }
    public function addPermission($roleId, $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $role = Role::findOrFail($roleId);

        if (!$role->permissions()->where('name', $permission->name)->exists()) {
            $role->permissions()->attach($permission);
        }

        return redirect()->back();
    }

    public function removePermission($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);

        $role->permissions()->detach($permission->id);

        return redirect()->back()->with('success', 'Permission removed successfully');
    }


}
