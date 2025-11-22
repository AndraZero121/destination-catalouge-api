<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("destinations", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table
                ->foreignId("category_id")
                ->constrained()
                ->onDelete("cascade");
            $table
                ->foreignId("province_id")
                ->constrained()
                ->onDelete("cascade");
            $table->foreignId("city_id")->constrained()->onDelete("cascade");
            $table->decimal("budget_min", 12, 2)->nullable();
            $table->decimal("budget_max", 12, 2)->nullable();
            $table->text("facilities")->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("destinations");
    }
};
