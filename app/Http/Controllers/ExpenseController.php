<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $budget = Budget::where('user_id', auth()->id())->first();
//        if ($budget) {
            $categories = Category::with('users')
                ->whereHas('users', function ($query) {
                    $query->where('users.id', auth()->id());
                })
                ->get();
            $expenses = Expense::where('user_id',auth()->id())->with(['users','category'])->get();
            return view('expenses.index', compact('expenses','categories'));
//        }
//        else{
//            return redirect()->route('budgets.create');
//        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('users')
            ->whereHas('users', function ($query) {
                $query->where('users.id', auth()->id());
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

            Expense::create([
                'amount'=>$request->amount,
                'description'=> $request->description,
                'user_id'=> auth()->id(),
                'date'=> $request->date,
                'category_id'=> $request->category_id
            ]);

            $budget = Budget::where('user_id', auth()->id())->first();
            if ($budget) {
//                dd($budget->limit, $budget->amount , $request->amount);
//
//                if($budget->limit < $budget->amount - $request->amount){
//
//                    return back()->withErrors(['amount'=> 'You have exceeded your limit']);
//                }else{
//                    if (($budget->amount - $request->amount)<0){
//
//                        return back()->withErrors(['amount'=> 'You have exceeded your budget']);
//                    } else{
                        $budget->decreaseBudget($request->amount);
//                    }

//                }
//
            }
            DB::commit();
            return redirect()->route('expenses.index');

        }catch (\Exception $e) {
            DB::rollBack();
            dd($e);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
