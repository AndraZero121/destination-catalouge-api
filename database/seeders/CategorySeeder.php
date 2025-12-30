<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            "Pantai",
            "Gunung",
            "Taman",
            "Museum",
            "Kuliner",
            "Belanja",
            "Air Terjun",
            "Danau",
            "Sejarah",
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ["slug" => Str::slug($category)],
                ["name" => $category],
            );
        }
    }
}
