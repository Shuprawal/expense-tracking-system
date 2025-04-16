<?php

namespace App\Http\Controllers;


use App\Http\Requests\IncomeRequest;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $incomes= Income::all();
        return view('income.index',compact('incomes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('income.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeRequest $request)
    {
        $user = auth()->user();
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'required',
        ]);


        $amount = $request->input('amount');
        try {
            DB::beginTransaction();
            $forecastIncome =  $user-> incomes()-> create([
                'amount'=>$amount,
                'date'=>$request->input('date'),
                'description'=>$request->input('description'),
            ]);
            $forecastIncome->statements()->create([
                'amount'=>$amount,
            ]);
            DB::commit();
            return redirect()->route('forecasts.index');
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->withInput()->with('error',$exception->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Income $income)
    {
        $user=auth()->user();
        abort_if($income->user_id != auth()->id(), 403);

        return view('income.edit',compact('income'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IncomeRequest $request, Income $income)
    {
        $user = auth()->user();


        $amount = $request->input('amount');
//        dd($income->amount);

        try {
            DB::beginTransaction();

//            $editIncome= Income::findOrFail($income);
            abort_if($income->user_id != auth()->id(), 403);
            $income -> update([
                'amount'=>$amount,
                'date'=>$request->input('date'),
                'description'=>$request->input('description'),
            ]);
//            $income->statements()->update([
//                'amount'=>$amount,
//            ]);
            DB::commit();
            return redirect()->route('forecasts.index');
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->withInput()->with('error',$exception->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        abort_if($income->user_id != auth()->id(), 403);
        $income->delete();
        return redirect()->back()->with('success','Income deleted successfully');
    }
}
