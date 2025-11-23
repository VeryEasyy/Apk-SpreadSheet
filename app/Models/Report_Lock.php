<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Factories\HasFactory;


class Report_Lock extends Model
{
     use HasFactory;
     
     public $timestamps = false;

    protected $fillable = [
        'sheet_id',
        'cell',
        'locked_by',
        'locked_at'
    ];

    public function sheet()
    {
        return $this->belongsTo(Report_Sheets::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }
}
