<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {

            $table->string('roll_no', 20);

            $table->string('name', 100);

            $table->string('branch', 50);

            $table->unsignedTinyInteger('year');

            $table->primary('roll_no');

            $table->string('email_id',30);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};