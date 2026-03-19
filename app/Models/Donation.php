<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'client_ref',
        'user_id',
        'cause_id',
        'amount_mxn',
        'message',
        'status',
    ];

    protected $casts = [
        'amount_mxn' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }
    
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
}