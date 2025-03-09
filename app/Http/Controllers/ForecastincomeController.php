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
        }

        $totalIncome = $income->amount;


        $categories = $user->categories()->withPivot('percentage')->get();
        $expenses = [];

        foreach ($categories as $category){
            $percentage = $category->pivot->percentage;
            $expenses[] = [
                'name' => $category->name,
                'percentage' => $percentage,
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
        $forecastIncome = Forecastincome::create([
            'amount'=>$amount,
            'user_id'=> auth()->id(),
        ]);
        $forecastIncome->statements()->create([
            'amount'=>$amount,
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

//    public function report( Request $request)
//    {
//
//        $expenses="";
////        if($request->input('month')){
//            $selectedMonth = $request->input('month', now()->month);
////            $selectedMonth = $request->input('month', now()->month);
////        }
////        dd(Carbon::now()->addMonth(1)->month);
//
//
//
//
//        $user = auth()->user();
//        $income = Forecastincome::where('user_id',$user->id)->first();
//
//        if (!$income){
//            return redirect()->route('forecasts.create');
//        }else{
//            $totalIncome = $income->amount;
//        }
//
////        $categories = $user->categories()
////            ->whereHas('users', function ($query) use ($selectedMonth) {
////                $query
////                    ->where('users.id', auth()->id());
////            })
////            ->withPivot('percentage','date')
////            ->get()
////        ->unique('id');
//
//
//
//
////          $categories = $user->categories()
////            ->whereHas('users', function ($query) use ($selectedMonth) {
////                $query->whereBetween('date', [
////                    now()->setMonth((int)$selectedMonth)->startOfMonth(),
////                    now()->setMonth((int)$selectedMonth)->endOfMonth()
////                ])
////                    ->where('users.id', auth()->id());
////            })
////            ->withPivot('percentage','date')
////            ->get()
////        ->unique('id');
//
//        $categories = $user->categories()
//            ->whereHas('users', function ($query) use ($selectedMonth) {
//                $query->where('users.id', auth()->id())
//                    ->whereBetween('category_user.date', [  // Ensure the correct table for the 'date' field
//                        now()->setMonth((int)$selectedMonth)->startOfMonth(),
//                        now()->setMonth((int)$selectedMonth)->endOfMonth()
//                    ])
//                ;
//            })
//            ->withPivot('percentage', 'date') // Load the 'percentage' and 'date' from the pivot table
//            ->get()
//            ->unique('id'); // Ensure categories are unique by 'id'
//
//
//
//        $expenses = [];
//
//
////        dd($selectedMonth);
//        foreach ($categories as $category){
//            $spended = Expense::where('user_id',$user->id)->where('category_id',$category->id)->whereMonth('date',$selectedMonth)->get();
//            if (!$spended){
//                $totalExpense = 0;
//            }else{
//                $totalExpense = $spended->sum('amount');
//
//            }
//
//
//
//
//            $percentage = $category->pivot->percentage;
//
//
//            $amount = ($percentage / 100) * $totalIncome;
//            $remaining = $amount - $totalExpense;
//            $spendPercentage = round( $totalExpense/$totalIncome * 100,2) ;
////            $spendPercentage = '100' ;
//
//            $forecastPercentage = round( ($percentage + $spendPercentage)/2  ,2) ;
//
////artisan console scdule
//
//            if($selectedMonth == Carbon::now()->addMonth(1)->month) {
//
////                dd(Carbon::now()->addMonth(1)->month);
//
//                $amount = ($forecastPercentage / 100) * $totalIncome;
//
//                $expenses[] = [
//                    'name' => $category->name,
//                    'percentage' => $forecastPercentage,
//                    'amount' => $amount,
//                    'spend' => "0",
//                    'spendPercentage'=>'0',
//                    'remaining' => "0",
//
//                ];
//            }else{
//                $expenses[] = [
//                    'name' => $category->name,
//                    'percentage' => $category->pivot->percentage,
//                    'amount' => $amount,
//                    'spend' => $totalExpense,
//                    'spendPercentage'=>$spendPercentage,
//                    'remaining' => $remaining,
//
//                ];
//            }
//
//
//
//        }
//
//        return view('forecastincome.report',compact('expenses','totalIncome','income','selectedMonth'));
//    }



    public function report(Request $request)
    {
        $selectedMonth = $request->input('month', now()->month);

        $user = auth()->user();
        $income = Forecastincome::where('user_id', $user->id)->first();
        if (!$income) {
            return redirect()->route('forecasts.create');
        } else {
            $totalIncome = $income->amount;
        }

        if ($selectedMonth == Carbon::now()->addMonth(1)->month || $selectedMonth == Carbon::now()->month) {

            $categories = $user->categories()
                ->whereHas('users', function ($query) use ($selectedMonth) {
                    $query->where('user_id', auth()->id())

                    ;
                })
                ->withPivot('percentage', 'date')
                ->get()
                ->unique('id');
        }else{
            $categories = $user->categories()
                ->whereHas('users', function ($query) use ($selectedMonth) {
                    $query->where('user_id', auth()->id())
                    ->whereBetween('category_user.date', [
                        now()->setMonth((int)$selectedMonth)->startOfMonth(),
                        now()->setMonth((int)$selectedMonth)->endOfMonth()
                    ]);
                })
                ->withPivot('percentage', 'date')
                ->get()
                ->unique('id');
        }



//        $categories = $user->categories()
//            ->whereHas('users', function ($query) use ($selectedMonth) {
//                $query->where('users.id', auth()->id())
//                    ->whereBetween('category_user.date', [  // Ensure the correct table for the 'date' field
//                        now()->setMonth((int)$selectedMonth)->startOfMonth(),
//                        now()->setMonth((int)$selectedMonth)->endOfMonth()
//                    ])
//                ;
//            })
//            ->withPivot('percentage', 'date') // Load the 'percentage' and 'date' from the pivot table
//            ->get()
//            ->unique('id'); // Ensure categories are unique by 'id'
//




        $expenses = [];
        foreach ($categories as $category) {


            if ($selectedMonth == Carbon::now()->addMonth(1)->month ) {

                $pivot = $category->users()
                    ->wherePivot('user_id', $user->id)
                    ->whereBetween('category_user.date', [
                        now()->setMonth((int)$selectedMonth)->subMonth(1)->startOfMonth(),
                        now()->setMonth((int)$selectedMonth)->subMonth(1)->endOfMonth()
                    ])
                    ->withPivot('percentage')
                    ->first();
            }else{
                $pivot = $category->users()
                    ->wherePivot('user_id', $user->id)
                    ->whereBetween('category_user.date', [
                        now()->setMonth((int)$selectedMonth)->startOfMonth(),
                        now()->setMonth((int)$selectedMonth)->endOfMonth()
                    ])
                    ->withPivot('percentage')
                    ->first();
            }

            $percentage = $pivot?->pivot->percentage ?? 0;

            $amount = ($percentage / 100) * $totalIncome;


//            dd($percentage);
            if ($selectedMonth == Carbon::now()->addMonth(1)->month) {
//

                $spended = Expense::where('user_id', $user->id)
                    ->where('category_id', $category->id)
                    ->whereMonth('date', $selectedMonth-1 )
                    ->get();
                $totalExpense = $spended->sum('amount');
                $remaining = $amount - $totalExpense;
                $spendPercentage = round($totalExpense / $totalIncome * 100, 2);

                $forecastPercentage = round(($percentage + $spendPercentage) / 2, 2);


                $amount = ($forecastPercentage / 100) * $totalIncome;
                $expenses[] = [
                    'name' => $category->name,
                    'percentage' => $forecastPercentage,
                    'amount' => $amount,
                    'spend' => 0,
                    'spendPercentage' => 0,
                    'remaining' => 0,
                ];
            } else {
                $spended = Expense::where('user_id', $user->id)
                    ->where('category_id', $category->id)
                    ->whereMonth('date', $selectedMonth)
                    ->get();
                $totalExpense = $spended->sum('amount');
                $remaining = $amount - $totalExpense;
                $spendPercentage = round($totalExpense / $totalIncome * 100, 2);

                $forecastPercentage = round(($percentage + $spendPercentage) / 2, 2);
                $expenses[] = [
                    'name' => $category->name,
                    'percentage' => $percentage,
                    'amount' => $amount,
                    'spend' => $totalExpense,
                    'spendPercentage' => $spendPercentage,
                    'remaining' => $remaining,
                ];
            }
        }

        return view('forecastincome.report', compact('expenses', 'totalIncome', 'income', 'selectedMonth'));
    }



}
