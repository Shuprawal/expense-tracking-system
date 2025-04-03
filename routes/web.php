<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ForecastincomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('expenses/search',[ExpenseController::class, 'search'])->name('expenses.search');
    Route::resource('expenses',ExpenseController::class);
    Route::get('categories/display', [CategoryController::class, 'display'])->name('categories.display');
    Route::post('categories/transfer', [CategoryController::class, 'transfer'])->name('categories.transfer');
    Route::resource('categories',CategoryController::class)->except('show');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');


    Route::resource('forecasts',ForecastincomeController::class);
    Route::get('forecasts/calculation',[ForecastincomeController::class, 'calculation'])->name('forecasts.calculation');
    Route::get('forecasts/expense/report',[ForecastincomeController::class, 'report'])->name('forecasts.report');

    Route::delete('/forecast/remove', [CategoryController::class, 'forecastDetach'])->name('forecasts.detach');



    Route::get('categories/define/category', [CategoryController::class, 'newCreate'])->name('new-categories');

//    Route::get('admin/categories', [CategoryController::class, 'adminIndex'])->name('admin_categories');
    Route::get('categories/define/forecast', [CategoryController::class, 'forecast'])->name('forecast');
    Route::post('categories/define/forecast', [CategoryController::class, 'forecastStore'])->name('forecast.percentage');
});




Route::middleware(['admin',
    'permission'
])->group(function () {
    Route::get('admin/dashboard',[UserController::class, 'dashboard'])->name('admin.dashboard');

    Route::resource('users',UserController::class);

    Route::get('permissions/search', [PermissionController::class, 'search'])->name('permissions.search');
    Route::resource('permissions',PermissionController::class)->except('index');
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');



    Route::resource('roles',RoleController::class);
    Route::post('detachRole/{role}/{user}',[RoleController::class, 'detachRole'])->name('role.detach');
    Route::post('attachRole/',[RoleController::class, 'attachRole'])->name('role.attach');
});



require __DIR__.'/auth.php';
