<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSyllabiTableToSyllabus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('syllabi') && !Schema::hasTable('syllabus')) {
            Schema::rename('syllabi', 'syllabus');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('syllabus') && !Schema::hasTable('syllabi')) {
            Schema::rename('syllabus', 'syllabi');
        }
    }
}