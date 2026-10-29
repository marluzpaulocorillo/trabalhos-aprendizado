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
            $table->float('torax')->nullable()->after('imc');
            $table->float('cintura')->nullable()->after('torax');
            $table->float('abdomen')->nullable()->after('cintura');
            $table->float('braco_relaxado_d')->nullable()->after('abdomen');
            $table->float('braco_relaxado_e')->nullable()->after('braco_relaxado_d');
            $table->float('braco_contraido_d')->nullable()->after('braco_relaxado_e');
            $table->float('braco_contraido_e')->nullable()->after('braco_contraido_d');
            $table->float('antebraco_d')->nullable()->after('braco_contraido_e');
            $table->float('antebraco_e')->nullable()->after('antebraco_d');
            $table->float('coxa_proximal_d')->nullable()->after('antebraco_e');
            $table->float('coxa_proximal_e')->nullable()->after('coxa_proximal_d');
            $table->float('coxa_medial_d')->nullable()->after('coxa_proximal_e');
            $table->float('coxa_medial_e')->nullable()->after('coxa_medial_d');
            $table->float('panturrilha_d')->nullable()->after('coxa_medial_e');
            $table->float('panturrilha_e')->nullable()->after('panturrilha_d');
            $table->float('quadril')->nullable()->after('panturrilha_e');
            $table->float('pescoco')->nullable()->after('quadril');
            $table->float('waist_hip_ratio')->nullable()->after('pescoco');
            $table->float('navy_body_fat_percentage')->nullable()->after('waist_hip_ratio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_assessments', function (Blueprint $table) {
            $table->dropColumn([
                'torax',
                'cintura',
                'abdomen',
                'braco_relaxado_d',
                'braco_relaxado_e',
                'braco_contraido_d',
                'braco_contraido_e',
                'antebraco_d',
                'antebraco_e',
                'coxa_proximal_d',
                'coxa_proximal_e',
                'coxa_medial_d',
                'coxa_medial_e',
                'panturrilha_d',
                'panturrilha_e',
                'quadril',
                'pescoco',
                'waist_hip_ratio',
                'navy_body_fat_percentage',
            ]);
        });
    }
};
