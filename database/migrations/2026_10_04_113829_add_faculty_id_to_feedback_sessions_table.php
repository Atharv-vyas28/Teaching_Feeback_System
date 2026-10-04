<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('feedback_sessions', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['class_section_id']);

            // Now drop the unique index safely
            $table->dropUnique('feedback_sessions_class_section_id_unique');

            // Add new faculty_id column
            $table->foreignId('faculty_id')
                ->after('class_section_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Add composite unique constraint
            $table->unique(
                ['class_section_id', 'faculty_id'],
                'feedback_session_section_faculty_unique'
            );

            $table->index('faculty_id')->nullable();
        });

    }

    public function down(): void
    {
        Schema::table('feedback_sessions', function (Blueprint $table) {
            $table->dropUnique('feedback_session_section_faculty_unique');
            $table->dropIndex(['faculty_id']);
            $table->dropForeign(['faculty_id']);
            $table->dropColumn('faculty_id');

            // Restore original foreign key + unique
            $table->unique('class_section_id', 'feedback_sessions_class_section_id_unique');
            $table->foreign('class_section_id')->references('id')->on('class_sections')->cascadeOnDelete();
        });

    }
};