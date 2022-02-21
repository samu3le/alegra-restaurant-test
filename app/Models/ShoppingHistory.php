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
        'created_at',
        'updated_at',
        'delete_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'delete_at' => 'datetime:Y-m-d H:i:s',
    ];

    public static function boot() {

        parent::boot();

        static::created(function($item) {
            \Log::info('Shopping History Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->created_by = config('app.env') === 'testing' ? 1 : \Auth::user()->id;
            \Log::info('Shopping History Creating Event:'.$item);
        });

	}

    public function getFillable() {
        return $this->fillable;
    }

    public function owner()
    {
        return $this->hasOne(User::class,'id','created_by');
    }

}
