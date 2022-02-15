<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Services;

class Product extends Model
{
    use HasFactory, Services\Storage;

    public static $env;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'name',
        'is_active',
        'image',
        'created_by'
    ];

    public static function boot() {

        parent::boot();

        static::created(function($item) {
            \Log::info('Ingredient Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->image ? $item->image = self::saveProductImage($item->image) : null ;
            $item->created_by = config('app.env') === 'testing' ? 1 :  1;//\Auth::user()->id;
            \Log::info('Ingredient Creating Event:'.$item);
        });

	}

    public function getFillable() {
        return $this->fillable;
    }

}