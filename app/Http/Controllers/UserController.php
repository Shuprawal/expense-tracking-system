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
    public function index(Request $request)
    {
        $search = $request->input('inputText');
        $users = User::where('username','LIKE',"%{$search}%")
            ->with('categories')->isNotAdmin()
            ->withCount('categories')
            ->withCount('expenses')
            ->with('roles')
            ->get();

        return view('admin.index', compact('users'));
    }

    public function userDashboard()
    {
        $user = Auth::user();

        $mostUsedCategories =$user->categories()->withSum('expenses', 'amount')->orderBy('expenses_sum_amount', 'desc')->limit(3)->get();
        $expenses = $user->expenses()->whereMonth('date', Carbon::now()->month)->avg('amount');
        $incomes = $user->incomes()->whereMonth('date', Carbon::now()->month)->avg('amount');

        return view('dashboard', compact('mostUsedCategories', 'expenses', 'incomes'));
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

    public function show(User $user)
    {
        $user->with('categories')->withWhereHas('expenses', function ($query) {
            $query->orderBy('date', 'desc');
        });

        return view('admin.show', compact('user') );
    }


    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();
            $user->roles()->detach();
            $user->categories()->detach();
            $user->delete();
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User has been deleted');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->route('users.index')->with('error','cannot delete the user id it has any permission' );
        }

    }
//    public function permission()
//    {
//        $permissions = Permission::get()->groupBy('group');
//        $roles= Role::isNotAdmin()->get();
//        return view('admin.permissions', compact('permissions', 'roles'));
//    }


}
