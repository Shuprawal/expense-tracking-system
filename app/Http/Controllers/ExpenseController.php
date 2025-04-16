<?php

namespace App\Http\Controllers;


use App\Http\Requests\DateDurationRequest;
use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\IncomeRequest;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{




    public function index(DateDurationRequest $request)
    {
//        dd($request->search);
        $user = auth()->user();

        $start = $request->input('start', Carbon::now()->startOfYear()->toDateString());
        $end = $request->input('end', Carbon::now()->endOfYear()->toDateString());
        $search = $request->input('inputText');
        $category = $request->input('category');

//        dd($search);
        $expensesQuery = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$start, $end]);

        if ($search) {
            $expensesQuery->where(function ($subQuery) use ($search) {
                $subQuery->where('description', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->withTrashed()->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }


        if ($category) {
            $expensesQuery->where('category_id', $category);
        }

        $expenses = $expensesQuery->orderBy('date', 'DESC')
            ->paginate(9)
            ->appends([
                'start' => $start,
                'end' => $end,
                'inputText' => $search,
                'category' => $category,
            ]);

        $categories = Category::whereHas('expenses', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->withTrashed()->get();


        $selectedCategoryName = $categories->firstWhere('id', $category)?->name;

        return view('expenses.index', compact(
            'categories', 'expenses', 'start', 'end', 'search', 'selectedCategoryName'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('users')
            ->whereHas('users', function ($query) {
                $query->where('users.id', auth()->id())
                ->whereMonth('date', now()->month);
            })
            ->get();
        return view('expenses.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
//        $aa=strlen($request->amount);
//        $aa=strlen($request->amount);
//        dd($aa);
//        dd(length().;
//        if (ob_get_length($request->amount))
        try {
            DB::beginTransaction();
           Expense::create([
                'amount'=>$request->amount,
                'description'=> $request->description,
                'user_id'=> auth()->id(),
                'date'=> $request->date,
                'category_id'=> $request->category_id
            ]);

//           $expense->statements()->create([
//               'amount'=>$request->amount
//           ]);


            DB::commit();
            return redirect()->route('expenses.index');

        }catch (\Exception $e) {
            DB::rollBack();
           return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
       abort_if($expense->user_id != auth()->id(), 403);
       $existingCategory = $expense->category;
        $categories =
            Category::with('users')
                ->whereHas('users', function ($query) {
                    $query->where('users.id', auth()->id())
                        ->whereMonth('date', now()->month);
                })

            ->get();
        $allCategory=$categories->contains($existingCategory)? $categories:  $categories->push($existingCategory);
//        dd($allCategory);
        return view('expenses.edit',compact('expense','allCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $user = auth()->user();

        try {
            DB::beginTransaction();

            $expense ->update([
                'amount'=>$request->amount,
                'description'=> $request->description,
                'date'=> $request->date,
                'category_id'=> $request->category_id
            ]);

            DB::commit();
            return redirect()->route('expenses.index');

        }catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {

        $expense->delete();
        return redirect()->route('expenses.index');
    }
//

}
