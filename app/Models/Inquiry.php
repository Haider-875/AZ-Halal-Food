<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'inquiry_type',
        'message',
        'status',
    ];
}
