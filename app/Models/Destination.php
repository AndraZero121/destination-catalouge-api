<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "category_id",
        "province_id",
        "city_id",
        "budget_min",
        "budget_max",
        "facilities",
        "latitude",
        "longitude",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function photos()
    {
        return $this->hasMany(DestinationPhoto::class)->orderBy("order");
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function savedBy()
    {
        return $this->hasMany(SavedDestination::class);
    }
}
