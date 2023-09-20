<?php

namespace App\Providers;

use App\Interfaces\AiInterface;
use App\Services\OpenAIService;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\OpenAIController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AiInterface::class,
            OpenAIService::class
        );

        $this->app->when(OpenAIController::class)
          ->needs(AiInterface::class)
          ->give(function () {
              return new OpenAIService();
          });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
