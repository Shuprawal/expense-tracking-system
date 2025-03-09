<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;


class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        

//        $array = collect([
//           [['id'=>1, 'name'=>'Abc']]
//        ]);

//        $array->map(function ($item) {
//            return $item;
//        });

//$array->mapWithKeys(function ($item , $key) {
//    return [$key => 5];
//});





//
//        $arraySum = collect([
//            ['id'=>1, 'amount'=>'100'],['id'=>2, 'amount'=>'200']
//        ]);
//
//       $sum = $arraySum->map(function ($item) {
//           return $item['amount'];
//       });
//        dd($sum);
//
//
//
//        $upper = collect([
//            ['id'=>1, 'name'=>'ABC'],['age'=>20, 'company'=>'DEF']
//        ]);
//        $a = $upper->flatMap(function(array $item) {
//         return array_map('strtolower', $item);
//        });

//        dd($a);
//
//
//
////
//        $array = [
//            ['name'=>"Abc",'age'=>20],['name'=>"Def",'age'=>25]
//        ];
//        $mapped = Arr::mapWithKeys($array, function (array $item, int $key) {
//            return [$item['name'] => $item['age']];
//        });
//        dd($mapped);
//
//
//
//        $array= collect([
//            [ 'id'=>1, 'name'=>'Abc']
//        ]);
//     $a= $array->map(function ($item) {
//          return ['label'=> $item['name'], 'value'=> $item['id']];
//      });
//      dd($a);

//        reduce

//        $upper = collect([
//            ['id'=>1, 'name'=>'ABC'],['id'=>2, 'name'=>'DEF']
//        ]);
//        $map = $upper->map(function ( $item) {
//            return $item['id'];
//        });
//        dd($map);


//        $sum = collect([
//           ['id'=>1, 'amount'=>'100','vat'=>'300'],['id'=>2, 'amount'=>'200', 'vat'=>'400']
//        ]);
//        $total = $sum->reduce(function (?int $carry,  $item) {
//            return  $item["amount"] + $carry;
//        });
//
//        dd($total);



        $budget = Budget::where('user_id', auth()->id())->first();
        if ($budget) {
            $incomes = Income::where('user_id',auth()->id())->with(['users', 'category'])->get();
            return view('income.index', compact('incomes'));
        }else{
            return redirect()->route('budgets.create');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('type','income')->with('users')
            ->userOrAdmin()

            ->get();
        return view('income.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeRequest $request)
    {
//        try {

//            dd($request->amount);


            Income::create([
                'amount'=>$request->amount,
                'description'=> $request->description,
                'user_id'=> auth()->id(),
                'date'=> $request->date,
                'category_id'=> $request->category_id
            ]);


        $budget = Budget::where('user_id', auth()->id())->first();
        if ($budget) {
            $budget->increaseBudget($request->amount);
        }else{
            Budget::create([
                'user_id'=> auth()->id(),
                'amount'=> $request->amount,
                'limit'=> '0.0',
                'alert'=> '0.0',
            ]);
        }


//        public function approveRequest(Borrow $borrow)
//    {
//        $borrow->update(['request' => 'approved', 'borrow_date' => now()]);
//        $borrow->book->decreaseCopies();
//
//        return redirect()->back()->with('success', 'Borrow request approved.');
//    }


            return redirect()->route('incomes.index');
//        }catch (\Exception $e) {
//            return back()->with('error', $e->getMessage());
//        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        //
    }
}
