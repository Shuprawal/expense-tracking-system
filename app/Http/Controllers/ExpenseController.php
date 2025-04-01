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
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedMonth = $request->input( 'month',now()->month);

        $expenses = Expense::where('user_id',$user->id)->whereMonth('date',((int)$selectedMonth))
            ->whereHas('category', function ($query) use ($user, $selectedMonth) {
                $query->whereHas('users', function ($subQuery) use ($user, $selectedMonth) {
                    $subQuery->where('category_user.user_id', $user->id)
                        ->whereMonth('date', (int)$selectedMonth);
                });
            })->distinct()->paginate(3);

        $categories = Category::whereHas('expenses', function ($query) use ($selectedMonth, $user) {
            $query->whereMonth('date', $selectedMonth)
                ->where('user_id', $user->id);

        })->withTrashed()->distinct()->get();

        return view('expenses.index', compact('categories','expenses', 'selectedMonth'));

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
