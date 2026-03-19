<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'donation_id',
        'provider',
        'provider_ref',
        'status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}