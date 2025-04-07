<?php

namespace App\Http\Controllers;

use App\Models\Imcome;
use App\Models\Income;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Imcome $imcome)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Imcome $imcome)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Imcome $imcome)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Imcome $imcome)
    {
        //
    }
}
