<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'status',
        'amount',
        'start_date',
        'end_date',
        'session',
        'payment_method',
        'payment_details',
        'device_id',
        'user_id',
        'expired_date'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
