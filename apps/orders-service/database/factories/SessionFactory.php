<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Session;

class SessionFactory extends Factory
{
    public function definition()
    {
        session()->put('ipAddress', '127.0.0.1');
        session()->put('userAgent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36');
        return [
            'token' => '',
            'user_id' => '',
            'expired_at' => now()->addMinutes(30),
        ];
    }
}
