<?php

namespace RiseTech\FormRequest;

use Illuminate\Support\ServiceProvider;
use RiseTech\FormRequest\Commands\MigrateCommand;
use RiseTech\FormRequest\Commands\SeedCommand;
use Illuminate\Support\Facades\Validator;
class FormRequestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('rules.php'),
            ], 'config');
        }

        $this->commands([
            MigrateCommand::class,
            SeedCommand::class,

        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->registerRules();
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        if (file_exists(base_path('config/rules.php'))) {
            $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'rules');
        }

        $this->app->singleton(Commands\MigrateCommand::class, function ($app) {
            return new Commands\MigrateCommand($app['migrator'], $app['events']);
        });

        $this->app->singleton('form-request', function () {
            return new FormRequest;
        });

        $this->app->singleton(ValidationRuleRepository::class);
    }

    private function registerRules(): void
    {
        $rules = config('rules.rules') ?? [];

        foreach ($rules as $rule => $className) {

            Validator::extend($rule, function ($attribute, $value, $parameters, $validator) use ($className) {
                return $className::validate($attribute, $value, $parameters, $validator);
            });

        }
    }
}
