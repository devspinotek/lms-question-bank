<?php

namespace SpinotekLms\QuestionBank;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class QuestionBankServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/question-bank.php', 'question-bank');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'question-bank');

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/question-bank.php' => config_path('question-bank.php'),
            ], 'question-bank-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/question-bank'),
            ], 'question-bank-views');
        }
    }

    protected function registerRoutes(): void
    {
        $routeConfig = config('question-bank.route', []);

        Route::group([
            'prefix' => $routeConfig['prefix'] ?? 'admin',
            'as' => $routeConfig['name_prefix'] ?? 'admin.',
            'middleware' => $routeConfig['middleware'] ?? ['web', 'auth'],
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        });
    }
}

