<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    public function index(): View
    {
        return view('welcome');
    }

    public function create(): View
    {
        return view('assessments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $foldFields = [
            'triceps',
            'chest',
            'midaxillary',
            'subscapular',
            'suprailiac',
            'abdominal',
            'thigh',
        ];
        $perimetryFields = [
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
        ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:10', 'max:120'],
            'gender' => ['required', 'in:male,female'],
            'weight' => ['required', 'numeric', 'gt:0'],
            'height' => ['required', 'numeric', 'gt:0'],
            'protocol' => ['required', 'in:3,5,7'],
            ...array_fill_keys($foldFields, ['nullable', 'numeric', 'min:0']),
            ...array_fill_keys($perimetryFields, ['nullable', 'numeric', 'min:0']),
        ]);

        $protocol = $validated['protocol'];
        $gender = $validated['gender'];
        $requiredFolds = match ($protocol) {
            '3' => $gender === 'male'
                ? ['chest', 'abdominal', 'thigh']
                : ['triceps', 'suprailiac', 'thigh'],
            '5' => ['triceps', 'subscapular', 'suprailiac', 'abdominal', 'thigh'],
            '7' => $foldFields,
        };

        $request->validate(array_fill_keys($requiredFolds, ['required', 'numeric', 'min:0']));

        $data = array_merge(
            array_fill_keys($foldFields, null),
            array_fill_keys($perimetryFields, null),
            $validated,
        );

        foreach ($foldFields as $fold) {
            if (! in_array($fold, $requiredFolds, true)) {
                $data[$fold] = null;
            }
        }

        $S = collect($requiredFolds)->sum(fn (string $fold): float => (float) $data[$fold]);

        $D = match ($protocol) {
            '3' => $gender === 'male'
                ? 1.10938 - (0.0008267 * $S) + (0.0000016 * $S * $S) - (0.0002574 * $data['age'])
                : 1.0994921 - (0.0009929 * $S) + (0.0000023 * $S * $S) - (0.0001392 * $data['age']),
            '5', '7' => $gender === 'male'
                ? 1.112 - (0.00043499 * $S) + (0.00000055 * $S * $S) - (0.00028826 * $data['age'])
                : 1.097 - (0.00046971 * $S) + (0.00000056 * $S * $S) - (0.00012828 * $data['age']),
        };

        $bodyFatPercentage = ((4.95 / $D) - 4.50) * 100;

        $data['body_density'] = round($D, 4);
        $data['body_fat_percentage'] = round($bodyFatPercentage, 2);
        $data['imc'] = round($data['weight'] / ($data['height'] * $data['height']), 2);
        $data['fat_mass'] = round($data['weight'] * ($data['body_fat_percentage'] / 100), 2);
        $data['lean_mass'] = round($data['weight'] - $data['fat_mass'], 2);
        $data['waist_hip_ratio'] = null;
        $data['navy_body_fat_percentage'] = null;

        if ((float) $data['cintura'] > 0 && (float) $data['quadril'] > 0) {
            $data['waist_hip_ratio'] = round($data['cintura'] / $data['quadril'], 2);
        }

        $heightInCentimeters = (float) $data['height'] * 100;
        $heightInInches = $heightInCentimeters / 2.54;
        $hasNavyMeasurements = (float) $data['pescoco'] > 0
            && $heightInCentimeters > 0
            && ($gender === 'male'
                ? (float) $data['abdomen'] - (float) $data['pescoco'] > 0
                : (float) $data['cintura'] > 0
                    && (float) $data['quadril'] > 0
                    && (float) $data['cintura'] + (float) $data['quadril'] - (float) $data['pescoco'] > 0);

        if ($hasNavyMeasurements) {
            $navyBodyFatPercentage = $gender === 'male'
                ? 86.010 * log10(((float) $data['abdomen'] - (float) $data['pescoco']) / 2.54)
                    - 70.041 * log10($heightInInches) + 36.76
                : 163.205 * log10(((float) $data['cintura'] + (float) $data['quadril'] - (float) $data['pescoco']) / 2.54)
                    - 97.684 * log10($heightInInches) - 78.387;

            $data['navy_body_fat_percentage'] = round($navyBodyFatPercentage, 2);
        }

        $client = Client::firstOrCreate(['name' => $validated['name']]);
        $data['client_id'] = $client->id;

        ClientAssessment::create($data);

        return redirect()
            ->route('history')
            ->with('success', 'Avaliação salva com sucesso.');
    }

    public function history(): View
    {
        $assessments = ClientAssessment::latest()->get();

        return view('assessments.history', compact('assessments'));
    }

    public function evolution(Request $request): View
    {
        $clients = Client::query()->orderBy('name')->get();
        $selectedClientId = $request->integer('client_id') ?: $clients->first()?->id;
        $selectedClient = $clients->firstWhere('id', $selectedClientId);
        $assessments = $selectedClient
            ? $selectedClient->assessments()->oldest('created_at')->get()
            : collect();

        return view('assessments.evolution', compact('clients', 'selectedClient', 'assessments'));
    }

    public function report(ClientAssessment $assessment): View
    {
        return view('assessments.report', $this->reportData($assessment));
    }

    public function infographic(ClientAssessment $assessment): View
    {
        return view('assessments.infographic-pdf', $this->reportData($assessment));
    }

    private function reportData(ClientAssessment $assessment): array
    {
        $assessment->load('client');
        $previousAssessment = ClientAssessment::query()
            ->where('client_id', $assessment->client_id)
            ->where('created_at', '<', $assessment->created_at)
            ->latest('created_at')
            ->first();
        $comparison = null;
        $feedback = null;

        if ($previousAssessment) {
            $currentMasses = $this->compositionMetrics($assessment);
            $previousMasses = $this->compositionMetrics($previousAssessment);
            $comparison = [
                'weight' => round((float) $assessment->weight - (float) $previousAssessment->weight, 2),
                'fat_mass' => round($currentMasses['fat_mass'] - $previousMasses['fat_mass'], 2),
                'lean_mass' => round($currentMasses['lean_mass'] - $previousMasses['lean_mass'], 2),
                'waist' => $this->difference($assessment->cintura, $previousAssessment->cintura),
                'fold_sum' => $this->difference($this->foldSum($assessment), $this->foldSum($previousAssessment)),
                'date' => $previousAssessment->created_at->format('d/m/Y'),
            ];
            $feedback = $this->buildFeedback($comparison);
        }

        return compact('assessment', 'previousAssessment', 'comparison', 'feedback');
    }

    private function compositionMetrics(ClientAssessment $assessment): array
    {
        $fatMass = $assessment->fat_mass ?? ((float) $assessment->weight * ((float) $assessment->body_fat_percentage / 100));

        return [
            'fat_mass' => round($fatMass, 2),
            'lean_mass' => round($assessment->lean_mass ?? ((float) $assessment->weight - $fatMass), 2),
        ];
    }

    private function foldSum(ClientAssessment $assessment): ?float
    {
        $folds = collect([
            $assessment->triceps,
            $assessment->chest,
            $assessment->midaxillary,
            $assessment->subscapular,
            $assessment->suprailiac,
            $assessment->abdominal,
            $assessment->thigh,
        ])->filter(fn ($fold): bool => $fold !== null);

        return $folds->isEmpty() ? null : (float) $folds->sum();
    }

    private function difference(?float $current, ?float $previous): ?float
    {
        return $current !== null && $previous !== null
            ? round($current - $previous, 2)
            : null;
    }

    private function buildFeedback(array $comparison): string
    {
        if ($comparison['fat_mass'] < -0.05 && $comparison['lean_mass'] > 0.05) {
            return 'Parabéns! Você perdeu '.number_format(abs($comparison['fat_mass']), 1, ',', '.').' kg de gordura e ganhou '.number_format($comparison['lean_mass'], 1, ',', '.').' kg de massa magra desde sua última avaliação em '.$comparison['date'].'.';
        }

        if ($comparison['fat_mass'] < -0.05) {
            return 'Boa evolução: houve redução de '.number_format(abs($comparison['fat_mass']), 1, ',', '.').' kg de gordura desde sua última avaliação em '.$comparison['date'].'.';
        }

        if ($comparison['lean_mass'] > 0.05) {
            return 'A massa magra aumentou '.number_format($comparison['lean_mass'], 1, ',', '.').' kg desde sua última avaliação em '.$comparison['date'].'. Continue consistente com o plano.';
        }

        return 'A avaliação atual foi comparada com a leitura de '.$comparison['date'].'. Use esta tendência para ajustar o próximo ciclo de treinamento e alimentação.';
    }

    public function destroy(ClientAssessment $assessment): RedirectResponse
    {
        $assessment->delete();

        return redirect()
            ->route('history')
            ->with('success', 'Avaliação excluída com sucesso.');
    }
}
