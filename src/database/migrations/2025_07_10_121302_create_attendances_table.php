<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーとのリレーション
            $table->date('date'); // 勤怠日
            $table->timestamp('clock_in')->nullable(); // 出勤時刻
            $table->timestamp('clock_out')->nullable(); // 退勤時刻
            $table->timestamp('break_start')->nullable(); // 休憩開始時刻
            $table->timestamp('break_end')->nullable(); // 休憩終了時刻
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
