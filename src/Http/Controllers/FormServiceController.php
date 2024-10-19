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

        $data = ServicesForm::getCEP($id);
        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function cnpj(Request $request): JsonResponse
    {
        $id = $request->id;

        $data = ServicesForm::getCNPJ($id);

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function cpf(Request $request): JsonResponse
    {
        $cpf = $request->cpf;
        $date = $request->date;

        $data = ServicesForm::getCPF($cpf, $date);

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function banks(Request $request): JsonResponse
    {
        $data = ServicesForm::getBANKS();

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function countries(Request $request): JsonResponse
    {
        $data = ServicesForm::getCountries();

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function states(Request $request): JsonResponse
    {
        $country = $request->country;

        $data = ServicesForm::getStates($country);

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }

    public function cities(Request $request): JsonResponse
    {
        $country = $request->country;
        $state = $request->state;

        $data = ServicesForm::getCities($country, $state);

        return response()->json([
            'success' => !is_null($data),
            'data' => $data
        ]);
    }
}
