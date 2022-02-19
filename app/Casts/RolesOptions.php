<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

// Guide:
// https://atymic.dev/blog/laravel-custom-casts/

class RolesOptions implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return isset($model::ROLES[$value]) ? $model::ROLES[$value] : null;
    }
    
    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}