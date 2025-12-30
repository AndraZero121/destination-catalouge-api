<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\City;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = [
            "Jawa Tengah" => ["Semarang", "Solo", "Magelang"],
            "Jawa Barat" => ["Bandung", "Bogor", "Depok"],
            "Jawa Timur" => ["Surabaya", "Malang", "Batu"],
            "Bali" => ["Denpasar", "Ubud", "Kuta"],
            "DKI Jakarta" => ["Jakarta Pusat", "Jakarta Selatan", "Kepulauan Seribu"],
        ];

        foreach ($provinces as $provinceName => $cities) {
            $province = Province::updateOrCreate(["name" => $provinceName]);

            foreach ($cities as $cityName) {
                City::updateOrCreate([
                    "province_id" => $province->id,
                    "name" => $cityName,
                ]);
            }
        }
    }
}
