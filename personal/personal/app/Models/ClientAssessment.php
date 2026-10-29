<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientAssessment extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'age',
        'gender',
        'weight',
        'height',
        'protocol',
        'triceps',
        'chest',
        'midaxillary',
        'subscapular',
        'abdominal',
        'suprailiac',
        'thigh',
        'body_density',
        'body_fat_percentage',
        'fat_mass',
        'lean_mass',
        'imc',
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
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'gender' => 'string',
            'weight' => 'float',
            'height' => 'float',
            'protocol' => 'string',
            'triceps' => 'float',
            'chest' => 'float',
            'midaxillary' => 'float',
            'subscapular' => 'float',
            'suprailiac' => 'float',
            'abdominal' => 'float',
            'thigh' => 'float',
            'body_density' => 'float',
            'body_fat_percentage' => 'float',
            'fat_mass' => 'float',
            'lean_mass' => 'float',
            'imc' => 'float',
            'torax' => 'float',
            'cintura' => 'float',
            'abdomen' => 'float',
            'braco_relaxado_d' => 'float',
            'braco_relaxado_e' => 'float',
            'braco_contraido_d' => 'float',
            'braco_contraido_e' => 'float',
            'antebraco_d' => 'float',
            'antebraco_e' => 'float',
            'coxa_proximal_d' => 'float',
            'coxa_proximal_e' => 'float',
            'coxa_medial_d' => 'float',
            'coxa_medial_e' => 'float',
            'panturrilha_d' => 'float',
            'panturrilha_e' => 'float',
            'quadril' => 'float',
            'pescoco' => 'float',
            'waist_hip_ratio' => 'float',
            'navy_body_fat_percentage' => 'float',
        ];
    }

    protected function basalMetabolicRate(): Attribute
    {
        return Attribute::get(function (): ?float {
            if (
                $this->weight === null
                || $this->height === null
                || $this->age === null
                || ! in_array($this->gender, ['male', 'female'], true)
            ) {
                return null;
            }

            $heightInCentimeters = (float) $this->height * 100;
            $genderAdjustment = $this->gender === 'male' ? 5 : -161;

            return (10 * (float) $this->weight)
                + (6.25 * $heightInCentimeters)
                - (5 * (int) $this->age)
                + $genderAdjustment;
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
