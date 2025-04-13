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

        $month=$request->date;
        $year = Carbon::now()->year;

        $date= Carbon::createFromDate($year, $month,1);

        $start = Carbon::create($date)->startOfMonth();
        $end = Carbon::create($date)->endOfMonth();

        $categories =  Category::find($request->category_id)->users()
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
        if (($exisitingPercentage - $oldPercentage) + $newPercentage > 100 || $newPercentage > 100) {
            return redirect()->back()->with('error', 'Altogether percentage must be less than 100.');
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
