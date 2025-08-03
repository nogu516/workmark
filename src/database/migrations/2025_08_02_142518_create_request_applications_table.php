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
        Schema::create('request_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamp('new_clock_in')->nullable();
            $table->timestamp('new_clock_out')->nullable();

            $table->timestamp('new_break_start')->nullable();
            $table->timestamp('new_break_end')->nullable();

            $table->timestamp('new_break2_start')->nullable();
            $table->timestamp('new_break2_end')->nullable();

            $table->text('note')->nullable();
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_applications');
    }
};
