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
        Schema::table('fee_heads', function (Blueprint $table) {
            $table->dropColumn('fee_type');
        });

        Schema::table('fee_heads', function (Blueprint $table) {
            $table->enum('fee_type', [
                'one_time',
                'monthly',
                'quarterly',
                'yearly',
                'semester',
                'annual',
                'exam',
                'transport',
                'hostel',
                'library',
                'laboratory',
                'other'
            ])->default('one_time')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_heads', function (Blueprint $table) {
            $table->dropColumn('fee_type');
        });

        Schema::table('fee_heads', function (Blueprint $table) {
            $table->enum('fee_type', ['one_time', 'monthly', 'semester', 'annual'])->default('one_time')->after('description');
        });
    }
};
