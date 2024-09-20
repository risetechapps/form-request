<?php

namespace RiseTech\FormRequest;



use Illuminate\Support\Facades\Route;
use RiseTech\FormRequest\Http\Controllers\FormController;

class FormRequest
{

    public static function routes(array $options = []): void
    {
        Route::group($options,function () use ($options) {

            Route::apiResource('forms', FormController::class, [
                'parameters' => [
                    'forms' => 'id'
                ],
            ]);
        });
    }
}
