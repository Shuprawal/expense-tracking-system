<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\ForecastPercentage;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MongoDB\Driver\Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact( 'categories'));
    }

    public function adminIndex()
    {
        $categories = Category::with('users')->get();
        return view('admin.category', compact('categories'));
    }
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
            if (!empty($request->selected_categories)) {
                foreach ($request->selected_categories as $categoryName) {
                    $category = Category::where('name', $categoryName)->first();
                    if ($category) {
                        $categoryIDs[] = $category->id;
                    }
                }
            }

            if (!empty($request->new_categories)) {
                foreach ($request->new_categories as $categoryName) {
                    $category = Category::firstOrCreate(['name' => $categoryName]);
                    $categoryIDs[] = $category->id;
                }
            }

            if (empty($categoryIDs)) {
                return back()->withErrors(['category' => 'Please select or add at least one category.'])->withInput();
            }


            $attachData = [];
            foreach ($categoryIDs as $categoryID) {
                $attachData[] = [
                    'category_id' => $categoryID,
                    'user_id' => auth()->id(),
                    'date' => $request->date,
                ];
            }

            auth()->user()->categories()->attach($attachData);

            session(['categoryDate' => $request->date]);
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
    public function show(Category $category)
    {

        $users = User::wherehas('categories', function ($query) use ($category) {
            $query->where('category_user.category_id', $category->id);
        }
        )->get();
        $userCount = $users->count();
        return view('admin.userCategory', compact('category', 'users', 'userCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
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

        $categories = Category::with('users')
            ->get();
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
    public function forecastStore(ForecastPercentage $request)
    {
        $user = Auth::user();
        $data = [];

        foreach ($request->category as $index => $category) {
            $data[$category] = ['percentage' => $request->percentage[$index]];
        }

        $user->categories()->wherePivot('date', session('categoryDate'))->syncWithoutDetaching($data);

        return redirect()->route('forecasts.index');

    }





}
