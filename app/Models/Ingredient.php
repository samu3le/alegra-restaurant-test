<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Services;

use App\Models\ShoppingHistory;

use App\Casts\ImageUrl;

class Ingredient extends Model
{
    use HasFactory,Services\Storage;

    public static $env;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public int $requested_quantity = 0;

    protected $fillable = [
        'name',
        'key',
        'is_active',
        'stock',
        'image',
        'created_by',
        'created_at'
    ];
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'image' => ImageUrl::class,
    ];

    public static function boot() {

        parent::boot();

        static::created(function($item) {
            \Log::info('Ingredient Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->image ? $item->image = self::saveIngredientImage($item->image) : null ;
            $item->created_by = config('app.env') === 'testing' ? 1 : \Auth::user()->id;
            \Log::info('Ingredient Creating Event:'.$item);
        });

        static::updating(function($item) {
            $item->image ? $item->image = self::saveIngredientImage($item->image) : null ;
            \Log::info('Ingredient Updating Event:'.$item);
        });

	}

    public function getFillable() {
        return $this->fillable;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'recipes', 'ingredient_id', 'product_id')
            ->withPivot(
                'id',
                'product_id',
                'ingredient_id',
                'quantity',
                'created_by',
            );
    }

    public function shoppings()
    {
        return $this->hasMany(ShoppingHistory::class, 'ingredient_id', 'id');
    }

    public function owner()
    {
        return $this->hasOne(User::class,'id','created_by');
    }

}
