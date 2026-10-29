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
            $table->float('weight')->nullable()->after('gender');
            $table->float('height')->nullable()->after('weight');
            $table->float('imc')->nullable()->after('body_fat_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_assessments', function (Blueprint $table) {
            $table->dropColumn(['weight', 'height', 'imc']);
        });
    }
};
