<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\ForecastPercentage;
use App\Http\Requests\StoreFormRequest;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('users')->get();
        return view('categories.index', compact('categories'));
    }

//
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }




    public function store(CategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            $categoryIDs = [];

            if (!empty($request->categories)) {
                $categoryIDs = Category::whereIn('name', $request->categories)->pluck('id')->toArray();
            }


            if (!empty($request->new_categories)) {
                foreach ($request->new_categories as $categoryName) {
                    $category = Category::withTrashed()->where('name', $categoryName)->first();

                    if ($category) {
                        $category->restore();
                    } else {


                        $category = Category::create([
                            'name' => $categoryName,
                            'user_id' => auth()->id()]);

                    }

                    $categoryIDs[] = $category->id;
                }
            }


            if (!empty($categoryIDs)) {
                auth()->user()->categories()->attach($categoryIDs, ['date' => $request->date]);
                session(['categoryDate' => $request->date]);
            }

            DB::commit();
            return redirect()->route('forecast')->with('success', 'Categories added successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, Category $category)
    {
        $selectedDate =$request->input('month',now()->month);
//
//        $users = User::wherehas('categories', function ($query) use ($category) {
//            $query->where('category_user.category_id', $category->id);
//        }
//        )->get();
//        $userCount = $users->count();
//        return view('admin.userCategory', compact('category', 'users', 'userCount'));

        $expenses =Expense::where('category_id',$category->id)
            ->where('user_id', auth()->id())->whereMonth('date',$selectedDate)->get();

        return view('categories.show', compact('category','expenses','selectedDate'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }


    public function newCreate()
    {
        $check = Category::whereHas('users', function ($query) {
            $query->where('user_id', Auth::id());
        })->count();

        if ($check == 0) {
            $categories = Category::with('users')
                ->get();
        }else{
            $categories = Category::whereDoesntHave('users', function ($query) {
                $query->whereYear('category_user.date', carbon::now()->year)
                    ->whereMonth('category_user.date', carbon::now()->month)
                    ->where('category_user.user_id', Auth::id());
            })->get();
        }
        return view('categories.new-category', compact('categories'));
    }

    public function forecast()
    {

        $categories = Category::with('users')

            ->whereHas('users', function ($query) {
                    $query->whereDate('category_user.date',session('categoryDate'))
                    ->where('users.id', auth()->id());
            })
            ->get();

        return view('categories.forecast', compact('categories'));


    }
    public function forecastStore(StoreFormRequest $request)
    {
        $user = Auth::user();
        $data = [];

        foreach ($request->category as $index => $category) {
            $data[$category] = ['percentage' => $request->percentage[$index]];
        }
        $user->categories()->wherePivot('date', session('categoryDate'))->syncWithoutDetaching($data);
        return redirect()->route('forecasts.index');
    }

    public function forecastDetach(Request $request)
    {

        $user = Auth::user();
        $user->categories()->whereMonth('date', carbon::now()->month)
            ->whereYear('date',carbon::now()->year)
            ->detach($request->category_id);
        return redirect()->back();
    }

}
