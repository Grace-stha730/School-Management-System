<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if syllabi table exists and syllabus doesn't, then rename
        if (Schema::hasTable('syllabi') && !Schema::hasTable('syllabus')) {
            Schema::rename('syllabi', 'syllabus');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the rename operation
        if (Schema::hasTable('syllabus') && !Schema::hasTable('syllabi')) {
            Schema::rename('syllabus', 'syllabi');
        }
    }
};
