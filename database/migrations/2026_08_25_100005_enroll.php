<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enroll', function (Blueprint $table) {

            $table->string('roll_no', 20);

            $table->unsignedBigInteger('course_id');

            $table->foreign('roll_no')
                ->references('roll_no')
                ->on('students')
                ->cascadeOnDelete();

            $table->foreign('course_id')
                ->references('course_id')
                ->on('courses')
                ->cascadeOnDelete();

            $table->primary([
                'course_id',
                'roll_no'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enroll');
    }
};