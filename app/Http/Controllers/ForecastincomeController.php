<?php

namespace App\Http\Controllers;

use App\Http\Requests\DateDurationRequest;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Forecastincome;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ForecastincomeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function display()
    {
        $user = auth()->user();
        $income = Forecastincome::where('user_id',$user->id)->first();

        if (!$income){
            return redirect()->route('forecasts.create');
        }
        $totalIncome = $income->amount;
        $categories = $user->categories()->withPivot('percentage')
            ->whereMonth('category_user.date', Carbon::now()->month)->get();
        $expenses = [];

        foreach ($categories as $category){
            $percentage = $category->pivot->percentage;
            $expenses[] = [
                'name' => $category->name,
                'percentage' => $percentage,
                'amount' => ($percentage / 100) * $totalIncome

            ];
        }
        return view('forecastincome.display',compact('expenses','totalIncome','income'));

    }
    public function index(DateDurationRequest $request)
    {
        $search = $request->get('inputText');
        $start=$request->input('start',Carbon::now()->startOfMonth()->toDateString());
        $end=$request->input('end',Carbon::now()->endOfMonth()->toDateString());
        $user = auth()->user();
        $incomes = Income::where('user_id',$user->id)
            ->where('description','LIKE',"%{$search}%")
        ->whereBetween('date',[$start,$end])->paginate(10);

        return view('forecastincome.index',compact('incomes','search','start','end'));

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
        $user = auth()->user();
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        if($request->input('type')=="yearly"){
            $amount = $request->input('amount') / 12;
        }else{
            $amount = $request->input('amount');
        }

        $amount = $request->input('amount');
        try {
            DB::beginTransaction();
            $forecastIncome =  $user-> forecastexpenses()-> create([
                    'amount'=>$amount,

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
            'amount' => 'required|numeric|min:0',
        ]);
        $forcast = Forecastincome::where('id',$id)->first();

        $forcast -> update([
            'amount'=>$request->input('amount'),

        ]);
        return redirect()->route('forecasts.index');
    }


    public function report(Request $request)
    {
        $expenses = [];
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);

        $selectedDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
        $user = auth()->user();
//        $start= $selectedDate->copy()->startOfMonth()->toDateString();
//        $end=$selectedDate->copy()->endOfMonth()->toDateString();
//        $startNext= $selectedDate->copy()->startOfMonth()->addMonth(1)->toDateString();
//        $endNext=$selectedDate->copy()->endOfMonth()->addMonth(1)->toDateString();

        $nextMonthSelected = $selectedMonth == Carbon::now()->addMonth()->month;
        $upComingMonths =$selectedMonth > Carbon::now()->month;

        $isMonth= $nextMonthSelected ? Carbon::now()->month : $selectedMonth;

        $income = Income::where('user_id', $user->id)
            ->whereMonth('date', $selectedMonth)
            ->sum('amount');

        if (!$income) {
            $forecastIncome=Forecastincome::where('user_id',$user->id)
//                ->whereMonth('date', $isMonth)
                ->sum('amount');

            if (!$forecastIncome){
                return redirect()->route('forecasts.create');
            }else{
                $incomeSource='forecastIncome';
//                $expense['totalIncome']='aaa';
                $totalIncome = $forecastIncome;
            }

        } else {
            $incomeSource='income';
            $totalIncome = $income;
        }

//        if (!$income && $selectedDate < Carbon::now()) {
//            return redirect()->route('incomes.create');
//        } else {
//            $totalIncome = $income;
//        }

        $categories = $user->categories()
            ->whereHas('users', function ($query) use ($isMonth) {
                $query->where('user_id', auth()->id())
                    ->whereMonth('category_user.date', $isMonth);
            })
            ->withPivot('percentage', 'date')->withTrashed()
            ->get()
            ->unique('id');


        foreach ($categories as $category) {

            $pivot = $category->users()
                ->wherePivot('user_id', $user->id)
                ->whereMonth('category_user.date', $isMonth)
                ->withPivot('percentage')
                ->first();
//            if ($selectedMonth == Carbon::now()->addMonth()->month ) {
//                $pivot= $category->users()->whereMonth('category_user.date', Carbon::now()->month) ->first();;
//
//
//            }else{
//                $pivot= $category->users()->whereMonth('category_user.date', Carbon::now()->month((int)$selectedMonth)) ->first();;
//
//            }

            $percentage = $pivot?->pivot->percentage ?? 0;
            $amount = ($percentage / 100) * $totalIncome;

            if ($selectedMonth == Carbon::now()->addMonth(1)->month) {


                $spended = Expense::where('user_id', $user->id)
                    ->where('category_id', $category->id)
                    ->whereMonth('date', $isMonth)
                    ->get();



//                dd($spended->amount);
                $totalExpense = $spended->sum('amount');
//                dd($totalIncome);
                $spendPercentage = round(($totalExpense / $totalIncome) * 100, 2);
//                dd($percentage,$spendPercentage);
                $sumPercentage = round($percentage + $spendPercentage);
                $forecastPercentage = round($sumPercentage / 2, 2);
                $amount = ($forecastPercentage / 100) * $totalIncome;
                $expenses[] = [
                    'name' => $category->name,
                    'percentage' => $forecastPercentage,
                    'amount' => $amount,
                    'spend' => 0,
                    'spendPercentage' => 0,
                    'remaining' => 0,
                    'category_id' => $category->id,

                ];

            } else {

                $spended = Expense::where('user_id', $user->id)
                    ->where('category_id', $category->id)
                    ->whereMonth('date', $isMonth)
                    ->get();

//                dd($spended);
                $totalExpense = $spended->sum('amount');
//                dd($totalExpense);
//                dd($totalExpense);
                $remaining = $amount - $totalExpense;
                $spendPercentage = round($totalExpense / $totalIncome * 100, 2);

                $expenses[] = [
                    'name' => $category->name,
                    'percentage' => $percentage,
                    'amount' => $amount,
                    'spend' => $totalExpense,
                    'spendPercentage' => $spendPercentage,
                    'remaining' => $remaining,
                    'category_id' => $category->id,
                ];
            }
        }

        return view('forecastincome.report', compact('expenses', 'totalIncome', 'income', 'selectedMonth','incomeSource'));
    }




}
