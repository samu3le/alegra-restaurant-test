<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $table = 'orders_details';

    public static $env;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'product_id',
        'order_id',
        'state',
        'quantity',
        'created_at'
    ];

    const STATE = [
        1 => 'created',
        2 => 'requested',
        3 => 'preparing',
        4 => 'prepared',
    ];

    public static function boot() {

        parent::boot();

        static::created(function($item) {
            \Log::info('Order details Created Event:'.$item);
        });

        static::creating(function($item) {
            \Log::info('Order details Creating Event:'.$item);
        });

	}

    public function getFillable() {
        return $this->fillable;
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
