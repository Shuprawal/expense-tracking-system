<?php

namespace App\Http\Controllers;

use App\Http\Requests\DateDurationRequest;
use App\Models\Statement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DateDurationRequest $request)
    {
//        $start = $request->input('start', Carbon::now()->startOfYear()->toDateString());

        $start_date = $request->input('start', Carbon::now()->startOfYear());
        $end_date = $request->input('end',Carbon::now()->endOfYear());

        $statements = Statement::orderBy('date','desc')
            ->whereBetween('date',[$start_date,$end_date])
            ->get();
//        dd($start_date, $end_date, $statements);;
        return view('statement.index' ,compact('statements')) ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Statement $statement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Statement $statement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Statement $statement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Statement $statement)
    {
        //
    }
}
