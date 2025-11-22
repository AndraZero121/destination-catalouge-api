<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
