<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',      // 新規作成時に入れる場合
        'clock_out',
        'new_clock_in',
        'new_clock_out',
        'new_break_start',
        'new_break_end',
        'new_break2_start',
        'new_break2_end',
        'note',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(RequestApplication::class, 'attendance_id');
    }
}
