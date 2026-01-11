<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $type;
    public int $expiresMinutes;
    public string $label;

    public function __construct(string $code, string $type, int $expiresMinutes = 10)
    {
        $this->code = $code;
        $this->type = $type;
        $this->expiresMinutes = $expiresMinutes;
        $this->label = match ($type) {
            "login" => "Login",
            "password_reset" => "Reset Password",
            "password_change" => "Change Password",
            default => "OTP",
        };
    }

    public function build(): self
    {
        return $this->subject("OTP Code for {$this->label}")
            ->view("emails.otp")
            ->with([
                "code" => $this->code,
                "label" => $this->label,
                "expiresMinutes" => $this->expiresMinutes,
            ]);
    }
}
