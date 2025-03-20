<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'email',
        'services',
        'total_cost',
        'greeting',
        'custom_message'
    ];

    protected $casts = [
        'services' => 'array', // Store as JSON
    ];

    public function client()
    {
        return $this->belongsTo(Clients::class);
    }
}
