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
        $categoryIncomes = Category::with('users')
            ->whereHas('users', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->get();


        $categoryExpenses = Category::with('users')
            ->whereHas('users', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->get();

        $categories = Category::all();

        return view('categories.index', compact('categoryIncomes', 'categoryExpenses', 'categories'));
    }

    public function adminIndex()
    {
        $categories = Category::all();
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
//        dd('aa');
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

            // Ensure at least one category is selected or added
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
     * Store a newly created resource in storage.
     */
//    public function store(CategoryRequest $request)
//    {
//        try {
//
//            DB::beginTransaction();
//
//            $categoryID = [];
//            $date = [];
//            $attchData = [];
//            foreach ($request->name as $categoryName) {
//                $category = Category::firstOrCreate(['name' => $categoryName]);
//                $categoryID[] = $category->id;
//                $date[] = $request->date;
//            }
//            foreach ($categoryID as $index => $categoryID) {
//                $attchData[]=[
//                    'category_id'=>$categoryID,
//                    'user_id'=>auth()->id(),
//                    'date'=>$date[$index]
//                ];
//
//            }
////            session(['selected_date' => $request->date]);
//
//            auth()->user()->categories()->attach($attchData);
//
//
//            DB::commit();
//            return redirect()->route('forecast');
//
//        } catch (\Throwable $th) {
//            DB::rollBack();
//            return back()->with('error', $th->getMessage());
//
//        }
//
//    }



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
        //
    }

    public function newCreate()
    {
//        $cat = Category::with('users')
//        ->whereHas('users', function ($query) {
////            $query->where('users.id', auth()->id());
//        })
//        ->get();
//
//        if (!$cat->isEmpty()){
//
//            $categories = Category::with('users')
//                ->whereHas('users',function ($query){
//                    $query->whereBetween('category_user.date', [Carbon::now()->subMonth(2)->startOfMonth(), Carbon::now()->endOfMonth()])
//                    ->where('users.id', auth()->id());
//                })
//                ->get();

//            if ( carbon::now()->day == 1){

//                $categories = Category::with('users')
//                    ->whereHas('category_user',function ($query){
//                        $month = Carbon::now()->month;
//                        $query->whereMonth('date', ($month - 1) )
//                            ->orwhereMonth('date', ($month ))
//                            ->whereHas('users', function ($subQuery)
//                            {
//                                $subQuery->where('users.id', auth()->id());
//                            });
//
//
//                    })
//
//                    ->get();
//            }




//        }else{
            $categories = Category::with('users')
//                ->userOrAdmin()
                ->get();
//        }




        return view('categories.new-category', compact('categories'));
//        return view('categories.new-category',compact('categories'));
    }

    public function forecast()
    {
        $repeatCategory=[];
        $categories = Category::with('users')

            ->whereHas('users', function ($query) {

//                    $query->whereBetween('category_user.date',[Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    $query->whereDate('category_user.date',session('categoryDate'))
                    ->where('users.id', auth()->id());
            })
            ->get();

        $categoryIds = $categories->pluck('id');

        $expense = Expense::where('user_id', auth()->id())
            ->whereIn('category_id', $categoryIds)
            ->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
            ->get();

//        dd($expense);

        $categoriesArray = $categories->pluck('percentage')->toArray();


        $cats = Category::with('users')
            ->whereHas('users', function ($query) {

                $query->whereBetween('category_user.date',[Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth(1)->endOfMonth()])
                    ->where('users.id', auth()->id());
            })->get();


        $repeatCategory1 = [];
        $repeatCategory1 = $cats->where($categories->pluck('name') , $cats->pluck('name'));
        if ($repeatCategory1){
//            dd($repeatCategory1);
        }


        foreach ($cats as $cat){
            $repeatCategory[] = $cat;
            if (count($repeatCategory) > 0){
//                dd($cat->percentage);
              $category1 =  $cat->whereHas('users', function ($query) {
                    $query->whereBetween('category_user.date',[Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                  ->where('users.id', auth()->id());
                })->get();
              $category2 =  $cat->whereHas('users', function ($query) {
                  $query->whereBetween('category_user.date',[Carbon::now()->subMonth(2)->startOfMonth(), Carbon::now()->subMonth(2)->endOfMonth()])
                  ->where('users.id', auth()->id());
              })->get();
//
              while ($category1->pluck('name')== $category2->pluck('name')){
//                  dd('same');
              }
            }
        }



        return view('categories.forecast', compact('categories'));


    }
    public function forecastStore(ForecastPercentage $request)
    {
        $user = Auth::user();
        $data = [];

        foreach ($request->category as $index => $category) {
            $data[$category] = ['percentage' => $request->percentage[$index]];
        }

//        $user->categories()->sync($data);
       $newCat = $user->categories()->wherePivot('date', session('categoryDate'))->syncWithoutDetaching($data);
//        dd($newCat);
//       $user->$newCat->sync($data);
//      $newCat->sync($data);
//        $user->categories()->attach($data);
        return redirect()->route('forecasts.index');

    }

    public function forecastEdit($category_id)
    {
        $category = Category::where('id',$category_id)
            ->with('users')
            ->whereHas('users', function ($subQuery) {
                $subQuery->where('users.id', auth()->id());
            })->get();

        return view('categories.forecast-edit', compact('category'));
    }
    public function forecastUpdate(ForecastPercentage $request, $category_id)
    {
        $user = Auth::user();

//        $data = ;
        {
            $data = ['percentage' => $request->percentage];
        }

        $user->categories()->sync($data);

    }


}
