<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Forecastincome;
use App\Models\Income;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('categories')
            ->isNotAdmin()
            ->get();

        return view('admin.index', compact('users'));
    }



    public function dashboard()
    {

        $userNumber = User::count();
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
//    public function permission()
//    {
//        $permissions = Permission::get()->groupBy('group');
//        $roles= Role::isNotAdmin()->get();
//        return view('admin.permissions', compact('permissions', 'roles'));
//    }


}
