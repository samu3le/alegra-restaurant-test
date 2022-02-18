<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public static $env;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'created_by',
        'quantity',
        'state',
        'created_at'
    ];

    const STATE = [
        1 => 'requested',
        2 => 'pending',
        3 => 'dispatched',
    ];

    public static function boot() {

        parent::boot();

        static::created(function($item) {
            \Log::info('Order Created Event:'.$item);
        });

        static::creating(function($item) {
            $item->created_by = config('app.env') === 'testing' ? 1 : \Auth::user()->id;
            \Log::info('Order Creating Event:'.$item);
        });

	}

    public function getFillable() {
        return $this->fillable;
    }

    public function details()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'id');
    }
}
