<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('staff_courses', function (Blueprint $table) {
            $table->foreignId('feedback_session_id')->nullable()->after('class_section_id')->constrained()->nullOnDelete();
            $table->foreignId('assigned_by')->nullable()->after('feedback_session_id')->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('active')->after('is_active');
            $table->timestamp('assigned_at')->nullable()->after('status');
            $table->timestamp('deactivated_at')->nullable()->after('assigned_at');
            $table->index(['user_id', 'class_section_id', 'is_active'], 'staff_course_access_index');
            $table->index(['feedback_session_id', 'is_active'], 'staff_feedback_access_index');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('designation', 100)->nullable()->after('employee_id');
        });
        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('staff_courses', function (Blueprint $table) {
            $table->dropIndex('staff_course_access_index');
            $table->dropIndex('staff_feedback_access_index');
            $table->dropConstrainedForeignId('feedback_session_id');
            $table->dropConstrainedForeignId('assigned_by');
            $table->dropColumn(['status', 'assigned_at', 'deactivated_at']);
        });
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('designation'));
    }
};
