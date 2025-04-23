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
        $date = $request->input('date');
        try {
            DB::beginTransaction();
            $income =  $user-> incomes()-> create([
                'amount'=>$amount,
                'date'=>$date,
                'description'=>$request->input('description'),
            ]);
            $income->statements()->create([
                'date'=>$date,
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


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Income $income)
    {
        abort_if($income->user_id != auth()->id(), 403);
        return view('income.edit',compact('income'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IncomeRequest $request, Income $income)
    {

        $amount = $request->input('amount');
        try {
            DB::beginTransaction();

            abort_if($income->user_id != auth()->id(), 403);
            $income -> update([
                'amount'=>$amount,
                'date'=>$request->input('date'),
                'description'=>$request->input('description'),
            ]);
            $income->statements()->update([
                'date'=> $request->date,
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
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        abort_if($income->user_id != auth()->id(), 403);
        try {
            DB::beginTransaction();
            $income->delete();
            $income->statements()->delete();
            DB::commit();
            return redirect()->back()->with('success','Income deleted successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->withInput()->with('error',$exception->getMessage());
        }


    }
}
