<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('lecture_id');

            $table->string('roll_no', 20);

            $table->enum(
                'status',
                ['present', 'absent']
            );

            $table->foreign('lecture_id')
                ->references('lecture_id')
                ->on('lectures')
                ->cascadeOnDelete();

            $table->foreign('roll_no')
                ->references('roll_no')
                ->on('students')
                ->cascadeOnDelete();

            /*
             * A student can have only one
             * attendance record per lecture.
             */
            $table->unique([
                'lecture_id',
                'roll_no'
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};