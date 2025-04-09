<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\DateDurationRequest;
use App\Http\Requests\ForecastPercentage;
use App\Http\Requests\StoreFormRequest;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use App\View\Components\DateDuration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search=$request->input('inputText');
        $categories = Category::with('users')
            ->where('name','LIKE',"%{$search}%")
//            ->where('disabled','no')
            ->withCount('users')
            ->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function display()
    {
        $user = Auth::user();
        $categories = Category::with('users')
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id',$user->id);
            })

            ->paginate(12);
        return view('categories.display', compact('categories'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $category = Category::findOrFail($id);
        $authorize= Category::whereHas('users', function ($query) use ($id,$user) {
            $query->where('user_id',$user->id)
                ->where('category_id',$id);
        })->first();
        if(!$authorize){
            abort(403);
        }
        return view('categories.edit', compact('category'));
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
//        dd($request->all());
        $start= Carbon::parse($request->date)->startOfMonth();
        $end= Carbon::parse($request->date)->endOfMonth();
//        $end=$request->date->startOfMonth();
//        dd($start);


            $user = Auth::user();
            $categoryIDs = [];


            if (!empty($request->categories)) {

                foreach ($request->categories as $category) {
                    $existingCategory = Category::where('name', $category)
                        ->whereHas('users', function ($query) use ($user,$start,$end) {
                            $query->where('user_id',$user->id)
                                ->whereBetween('date',[$start,$end]);
                        })->exists();

                    if ($existingCategory){
                        return back()->withInput()->withErrors(['error1' => 'Category already exists for this month!']);
                    }
                }

                $categoryIDs = Category::whereIn('name', $request->categories)->pluck('id')->toArray();
            }

        try {
            if (!empty($request->new_categories)) {
                foreach ($request->new_categories as $categoryName) {
//                    if ($request->categories == $categoryName) {
//                        dd('aaaa');
//                    }
//                    dd($request->categories, $categoryName);

                    $existingCategory = Category::where('name', $categoryName)
                        ->whereHas('users', function ($query) use ($user,$start,$end) {
                        $query->where('user_id',$user->id)
                            ->whereBetween('date',[$start,$end]);
                    })->exists();

                    if ($existingCategory){
                        return back()->withInput()->withErrors(['error1' => 'Category already exists for this month!']);
                    }
                    $category = Category::withTrashed()->where('name', $categoryName)->first();
                    DB::beginTransaction();

                    if ($categoryName != []) {

                        if ($category) {
//                            dd($category);
                            $category->restore();
//                        $category -> update(['user_id' => $user->id]);
                        } else {
                            $category = Category::create([
                                'name' => ucfirst($categoryName),
                                'user_id' => $user->id
                            ]);
                        }

                        $categoryIDs[] = $category->id;
                    }

                }
            }

            if (!empty($categoryIDs)) {
                $date=$request->date;
                $dateTime = Carbon::parse($date)->setTimeFrom(Carbon::now());

                auth()->user()->categories()->attach($categoryIDs, ['date' => $dateTime]);
                session(['categoryDate' => $dateTime]);
            }

            DB::commit();
            return redirect()->route('forecast')->with('success', 'Categories added successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', $th->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
//        dd($request);
        $request->validate([
            'name' => 'required|string',
        ]);
        $newName = $request->get('name');
        $user = Auth::user();

        $categoryTrashed = Category::withTrashed()->where('name', $newName)->first();

        if ($categoryTrashed) {
            $categoryTrashed->restore();
            $categoryTrashed -> update(['user_id' => $user->id]);
            $newCategory = $categoryTrashed;
        }
        else
        {
            $existingCategory = Category::where('name', $newName)->first();

            if ($existingCategory) {
                $newCategory = $existingCategory;
            }else{
                $newCategory= Category::create([
                    'name' => ucfirst($newName),
                    'user_id' => $user->id,    //not working
                    'date' => Carbon::now()->format('Y-m-d'),
                ]);
            }

        }
        if (!$newCategory) {
            return redirect()->back()->with('error', 'Failed to create or retrieve the category.');
        }

        $oldCategory = Category::findOrFail($id);

        Expense::where('category_id', $oldCategory->id)->update(['category_id' => $newCategory->id]);
        $newCategory->users()->sync(
            $oldCategory->users->mapWithKeys(function ($user) {
                return [$user->id => [
                    'date' => $user->pivot->date,
                    'percentage' => $user->pivot->percentage,
                ]];
            })->toArray()
        );
        $oldCategory->users()->detach();

        return redirect()->route('categories.display')->with('success', 'Categories updated successfully.');

    }

//    public function transfer(Request $request)
//    {
//
//        $oldCategoryId = $request->input('old_category');
//        $newCategoryId = $request->input('new_category');
//        if($request->input('transfer') == 'yes') {
//
//
////            $oldCategory = Category::find($oldCategoryId);
////            $newCategory = Category::find($newCategoryId);
////
//////            dd($oldCategory, $newCategory);
////
//
//
////            Category::findOrFail($oldCategoryId)->delete();
//            Category::where('id', $oldCategoryId)->update(['user_id' => '']);
//            return redirect()->route('categories.display')->with('success', 'Categories transferred successfully.');
//        }
////        Category::findOrFail($oldCategoryId)->delete();
//        Category::where('id', $oldCategoryId)->update(['user_id' => '']);
//        return redirect()->route('categories.display')->with('success', 'Categories were not transferred successfully.');
//    }



    /**
     * Display the specified resource.
     */
    public function show(DateDurationRequest $request, Category $category)
    {
        $selectedDate =$request->input('month',now()->month);
        $start =$request->input('start',now()->startOfYear()->format('Y-m-d'));
        $end =$request->input('end',now()->endOfYear()->format('Y-m-d'));
//
//        $users = User::wherehas('categories', function ($query) use ($category) {
//            $query->where('category_user.category_id', $category->id);
//        }
//        )->get();
//        $userCount = $users->count();
//        return view('admin.userCategory', compact('category', 'users', 'userCount'));

        $expenses =Expense::where('category_id',$category->id)
            ->where('user_id', auth()->id())
           ->whereBetween('date', [$start, $end])
            ->paginate(5);

        return view('categories.show', compact('category','expenses','start','end'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $user = Auth::user();
        if ($user->hasAdminRole()) {
            $category->delete();
        }else{
            $category->users()->detach();
        }
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }


    public function newCreate()
    {
        $check = Category::whereHas('users', function ($query) {
            $query->where('user_id', Auth::id());
        })->count();

        if ($check == 0) {
            $categories = Category::withSum('expenses', 'amount')
                ->orderBy('expenses_sum_amount', 'desc')
                ->where('disabled','no')
                ->limit(6)->get();

        }else{
            $categories = Category::withSum('expenses', 'amount')
                ->orderBy('expenses_sum_amount', 'desc')
                ->whereDoesntHave('users', function ($query) {
                $query->whereYear('category_user.date', carbon::now()->year)
                    ->whereMonth('category_user.date', carbon::now()->month)
                    ->where('category_user.user_id', Auth::id());
            })->where('disabled','no')->limit(6)->get();
        }

        return view('categories.new-category', compact('categories'));
    }

    public function disable(Request $request, Category $category)
    {
        $disable=$request->input('disable');
        if ($disable == 'Yes') {
            $category->update(['disabled' => 'yes']);
            return redirect()->back()->with('success', 'Category disabled successfully.');
        }else{
            $category->update(['disabled' => 'no']);
            return redirect()->back()->with('success', 'Category removed from disabled successfully.');
        }

    }

    public function forecast()
    {

        $categories = Category::with('users')

            ->whereHas('users', function ($query) {
                    $query->where('category_user.date',session('categoryDate'))
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

        $month = Carbon::createFromFormat('m', $request->date);


        $start = $month->startOfMonth();
        $end = $month->endOfMonth();
        if (!empty($request->category_id)) {

            $user->categories()
                ->wherePivot('category_id', $request->category_id)
                ->wherePiviot('date','>=', $start)
                ->wherePivot('date','<=', $end)
                ->detach();

            return redirect()->back()->with('success', 'User category deleted successfully.');
        }


        return redirect()->back()->with('error', 'Category ID is required.');
    }


    public function forecastEdit(Request $request)
    {
//        dd($request->all());
        $month=$request->date;
        $year = Carbon::now()->year;

        $start= Carbon::createFromDate($year, $month,1);

//        dd($date);
//        $start = Carbon::create($date)->startOfMonth();
        $end = Carbon::create($start)->endOfMonth();
//        dd($date, $end);
        $user = Auth::user();
        $category =$request->category_id;
//        dd($category);
        $categories = Category::with('users')
            ->whereHas('users', function ($query) use ($user, $start,$end, $category) {
                $query->where('category_id', $category)
                        ->whereBetween('category_user.date', [$start, $end])
                    ->where('users.id', $user->id);
            })
            ->first();

        $userWithPivot = $categories->users->first();
            $percentage = $userWithPivot->pivot->percentage;

//            ->wherePivot('user_id', $user->id)
//            ->whereBetween('category_user.date', [$start, $end])
//            ->withPivot('percentage')
//            ->first();
//        dd($categories->percentage);
//        dd($percentage);

        return view('categories.forecastEdit', compact('categories','percentage','start','end'));

    }






}
