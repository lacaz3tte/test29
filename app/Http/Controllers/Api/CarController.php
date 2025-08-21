<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CarController extends Controller
{
    public function index(): JsonResponse
    {
        $cars = Car::query()->with('carModel.brand')
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($cars);
    }

    public function store(CarRequest $carRequest): JsonResponse
    {
        $car = $carRequest->user()->cars()->create([...$carRequest->validated(), 'user_id' => $carRequest->user()->id]);
        $car->load('carModel.brand');

        return response()->json($car, 201);
    }

    public function show(Car $car): JsonResponse
    {
        Gate::authorize('view', $car);
        $car->load('carModel.brand');
        return response()->json($car);
    }

    public function update(CarRequest $carRequest, Car $car): JsonResponse
    {
        Gate::authorize('update', $car);

        $car->update($carRequest->validated());
        $car->load('carModel.brand');

        return response()->json($car);
    }

    public function destroy(Car $car): JsonResponse
    {
        Gate::authorize('delete', $car);
        $car->delete();

        return response()->json(null, 204);
    }
}
