<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ForecastincomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('budgets',BudgetController::class);
    Route::resource('incomes',IncomeController::class);
    Route::resource('expenses',ExpenseController::class);
    Route::resource('categories',CategoryController::class);
    Route::resource('forecasts',ForecastincomeController::class);
    Route::get('forecasts/calculation',[ForecastincomeController::class, 'calculation'])->name('forecasts.calculation');
    Route::get('forecasts/expense/report',[ForecastincomeController::class, 'report'])->name('forecasts.report');
});

Route::get('categories/define/category', [CategoryController::class, 'newCreate'])->name('new-categories');
Route::get('categories/define/forecast', [CategoryController::class, 'forecast'])->name('forecast');
Route::post('categories/define/forecast', [CategoryController::class, 'forecastStore'])->name('forecast.percentage');



require __DIR__.'/auth.php';
