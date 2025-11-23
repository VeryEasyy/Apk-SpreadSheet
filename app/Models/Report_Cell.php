<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Factories\HasFactory;

class Report_Cell extends Model
{
    use HasFactory;

    protected $fillable = [
        'sheet_id',
        'cell',
        'value',
        'updated_by'
    ];

    public function sheet()
    {
        return $this->belongsTo(Report_Sheets::class,  'sheet_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
