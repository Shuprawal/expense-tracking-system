<?php

namespace App\Http\Controllers;

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



    public function index(Request $request)
    {

        $user = auth()->user();
        $start=$request->input('start',Carbon::now()->startOfMonth()->toDateString());
        $end=$request->input('end',Carbon::now()->endOfMonth()->toDateString());
        $selectedMonth = $request->input('month', now()->month);


        $expenses = Expense::where('user_id', $user->id)
            ->whereMonth('date', (int)$selectedMonth)
            ->whereHas('category', function ($query) {
                $query->withTrashed();
            })
            ->orderBy('date', 'DESC')
            ->paginate(4);

        $categories = Category::whereHas('expenses', function ($query) use ($selectedMonth, $user) {
            $query
                ->where('user_id', $user->id);
        })
            ->withTrashed()
            ->get();

        return view('expenses.index', compact('categories', 'expenses', 'selectedMonth'));
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
    public function store(IncomeRequest $request)
    {

        try {
            DB::beginTransaction();
           $expense = Expense::create([
                'amount'=>$request->amount,
                'description'=> $request->description,
                'user_id'=> auth()->id(),
                'date'=> $request->date,
                'category_id'=> $request->category_id
            ]);

           $expense->statements()->create([
               'amount'=>$request->amount
           ]);


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
        $categories = Category::with('users')
            ->whereHas('users', function ($query) {
                $query->where('users.id', auth()->id())
                    ->whereMonth('date', now()->month);
            })
            ->get();
        return view('expenses.edit',compact('expense','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IncomeRequest $request, Expense $expense)
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
    public function search(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('inputText');
        $selectedMonth = $request->input('month', now()->month);

//
        $expenses = Expense::where('user_id', $user->id)
        ->where(function ($query) use ($search) {
            $query->where('description', 'LIKE', "%{$search}%")
                ->orWhere('amount', 'LIKE', "%{$search}%")
                ->orWhereHas('category', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                });
        })
            ->orderBy('date', 'DESC')
            ->paginate(5);

        $categories = Category::whereHas('expenses', function ($query) use ($selectedMonth, $user) {
            $query->whereMonth('date', $selectedMonth)
                ->where('user_id', $user->id);
        })
            ->withTrashed()
            ->distinct()
            ->get();

        return view('expenses.index', compact('categories', 'expenses', 'selectedMonth', 'search'));
    }

}
