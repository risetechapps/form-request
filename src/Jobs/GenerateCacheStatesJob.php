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

class GenerateCacheStatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $country;

    public function __construct(string $country)
    {
        $this->country = $country;
    }

    public function handle(): void
    {
        try {

            $country = $this->country;

            $data = Cache::remember("FORM_SERVICE_STATE_{$country}", Carbon::now()->addMonth(), function () use ($country) {
                return ServicesForm::getStates($country);
            });

            for ($i = 0; $i < count($data); $i++) {

                GenerateCacheCitiesJob::dispatch($data[$i]['country'], $data[$i]['state'])->delay(Carbon::now()->addMinute());
            }

        } catch (\Exception $exception) {

        }
    }
}
