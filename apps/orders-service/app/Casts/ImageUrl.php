<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use Illuminate\Support\Facades\Storage;

// Guide:
// https://atymic.dev/blog/laravel-custom-casts/

class ImageUrl implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return $value ? Storage::disk('public')->url($value) : null;
    }
    
    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}