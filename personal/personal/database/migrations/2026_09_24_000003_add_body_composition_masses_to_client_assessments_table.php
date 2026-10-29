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
        Schema::table('client_assessments', function (Blueprint $table) {
            $table->float('fat_mass')->nullable()->after('body_fat_percentage');
            $table->float('lean_mass')->nullable()->after('fat_mass');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_assessments', function (Blueprint $table) {
            $table->dropColumn(['fat_mass', 'lean_mass']);
        });
    }
};
