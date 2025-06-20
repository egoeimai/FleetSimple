<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email', 'old_id', 'company_name'];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'client_id'); // Explicitly define the foreign key column
    }
    

}
