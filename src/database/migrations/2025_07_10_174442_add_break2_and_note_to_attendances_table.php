<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBreak2AndNoteToAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->timestamp('break2_start')->nullable()->after('break_end');
            $table->timestamp('break2_end')->nullable()->after('break2_start');
            $table->text('note')->nullable()->after('break2_end');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['break2_start', 'break2_end', 'note']);
        });
    }
}
