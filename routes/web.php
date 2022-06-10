<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [App\Http\Controllers\GeneralController::class, 'viewDashboard'])->name('dashboard');
    Route::resource('todo-list', App\Http\Controllers\TodoListController::class);
    Route::put('/todo-list/set-is-complete/{id}',[App\Http\Controllers\TodoListController::class, 'setIsComplete']);

    Route::middleware('admin-only')->group(function() {
        Route::resource('users', App\Http\Controllers\UserController::class);
    });

    Route::get('/clients', function(Request $request){
        return view('pages.clients', ['clients' => $request->user()->clients]);
    });
});
Route::get('/login', [App\Http\Controllers\LoginController::class, 'viewLogin'])->name('login');
Route::post('/authenticate',[App\Http\Controllers\LoginController::class, 'authenticate']);


Route::get('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
