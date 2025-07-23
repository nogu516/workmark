<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }

            $table->timestamp('work_start')->nullable();
            $table->timestamp('work_end')->nullable();
            $table->integer('break_duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'work_start', 'work_end', 'break_duration']);
        });
    }
};
