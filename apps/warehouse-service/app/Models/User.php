<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Services;

use App\Casts\RolesOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nickname',
        'email',
        'uuid',
        'password',
        'is_active',
        'role',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'role' => RolesOptions::class,
    ];

    const ROLES = [
        1 => 'manager',
        2 => 'kitchen',
        3 => 'warehouse',
        4 => 'guest',
    ];

    public static function boot() {

        parent::boot();

        // https://www.nicesnippets.com/blog/laravel-model-created-event-example

        static::created(function($user) {

            $user->token = Services\JWT::GenerateToken($user->uuid, $user->id);

            \Log::info('User Created Event:'.$user);
        });

        static::creating(function( $user ) {
            $user->uuid = (string) Str::uuid();
            $user->password = Hash::make($user->password);
            if(!$user->role) {
                $user->role = 4;
            }

            \Log::info('User Creating Event:'.$user);
        });
	}

    public function generateToken($remember_me = false)
    {
        $this->token = Services\JWT::GenerateToken(
            uuid: $this->uuid,
            user_id: $this->id,
            remember_me: $remember_me,
        );
    }

    public function canAccess($role_name)
    {
        return in_array($role_name, self::ROLES);
    }

}
