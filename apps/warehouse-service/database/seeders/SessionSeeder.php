<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Session;
use App\Models\User;
use App\Services\JWT;

class SessionSeeder extends Seeder
{
    public function run()
    {
        foreach(range(1,100) as $index) {
            $user_id = User::all()->random()->id;
            $token = JWT::GenerateToken('', $user_id);

            Session::factory([
                'token' => $token,
                'user_id' => $user_id,
            ])
            ->create();
        }
    }
}
