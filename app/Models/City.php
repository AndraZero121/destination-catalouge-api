<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ["province_id", "name"];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
