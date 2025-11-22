<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Destination;
use App\Models\DestinationPhoto;
use App\Models\Province;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                "name" => "Pantai Parangtritis",
                "description" => "Pantai pasir hitam yang populer untuk menikmati sunset dan aktivitas ATV di sepanjang bibir pantai.",
                "category" => "Pantai",
                "province" => "Jawa Tengah",
                "city" => "Semarang",
                "budget_min" => 25000,
                "budget_max" => 100000,
                "facilities" => "Area parkir, penyewaan ATV, gazebo, warung makan",
                "latitude" => -7.94250000,
                "longitude" => 110.33040000,
                "photos" => [
                    "https://images.unsplash.com/photo-1507525428034-b723cf961d3e",
                    "https://images.unsplash.com/photo-1493558103817-58b2924bce98",
                ],
            ],
            [
                "name" => "Gunung Bromo",
                "description" => "Salah satu ikon wisata Jawa Timur yang terkenal dengan sunrise view di lautan pasir dan kawah aktifnya.",
                "category" => "Gunung",
                "province" => "Jawa Timur",
                "city" => "Malang",
                "budget_min" => 150000,
                "budget_max" => 500000,
                "facilities" => "Jeep tour, area parkir, mushola, tempat makan",
                "latitude" => -7.94249300,
                "longitude" => 112.95301200,
                "photos" => [
                    "https://images.unsplash.com/photo-1500530855697-b586d89ba3ee",
                    "https://images.unsplash.com/photo-1441974231531-c6227db76b6e",
                ],
            ],
            [
                "name" => "Kawah Putih Ciwidey",
                "description" => "Danau kawah dengan air berwarna kebiruan dan kabut tipis yang memberikan suasana dramatis.",
                "category" => "Taman",
                "province" => "Jawa Barat",
                "city" => "Bandung",
                "budget_min" => 50000,
                "budget_max" => 150000,
                "facilities" => "Shuttle, spot foto, toilet, tempat makan",
                "latitude" => -7.16666900,
                "longitude" => 107.40279400,
                "photos" => [
                    "https://images.unsplash.com/photo-1469474968028-56623f02e42e",
                    "https://images.unsplash.com/photo-1437622368342-7a3d73a34c8f",
                ],
            ],
        ];

        foreach ($destinations as $data) {
            $categoryId = Category::where("name", $data["category"])->value("id");
            $province = Province::where("name", $data["province"])->first();
            $cityId = City::where("name", $data["city"])
                ->where("province_id", $province?->id)
                ->value("id");

            if (!$categoryId || !$province || !$cityId) {
                continue;
            }

            $destination = Destination::create([
                "name" => $data["name"],
                "description" => $data["description"],
                "category_id" => $categoryId,
                "province_id" => $province->id,
                "city_id" => $cityId,
                "budget_min" => $data["budget_min"],
                "budget_max" => $data["budget_max"],
                "facilities" => $data["facilities"],
                "latitude" => $data["latitude"],
                "longitude" => $data["longitude"],
            ]);

            foreach ($data["photos"] as $index => $photoUrl) {
                DestinationPhoto::create([
                    "destination_id" => $destination->id,
                    "photo_url" => $photoUrl,
                    "order" => $index + 1,
                ]);
            }
        }
    }
}
