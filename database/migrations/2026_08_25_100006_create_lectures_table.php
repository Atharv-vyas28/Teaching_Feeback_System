<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lectures', function (Blueprint $table) {

            $table->id('lecture_id');

            $table->unsignedBigInteger('course_id');

            $table->unsignedBigInteger('faculty_id');

            $table->unsignedInteger('lecture_no');

            $table->string('topic', 255);

            $table->date('date');

            $table->foreign('course_id')
                ->references('course_id')
                ->on('courses')
                ->cascadeOnDelete();

            $table->foreign('faculty_id')
                ->references('id')
                ->on('faculties')
                ->cascadeOnDelete();

            /*
             * Lecture number must be unique
             * within a particular course.
             */
            $table->unique([
                'course_id',
                'lecture_no'
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};