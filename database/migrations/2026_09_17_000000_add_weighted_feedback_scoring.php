<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Keep the existing attendance unique constraint:
         *
         * UNIQUE(class_session_id, student_id)
         *
         * There must be only one attendance record for a student
         * in a particular class session.
         *
         * Feedback must update the existing attendance record rather
         * than create a second attendance record.
         */

        Schema::table('feedback_eligibility', function (Blueprint $table) {
            if (!Schema::hasColumn('feedback_eligibility', 'anonymous_token')) {
                $table->string('anonymous_token', 100)
                    ->nullable()
                    ->after('student_id')
                    ->index();
            }

            if (!Schema::hasColumn('feedback_eligibility', 'included_in_score')) {
                $table->boolean('included_in_score')
                    ->default(false)
                    ->after('has_submitted');
            }

            if (!Schema::hasColumn('feedback_eligibility', 'attendance_weight')) {
                $table->decimal('attendance_weight', 8, 6)
                    ->nullable()
                    ->after('included_in_score');
            }
        });

        Schema::table('rating_results', function (Blueprint $table) {
            if (!Schema::hasColumn('rating_results', 'feedback_session_id')) {
                $table->foreignId('feedback_session_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('feedback_sessions')
                    ->cascadeOnDelete()
                    ->unique();
            }
        });
    }

    public function down(): void
    {
        Schema::table('rating_results', function (Blueprint $table) {
            if (Schema::hasColumn('rating_results', 'feedback_session_id')) {
                $table->dropForeign(['feedback_session_id']);
                $table->dropUnique(['feedback_session_id']);
                $table->dropColumn('feedback_session_id');
            }
        });

        Schema::table('feedback_eligibility', function (Blueprint $table) {
            if (Schema::hasColumn('feedback_eligibility', 'anonymous_token')) {
                $table->dropIndex(['anonymous_token']);
                $table->dropColumn('anonymous_token');
            }

            if (Schema::hasColumn('feedback_eligibility', 'included_in_score')) {
                $table->dropColumn('included_in_score');
            }

            if (Schema::hasColumn('feedback_eligibility', 'attendance_weight')) {
                $table->dropColumn('attendance_weight');
            }
        });

        /*
         * DO NOT change the attendance unique constraint.
         *
         * The existing:
         * UNIQUE(class_session_id, student_id)
         *
         * remains intact.
         */
    }
};