<?php

namespace RiseTech\FormRequest\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use RiseTech\FormRequest\Services\ServicesForm;

class GenerateCacheCountriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        try{
            $data = Cache::remember("FORM_SERVICE_COUNTRIES", Carbon::now()->addMonth(), function () {
                return ServicesForm::getCountries();
            });

            for ($i = 0; $i < count($data); $i++) {
                GenerateCacheStatesJob::dispatch($data[$i]['iso3'])->delay(Carbon::now()->addMinute());
            }

        }catch (\Exception $exception){

        }
    }
}
