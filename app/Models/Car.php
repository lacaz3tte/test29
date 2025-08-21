<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_model_id',
        'year',
        'mileage',
        'color',
        'user_id'
    ];

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getBrandNameAttribute()
    {
        return $this->carModel->brand->name;
    }

    public function getModelNameAttribute()
    {
        return $this->carModel->name;
    }
}
