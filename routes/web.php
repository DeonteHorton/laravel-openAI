<?php

use App\Http\Controllers\OpenAIController;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

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
    });

    Route::get('/test', function () {
        // $result = OpenAI::completions()->create([
        //     'model' => 'text-curie-001',
        //     // 'model' => 'text-davinci-003',
        //     'prompt' => 'what explain this code ',
        //     'max_tokens' => 256 * 2
        // ]);

        // echo $result['choices'][0]['text']; // an open-source, widely-used, server-side scripting language.
    });
});
