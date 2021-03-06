<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Recipe;
use App\Services;

use App\Casts\ImageUrl;

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
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'image' => ImageUrl::class,

    ];

    public static function boot() {

        parent::boot();

        static::created(function($item) {
            \Log::info('Product Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->image ? $item->image = self::saveProductImage($item->image) : null ;
            $item->created_by = \Auth::user() ? \Auth::user()->id : 1;
            \Log::info('Product Creating Event:'.$item);
        });

        static::updating(function($item) {
            $item->image ? $item->image = self::saveProductImage($item->image) : null ;
            \Log::info('Product Updating Event:'.$item);
        });
	}

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipes', 'product_id', 'ingredient_id')
        ->withPivot(
            'id',
            'product_id',
            'ingredient_id',
            'quantity',
            'created_by',
        );
    }

    public function recipe()
    {
        return $this->hasMany(Recipe::class, 'product_id', 'id');
    }

    public function getFillable() {
        return $this->fillable;
    }

    public function orders_details()
    {
        return $this->hasMany(OrderDetails::class, 'product_id', 'id');
    }

}
