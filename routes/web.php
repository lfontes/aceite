<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/clientes', function(){
        return view('livewire.clientes.index');
    })->name('clientes');

    Route::get('/movimientos', function(){
        return view('livewire.movimientos.index');
    })->name('movimientos');
    
});



//Route Hooks - Do not delete//
	Route::view('movimientos', 'livewire.movimientos.index')->middleware('auth');
