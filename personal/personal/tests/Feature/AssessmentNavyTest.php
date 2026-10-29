<?php

namespace Tests\Feature;

use App\Models\ClientAssessment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentNavyTest extends TestCase
{
    use RefreshDatabase;

    public function test_female_navy_calculation_converts_centimeters_to_inches_before_saving(): void
    {
        $this->post(route('assessments.store'), $this->assessmentData([
            'name' => 'Avaliação feminina Navy',
            'gender' => 'female',
        ]))->assertRedirect(route('history'));

        $assessment = ClientAssessment::query()->firstOrFail();

        $this->assertEqualsWithDelta(40.69, $assessment->navy_body_fat_percentage, 0.01);
    }

    public function test_male_navy_calculation_uses_abdomen_minus_neck_and_converts_to_inches(): void
    {
        $this->post(route('assessments.store'), $this->assessmentData([
            'name' => 'Avaliação masculina Navy',
            'gender' => 'male',
        ]))->assertRedirect(route('history'));

        $assessment = ClientAssessment::query()->firstOrFail();

        $this->assertEqualsWithDelta(36.19, $assessment->navy_body_fat_percentage, 0.01);
    }

    public function test_infographic_shows_female_mifflin_st_jeor_basal_metabolic_rate(): void
    {
        $this->post(route('assessments.store'), $this->assessmentData([
            'name' => 'Avaliação feminina TMB',
            'gender' => 'female',
        ]));

        $assessment = ClientAssessment::query()->firstOrFail();

        $response = $this->get(route('assessments.infographic', $assessment))
            ->assertOk()
            ->assertSee('Gasto Basal (TMB)')
            ->assertSee('1.632')
            ->assertSee('kcal/dia')
            ->assertSee('Distribuição de Massa (kg)')
            ->assertSee('Classificação de Gordura Corporal')
            ->assertSee('Protocolo 7 Dobras · Adipometria')
            ->assertSee(number_format($assessment->body_fat_percentage, 2, ',', '.').'%', false)
            ->assertSee('Atletas')
            ->assertSee('Obesidade Grau I (Elevado)')
            ->assertSee('Obesidade Grau II')
            ->assertSee('Obesidade Grau III (Mórbida)')
            ->assertSee('classification-adipometry')
            ->assertSee('Valor Atual')
            ->assertSee('Referências gerais da ACE')
            ->assertSee('navy-classification')
            ->assertSee('Método U.S. Navy')
            ->assertSee(number_format($assessment->navy_body_fat_percentage, 2, ',', '.').'%', false)
            ->assertSee('Obesidade Grau II');

        $html = $response->getContent();
        $classificationStart = strpos($html, '<section class="classification classification-adipometry"');

        $this->assertNotFalse($classificationStart);

        $classificationMarkup = substr($html, $classificationStart);
        $classificationEnd = strpos($classificationMarkup, '</section>');

        $this->assertNotFalse($classificationEnd);

        $classificationMarkup = substr($classificationMarkup, 0, $classificationEnd);
        $this->assertStringContainsString(number_format($assessment->body_fat_percentage, 2, ',', '.').'%', $classificationMarkup);
        $this->assertStringNotContainsString(number_format($assessment->navy_body_fat_percentage, 2, ',', '.').'%', $classificationMarkup);
        $this->assertStringContainsString('class="range-fill range-essential"', $classificationMarkup);
        $this->assertStringContainsString('background-color:#397c70;-webkit-print-color-adjust:exact;print-color-adjust:exact', $classificationMarkup);
        $this->assertStringContainsString('<table role="presentation" style="width:100%;height:9px;', $classificationMarkup);
        $this->assertStringContainsString('width:33.333%;height:9px;padding:0;border:0;line-height:0;background-color:#20d69a;-webkit-print-color-adjust:exact;print-color-adjust:exact', $classificationMarkup);
        $this->assertStringContainsString('class="current-marker" style="display:block;position:absolute;top:-4px;left:26.10%;width:3px;height:17px;background-color:#ffffff;-webkit-print-color-adjust:exact;print-color-adjust:exact"', $classificationMarkup);

        $navyStart = strpos($html, '<section class="classification navy-classification"');
        $this->assertNotFalse($navyStart);

        $navyMarkup = substr($html, $navyStart);
        $navyEnd = strpos($navyMarkup, '</section>');
        $this->assertNotFalse($navyEnd);

        $navyMarkup = substr($navyMarkup, 0, $navyEnd);
        $this->assertGreaterThan($classificationStart, $navyStart);
        $this->assertStringContainsString(number_format($assessment->navy_body_fat_percentage, 2, ',', '.').'%', $navyMarkup);
        $this->assertStringContainsString('Obesidade Grau II', $navyMarkup);
        $this->assertStringNotContainsString('Valor Atual', $navyMarkup);
        $this->assertStringNotContainsString('range-list', $navyMarkup);
    }

    public function test_infographic_keeps_reference_bars_for_skinfold_classification_without_navy(): void
    {
        $this->post(route('assessments.store'), $this->assessmentData([
            'name' => 'Avaliação somente adipometria',
            'pescoco' => 0,
        ]));

        $assessment = ClientAssessment::query()->firstOrFail();

        $this->assertNull($assessment->navy_body_fat_percentage);
        $this->get(route('assessments.infographic', $assessment))
            ->assertOk()
            ->assertSee('Classificação de Gordura Corporal')
            ->assertSee('Protocolo 7 Dobras · Adipometria')
            ->assertSee('classification-adipometry')
            ->assertSee(number_format($assessment->body_fat_percentage, 2, ',', '.').'%', false)
            ->assertSee('Valor Atual')
            ->assertSee('Referências gerais da ACE')
            ->assertDontSee('<section class="classification navy-classification"', false);
    }

    public function test_infographic_shows_male_mifflin_st_jeor_basal_metabolic_rate(): void
    {
        $this->post(route('assessments.store'), $this->assessmentData([
            'name' => 'Avaliação masculina TMB',
            'gender' => 'male',
        ]));

        $assessment = ClientAssessment::query()->firstOrFail();

        $this->get(route('assessments.infographic', $assessment))
            ->assertOk()
            ->assertSee('1.798')
            ->assertSee('kcal/dia');
    }

    public function test_navy_obesity_grades_use_sex_specific_thresholds(): void
    {
        foreach ([
            ['female', 39.99, 'Obesidade Grau I (Elevado)'],
            ['female', 50, 'Obesidade Grau III (Mórbida)'],
            ['male', 29.99, 'Obesidade Grau I (Elevado)'],
            ['male', 40, 'Obesidade Grau III (Mórbida)'],
        ] as [$gender, $navyPercentage, $expectedCategory]) {
            $name = "Navy {$gender} {$navyPercentage}";
            $this->post(route('assessments.store'), $this->assessmentData([
                'name' => $name,
                'gender' => $gender,
            ]));

            $assessment = ClientAssessment::query()->where('name', $name)->firstOrFail();
            $assessment->navy_body_fat_percentage = $navyPercentage;
            $assessment->save();

            $html = $this->get(route('assessments.infographic', $assessment))
                ->assertOk()
                ->getContent();
            $navyStart = strpos($html, '<section class="classification navy-classification"');

            $this->assertNotFalse($navyStart);

            $navyMarkup = substr($html, $navyStart);
            $navyEnd = strpos($navyMarkup, '</section>');

            $this->assertNotFalse($navyEnd);
            $this->assertStringContainsString($expectedCategory, substr($navyMarkup, 0, $navyEnd));
        }
    }

    public function test_standard_report_shows_mass_distribution_tmb_and_body_fat_classification(): void
    {
        $this->post(route('assessments.store'), $this->assessmentData([
            'name' => 'Avaliação no relatório padrão',
        ]));

        $assessment = ClientAssessment::query()->firstOrFail();

        $this->get(route('assessments.report', $assessment))
            ->assertOk()
            ->assertSee('Distribuição de Massa (kg)')
            ->assertSee('Peso Total')
            ->assertSee('Massa Magra')
            ->assertSee('Massa Gorda')
            ->assertSee('Gasto Basal (TMB)')
            ->assertSee('1.632')
            ->assertSee('Classificação de Gordura Corporal')
            ->assertSee('Protocolo 7 Dobras · Adipometria')
            ->assertSee(number_format($assessment->body_fat_percentage, 2, ',', '.').'%', false)
            ->assertSee('Valor Atual')
            ->assertSee('Faixas gerais de referência da ACE')
            ->assertSee('40,69%');
    }

    public function test_standard_report_uses_skinfold_percentage_for_full_classification_without_navy(): void
    {
        $this->post(route('assessments.store'), $this->assessmentData([
            'name' => 'Avaliação de adipometria no relatório',
            'pescoco' => 0,
        ]));

        $assessment = ClientAssessment::query()->firstOrFail();

        $this->assertNull($assessment->navy_body_fat_percentage);
        $this->get(route('assessments.report', $assessment))
            ->assertOk()
            ->assertSee('Protocolo 7 Dobras · Adipometria')
            ->assertSee(number_format($assessment->body_fat_percentage, 2, ',', '.').'%', false)
            ->assertSee('Valor Atual')
            ->assertSee('Faixas gerais de referência da ACE');
    }

    public function test_history_and_infographic_use_metrics_from_the_selected_assessment(): void
    {
        $this->post(route('assessments.store'), $this->assessmentData([
            'name' => 'Cliente com histórico',
        ]));
        $this->post(route('assessments.store'), $this->assessmentData([
            'name' => 'Cliente com histórico',
            'age' => 45,
            'weight' => 70,
            'height' => 1.65,
            'cintura' => 75,
            'abdomen' => 80,
            'quadril' => 100,
            'pescoco' => 32,
        ]));

        $assessments = ClientAssessment::query()->orderBy('id')->get();
        $firstAssessment = $assessments->firstOrFail();
        $secondAssessment = $assessments->last();

        $this->assertEqualsWithDelta(40.69, $firstAssessment->navy_body_fat_percentage, 0.01);
        $this->assertEqualsWithDelta(30.24, $secondAssessment->navy_body_fat_percentage, 0.01);
        $this->assertEqualsWithDelta(1631.75, $firstAssessment->basal_metabolic_rate, 0.01);
        $this->assertEqualsWithDelta(1345.25, $secondAssessment->basal_metabolic_rate, 0.01);

        $this->get(route('assessments.infographic', $firstAssessment))
            ->assertSee('40,69%')
            ->assertSee('1.632');
        $this->get(route('assessments.infographic', $secondAssessment))
            ->assertSee('30,24%')
            ->assertSee('1.345');
        $this->get(route('history'))
            ->assertSee('40,69%')
            ->assertSee('30,24%')
            ->assertSee('1.632 kcal/dia')
            ->assertSee('1.345 kcal/dia');
    }

    public function test_navy_backfill_corrects_values_already_saved_for_historical_assessments(): void
    {
        $this->post(route('assessments.store'), $this->assessmentData([]));

        $assessment = ClientAssessment::query()->firstOrFail();
        ClientAssessment::query()->whereKey($assessment->id)->update([
            'navy_body_fat_percentage' => 74.43,
        ]);

        $migration = require database_path('migrations/2026_10_06_000000_recalculate_navy_percentages.php');
        $migration->up();

        $this->assertEqualsWithDelta(40.69, $assessment->fresh()->navy_body_fat_percentage, 0.01);
    }

    private function assessmentData(array $overrides): array
    {
        return array_merge([
            'name' => 'Avaliação Navy',
            'age' => 30,
            'gender' => 'female',
            'weight' => 82.4,
            'height' => 1.79,
            'protocol' => '7',
            'triceps' => 10,
            'chest' => 10,
            'midaxillary' => 10,
            'subscapular' => 10,
            'suprailiac' => 10,
            'abdominal' => 10,
            'thigh' => 10,
            'cintura' => 87,
            'abdomen' => 116,
            'quadril' => 123,
            'pescoco' => 36,
        ], $overrides);
    }
}
