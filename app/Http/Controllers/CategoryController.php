<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminCategoryRequest;
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
            ->orderBy('created_at','DESC')
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

    public function adminEdit(Category $category)
    {
        $user = Auth::user();
        abort_unless($user->hasAdminRole(),403);
        return view('categories.adminEdit', compact('category'));
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


    public function adminStore(AdminCategoryRequest $request)
    {
        $newCategory = $request->category;

        $user = Auth::user();
        $categoryQuery=Category::where('name', $newCategory);
        $existingCategory=$categoryQuery->first();
        $thrashedCategory=$categoryQuery->onlyTrashed()->first();
        if ($existingCategory) {
            return redirect()->back()->withInput()->withErrors(['category' => 'Category already exists']);
        }
        if ($thrashedCategory) {
            $thrashedCategory->restore();

            return redirect()->route('categories.index');
        }
        try {
            if (!$existingCategory && !$thrashedCategory){
                Category::create([
                    'name' => ucfirst(trim($newCategory)),
                    'user_id' => $user->id
                ]);
                return redirect()->route('categories.index');
            }


        }catch (\Exception $exception){
            return back()->withInput()->withErrors($exception->getMessage());
        }

    }


    public function store(CategoryRequest $request)
    {
        $start = Carbon::parse($request->date)->startOfMonth();
        $end = Carbon::parse($request->date)->endOfMonth();
        $user = Auth::user();
        $categoryIDs = [];

        DB::beginTransaction();

        try {

            if (!empty($request->categories)) {
                foreach ($request->categories as $category) {
                    $existing = Category::where('name', $category)
                        ->whereHas('users', function ($query) use ($user, $start, $end) {
                            $query->where('user_id', $user->id)
                                ->whereBetween('date', [$start, $end]);
                        })->exists();

                    if ($existing) {
                        DB::rollBack();
                        return back()->withInput()->withErrors(['error1' => 'Category already exists for this month!']);
                    }
                }
                $categoryIDs = Category::whereIn('name', $request->categories)->pluck('id')->toArray();
            }

            if (!empty($request->new_categories)) {
                foreach ($request->new_categories as $categoryName) {
                    if (empty($categoryName)) continue;

                    $existing = Category::where('name', $categoryName)
                        ->whereHas('users', function ($query) use ($user, $start, $end) {
                            $query->where('user_id', $user->id)
                                ->whereBetween('date', [$start, $end]);
                        })->exists();
                    if ($existing) {
                        DB::rollBack();
                        return back()->withInput()->withErrors(['error1' => 'Category already exists for this month!']);
                    }
                    $category = Category::withTrashed()->where('name', $categoryName)->first();
                    if ($category) {
                        $category->restore();
                    } else {
                        $category = Category::create([
                            'name' => ucfirst(trim($categoryName)),
                            'user_id' => $user->id
                        ]);
                    }
                    $categoryIDs[] = $category->id;
                }
            }


            if (!empty($categoryIDs)) {
                $dateTime = Carbon::parse($request->date)->setTimeFrom(Carbon::now());
                $user->categories()->attach($categoryIDs, ['date' => $dateTime]);
                session(['categoryDate' => $dateTime]);
            }

            DB::commit();
            return redirect()->route('forecast')->with('success', 'Categories added successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', $th->getMessage());
        }
    }



    public function adminUpdate(AdminCategoryRequest $request, Category $category)
    {
        $categoryName=$request->input('category');
        $categoryQuery=Category::where('name', $categoryName);
        $existingCategory=$categoryQuery->where('id','!=',$category->id)->first();
        $thrashedCategory=$categoryQuery->onlyTrashed()->first();
        if ($existingCategory) {
            return redirect()->back()->withInput()->withErrors(['category' => 'Category already exists']);
        }
        if ($thrashedCategory) {
            $thrashedCategory->restore();
            Expense::where('category_id',$category->id)->update(['category_id'=>$thrashedCategory->id]);
            $thrashedCategory->users()->sync(
                $category->users->mapWithKeys(function ($user) {
                    return [$user->id => [
                        'date' => $user->pivot->date,
                        'percentage' => $user->pivot->percentage,
                    ]];
                })->toArray()
            );
            $category->users()->detach();

//            Expense::where('category_id', $oldCategory->id)->update(['category_id' => $newCategory->id]);

            $category->delete();

        }
        try {
            if (!$existingCategory && !$thrashedCategory) {
                $category->update([
                    'name' => ucfirst(trim($categoryName)),
                ]);
            }


            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');

        }catch (\Exception $exception){
            return back()->withInput()->withErrors($exception->getMessage());
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

        $categoryTrashed = Category::onlyTrashed()->where('name', $newName)->first();

        if ($categoryTrashed) {
//            dd('aaa');
            $categoryTrashed->restore();
            $categoryTrashed -> update(['user_id' => $user->id]);
            $newCategory = $categoryTrashed;
        }
        else
        {
            $existingCategory = Category::where('name', $newName)->first();

            if ($existingCategory) {
//                dd('bbbb');
//                dd($existingCategory);;
                $newCategory = $existingCategory;
               $userCategory= $existingCategory->whereHas('users', function ($query) use ($user) {
                    $query->where('user_id',auth()->id());
                });
               if ($userCategory){
                   return redirect()->back()->with('error', 'Category already exists.');
               }
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
//        dd($request->all());

//        abort_if($category->user_id != auth()->id(), 403);
//        $selectedDate =$request->input('month',now()->month);
        $user=Auth::user();
        $authorize= Category::whereHas('users', function ($query) use ($category,$user) {
            $query->where('user_id',$user->id)
                ->where('category_id',$category->id);
        })->first();
        if(!$authorize){
            abort(403);
        }
        $start =$request->input('start',now()->startOfYear()->format('Y-m-d'));
        $end =$request->input('end',now()->endOfYear()->format('Y-m-d'));
//
//        $users = User::wherehas('categories', function ($query) use ($category) {
//            $query->where('category_user.category_id', $category->id);
//        }
//        )->get();
        $users = $category->users()->wherePivot('date','>=',$start)
            ->wherePivot('date','<=',$end)->exists();
        abort_unless($users, 403);

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
        $users=User::all()->pluck('id')->toArray();
        if ($user->hasAdminRole()) {
            $category->users()->detach($users);
            $category->delete();
        }else{
            $category->users()->detach();
        }
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }


    public function newCreate()
    {
        $user = Auth::user();

        $check = Category::whereHas('users', function ($query) {
            $query->where('user_id', Auth::id());
        })->count();

//        dd($check);
//        dd($check->pluck('name')->toArray());

//        dd(Carbon::now()->month,Carbon::now()->year);
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
        $existingPercentage = $user->categories()
            ->whereYear('category_user.date', carbon::now()->year)
            ->whereMonth('category_user.date', carbon::now()->month)
            ->where('category_user.user_id', Auth::id())
//            ->get();
            ->sum('category_user.percentage');

//        ->get();
//        dd($existingPercentage);
//        dd($exisitingPercentage->pluck('percentage')->toArray());

        return view('categories.new-category', compact('categories','existingPercentage'));
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

        $user = Auth::user();

        if ($request->date<1 || $request->date>12) {
            return redirect()->back()->with('error', 'Invalid month.');
        }
        $category=$user->categories()->where('category_id',$request->category_id)->first();

        abort_if(!$category, 403);


        $month=$request->date;
        $year = Carbon::now()->year;

        $date= Carbon::createFromDate($year, $month,1);

        $start = Carbon::create($date)->startOfMonth();
        $end = Carbon::create($date)->endOfMonth();
        $invalidDate=$user->categories()->wherePivot('date','>=', $start)->wherePivot('date','<=', $end)->first();
        abort_if(!$invalidDate, 404);

        $categories =  Category::findOrFail($request->category_id)->users()
            ->where('id',Auth::id())
            ->wherePivot('date','>=', $start)->
            wherePivot('date','<=', $end)
            ->first();


//        $percentage = $categories->pivot->percentage;


        return view('categories.forecastEdit', compact('categories','start','end'));

    }


    public function forecastUpdate(Request $request)
    {

//        dd($request->all());
        $user = Auth::user();
        $start=$request->input('start');
        $end=$request->input('end');
        $category=$request->input('category');
        $newPercentage=$request->input('percentage');
        $oldPercentage=$request->input('oldPercentage');

        $exisitingPercentage= $user->categories()
            ->whereBetween('category_user.date', [$start, $end])
            ->wherePivot('category_id','!=',$category)
            ->sum('category_user.percentage');

//        dd($category , Auth::id());
//        dd($exisitingPercentage);

        if ($newPercentage<0){
            return redirect()->back()->with('error', 'percentage must be at least 0.');
        }
        $newTotal = $exisitingPercentage + $newPercentage;
        if ($newTotal > 100) {
            $remaining = 100 - $exisitingPercentage;
            return redirect()->back()->with('error', "Invalid update. You can only assign up to $remaining% to this category for the selected month.");
        }


        $data=['percentage'=>$newPercentage];

//        dd(Category::find($category));

     $user->categories()
            ->where('id',$category)
            ->wherePivot('date', '>=', $start)
            ->wherePivot('date', '<=', $end)

            ->update($data);

        return redirect()->route('forecasts.index');
    }




}
