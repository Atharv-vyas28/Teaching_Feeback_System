<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('semesters', 'program_id')) {
            Schema::table('semesters', function (Blueprint $table) {
                $table->foreignId('program_id')
                    ->nullable()
                    ->after('academic_year_id')
                    ->constrained('programs')
                    ->nullOnDelete()
                    ->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('semesters', 'program_id')) {
            Schema::table('semesters', function (Blueprint $table) {
                $table->dropConstrainedForeignId('program_id');
            });
        }
    }
};