<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Otp extends Model
{
    public const TYPE_LOGIN = "login";
    public const TYPE_PASSWORD_RESET = "password_reset";
    public const TYPE_PASSWORD_CHANGE = "password_change";
    public const TTL_MINUTES = 10;

    protected $fillable = [
        "user_id",
        "email",
        "type",
        "code_hash",
        "expires_at",
        "consumed_at",
    ];

    protected $casts = [
        "expires_at" => "datetime",
        "consumed_at" => "datetime",
    ];

    public static function issue(
        User $user,
        string $type,
        int $ttlMinutes = self::TTL_MINUTES
    ): string
    {
        self::where("user_id", $user->id)
            ->where("type", $type)
            ->whereNull("consumed_at")
            ->delete();

        $code = str_pad((string) random_int(0, 999999), 6, "0", STR_PAD_LEFT);

        self::create([
            "user_id" => $user->id,
            "email" => $user->email,
            "type" => $type,
            "code_hash" => Hash::make($code),
            "expires_at" => now()->addMinutes($ttlMinutes),
        ]);

        return $code;
    }

    public static function verify(User $user, string $type, string $code): ?self
    {
        $otp = self::where("user_id", $user->id)
            ->where("type", $type)
            ->whereNull("consumed_at")
            ->where("expires_at", ">=", now())
            ->latest()
            ->first();

        if (!$otp || !Hash::check($code, $otp->code_hash)) {
            return null;
        }

        return $otp;
    }

    public function consume(): void
    {
        $this->consumed_at = now();
        $this->save();
    }
}
