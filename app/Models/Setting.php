<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    protected $fillable = ['excluded_days', 'excluded_dates', 'email_days', 'greeting_text', 'email_message'];

    protected $casts = [
        'excluded_days' => 'array',
        'excluded_dates' => 'array',
        'email_days' => 'array',
    ];
}
