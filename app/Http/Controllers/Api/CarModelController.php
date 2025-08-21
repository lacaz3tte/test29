<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Http\JsonResponse;

class CarModelController extends Controller
{
    public function index(): JsonResponse
    {
        $models = CarModel::with('brand:id,name')
            ->get(['id', 'name', 'brand_id']);

        return response()->json($models);
    }

    public function modelsByBrand(Brand $brand): JsonResponse
    {
        $models = $brand->carModels()->get(['id', 'name']);
        return response()->json($models);
    }
}
