<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Forecastincome;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ForecastincomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $income = Forecastincome::where('user_id',$user->id)->first();

        if (!$income){
            return redirect()->route('forecasts.create');
        }else{
            $totalIncome = $income->amount;
        }

        $categories = $user->categories()->withPivot('percentage')->get();
        $expenses = [];

        foreach ($categories as $category){
            $percentage = $category->pivot->percentage;
            $expenses[] = [
                'name' => $category->name,
                'percentage' => $category->pivot->percentage,
                'amount' => ($percentage / 100) * $totalIncome

            ];
        }

        return view('forecastincome.index',compact('expenses','totalIncome','income'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('forecastincome.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required',
        ]);
        if($request->input('type')=="yearly"){
            $amount = $request->input('amount') / 12;
        }else{
            $amount = $request->input('amount');
        }
        Forecastincome::create([
            'amount'=>$amount,
            'user_id'=> auth()->id(),
        ]);
        return redirect()->route('forecasts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ForecastincomeController $forecastincome)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ForecastincomeController $forecastincome)
    {
        $income = Forecastincome::where('user_id',auth()->id())->first();
        return view('forecastincome.edit',compact('income'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required',

        ]);


        $forcast = Forecastincome::where('id',$id)->first();

        $forcast -> update([
            'amount'=>$request->input('amount'),

        ]);
        return redirect()->route('forecasts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ForecastincomeController $forecastincome)
    {
        //
    }

    public function report( Request $request)
    {

        $expenses="";
//        if($request->input('month')){
            $selectedMonth = $request->input('month', now()->month);
//            $selectedMonth = $request->input('month', now()->month);
//        }
//        dd(Carbon::now()->addMonth(1)->month);




        $user = auth()->user();
        $income = Forecastincome::where('user_id',$user->id)->first();

        if (!$income){
            return redirect()->route('forecasts.create');
        }else{
            $totalIncome = $income->amount;
        }

        $categories = $user->categories()
            ->whereHas('users', function ($query) use ($selectedMonth) {
                $query->whereBetween('date', [
                    now()->setMonth((int)$selectedMonth)->startOfMonth(),
                    now()->setMonth((int)$selectedMonth)->endOfMonth()
                ])
                    ->where('users.id', auth()->id());
            })
            ->withPivot('percentage','date')
            ->get()
        ->unique('id');
//        dd($categories->pluck('id'));

        $expenses = [];


        foreach ($categories as $category){
            $spended = Expense::where('user_id',$user->id)->where('category_id',$category->id)->whereMonth('date',$selectedMonth)->get();

            if (!$spended){
                $totalExpense = 0;
            }else{
                $totalExpense = $spended->sum('amount');

            }



            $percentage = $category->pivot->percentage;
            $amount = ($percentage / 100) * $totalIncome;
            $remaining = $amount - $totalExpense;
            $spendPercentage = round( $totalExpense/$amount * 100,2) ;

            $forecastPercentage = round( ($percentage + $spendPercentage)/2 *100 ,2) ;


            if($selectedMonth = Carbon::now()->addMonth(1)->month) {
                $percentage =

                $expenses[] = [
                    'name' => $category->name,
                    'percentage' => $percentage,
                    'amount' => $amount,
                    'spend' => $totalExpense,
                    'spendPercentage'=>$forecastPercentage,
                    'remaining' => $remaining,

                ];
            }


            $expenses[] = [
                'name' => $category->name,
                'percentage' => $category->pivot->percentage,
                'amount' => $amount,
                'spend' => $totalExpense,
                'spendPercentage'=>$spendPercentage,
                'remaining' => $remaining,

            ];
        }

        return view('forecastincome.report',compact('expenses','totalIncome','income','selectedMonth'));
    }
    public function forecastNextMonth()
    {

        $user = auth()->id();

    }
    public function calculation()
    {

//        $user = auth()->user();
//        $income = Forecastincome::where('user_id',$user)->get();
//        $totalIncome = $income;
//        $categories = $user->categories()->withPivot('percentage')->get();
//        $expense = [];
//
//        foreach ($categories as $category){
//            $expense[] = [
//                'name' => $category->name,
//                'percentage' => $category->pivot->percentage,
//                'amount' => (($category->pivot->percentage)/100) * $totalIncome,
//            ];
//        }
//
//        return view('forecastincome.index',compact('expense','totalIncome'));

    }
}
