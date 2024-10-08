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

class GenerateCacheCitiesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $country;
    public string $state;

    public function __construct(string $country, string $state)
    {
        $this->country = $country;
        $this->state = $state;
    }

    public function handle(): void
    {
        try {

            $country = $this->country;
            $state = $this->state;

            Cache::remember("FORM_SERVICE_CITIES_{$country}_{$state}", Carbon::now()->addMonth(), function () use ($country, $state) {
                return ServicesForm::getCities($country, $state);
            });

        } catch (\Exception $exception) {

        }
    }
}
