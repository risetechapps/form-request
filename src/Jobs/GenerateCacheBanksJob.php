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

class GenerateCacheBanksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        try{
            Cache::remember("FORM_SERVICE_BANKS", Carbon::now()->addMonth(), function () {
                return ServicesForm::getBANKS();
            });

        }catch (\Exception $exception){

        }
    }
}
