<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Services;

class Recipe extends Model
{
    use HasFactory;

    public static $env;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'product_id',
        'ingredient_id',
        'quantity',
        'created_by',
    ];

    public static function boot() {

        parent::boot();

        static::created(function($item) {
            \Log::info('Recipe Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->created_by = config('app.env') === 'testing' ? 1 : 1;
            \Log::info('Recipe Creating Event:'.$item);
        });

	}

    public function getFillable() {
        return $this->fillable;
    }

}
