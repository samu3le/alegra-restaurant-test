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
        'created_at',
        'updated_at',
        'delete_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'delete_at' => 'datetime:Y-m-d H:i:s',
    ];
    
    const STATE = [
        1 => 'created', // cuando fue generado el pedido
        2 => 'requested', // cuando fue solicitado a bodega
        3 => 'preparing', // cuando cocina confirma que hay items para preparar
        4 => 'prepared', // cuando esta listo para ser entregado
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
