<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()){
        return redirect('/dashboard');
    }else{
        return view('auth.login');
    }
});

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

/*Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});*/

/*rotas que precisam de autenticação*/
Route::middleware(['auth', 'verified'])->group(function () {

    /************************************
    *****Rotas para PagesController******
    ************************************/
    Route::controller(PagesController::class)->group(function () {
        Route::get('/dashboard','showHome')->name('dashboard');
    });/* fim Rotas para Recuperar dados */
});

require __DIR__.'/auth.php';
