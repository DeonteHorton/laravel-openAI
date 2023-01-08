<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OpenAIController;

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

Route::get('/', function() {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::controller(OpenAIController::class)->group(function() {
        Route::get('/chat', 'index')->name('chat');
        Route::post('/chat', 'store')->name('chat.ask');
    });

    Route::controller(UserController::class)->group(function() {
        Route::get('/users',  'index')->name('user.index');
        Route::post('/users', 'store')->name('user.create');
        Route::put('/users/{user}', 'update')->name('user.update');
        Route::delete('/users/{user}', 'destroy')->name('user.destroy');
    });
});
