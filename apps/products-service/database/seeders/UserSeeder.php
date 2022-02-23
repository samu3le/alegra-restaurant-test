<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $role = random_int(
            1,
            count(User::ROLES),
        );
        
        foreach(range(1,50) as $index) {
            User::factory([
                'role' => $role,
            ])
            ->create();
        }
    }
}
