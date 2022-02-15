<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\Role;

use App\Services;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nickname',
        'email',
        'uuid',
        'password',
        'is_active',
        'role_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public static function boot() {

        parent::boot();

        static::created(function($user) {

            $user->token = Services\JWT::GenerateToken($user->uuid, $user->id);

            \Log::info('User Created Event:'.$user);
        });

        static::creating(function( $user ) {
            $user->uuid = (string) Str::uuid();
            $user->password = Hash::make($user->password);
            if(!$user->role_id) {
                $user->role_id = Role::where('name', 'user')->first()->id;
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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }




}
