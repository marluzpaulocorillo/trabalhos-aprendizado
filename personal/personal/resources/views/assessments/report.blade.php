@php
    $asymmetryPairs = [
        ['Braço relaxado', $assessment->braco_relaxado_d, $assessment->braco_relaxado_e],
        ['Braço contraído', $assessment->braco_contraido_d, $assessment->braco_contraido_e],
        ['Antebraço', $assessment->antebraco_d, $assessment->antebraco_e],
        ['Coxa proximal', $assessment->coxa_proximal_d, $assessment->coxa_proximal_e],
        ['Coxa medial', $assessment->coxa_medial_d, $assessment->coxa_medial_e],
        ['Panturrilha', $assessment->panturrilha_d, $assessment->panturrilha_e],
    ];
    $asymmetries = collect($asymmetryPairs)->filter(fn (array $pair): bool => $pair[1] !== null && $pair[2] !== null && abs($pair[1] - $pair[2]) > 1.0);
    $ratioThreshold = $assessment->gender === 'male' ? 0.90 : 0.85;
    $ratioHasRisk = $assessment->waist_hip_ratio !== null && $assessment->waist_hip_ratio >= $ratioThreshold;
    $fatMass = $assessment->fat_mass ?? ($assessment->weight !== null ? $assessment->weight * ($assessment->body_fat_percentage / 100) : null);
    $leanMass = $assessment->lean_mass ?? ($assessment->weight !== null && $fatMass !== null ? $assessment->weight - $fatMass : null);
    $isMale = $assessment->gender === 'male';
    $adipometryPercentage = $assessment->body_fat_percentage;
    $classificationPercentage = $adipometryPercentage;
    $classificationMethod = 'Protocolo '.($assessment->protocol ?? '—').' Dobras · Adipometria';
    $classificationRanges = $isMale
        ? [
            ['Essencial / atletas', '0–13%', 0, 13, 'bg-emerald-500'],
            ['Fitness / saudável', '14–17%', 13, 17, 'bg-green-600'],
            ['Aceitável / moderado', '18–24%', 17, 24, 'bg-amber-500'],
            ['Elevado / obesidade', '≥ 25%', 24, 40, 'bg-rose-500'],
        ]
        : [
            ['Essencial / atletas', '0–20%', 0, 20, 'bg-emerald-500'],
            ['Fitness / saudável', '21–24%', 20, 24, 'bg-green-600'],
            ['Aceitável / moderado', '25–31%', 24, 31, 'bg-amber-500'],
            ['Elevado / obesidade', '≥ 32%', 31, 50, 'bg-rose-500'],
        ];
    $classificationScale = $isMale ? 40 : 50;
    $classificationCategory = $classificationPercentage === null
        ? 'Sem classificação'
        : ((float) $classificationPercentage <= ($isMale ? 13 : 20)
            ? 'Essencial / atletas'
            : ((float) $classificationPercentage <= ($isMale ? 17 : 24)
                ? 'Fitness / saudável'
                : ((float) $classificationPercentage <= ($isMale ? 24 : 31)
                    ? 'Aceitável / moderado'
                    : 'Elevado / obesidade')));
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>Relatório de avaliação | {{ $assessment->client?->name ?? $assessment->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['DM Sans', 'ui-sans-serif', 'system-ui'] }, colors: { ink: '#0b1f33', ocean: '#07845b', bronze: '#b87838' } } } };</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body{font-family:'DM Sans',sans-serif}h1,h2,h3{font-family:'Manrope',sans-serif}.text-cyan-300,.text-cyan-200{color:#b87838!important}.bg-cyan-50{background-color:#ecfdf5!important}.border-cyan-100,.border-cyan-200{border-color:rgba(7,132,91,.25)!important}@media print{.print-hidden{display:none!important}.page{max-width:none!important;padding:0!important}.print-shadow{box-shadow:none!important;border-color:#e2e8f0!important}body{background:#fff!important}}
    </style>
    <style>
        .print-avoid { break-inside: avoid; }
        @media print {
            .report-metrics { gap: 10px !important; }
            .metric-track { height: 6px !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#f7faf8] text-slate-900 antialiased">
    <div class="print-hidden border-b border-slate-200 bg-white"><div class="mx-auto flex max-w-4xl items-center justify-between gap-4 px-5 py-4 sm:px-8"><a href="{{ route('assessments.evolution', ['client_id' => $assessment->client_id]) }}" class="text-sm font-bold text-ocean">← Voltar para evolução</a><button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-700">Gerar Relatório em PDF</button></div></div>
    <main class="page mx-auto max-w-4xl px-5 py-8 sm:px-8 sm:py-12">
        <header class="flex items-start justify-between gap-6 border-b-2 border-ink pb-7"><div><div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-ink text-xs font-extrabold text-bronze">MPC</span><span class="font-[Manrope] text-lg font-extrabold text-ink">Marluz Personal</span></div><p class="mt-5 text-xs font-bold uppercase tracking-[.16em] text-ocean">Relatório de avaliação física</p><h1 class="mt-2 text-3xl font-extrabold text-ink">{{ $assessment->client?->name ?? $assessment->name }}</h1><p class="mt-2 text-sm text-slate-500">Avaliação realizada em {{ $assessment->created_at->format('d/m/Y') }}</p></div><div class="text-right text-sm text-slate-500"><p>{{ $assessment->age }} anos</p><p>{{ $assessment->gender === 'male' ? 'Masculino' : 'Feminino' }}</p><p>Protocolo {{ $assessment->protocol ?? '7' }} dobras</p></div></header>
        <section class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-6"><div class="print-shadow rounded-2xl bg-ink p-4 text-white shadow-lg"><p class="text-xs font-bold uppercase tracking-wider text-cyan-200">Peso</p><p class="mt-3 text-2xl font-extrabold">{{ $assessment->weight !== null ? number_format($assessment->weight, 1, ',', '.') : '—' }} <span class="text-sm">kg</span></p></div><div class="print-shadow rounded-2xl border border-slate-200 bg-white p-4 shadow-lg"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Massa gorda</p><p class="mt-3 text-2xl font-extrabold text-rose-700">{{ $fatMass !== null ? number_format($fatMass, 2, ',', '.') : '—' }} <span class="text-sm">kg</span></p></div><div class="print-shadow rounded-2xl border border-slate-200 bg-white p-4 shadow-lg"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Massa magra</p><p class="mt-3 text-2xl font-extrabold text-emerald-700">{{ $leanMass !== null ? number_format($leanMass, 2, ',', '.') : '—' }} <span class="text-sm">kg</span></p></div><div class="print-shadow rounded-2xl border border-slate-200 bg-white p-4 shadow-lg"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">IMC</p><p class="mt-3 text-2xl font-extrabold text-ocean">{{ $assessment->imc !== null ? number_format($assessment->imc, 2, ',', '.') : '—' }}</p></div><div class="print-shadow rounded-2xl border border-slate-200 bg-white p-4 shadow-lg"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Gordura</p><p class="mt-3 text-2xl font-extrabold text-ocean">{{ number_format($assessment->body_fat_percentage, 2, ',', '.') }}%</p></div><div class="print-shadow rounded-2xl border border-slate-200 bg-white p-4 shadow-lg"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Navy</p><p class="mt-3 text-2xl font-extrabold text-emerald-600">{{ $assessment->navy_body_fat_percentage !== null ? number_format($assessment->navy_body_fat_percentage, 2, ',', '.') . '%' : '—' }}</p></div></section>
        <section class="report-metrics mt-6 grid items-stretch gap-4 md:grid-cols-2" aria-label="Composição e classificação corporal">
            <div class="print-shadow print-avoid rounded-2xl border border-slate-200 bg-white p-5 shadow-lg sm:p-6">
                <h2 class="text-lg font-extrabold text-ink">Distribuição de Massa (kg)</h2>
                <div class="mt-5 space-y-4">
                    @foreach ([
                        ['Peso Total', $assessment->weight, 'bg-slate-400'],
                        ['Massa Magra', $leanMass, 'bg-emerald-500'],
                        ['Massa Gorda', $fatMass, 'bg-amber-400'],
                    ] as [$label, $value, $color])
                        <div>
                            <div class="mb-2 flex items-baseline justify-between gap-3 text-sm"><span class="font-semibold text-slate-600">{{ $label }}</span><strong class="tabular-nums text-ink">{{ $value !== null ? number_format($value, 2, ',', '.') : '—' }} kg</strong></div>
                            <div class="metric-track h-2 overflow-hidden rounded-full bg-slate-100"><div class="{{ $color }} h-full rounded-full" style="width: {{ $value !== null ? min(100, max(0, ((float) $value / 100) * 100)) : 0 }}%"></div></div>
                        </div>
                    @endforeach
                </div>
                <p class="mt-3 text-xs text-slate-400">Escala visual de 0 a 100 kg.</p>
            </div>

            <div class="print-shadow print-avoid flex flex-col justify-between rounded-2xl border border-emerald-900 bg-ink p-5 text-white shadow-lg sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div><p class="text-xs font-bold uppercase tracking-[.14em] text-emerald-300">Mifflin-St Jeor</p><h2 class="mt-2 text-lg font-extrabold">Gasto Basal (TMB)</h2></div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-emerald-400/40 bg-emerald-400/10 text-sm font-extrabold text-emerald-300" aria-hidden="true">TMB</span>
                </div>
                <div class="mt-6"><p class="text-4xl font-extrabold tracking-tight text-emerald-300">{{ $assessment->basal_metabolic_rate !== null ? number_format($assessment->basal_metabolic_rate, 0, ',', '.') : '—' }}</p><p class="mt-1 text-sm font-semibold text-slate-300">kcal/dia</p></div>
                <p class="mt-5 border-t border-white/10 pt-3 text-xs leading-5 text-slate-300">Estimativa individual baseada nos dados desta avaliação.</p>
            </div>

            <div class="print-shadow print-avoid rounded-2xl border border-slate-200 bg-white p-5 shadow-lg md:col-span-2 sm:p-6">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div><h2 class="text-lg font-extrabold text-ink">Classificação de Gordura Corporal</h2><p class="mt-1 text-xs font-semibold text-slate-500">{{ $classificationMethod }}</p></div>
                    <div class="flex items-center gap-3 sm:justify-end"><strong class="text-2xl font-extrabold text-ocean">{{ $adipometryPercentage !== null ? number_format($adipometryPercentage, 2, ',', '.') : '—' }}%</strong><span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-extrabold text-emerald-800">{{ $classificationCategory }}</span></div>
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    @foreach ($classificationRanges as [$label, $rangeLabel, $rangeStart, $rangeEnd, $color])
                        <div>
                            <div class="mb-2 flex items-baseline justify-between gap-2 text-xs"><span class="font-semibold text-slate-600">{{ $label }}</span><span class="shrink-0 font-semibold tabular-nums text-slate-500">{{ $rangeLabel }}</span></div>
                            <div class="relative h-2 overflow-hidden rounded-full bg-slate-100"><span class="absolute inset-y-0 {{ $color }} rounded-full" style="left: {{ ($rangeStart / $classificationScale) * 100 }}%; width: {{ (($rangeEnd - $rangeStart) / $classificationScale) * 100 }}%"></span></div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 border-t border-slate-100 pt-4">
                    <div class="flex items-center justify-between gap-3 text-xs font-bold"><span class="text-ink">Valor Atual</span><span class="text-ocean">{{ $adipometryPercentage !== null ? number_format($adipometryPercentage, 2, ',', '.') : '—' }}%</span></div>
                    <div class="relative mt-2 h-2 overflow-visible rounded-full bg-slate-100"><div class="h-full rounded-full bg-gradient-to-r from-emerald-500 via-amber-400 to-rose-500" style="width: {{ $adipometryPercentage !== null ? min(100, max(0, ((float) $adipometryPercentage / $classificationScale) * 100)) : 0 }}%"></div><span class="absolute -top-1 h-4 w-0.5 bg-ink" style="left: {{ $adipometryPercentage !== null ? min(100, max(0, ((float) $adipometryPercentage / $classificationScale) * 100)) : 0 }}%"></span></div>
                </div>
                <p class="mt-3 text-xs leading-5 text-slate-400">Faixas gerais de referência da ACE; podem variar conforme idade e contexto clínico. Não substituem avaliação profissional.</p>
            </div>
        </section>

        @if ($comparison)
            <section class="print-shadow mt-7 rounded-2xl border border-cyan-100 bg-cyan-50/70 p-5 shadow-lg sm:p-6"><div class="flex items-start gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-ocean shadow-sm"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-7-7 7 7-7 7"/></svg></span><div><p class="text-xs font-bold uppercase tracking-[.16em] text-ocean">Feedback inteligente</p><h2 class="mt-1 text-xl font-extrabold text-ink">Parecer do avaliador</h2><p class="mt-3 max-w-3xl text-sm leading-6 text-slate-700">{{ $feedback }}</p><p class="mt-2 text-xs font-semibold text-slate-500">Comparativo desde {{ $comparison['date'] }}</p></div></div><div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-5"><div class="rounded-xl bg-white/80 p-3"><p class="text-xs font-bold text-slate-400">Δ Peso</p><p class="mt-1 text-lg font-extrabold {{ $comparison['weight'] <= 0 ? 'text-emerald-700' : 'text-amber-700' }}">{{ $comparison['weight'] >= 0 ? '+' : '' }}{{ number_format($comparison['weight'], 2, ',', '.') }} kg</p></div><div class="rounded-xl bg-white/80 p-3"><p class="text-xs font-bold text-slate-400">Δ Massa gorda</p><p class="mt-1 text-lg font-extrabold {{ $comparison['fat_mass'] <= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $comparison['fat_mass'] >= 0 ? '+' : '' }}{{ number_format($comparison['fat_mass'], 2, ',', '.') }} kg</p></div><div class="rounded-xl bg-white/80 p-3"><p class="text-xs font-bold text-slate-400">Δ Massa magra</p><p class="mt-1 text-lg font-extrabold {{ $comparison['lean_mass'] >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $comparison['lean_mass'] >= 0 ? '+' : '' }}{{ number_format($comparison['lean_mass'], 2, ',', '.') }} kg</p></div><div class="rounded-xl bg-white/80 p-3"><p class="text-xs font-bold text-slate-400">Δ Cintura</p><p class="mt-1 text-lg font-extrabold text-ink">{{ $comparison['waist'] !== null ? ($comparison['waist'] >= 0 ? '+' : '') . number_format($comparison['waist'], 1, ',', '.') . ' cm' : '—' }}</p></div><div class="rounded-xl bg-white/80 p-3"><p class="text-xs font-bold text-slate-400">Δ Dobras</p><p class="mt-1 text-lg font-extrabold text-ink">{{ $comparison['fold_sum'] !== null ? ($comparison['fold_sum'] >= 0 ? '+' : '') . number_format($comparison['fold_sum'], 1, ',', '.') . ' mm' : '—' }}</p></div></div></section>
        @endif
        <section class="print-shadow mt-7 rounded-2xl border border-slate-200 bg-white p-5 shadow-lg sm:p-6"><h2 class="text-lg font-extrabold text-ink">Perimetria</h2><div class="mt-5 grid gap-3 sm:grid-cols-3"><div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Tórax</p><p class="mt-1 text-lg font-extrabold text-ink">{{ $assessment->torax !== null ? number_format($assessment->torax, 1, ',', '.') . ' cm' : '—' }}</p></div><div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Cintura</p><p class="mt-1 text-lg font-extrabold text-ink">{{ $assessment->cintura !== null ? number_format($assessment->cintura, 1, ',', '.') . ' cm' : '—' }}</p></div><div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Abdômen</p><p class="mt-1 text-lg font-extrabold text-ink">{{ $assessment->abdomen !== null ? number_format($assessment->abdomen, 1, ',', '.') . ' cm' : '—' }}</p></div><div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Quadril</p><p class="mt-1 text-lg font-extrabold text-ink">{{ $assessment->quadril !== null ? number_format($assessment->quadril, 1, ',', '.') . ' cm' : '—' }}</p></div><div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pescoço</p><p class="mt-1 text-lg font-extrabold text-ink">{{ $assessment->pescoco !== null ? number_format($assessment->pescoco, 1, ',', '.') . ' cm' : '—' }}</p></div><div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">RCQ</p><p class="mt-1 text-lg font-extrabold {{ $ratioHasRisk ? 'text-rose-700' : 'text-emerald-700' }}">{{ $assessment->waist_hip_ratio !== null ? number_format($assessment->waist_hip_ratio, 2, ',', '.') . ' · ' . ($ratioHasRisk ? 'Risco alto' : 'Seguro') : '—' }}</p></div></div></section>
        <section class="print-shadow mt-7 rounded-2xl border border-slate-200 bg-white p-5 shadow-lg sm:p-6"><h2 class="text-lg font-extrabold text-ink">Simetria muscular</h2>@if ($asymmetries->isNotEmpty())<div class="mt-4 space-y-2">@foreach ($asymmetries as $asymmetry)<p class="rounded-xl bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800">Atenção: Assimetria detectada no {{ $asymmetry[0] }} ({{ number_format(abs($asymmetry[1] - $asymmetry[2]), 1, ',', '.') }} cm)</p>@endforeach</div>@else<p class="mt-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">Nenhuma assimetria acima de 1,0 cm foi identificada.</p>@endif</section>
        <footer class="mt-10 border-t border-slate-200 pt-4 text-xs text-slate-400">Documento gerado por Marluz Personal · {{ now()->format('d/m/Y H:i') }}</footer>
    </main>
</body>
</html>
