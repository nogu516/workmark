<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'user_id',
        'date',
        'new_clock_in',
        'new_clock_out',
        'new_break_start',
        'new_break_end',
        'new_break2_start',
        'new_break2_end',
        'note',
        'status',
    ];

    protected $casts = [
        'new_clock_in' => 'datetime',
        'new_clock_out' => 'datetime',
        'new_break_start' => 'datetime',
        'new_break_end' => 'datetime',
        'new_break2_start' => 'datetime',
        'new_break2_end' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
