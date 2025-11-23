<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Factories\HasFactory;

class Report_Edit_Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'sheet_id',
        'cell',
        'old_value',
        'new_value',
        'edited_by'
    ];

    public $timestamps = false;
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function sheet()
    {
        return $this->belongsTo(Report_Sheets::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}
