<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Forecastincome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $user = auth()->user();
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        if($request->input('type')=="yearly"){
            $amount = $request->input('amount') / 12;
        }else{
            $amount = $request->input('amount');
        }
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
            'amount' => 'required|numeric|min:1',
        ]);
        $forcast = Forecastincome::where('id',$id)->first();

        $forcast -> update([
            'amount'=>$request->input('amount'),

        ]);
        return redirect()->route('forecasts.index');
    }


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
                    ->whereMonth('category_user.date', Carbon::now()->month);
                })
                ->withPivot('percentage', 'date')->withTrashed()
                ->get()
                ->unique('id');

        }else{
            $categories = $user->categories()
                ->whereHas('users', function ($query) use ($selectedMonth) {
                    $query->where('user_id', auth()->id())
                        ->whereMonth('category_user.date', Carbon::now()->month((int)$selectedMonth));
                })
            ->withPivot('percentage', 'date')
            ->get()
            ->unique('id');
        }

        $expenses = [];
        foreach ($categories as $category) {

            if ($selectedMonth == Carbon::now()->addMonth()->month ) {

                $pivot = $category->users()
                    ->wherePivot('user_id', $user->id)
//                    ->whereMonth('category_user.date', Carbon::now()->month((int)$selectedMonth))
                    ->whereMonth('category_user.date', Carbon::now()->month)
                    ->withPivot('percentage')
                    ->first();

            }else{
                $pivot = $category->users()
                    ->wherePivot('user_id', $user->id)
                    ->whereMonth('category_user.date', Carbon::now()->month((int)$selectedMonth))
                    ->withPivot('percentage')
                    ->first();
            }

            $percentage = $pivot?->pivot->percentage ?? 0;
            $amount = ($percentage / 100) * $totalIncome;

            if ($selectedMonth == Carbon::now()->addMonth(1)->month) {


                $spended = Expense::where('user_id', $user->id)
                    ->where('category_id', $category->id)
                    ->whereMonth('date', $selectedMonth-1 )
                    ->get();

                $totalExpense = $spended->sum('amount');
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
                    'category_id' => $category->id,

                ];

            } else {

                $spended = Expense::where('user_id', $user->id)
                    ->where('category_id', $category->id)
                    ->whereMonth('date', $selectedMonth)
                    ->get();
                $totalExpense = $spended->sum('amount');
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

        return view('forecastincome.report', compact('expenses', 'totalIncome', 'income', 'selectedMonth'));
    }



}
