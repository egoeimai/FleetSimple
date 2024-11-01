<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComingModel extends Model
{
    use HasFactory;
    protected $fillable = ['clientid', 'payment', 'documents'];
}
