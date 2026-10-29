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
            $table->string('protocol', 1)->default('7')->after('gender');
        });

        Schema::table('client_assessments', function (Blueprint $table) {
            $table->float('triceps')->nullable()->change();
            $table->float('chest')->nullable()->change();
            $table->float('midaxillary')->nullable()->change();
            $table->float('subscapular')->nullable()->change();
            $table->float('suprailiac')->nullable()->change();
            $table->float('abdominal')->nullable()->change();
            $table->float('thigh')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_assessments', function (Blueprint $table) {
            $table->dropColumn('protocol');
        });

        Schema::table('client_assessments', function (Blueprint $table) {
            $table->float('triceps')->nullable(false)->change();
            $table->float('chest')->nullable(false)->change();
            $table->float('midaxillary')->nullable(false)->change();
            $table->float('subscapular')->nullable(false)->change();
            $table->float('suprailiac')->nullable(false)->change();
            $table->float('abdominal')->nullable(false)->change();
            $table->float('thigh')->nullable(false)->change();
        });
    }
};
