<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teach', function (Blueprint $table) {

            $table->foreignId('faculty_id')
                ->constrained('faculties')
                ->cascadeOnDelete();

            $table->foreignId('course_id')
                ->constrained('courses', 'course_id')
                ->cascadeOnDelete();

            $table->primary([
                'faculty_id',
                'course_id'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teach');
    }
};