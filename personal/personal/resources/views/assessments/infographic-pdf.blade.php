@php
    $folds = collect([
        'Tríceps' => $assessment->triceps,
        'Subescapular' => $assessment->subscapular,
        'Supra-ilíaca' => $assessment->suprailiac,
        'Abdominal' => $assessment->abdominal,
        'Coxa' => $assessment->thigh,
        'Tórax' => $assessment->chest,
        'Axilar média' => $assessment->midaxillary,
    ])->filter(fn ($value): bool => $value !== null);
    $foldSum = $folds->sum();
    $fatMass = $assessment->fat_mass ?? ($assessment->weight !== null && $assessment->body_fat_percentage !== null
        ? $assessment->weight * ($assessment->body_fat_percentage / 100)
        : null);
    $leanMass = $assessment->lean_mass ?? ($assessment->weight !== null && $fatMass !== null
        ? $assessment->weight - $fatMass
        : null);
    $fatPercentage = $assessment->body_fat_percentage;
    $adipometryPercentage = $assessment->body_fat_percentage;
    $leanPercentage = $fatPercentage !== null ? max(0, 100 - $fatPercentage) : null;
    $massScale = 100;
    $navyPercentage = $assessment->navy_body_fat_percentage;
    $hasNavyClassification = $navyPercentage !== null;
    $isMale = $assessment->gender === 'male';
    $classificationCategoryFor = function (?float $percentage) use ($isMale): string {
        return match (true) {
            $percentage === null => 'Sem classificação',
            $percentage < ($isMale ? 2 : 10) => 'Abaixo do essencial',
            $percentage < ($isMale ? 6 : 14) => 'Essencial',
            $percentage < ($isMale ? 14 : 21) => 'Atletas',
            $percentage < ($isMale ? 18 : 25) => 'Fitness / saudável',
            $percentage < ($isMale ? 25 : 32) => 'Aceitável / moderado',
            $percentage < ($isMale ? 30 : 40) => 'Obesidade Grau I (Elevado)',
            $percentage < ($isMale ? 40 : 50) => 'Obesidade Grau II',
            default => 'Obesidade Grau III (Mórbida)',
        };
    };
    $classificationPercentage = $adipometryPercentage;
    $classificationCategory = $classificationCategoryFor($adipometryPercentage);
    $navyClassificationCategory = $classificationCategoryFor($navyPercentage);
    $classificationMethod = 'Protocolo '.($assessment->protocol ?? '—').' Dobras · Adipometria';
    $classificationScale = $isMale ? 50 : 60;
    $classificationRanges = $isMale
        ? [
            ['Essencial', '2–5%', 2, 6, 'range-essential', '#397c70'],
            ['Atletas', '6–13%', 6, 14, 'range-athletes', '#3a9f7b'],
            ['Fitness / saudável', '14–17%', 14, 18, 'range-fitness', '#20d69a'],
            ['Aceitável / moderado', '18–24%', 18, 25, 'range-moderate', '#d4b16b'],
            ['Obesidade Grau I (Elevado)', '25–29%', 25, 30, 'range-obesity-one', '#dda34f'],
            ['Obesidade Grau II', '30–39%', 30, 40, 'range-obesity-two', '#d97847'],
            ['Obesidade Grau III (Mórbida)', '≥ 40%', 40, 50, 'range-obesity-three', '#c34f57'],
        ]
        : [
            ['Essencial', '10–13%', 10, 14, 'range-essential', '#397c70'],
            ['Atletas', '14–20%', 14, 21, 'range-athletes', '#3a9f7b'],
            ['Fitness / saudável', '21–24%', 21, 25, 'range-fitness', '#20d69a'],
            ['Aceitável / moderado', '25–31%', 25, 32, 'range-moderate', '#d4b16b'],
            ['Obesidade Grau I (Elevado)', '32–39%', 32, 40, 'range-obesity-one', '#dda34f'],
            ['Obesidade Grau II', '40–49%', 40, 50, 'range-obesity-two', '#d97847'],
            ['Obesidade Grau III (Mórbida)', '≥ 50%', 50, 60, 'range-obesity-three', '#c34f57'],
        ];
    $classificationCurrentWidth = $classificationPercentage !== null
        ? min(100, max(0, ((float) $classificationPercentage / $classificationScale) * 100))
        : 0;
    $cssNumber = fn (float $value): string => number_format($value, 2, '.', '');
    $bmi = $assessment->imc;
    $bmiStatus = match (true) {
        $bmi === null => 'Sem classificação',
        $bmi < 18.5 => 'Abaixo do peso',
        $bmi < 25 => 'Faixa adequada',
        $bmi < 30 => 'Sobrepeso',
        default => 'Obesidade',
    };
    $bmiPosition = $bmi !== null ? min(100, max(0, (($bmi - 15) / 25) * 100)) : 0;
    $measurements = [
        ['Braço relaxado', $assessment->braco_relaxado_d, $assessment->braco_relaxado_e],
        ['Braço contraído', $assessment->braco_contraido_d, $assessment->braco_contraido_e],
        ['Antebraço', $assessment->antebraco_d, $assessment->antebraco_e],
        ['Coxa proximal', $assessment->coxa_proximal_d, $assessment->coxa_proximal_e],
        ['Coxa medial', $assessment->coxa_medial_d, $assessment->coxa_medial_e],
        ['Panturrilha', $assessment->panturrilha_d, $assessment->panturrilha_e],
    ];
    $asymmetries = collect($measurements)->filter(fn (array $measurement): bool =>
        $measurement[1] !== null && $measurement[2] !== null && abs($measurement[1] - $measurement[2]) > 1
    );
    $format = fn ($value, int $precision = 1): string => $value !== null
        ? number_format((float) $value, $precision, ',', '.')
        : '—';
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>Infográfico de avaliação | {{ $assessment->client?->name ?? $assessment->name }}</title>
    <style>
        :root {
            color-scheme: dark;
            --night: #071018;
            --panel: #0c1820;
            --panel-raised: #101f28;
            --line: #263842;
            --muted: #91a2a8;
            --white: #edf3ef;
            --gold: #d4b16b;
            --gold-soft: #8e784e;
            --green: #20d69a;
            --red: #ed8b78;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: #03090e; color: var(--white); font-family: 'Arial', sans-serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .toolbar { display: flex; justify-content: center; gap: 12px; padding: 18px; }
        .toolbar a, .toolbar button { border: 1px solid #43535a; border-radius: 4px; padding: 10px 16px; color: var(--white); background: #101a20; font: 700 12px Arial, sans-serif; text-decoration: none; cursor: pointer; }
        .toolbar button { border-color: var(--gold); color: var(--gold); }
        .sheet { position: relative; width: min(1240px, calc(100% - 32px)); margin: 0 auto 28px; padding: 28px; overflow: hidden; border: 1px solid #35434a; background: var(--night); box-shadow: 0 24px 80px #0009; }
        .sheet::before { position: absolute; inset: 7px; border: 1px solid #6f5d3d; content: ''; pointer-events: none; }
        .header { display: flex; align-items: center; justify-content: space-between; gap: 24px; padding: 0 8px 20px; border-bottom: 1px solid var(--gold-soft); }
        .brand { display: flex; align-items: center; gap: 13px; }
        .brand-mark { display: grid; width: 46px; height: 46px; place-items: center; border: 1px solid var(--gold); color: var(--gold); font: 700 16px Georgia, serif; letter-spacing: 1px; }
        .brand-name { color: var(--gold); font: 700 23px Georgia, serif; letter-spacing: 2px; }
        .brand-subtitle { margin-top: 5px; color: #bdc7c6; font-size: 9px; font-weight: 700; letter-spacing: 2px; }
        .report-meta { color: var(--muted); font-size: 11px; line-height: 1.7; text-align: right; }
        .report-meta strong { color: var(--gold); font-size: 12px; }
        .grid { display: grid; grid-template-columns: .88fr 1.27fr 1.25fr; gap: 12px; margin-top: 14px; }
        .column { display: flex; min-width: 0; flex-direction: column; gap: 10px; }
        .block { border: 1px solid var(--line); background: var(--panel); padding: 13px; }
        .block-title { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin: 0 0 12px; color: var(--gold); font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; }
        .block-title small { color: var(--muted); font-size: 9px; font-weight: 500; letter-spacing: .5px; text-transform: none; }
        .client-name { margin: 0 0 12px; overflow-wrap: anywhere; color: var(--white); font: 700 18px Georgia, serif; }
        .profile-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 7px; }
        .stat { min-height: 62px; border: 1px solid #30434a; border-left: 2px solid var(--green); background: var(--panel-raised); padding: 9px; }
        .basal-stat { grid-column: 1 / -1; border-left-color: var(--gold); background: #111f25cc; }
        .basal-content { display: flex; align-items: center; gap: 9px; }
        .basal-seal { display: grid; width: 32px; height: 32px; flex: 0 0 32px; place-items: center; border: 1px solid var(--gold-soft); border-radius: 50%; color: var(--gold); font-size: 8px; font-weight: 700; }
        .basal-stat .stat-label { letter-spacing: .4px; text-transform: none; }
        .basal-stat .stat-value { margin-top: 3px; color: var(--green); }
        .stat-label { color: var(--muted); font-size: 8px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
        .stat-value { margin-top: 6px; color: var(--white); font: 700 17px Georgia, serif; }
        .stat-value em { color: var(--muted); font: 400 10px Arial, sans-serif; }
        .profile-note { margin-top: 9px; color: var(--muted); font-size: 10px; }
        .fold-list { display: grid; gap: 9px; }
        .fold-row { display: grid; grid-template-columns: 89px 1fr 42px; align-items: center; gap: 8px; font-size: 10px; }
        .fold-label { color: #c6d0ce; }
        .bar { height: 6px; overflow: hidden; background: #26353a; }
        .bar-fill { height: 100%; background: var(--green); }
        .fold-value { color: var(--white); font-variant-numeric: tabular-nums; text-align: right; }
        .fold-total { display: flex; align-items: baseline; justify-content: space-between; margin-top: 13px; padding-top: 10px; border-top: 1px solid var(--line); }
        .fold-total span { color: var(--muted); font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
        .fold-total strong { color: var(--green); font: 700 22px Georgia, serif; }
        .fold-total small { font: 400 10px Arial, sans-serif; }
        .composition { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .composition-card { min-height: 81px; border: 1px solid #33434a; background: var(--panel-raised); padding: 10px; }
        .composition-card.fat { border-top: 2px solid var(--gold); }
        .composition-card.lean { border-top: 2px solid var(--green); }
        .composition-label { color: var(--muted); font-size: 8px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
        .composition-value { margin-top: 6px; color: var(--white); font: 700 22px Georgia, serif; }
        .composition-value span { color: var(--muted); font: 400 10px Arial, sans-serif; }
        .composition-percent { margin-top: 2px; color: var(--gold); font-size: 10px; }
        .mass-distribution { display: grid; gap: 7px; margin-top: 11px; }
        .mass-title { margin: 0 0 1px; color: var(--white); font-size: 9px; font-weight: 700; }
        .mass-row { display: grid; gap: 4px; }
        .mass-meta { display: flex; align-items: baseline; justify-content: space-between; gap: 8px; color: var(--muted); font-size: 8px; }
        .mass-meta strong { color: var(--white); font-size: 9px; font-variant-numeric: tabular-nums; }
        .mass-track { height: 6px; overflow: hidden; background: #25353b; }
        .mass-fill { height: 100%; }
        .mass-fill.total { background: #8ea3aa; }
        .mass-fill.lean { background: var(--green); }
        .mass-fill.fat { background: var(--gold); }
        .classification { margin-top: 12px; padding-top: 10px; border-top: 1px solid var(--line); }
        .classification-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 8px; }
        .classification-title { margin: 0; color: var(--white); font-size: 9px; font-weight: 700; }
        .classification-method { margin-top: 3px; color: var(--muted); font-size: 8px; }
        .classification-result { flex: 0 0 auto; text-align: right; }
        .classification-result strong { display: block; color: var(--gold); font: 700 15px Georgia, serif; }
        .classification-result span { display: inline-block; margin-top: 3px; border: 1px solid #69594a; padding: 2px 5px; color: #e3c98f; font-size: 7px; }
        .range-list { display: grid; gap: 5px; }
        .range-row { display: grid; grid-template-columns: 132px minmax(0, 1fr) 40px; align-items: center; gap: 6px; color: var(--muted); font-size: 7px; }
        .range-track { display: block; width: 100%; height: 7px; overflow: hidden; background-color: #202f35; }
        .range-fill { display: block; width: 100%; height: 100%; }
        .range-essential { background: #397c70; }
        .range-athletes { background: #3a9f7b; }
        .range-fitness { background: #20d69a; }
        .range-moderate { background: #d4b16b; }
        .range-obesity-one { background: #dda34f; }
        .range-obesity-two { background: #d97847; }
        .range-obesity-three { background: #c34f57; }
        .range-value { text-align: right; white-space: nowrap; }
        .current-range { margin-top: 7px; color: var(--white); font-size: 8px; font-weight: 700; }
        .current-track { position: relative; width: 100%; height: 9px; margin-top: 4px; overflow: visible; }
        .current-track table { width: 100%; height: 9px; table-layout: fixed; border-collapse: collapse; }
        .current-track td { height: 9px; padding: 0; border: 0; line-height: 0; }
        .current-marker { position: absolute; top: -4px; width: 3px; height: 17px; background-color: #fff; }
        .classification-note { margin: 6px 0 0; color: var(--muted); font-size: 7px; line-height: 1.35; }
        .health-top { display: grid; grid-template-columns: 1fr 1.3fr; gap: 10px; }
        .bmi-value { color: var(--white); font: 700 24px Georgia, serif; }
        .bmi-status { margin-top: 3px; color: var(--green); font-size: 9px; }
        .bmi-bar { position: relative; height: 8px; margin-top: 13px; background: linear-gradient(90deg, #4c91a1 0 14%, #20a77c 14% 40%, #d5b25e 40% 60%, #d57d55 60% 100%); }
        .bmi-marker { position: absolute; top: -4px; left: var(--position); width: 2px; height: 16px; background: #fff; box-shadow: 0 0 0 1px #071018; }
        .bmi-legend { display: flex; justify-content: space-between; margin-top: 5px; color: var(--muted); font-size: 7px; }
        .navy-classification { margin-top: 9px; padding: 10px; border: 1px solid var(--line); background: var(--panel-raised); }
        .navy-classification .classification-heading { align-items: center; margin-bottom: 0; }
        .rings { display: flex; align-items: center; justify-content: space-around; gap: 8px; }
        .ring { display: grid; width: 62px; height: 62px; place-items: center; border-radius: 50%; background: conic-gradient(var(--ring-color) calc(var(--ring-value) * 1%), #25353b 0); }
        .ring-inner { display: grid; width: 50px; height: 50px; place-content: center; border-radius: 50%; background: var(--panel); text-align: center; }
        .ring-inner strong { font: 700 13px Georgia, serif; }
        .ring-inner span { margin-top: 2px; color: var(--muted); font-size: 7px; text-transform: uppercase; }
        .measure-table { width: 100%; border-collapse: collapse; font-size: 9px; }
        .measure-table th { padding: 0 4px 7px; color: var(--muted); font-size: 8px; font-weight: 700; text-align: left; text-transform: uppercase; }
        .measure-table td { padding: 6px 4px; border-top: 1px solid #24343b; color: #dbe2df; vertical-align: middle; }
        .measure-table td:last-child, .measure-table th:last-child { text-align: right; }
        .measure-table .asymmetry { color: var(--red); font-weight: 700; }
        .measure-table .balanced { color: var(--green); }
        .notes { display: grid; grid-template-columns: 1fr 1.5fr; gap: 10px; margin-top: 10px; }
        .note-text { margin: 0; color: #c4cecb; font-size: 10px; line-height: 1.5; }
        .note-text strong { color: var(--white); }
        .footer { display: flex; justify-content: space-between; gap: 12px; margin-top: 11px; padding: 9px 8px 0; border-top: 1px solid var(--gold-soft); color: var(--muted); font-size: 8px; }
        @media (max-width: 800px) {
            .sheet { width: calc(100% - 20px); padding: 20px; }
            .grid { grid-template-columns: 1fr 1fr; }
            .column:last-child { grid-column: 1 / -1; }
        }
        @media (max-width: 560px) {
            .header { align-items: flex-start; flex-direction: column; }
            .report-meta { text-align: left; }
            .grid, .notes { grid-template-columns: 1fr; }
            .column:last-child { grid-column: auto; }
        }
        @page { size: A4 landscape; margin: 10mm; }
        @media print {
            html, body { width: 277mm; height: 190mm; background: var(--night) !important; }
            body { margin: 0; }
            nav, .toolbar, button { display: none !important; }
            .sheet { width: 277mm; height: 190mm; margin: 0; padding: 7mm; border: 0; box-shadow: none; }
            .sheet::before { inset: 2mm; }
            .header { padding-bottom: 4mm; }
            .grid { gap: 3mm; margin-top: 3mm; }
            .column { gap: 2.5mm; }
            .block { padding: 3mm; break-inside: avoid; }
            .block-title { margin-bottom: 2.5mm; }
            .stat { min-height: 15mm; padding: 2mm; }
            .basal-stat { min-height: 13mm; }
            .basal-seal { width: 8mm; height: 8mm; flex-basis: 8mm; }
            .fold-list { gap: 2mm; }
            .fold-total { margin-top: 2.5mm; padding-top: 2mm; }
            .composition-card { min-height: 19mm; padding: 2mm; }
            .mass-distribution { gap: 1.2mm; margin-top: 2mm; }
            .mass-track { height: 1.5mm; }
            .classification { margin-top: 2mm; padding-top: 1.5mm; }
            .classification-heading { margin-bottom: 1.5mm; }
            .range-list { gap: 1mm; }
            .range-row { grid-template-columns: 32mm minmax(0, 1fr) 10mm; gap: 1.2mm; font-size: 6px; }
            .range-track { height: 1.5mm; }
            .current-range { margin-top: 1.5mm; }
            .current-track, .current-track table, .current-track td { height: 1.8mm; margin-top: 1mm; }
            .current-marker { top: -1mm; height: 3.8mm; }
            .classification-note { margin-top: 1mm; }
            .notes { gap: 3mm; margin-top: 3mm; }
            .footer { margin-top: 2.5mm; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('assessments.report', $assessment) }}">Voltar ao relatório</a>
        <button type="button" onclick="window.print()">Imprimir / salvar em PDF</button>
    </div>
    <main class="sheet">
        <header class="header">
            <div class="brand">
                <div class="brand-mark" aria-hidden="true">MP</div>
                <div>
                    <div class="brand-name">MARLUZ PERSONAL</div>
                    <div class="brand-subtitle">RELATÓRIO DE AVALIAÇÃO FÍSICA PROFISSIONAL</div>
                </div>
            </div>
            <div class="report-meta">
                <strong>AVALIAÇÃO CORPORAL</strong><br>
                {{ $assessment->created_at?->format('d/m/Y') }} · {{ $assessment->protocol ?? '—' }} dobras
            </div>
        </header>

        <div class="grid">
            <section class="column" aria-label="Dados básicos do perfil">
                <div class="block">
                    <h2 class="block-title">Perfil <small>Dados básicos</small></h2>
                    <h1 class="client-name">{{ $assessment->client?->name ?? $assessment->name }}</h1>
                    <div class="profile-grid">
                        <div class="stat"><div class="stat-label">Peso atual</div><div class="stat-value">{{ $format($assessment->weight) }} <em>kg</em></div></div>
                        <div class="stat"><div class="stat-label">Altura</div><div class="stat-value">{{ $assessment->height !== null ? $format($assessment->height * 100, 0) : '—' }} <em>cm</em></div></div>
                        <div class="stat"><div class="stat-label">Idade</div><div class="stat-value">{{ $assessment->age ?? '—' }} <em>anos</em></div></div>
                        <div class="stat"><div class="stat-label">Sexo</div><div class="stat-value">{{ $assessment->gender === 'male' ? 'Masc.' : 'Fem.' }}</div></div>
                        <div class="stat basal-stat"><div class="basal-content"><span class="basal-seal" aria-hidden="true">TMB</span><div><div class="stat-label">Gasto Basal (TMB) · Mifflin-St Jeor</div><div class="stat-value">{{ $assessment->basal_metabolic_rate !== null ? number_format($assessment->basal_metabolic_rate, 0, ',', '.') : '—' }} <em>kcal/dia</em></div></div></div></div>
                    </div>
                    <div class="profile-note">Registro individual · {{ $assessment->created_at?->format('d/m/Y') }}</div>
                </div>
            </section>

            <section class="column" aria-label="Dobras e composição corporal">
                <div class="block">
                    <h2 class="block-title">Dobras cutâneas <small>{{ $assessment->protocol ?? '—' }} dobras</small></h2>
                    @if ($folds->isNotEmpty())
                        <div class="fold-list">
                            @foreach ($folds as $label => $value)
                                <div class="fold-row">
                                    <span class="fold-label">{{ $label }}</span>
                                    <div class="bar"><div class="bar-fill" style="width: {{ min(100, max(0, ($value / 40) * 100)) }}%"></div></div>
                                    <span class="fold-value">{{ $format($value) }} mm</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="fold-total"><span>Soma total</span><strong>{{ $format($foldSum, 1) }} <small>mm</small></strong></div>
                    @else
                        <p class="note-text">Dobras cutâneas não informadas nesta avaliação.</p>
                    @endif
                </div>
                <div class="block">
                    <h2 class="block-title">Composição final</h2>
                    <div class="composition">
                        <div class="composition-card fat"><div class="composition-label">Massa gorda</div><div class="composition-value">{{ $format($fatMass, 2) }} <span>kg</span></div><div class="composition-percent">{{ $format($fatPercentage, 1) }}%</div></div>
                        <div class="composition-card lean"><div class="composition-label">Massa magra</div><div class="composition-value">{{ $format($leanMass, 2) }} <span>kg</span></div><div class="composition-percent">{{ $format($leanPercentage, 1) }}%</div></div>
                    </div>
                    <div class="mass-distribution" aria-label="Distribuição de massa em quilogramas">
                        <h3 class="mass-title">Distribuição de Massa (kg)</h3>
                        @foreach ([
                            ['Peso Total', $assessment->weight, 'total'],
                            ['Massa Magra', $leanMass, 'lean'],
                            ['Massa Gorda', $fatMass, 'fat'],
                        ] as [$label, $value, $variant])
                            <div class="mass-row">
                                <div class="mass-meta"><span>{{ $label }}</span><strong>{{ $value !== null ? $format($value, 2).' kg' : '—' }}</strong></div>
                                <div class="mass-track"><div class="mass-fill {{ $variant }}" style="width: {{ $value !== null ? min(100, max(0, ((float) $value / $massScale) * 100)) : 0 }}%"></div></div>
                            </div>
                        @endforeach
                    </div>
                    <section class="classification classification-adipometry" aria-label="Classificação de gordura corporal por adipometria">
                        <div class="classification-heading">
                            <div><h3 class="classification-title">Classificação de Gordura Corporal</h3><div class="classification-method">{{ $classificationMethod }}</div></div>
                            <div class="classification-result"><strong>{{ $format($classificationPercentage, 2) }}%</strong><span>{{ $classificationCategory }}</span></div>
                        </div>
                        <div class="range-list">
                            @foreach ($classificationRanges as [$label, $rangeLabel, $rangeStart, $rangeEnd, $rangeClass, $rangeColor])
                                <div class="range-row">
                                    <span>{{ $label }}</span>
                                    <div class="range-track" style="display:block;width:100%;height:7px;overflow:hidden;background-color:#202f35;-webkit-print-color-adjust:exact;print-color-adjust:exact"><div class="range-fill {{ $rangeClass }}" style="display:block;width:100%;height:7px;background-color:{{ $rangeColor }};-webkit-print-color-adjust:exact;print-color-adjust:exact"></div></div>
                                    <span class="range-value">{{ $rangeLabel }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="current-range">Valor Atual · {{ $format($adipometryPercentage, 2) }}%</div>
                        <div class="current-track" style="position:relative;display:block;width:100%;height:9px;margin-top:4px;overflow:visible">
                            <table role="presentation" style="width:100%;height:9px;table-layout:fixed;border-collapse:collapse"><tbody><tr>
                                <td style="width:33.333%;height:9px;padding:0;border:0;line-height:0;background-color:#20d69a;-webkit-print-color-adjust:exact;print-color-adjust:exact">&nbsp;</td>
                                <td style="width:33.333%;height:9px;padding:0;border:0;line-height:0;background-color:#d4b16b;-webkit-print-color-adjust:exact;print-color-adjust:exact">&nbsp;</td>
                                <td style="width:33.334%;height:9px;padding:0;border:0;line-height:0;background-color:#c34f57;-webkit-print-color-adjust:exact;print-color-adjust:exact">&nbsp;</td>
                            </tr></tbody></table>
                            <span class="current-marker" style="display:block;position:absolute;top:-4px;left:{{ $cssNumber($classificationCurrentWidth) }}%;width:3px;height:17px;background-color:#ffffff;-webkit-print-color-adjust:exact;print-color-adjust:exact"></span>
                        </div>
                        <p class="classification-note">Referências gerais da ACE; as faixas variam com idade e contexto clínico. Não substitui avaliação profissional.</p>
                    </section>
                    @if ($hasNavyClassification)
                        <section class="classification navy-classification" aria-label="Resumo de gordura corporal pelo Método U.S. Navy">
                            <div class="classification-heading">
                                <div><h3 class="classification-title">Classificação de Gordura Corporal</h3><div class="classification-method">Método U.S. Navy</div></div>
                                <div class="classification-result"><strong>{{ $format($navyPercentage, 2) }}%</strong><span>{{ $navyClassificationCategory }}</span></div>
                            </div>
                        </section>
                    @endif
                </div>
            </section>

            <section class="column" aria-label="Índices de saúde e medidas">
                <div class="block">
                    <h2 class="block-title">Índices de saúde</h2>
                    <div class="health-top">
                        <div>
                            <div class="bmi-value">{{ $format($bmi, 1) }}</div>
                            <div class="bmi-status">{{ $bmiStatus }}</div>
                            <div class="bmi-bar"><span class="bmi-marker" style="--position: {{ $bmiPosition }}%"></span></div>
                            <div class="bmi-legend"><span>Baixo</span><span>Adequado</span><span>Elevado</span></div>
                        </div>
                        <div class="rings">
                            <div class="ring" style="--ring-color: var(--gold); --ring-value: {{ min(100, max(0, (float) ($fatPercentage ?? 0))) }}"><div class="ring-inner"><strong>{{ $format($fatPercentage, 1) }}%</strong><span>Gordura</span></div></div>
                            <div class="ring" style="--ring-color: var(--green); --ring-value: {{ min(100, max(0, (float) ($leanPercentage ?? 0))) }}"><div class="ring-inner"><strong>{{ $format($leanPercentage, 1) }}%</strong><span>Magra</span></div></div>
                        </div>
                    </div>
                </div>
                <div class="block">
                    <h2 class="block-title">Circunferências <small>cm</small></h2>
                    <table class="measure-table">
                        <thead><tr><th>Região</th><th>Medida</th><th>Região</th><th>Medida</th></tr></thead>
                        <tbody>
                            <tr><td>Tórax</td><td>{{ $assessment->torax !== null ? $format($assessment->torax) : '—' }}</td><td>Cintura</td><td>{{ $assessment->cintura !== null ? $format($assessment->cintura) : '—' }}</td></tr>
                            <tr><td>Abdômen</td><td>{{ $assessment->abdomen !== null ? $format($assessment->abdomen) : '—' }}</td><td>Quadril</td><td>{{ $assessment->quadril !== null ? $format($assessment->quadril) : '—' }}</td></tr>
                            <tr><td>Braços</td><td>{{ $assessment->braco_relaxado_d !== null ? $format($assessment->braco_relaxado_d) : '—' }} / {{ $assessment->braco_relaxado_e !== null ? $format($assessment->braco_relaxado_e) : '—' }}</td><td>Coxas</td><td>{{ $assessment->coxa_proximal_d !== null ? $format($assessment->coxa_proximal_d) : '—' }} / {{ $assessment->coxa_proximal_e !== null ? $format($assessment->coxa_proximal_e) : '—' }}</td></tr>
                            <tr><td>Pescoço</td><td>{{ $assessment->pescoco !== null ? $format($assessment->pescoco) : '—' }}</td><td>RCQ</td><td>{{ $format($assessment->waist_hip_ratio, 2) }}</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="block">
                    <h2 class="block-title">Perímetros bilaterais <small>cm · Δ acima de 1 cm sinalizado</small></h2>
                    <table class="measure-table">
                        <thead><tr><th>Medida</th><th>Direita</th><th>Esquerda</th><th>Δ</th></tr></thead>
                        <tbody>
                            @foreach ($measurements as [$label, $right, $left])
                                @php $difference = $right !== null && $left !== null ? abs($right - $left) : null; @endphp
                                <tr><td>{{ $label }}</td><td>{{ $right !== null ? $format($right) : '—' }}</td><td>{{ $left !== null ? $format($left) : '—' }}</td><td class="{{ $difference !== null ? ($difference > 1 ? 'asymmetry' : 'balanced') : '' }}">{{ $difference !== null ? ($difference > 1 ? '!' : 'OK') : '—' }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <section class="notes" aria-label="Notas e recomendações">
            <div class="block">
                <h2 class="block-title">Simetria muscular</h2>
                @if ($asymmetries->isNotEmpty())
                    @foreach ($asymmetries as [$label, $right, $left])
                        <p class="note-text" style="margin: 0 0 5px; color: var(--red)"><strong>{{ $label }}:</strong> diferença de {{ $format(abs($right - $left)) }} cm entre os lados.</p>
                    @endforeach
                @else
                    <p class="note-text">Sem assimetrias superiores a 1,0 cm nas medidas pareadas disponíveis.</p>
                @endif
            </div>
            <div class="block">
                <h2 class="block-title">Evolução e recomendações</h2>
                <p class="note-text">{{ $feedback ?? 'Esta é a avaliação de referência. Compare as próximas medidas para acompanhar as mudanças de composição corporal.' }} Os indicadores devem ser interpretados pelo profissional responsável em conjunto com o histórico individual.</p>
            </div>
        </section>
        <footer class="footer"><span>MARLUZ PERSONAL · RELATÓRIO DE AVALIAÇÃO FÍSICA</span><span>Documento gerado em {{ now()->format('d/m/Y H:i') }}</span></footer>
    </main>
</body>
</html>