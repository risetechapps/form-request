<?php

namespace RiseTech\FormRequest;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use RiseTech\FormRequest\Commands\MigrateCommand;
use RiseTech\FormRequest\Commands\SeedCommand;
use Illuminate\Support\Facades\Validator;
use RiseTech\FormRequest\Contracts\ValidatorContract;
use Illuminate\Console\Scheduling\Schedule;
use RiseTech\FormRequest\Jobs\GenerateCacheBanksJob;
use RiseTech\FormRequest\Jobs\GenerateCacheCountriesJob;


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

        $this->app->booted(function () {
            $this->registerSchedule();
        });
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
        $validatorConfig = config('rules.validators') ?? [];
        $validatorDefault = Rules::defaultValidators();

        foreach ($validatorConfig as $rule => $className) {

            if(new $className() instanceof ValidatorContract){
                Validator::extend($rule, function ($attribute, $value, $parameters, $validator) use ($className) {
                    return $className::validate($attribute, $value, $parameters, $validator);
                });
            }
        }

        foreach ($validatorDefault as $rule => $className) {

            if(new $className() instanceof ValidatorContract){
                Validator::extend($rule, function ($attribute, $value, $parameters, $validator) use ($className) {
                    return $className::validate($attribute, $value, $parameters, $validator);
                });
            }
        }
    }

    /**
     * @throws BindingResolutionException
     */
    private function registerSchedule(): void
    {

        $schedule = $this->app->make(Schedule::class);

        $schedule->job(new GenerateCacheBanksJob)->daily();
        $schedule->job(new GenerateCacheCountriesJob)->daily();
    }
}
