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
            [
                "name" => "Pura Tanah Lot",
                "description" => "Ikon Bali dengan pura di atas batu karang, populer untuk menikmati sunset dramatis.",
                "category" => "Pantai",
                "province" => "Bali",
                "city" => "Kuta",
                "budget_min" => 60000,
                "budget_max" => 200000,
                "facilities" => "Area parkir, spot foto, toko suvenir, warung makan",
                "latitude" => -8.621737,
                "longitude" => 115.086647,
                "photos" => [
                    "https://images.unsplash.com/photo-1501785888041-af3ef285b470",
                    "https://images.unsplash.com/photo-1505236732-1c7978f2d2c7",
                ],
            ],
            [
                "name" => "Monumen Nasional",
                "description" => "Landmark Jakarta dengan museum sejarah dan dek observasi untuk melihat kota dari ketinggian.",
                "category" => "Sejarah",
                "province" => "DKI Jakarta",
                "city" => "Jakarta Pusat",
                "budget_min" => 20000,
                "budget_max" => 75000,
                "facilities" => "Museum, lift observasi, area parkir, taman",
                "latitude" => -6.175392,
                "longitude" => 106.827153,
                "photos" => [
                    "https://images.unsplash.com/photo-1500375592092-40eb2168fd21",
                    "https://images.unsplash.com/photo-1548670984-34846dc9d6b7",
                ],
            ],
            [
                "name" => "Curug Cimahi",
                "description" => "Air terjun tinggi di Bandung dengan nuansa hutan pinus dan udara sejuk.",
                "category" => "Air Terjun",
                "province" => "Jawa Barat",
                "city" => "Bandung",
                "budget_min" => 25000,
                "budget_max" => 75000,
                "facilities" => "Area parkir, deck foto, toilet",
                "latitude" => -6.820650,
                "longitude" => 107.580589,
                "photos" => [
                    "https://images.unsplash.com/photo-1502082553048-f009c37129b9",
                    "https://images.unsplash.com/photo-1448518184296-a22facb4446f",
                ],
            ],
            [
                "name" => "Lawang Sewu",
                "description" => "Bangunan heritage berarsitektur Belanda di Semarang dengan tur sejarah.",
                "category" => "Sejarah",
                "province" => "Jawa Tengah",
                "city" => "Semarang",
                "budget_min" => 20000,
                "budget_max" => 80000,
                "facilities" => "Pemandu tur, area parkir, galeri",
                "latitude" => -6.980265,
                "longitude" => 110.409088,
                "photos" => [
                    "https://images.unsplash.com/photo-1489515217757-5fd1be406fef",
                    "https://images.unsplash.com/photo-1467269204594-9661b134dd2b",
                ],
            ],
            [
                "name" => "Ranu Kumbolo",
                "description" => "Danau jernih di jalur pendakian Semeru dengan pemandangan bintang saat malam.",
                "category" => "Danau",
                "province" => "Jawa Timur",
                "city" => "Malang",
                "budget_min" => 100000,
                "budget_max" => 350000,
                "facilities" => "Camping ground, jalur trekking, area istirahat",
                "latitude" => -8.009095,
                "longitude" => 112.934967,
                "photos" => [
                    "https://images.unsplash.com/photo-1500534314209-a25ddb2bd429",
                    "https://images.unsplash.com/photo-1501785888041-af3ef285b470",
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

            $destination = Destination::updateOrCreate(
                ["name" => $data["name"]],
                [
                    "description" => $data["description"],
                    "category_id" => $categoryId,
                    "province_id" => $province->id,
                    "city_id" => $cityId,
                    "budget_min" => $data["budget_min"],
                    "budget_max" => $data["budget_max"],
                    "facilities" => $data["facilities"],
                    "latitude" => $data["latitude"],
                    "longitude" => $data["longitude"],
                ],
            );

            foreach ($data["photos"] as $index => $photoUrl) {
                DestinationPhoto::updateOrCreate(
                    [
                        "destination_id" => $destination->id,
                        "order" => $index + 1,
                    ],
                    [
                        "photo_url" => $photoUrl,
                    ],
                );
            }
        }
    }
}
