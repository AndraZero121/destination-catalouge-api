<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function provinces(Request $request)
    {
        return response()->json(
            Province::query()->orderBy("name")->get(),
        );
    }

    public function cities(Request $request, $provinceId)
    {
        $province = Province::findOrFail($provinceId);

        return response()->json(
            City::query()
                ->where("province_id", $province->id)
                ->orderBy("name")
                ->get(),
        );
    }
}
