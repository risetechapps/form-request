<?php

namespace RiseTech\FormRequest\Database\Seeds;

use Illuminate\Database\Seeder;
use RiseTech\FormRequest\Models\FormRequest;
use RiseTech\FormRequest\Rules;

class FormRequestSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Rules::defaultRules() as $key => $value) {

            if(!FormRequest::where('form' , $key)->exists()){

                FormRequest::create([
                    'form' => $key,
                    'rules' => $value,
                ]);
            }
        }
    }
}
