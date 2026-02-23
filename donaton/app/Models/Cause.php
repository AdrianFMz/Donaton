<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cause extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'problem_description',
        'use_of_funds',
        'since_date',
        'is_active',
    ];

    protected $casts = [
        'since_date' => 'date',
        'is_active' => 'boolean',
    ];
}