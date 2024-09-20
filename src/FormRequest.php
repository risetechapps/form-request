<?php

namespace RiseTech\FormRequest;


use Illuminate\Support\Facades\Route;
use RiseTech\FormRequest\Http\Controllers\FormController;

class FormRequest
{

    public static function routes(array $options = []): void
    {
        Route::group($options, function () use ($options) {

            Route::apiResource('forms', FormController::class, [
                'parameters' => [
                    'forms' => 'id'
                ],
            ]);

            Route::get('/forms/services/cep/{id}', [\RiseTech\FormRequest\Http\Controllers\FormServiceController::class, 'cep']);
            Route::get('/forms/services/cnpj/{id}', [\RiseTech\FormRequest\Http\Controllers\FormServiceController::class, 'cnpj']);
            Route::get('/forms/services/cpf/{cpf}/{date}', [\RiseTech\FormRequest\Http\Controllers\FormServiceController::class, 'cpf']);
            Route::get('/forms/services/banks', [\RiseTech\FormRequest\Http\Controllers\FormServiceController::class, 'banks']);

            Route::get('/forms/services/countries', [\RiseTech\FormRequest\Http\Controllers\FormServiceController::class, 'countries']);
            Route::get('/forms/services/states/{country}', [\RiseTech\FormRequest\Http\Controllers\FormServiceController::class, 'states']);
            Route::get('/forms/services/cities/{country}/{state}', [\RiseTech\FormRequest\Http\Controllers\FormServiceController::class, 'cities']);

        });
    }
}
