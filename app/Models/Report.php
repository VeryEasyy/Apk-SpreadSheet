<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'title',
        'description',
        'owner_id',
        'status'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function sheets()
    {
        return $this->hasMany(Report_Sheets::class);
    }

    public function logs()
    {
        return $this->hasMany(Report_Edit_Log::class);
    }

    public function files()
    {
        return $this->hasMany(Report_File::class);
    }

    

}
