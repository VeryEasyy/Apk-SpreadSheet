<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Factories\HasFactory;

class karyawan extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'address',
        'phone',
        'position',
        'maintenance',
        'join_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
