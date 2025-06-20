<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clients;

class ScheduledEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'send_date',
        'type',
        'subscriptions',
        'sent',
         'total_cost', // <-- ADD THIS LINE
        'sent_at',
    ];

    protected $casts = [
        'subscriptions' => 'array',
        'send_date' => 'date',
        'sent' => 'boolean',
        'sent_at' => 'datetime',
    ];
    
    

    public function client()
    {
        return $this->belongsTo(Clients::class);
    }
	    	public function scheduledEmails()
{
    return $this->hasMany(ScheduledEmail::class, 'client_id');
}


	

}
