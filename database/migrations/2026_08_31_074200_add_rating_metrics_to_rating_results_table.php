<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('rating_results', 'course_rating')) {
            Schema::table('rating_results', function (Blueprint $table) {
                $table->decimal('course_rating', 5, 2)
                    ->nullable()
                    ->after('overall_weighted_rating');
            });
        }

        if (! Schema::hasColumn('rating_results', 'eligible_count')) {
            Schema::table('rating_results', function (Blueprint $table) {
                $table->integer('eligible_count')
                    ->default(0)
                    ->after('response_count');
            });
        }

        if (! Schema::hasColumn('rating_results', 'response_rate')) {
            Schema::table('rating_results', function (Blueprint $table) {
                $table->decimal('response_rate', 5, 2)
                    ->default(0)
                    ->after('eligible_count');
            });
        }
    }

    public function down(): void
    {
        Schema::table('rating_results', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('rating_results', 'course_rating')) {
                $columns[] = 'course_rating';
            }

            if (Schema::hasColumn('rating_results', 'eligible_count')) {
                $columns[] = 'eligible_count';
            }

            if (Schema::hasColumn('rating_results', 'response_rate')) {
                $columns[] = 'response_rate';
            }

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};