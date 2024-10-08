<?php

namespace RiseTech\FormRequest\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use RiseTech\FormRequest\Services\ServicesForm;

class FormServiceController extends Controller
{
    public function cep(Request $request): JsonResponse
    {
        $id = $request->id;

        $data = Cache::remember("FORM_SERVICE_CEP_{$id}", Carbon::now()->addHours(24), function () use ($id) {
            return ServicesForm::getCEP($id);
        });
        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function cnpj(Request $request): JsonResponse
    {
        $id = $request->id;

        $data = Cache::remember("FORM_SERVICE_CNPJ_{$id}", Carbon::now()->addHours(24), function () use ($id) {
            return ServicesForm::getCNPJ($id);
        });

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function cpf(Request $request): JsonResponse
    {
        $cpf = $request->cpf;
        $date = $request->date;

        $data = Cache::remember("FORM_SERVICE_CPF_{$cpf}_{$date}", Carbon::now()->addMonth(), function () use ($cpf, $date) {
            return ServicesForm::getCPF($cpf, $date);
        });

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function banks(Request $request): JsonResponse
    {
        $data = Cache::remember("FORM_SERVICE_BANKS", Carbon::now()->addMonth(), function () {
            return ServicesForm::getBANKS();
        });

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function countries(Request $request): JsonResponse
    {
        $data = Cache::remember("FORM_SERVICE_COUNTRIES", Carbon::now()->addMonth(), function () {
            return ServicesForm::getCountries();
        });

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function states(Request $request): JsonResponse
    {
        $country = $request->country;

        $data = Cache::remember("FORM_SERVICE_STATE_{$country}", Carbon::now()->addMonth(), function () use ($country) {
            return ServicesForm::getStates($country);
        });

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function cities(Request $request): JsonResponse
    {
        $country = $request->country;
        $state = $request->state;

        $data = Cache::remember("FORM_SERVICE_CITIES_{$country}_{$state}", Carbon::now()->addMonth(), function () use ($country, $state) {
            return ServicesForm::getCities($country, $state);
        });

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }
}
