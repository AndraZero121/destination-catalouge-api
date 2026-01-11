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
        Schema::table("users", function (Blueprint $table) {
            $table->timestamp("last_logout_at")->nullable()->after("remember_token");
        });

        Schema::create("otps", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->string("email");
            $table->string("type");
            $table->string("code_hash");
            $table->timestamp("expires_at");
            $table->timestamp("consumed_at")->nullable();
            $table->timestamps();

            $table->index(["email", "type"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("otps");

        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("last_logout_at");
        });
    }
};
