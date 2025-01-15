<?php

namespace App\Models;

use App\Models\Clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'brand',
        'model',
        'license_plate',
        'enable_reminder',
    ];

    /**
     * Define the relationship to the Client model.
     */
    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id'); // Explicitly define the foreign key column
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
