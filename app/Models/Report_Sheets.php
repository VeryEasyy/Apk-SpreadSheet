<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Factories\HasFactory;

class Report_Sheets extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'report_id',
        'sheet_name',
        'order'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function cells()
    {
        return $this->hasMany(Report_Cell::class, 'sheet_id');
    }

    public function locks()
    {
        return $this->hasMany(Report_Lock::class, 'sheet_id');
    }
}
