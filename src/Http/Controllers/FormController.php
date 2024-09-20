<?php

namespace RiseTech\FormRequest\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RiseTech\FormRequest\Http\Requests\StoreFormRequest;
use RiseTech\FormRequest\Http\Requests\UpdateFormRequest;
use RiseTech\FormRequest\Http\Resources\FormRequestResource;
use RiseTech\FormRequest\Models\FormRequest;
use RiseTech\FormRequest\Rules;
use RiseTech\FormRequest\ValidationRuleRepository;

class FormController extends Controller
{
    public function index(): JsonResponse
    {
        try {

            $model = new FormRequest();

            return response()->json([
                'success' => true,
                'data' => FormRequestResource::collection($model->all())
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, $e->getMessage()], 500);
        }
    }

    public function store(StoreFormRequest $request): JsonResponse
    {
        try {
            $model = new FormRequest();
            $store = $model->create($request->validationData());

            return response()->json([
                'success' => true,
                'data' => $store->getKey()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function show(Request $request): JsonResponse
    {
        try {
            $model = new FormRequest();
            $data = $model->find($request->id);

            return response()->json([
                'success' => true,
                'data' => FormRequestResource::make( $model->find($request->id))
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function update(UpdateFormRequest $request): JsonResponse
    {

        try {
            $model = new FormRequest();
            $model = $model->find($request->id);
            $update = $model->update($request->validationData());
            return response()->json([
                'success' => true,
                'data' => $update
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try {

            $model = new FormRequest();
            $data = $model->find($request->id);
            return response()->json([
                'success' => $data->delete()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
