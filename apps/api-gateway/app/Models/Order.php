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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
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
            $item->created_by = \Auth::user() ? \Auth::user()->id : 1;
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

    public function owner()
    {
        return $this->hasOne(User::class,'id','created_by');
    }

}
