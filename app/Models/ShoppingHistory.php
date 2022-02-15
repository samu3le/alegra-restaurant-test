<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingHistory extends Model
{
    use HasFactory;

    protected $table = 'shopping_histories';

    public static $env;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'ingredient_id',
        'quantity',
        'created_by',
    ];

    public static function boot() {

        parent::boot();

        static::created(function($item) {
            \Log::info('Shopping History Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->created_by = config('app.env') === 'testing' ? 1 : 1;
            \Log::info('Shopping History Creating Event:'.$item);
        });

	}

    public function getFillable() {
        return $this->fillable;
    }

}
