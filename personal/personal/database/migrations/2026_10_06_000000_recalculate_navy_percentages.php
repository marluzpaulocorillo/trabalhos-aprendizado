<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('client_assessments')
            ->select(['id', 'gender', 'height', 'abdomen', 'cintura', 'quadril', 'pescoco'])
            ->orderBy('id')
            ->chunkById(500, function ($assessments): void {
                foreach ($assessments as $assessment) {
                    $heightInInches = (float) $assessment->height * 100 / 2.54;
                    $navyPercentage = null;

                    if ($heightInInches > 0 && (float) $assessment->pescoco > 0) {
                        if (
                            $assessment->gender === 'male'
                            && (float) $assessment->abdomen > (float) $assessment->pescoco
                        ) {
                            $navyPercentage = 86.010 * log10(((float) $assessment->abdomen - (float) $assessment->pescoco) / 2.54)
                                - 70.041 * log10($heightInInches) + 36.76;
                        } elseif (
                            $assessment->gender === 'female'
                            && (float) $assessment->cintura > 0
                            && (float) $assessment->quadril > 0
                            && (float) $assessment->cintura + (float) $assessment->quadril > (float) $assessment->pescoco
                        ) {
                            $navyPercentage = 163.205 * log10(((float) $assessment->cintura + (float) $assessment->quadril - (float) $assessment->pescoco) / 2.54)
                                - 97.684 * log10($heightInInches) - 78.387;
                        }
                    }

                    DB::table('client_assessments')
                        ->where('id', $assessment->id)
                        ->update([
                            'navy_body_fat_percentage' => $navyPercentage !== null ? round($navyPercentage, 2) : null,
                        ]);
                }
            });
    }

    public function down(): void {}
};
