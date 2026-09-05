<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            if (! Schema::hasColumn('attendance', 'source')) {
                $table->string('source', 20)->default('regular')->after('status');
                $table->index('source');
            }
        });

        Schema::table('feedback_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('feedback_sessions', 'assigned_staff_id')) {
                $table->foreignId('assigned_staff_id')->nullable()->after('created_by')
                    ->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('feedback_sessions', 'release_at')) {
                $table->timestamp('release_at')->nullable()->after('opened_at');
            }
            if (! Schema::hasColumn('feedback_sessions', 'deadline_at')) {
                $table->timestamp('deadline_at')->nullable()->after('release_at');
                $table->index('deadline_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('feedback_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_staff_id');
            $table->dropIndex(['deadline_at']);
            $table->dropColumn(['release_at', 'deadline_at']);
        });

        Schema::table('attendance', function (Blueprint $table) {
            $table->dropIndex(['source']);
            $table->dropColumn('source');
        });
    }
};
