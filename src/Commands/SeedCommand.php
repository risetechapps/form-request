<?php

namespace RiseTech\FormRequest\Commands;

use Illuminate\Console\Command;

class SeedCommand extends Command
{
    protected $signature = 'form-request:seed';

    protected $description = 'Command description';

    public function handle(): void
    {
        $this->call('db:seed', [
            '--class' => 'RiseTech\\FormRequest\\Database\\Seeds\\FormRequestSeeder',

        ]);

        $this->info('Form request seeder executed successfully.');

    }
}
