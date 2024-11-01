<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesModel extends Model
{
    use HasFactory;
    protected $fillable = ['amount', 'category', 'description', 'payment_date'];
}
