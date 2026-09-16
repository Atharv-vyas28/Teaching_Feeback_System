<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── Drop old tables if they exist (from incomplete prior migrations) ──
        Schema::dropIfExists('feedback_responses');
        Schema::dropIfExists('feedback_claims');
        Schema::dropIfExists('feedback_forms');
        Schema::dropIfExists('course_student');

        // ── Departments ────────────────────────────────────────────────────────
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Programs ───────────────────────────────────────────────────────────
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code', 20)->unique();
            $table->integer('duration_years')->default(4);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Update users table with missing columns ────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'program_id')) {
                $table->foreignId('program_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'roll_number')) {
                $table->string('roll_number', 50)->nullable()->unique();
            }
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id', 50)->nullable()->unique();
            }
            if (!Schema::hasColumn('users', 'current_semester')) {
                $table->integer('current_semester')->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable();
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
        });

        // ── Academic Years ─────────────────────────────────────────────────────
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20); // e.g. "2024-25"
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        // ── Semesters ─────────────────────────────────────────────────────────
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name', 50); // e.g. "Semester 5 B.Tech"
            $table->integer('number')->nullable(); // 1-8
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        // ── Courses ────────────────────────────────────────────────────────────
        // Drop and recreate with correct schema
        Schema::dropIfExists('courses');
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->integer('credits')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Class Sections ─────────────────────────────────────────────────────
        Schema::create('class_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('semester_id')->nullable()->constrained()->onDelete('set null');
            $table->string('section_name', 50)->default('A');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Faculty Courses (assignment) ───────────────────────────────────────
        Schema::create('faculty_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // faculty
            $table->foreignId('class_section_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Staff Courses (assignment) ─────────────────────────────────────────
        Schema::create('staff_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // staff
            $table->foreignId('class_section_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Course Enrollments (students) ──────────────────────────────────────
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // student
            $table->foreignId('class_section_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'dropped', 'completed'])->default('active');
            $table->timestamps();
            $table->unique(['user_id', 'class_section_id']);
        });

        // ── Class Sessions ─────────────────────────────────────────────────────
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_section_id')->constrained()->onDelete('cascade');
            $table->foreignId('conducted_by')->constrained('users')->onDelete('cascade');
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('topic')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });

        // ── Attendance ─────────────────────────────────────────────────────────
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('marked_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('absent');
            $table->boolean('feedback_enabled')->default(false);
            $table->enum('source', ['regular', 'feedback_day'])->default('regular');
            $table->timestamp('marked_at')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->unique(['class_session_id', 'student_id']);
        });

        // ── Feedback Questions ─────────────────────────────────────────────────
        Schema::create('feedback_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->enum('type', ['rating', 'text', 'boolean'])->default('rating');
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->integer('display_order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Feedback Sessions ──────────────────────────────────────────────────
        Schema::create('feedback_sessions', function (Blueprint $table) {
            $table->id();

            // One feedback session per course section
            $table->foreignId('class_section_id')
                ->unique()
                ->constrained()
                ->onDelete('cascade');

            // The special class session on the feedback day
            $table->foreignId('class_session_id')
                ->nullable()
                ->unique()
                ->constrained()
                ->onDelete('set null');

            $table->foreignId('created_by')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('assigned_staff_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->enum(
                'status',
                ['draft', 'active', 'closed']
            )->default('draft');

            $table->timestamp('release_at')->nullable();

            $table->timestamp('deadline_at')->nullable();

            $table->timestamp('opened_at')->nullable();

            $table->timestamp('closed_at')->nullable();

            $table->boolean('is_released')->default(false);

            $table->timestamps();
        });

        // ── Feedback Eligibility ───────────────────────────────────────────────
        Schema::create('feedback_eligibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->boolean('has_submitted')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->unique(['feedback_session_id', 'student_id']);
            $table->uuid('anonymous_token')->nullable()->unique();
            $table->boolean('included_in_score')->default(false);
            $table->decimal('attendance_weight', 5, 4)->nullable();
        });

        // ── Feedback Responses (anonymous) ─────────────────────────────────────
        Schema::create('feedback_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_session_id')->constrained()->onDelete('cascade');
            $table->string('anonymous_token', 100)->unique();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        // ── Feedback Answers ───────────────────────────────────────────────────
        Schema::create('feedback_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_response_id')->constrained()->onDelete('cascade');
            $table->foreignId('feedback_question_id')->constrained()->onDelete('cascade');
            $table->decimal('rating_value', 4, 2)->nullable();
            $table->text('text_answer')->nullable();
            $table->timestamps();
        });

        // ── Rating Results ─────────────────────────────────────────────────────
        Schema::create('rating_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('feedback_session_id')
                ->unique()
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('faculty_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('class_section_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('semester_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            $table->decimal(
                'overall_weighted_rating',
                5,
                2
            )->nullable();

            $table->integer('response_count')->default(0);

            $table->json('question_averages')->nullable();

            $table->timestamp('calculated_at')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'faculty_id',
                    'class_section_id',
                    'semester_id',
                ],
                'rating_results_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating_results');
        Schema::dropIfExists('feedback_answers');
        Schema::dropIfExists('feedback_responses');
        Schema::dropIfExists('feedback_eligibility');
        Schema::dropIfExists('feedback_sessions');
        Schema::dropIfExists('feedback_questions');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('class_sessions');
        Schema::dropIfExists('course_enrollments');
        Schema::dropIfExists('staff_courses');
        Schema::dropIfExists('faculty_courses');
        Schema::dropIfExists('class_sections');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('departments');
    }
};
