<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $primaryKey = 'token';

    protected $fillable = [
        'token',
        'user_id',
        'ip_address',
        'user_agent',
        'expired_at',
    ];

    public function isDeleted()
    {
        return $this->deleted_at !== null;
    }

    public function close()
    {
        $this->deleted_at = now();
        $this->save();
    }

    public static function boot() {

        parent::boot();

        static::creating(function($item) {
            $item->ip_address = session('ipAddress', '');
            $item->user_agent = session('userAgent', '');
            \Log::info('Session Creating Event:'.$item);
        });

        static::created(function($item) {
            \Log::info('Session Created Event:'.$item);
        });

	}
}
