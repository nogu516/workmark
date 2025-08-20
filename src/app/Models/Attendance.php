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

    // 日付型にキャスト
    // protected $casts = [
        // 'date' => 'date',
        // 'clock_in' => 'datetime',
        // 'clock_out' => 'datetime',
        // 'break_start' => 'datetime',
        // 'break_end' => 'datetime',
        // 'break2_start' => 'datetime',
        // 'break2_end' => 'datetime',
        // 'new_clock_in' => 'datetime',
        // 'new_clock_out' => 'datetime',
        // 'new_break_start' => 'datetime',
        // 'new_break_end' => 'datetime',
        // 'new_break2_start' => 'datetime',
        // 'new_break2_end' => 'datetime',
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RequestApplication とのリレーション
    public function requestApplications()
    {
        return $this->hasMany(RequestApplication::class, 'attendance_id');
    }
}
