<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    public static $env;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'name',
        'key',
        'is_active',
        'created_by'
    ];

    public static function boot() {

        parent::boot();

        // https://www.nicesnippets.com/blog/laravel-model-created-event-example

        static::created(function($item) {
            \Log::info('Ingredient Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->created_by = config('app.env') === 'testing' ? 1 :  1;//\Auth::user()->id;
            \Log::info('Ingredient Creating Event:'.$item);
        });

	}

    public function getFillable() {
        return $this->fillable;
    }
}
