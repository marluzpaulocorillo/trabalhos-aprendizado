@php
    $chartData = $assessments->map(function ($assessment) {
        $arms = collect([$assessment->braco_relaxado_d, $assessment->braco_relaxado_e])->filter(fn ($value) => $value !== null)->avg();
        $thighs = collect([$assessment->coxa_medial_d, $assessment->coxa_medial_e])->filter(fn ($value) => $value !== null)->avg();
        $leanMass = $assessment->weight !== null && $assessment->body_fat_percentage !== null
            ? round($assessment->weight * (1 - ($assessment->body_fat_percentage / 100)), 2)
            : null;

        return [
            'label' => $assessment->created_at->format('d/m/Y'),
            'weight' => $assessment->weight,
            'bodyFat' => $assessment->body_fat_percentage,
            'leanMass' => $leanMass,
            'waist' => $assessment->cintura,
            'abdomen' => $assessment->abdomen,
            'arms' => $arms !== null ? round($arms, 2) : null,
            'thighs' => $thighs !== null ? round($thighs, 2) : null,
        ];
    })->values();
    $latest = $assessments->last();
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="description" content="Dashboard de evolução corporal da Marluz Personal.">
    <title>Evolução | Marluz Personal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['DM Sans', 'ui-sans-serif', 'system-ui'] }, colors: { ink: '#0b1f33', ocean: '#07845b', bronze: '#b87838' } } } };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif}h1,h2,h3{font-family:'Manrope',sans-serif}.text-cyan-300,.text-cyan-200{color:#b87838!important}.bg-cyan-50{background-color:#ecfdf5!important}.border-cyan-100,.border-cyan-200{border-color:rgba(7,132,91,.25)!important}.chart-wrap{height:18rem}@media(min-width:1024px){.chart-wrap{height:21rem}}</style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_90%_0%,_#e9f5ef_0,_transparent_28%),linear-gradient(135deg,#fbfcfa_0%,#eef7f2_100%)]">
        <nav class="border-b border-slate-200/80 bg-white/85 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8 lg:px-10">
                <a href="{{ route('assessments.index') }}" class="flex items-center gap-3" aria-label="Marluz Personal, início"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-ink text-xs font-extrabold text-bronze">MPC</span><span><span class="block font-[Manrope] text-lg font-extrabold tracking-tight text-ink">Marluz Personal</span><span class="block text-[9px] font-bold uppercase tracking-[.14em] text-ocean">Profilaxia e qualidade de vida</span></span></a>
                <div class="flex items-center gap-1 text-sm font-semibold text-slate-600"><a href="{{ route('assessments.create') }}" class="hidden rounded-lg px-3 py-2 transition hover:bg-emerald-50 hover:text-emerald-800 sm:block">Nova avaliação</a><a href="{{ route('history') }}" class="rounded-lg px-3 py-2 transition hover:bg-emerald-50 hover:text-emerald-800">Histórico</a><a href="{{ route('assessments.evolution') }}" class="rounded-lg bg-emerald-50 px-3 py-2 text-emerald-800">Evolução</a></div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-5 py-10 sm:px-8 lg:px-10 lg:py-14">
            <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div><div class="mb-4 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[.16em] text-ocean"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Dashboard de evolução</div><h1 class="text-4xl font-extrabold tracking-[-.04em] text-ink sm:text-5xl">Acompanhe cada avanço.</h1><p class="mt-4 max-w-xl text-base leading-7 text-slate-600">Compare avaliações ao longo do tempo e transforme cada medida em uma decisão melhor.</p></div>
                <form method="GET" action="{{ route('assessments.evolution') }}" class="flex w-full flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-lg shadow-slate-900/[.04] sm:flex-row sm:items-end lg:w-auto">
                    <div class="min-w-0 flex-1 sm:min-w-64"><label for="client_id" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">Cliente</label><select id="client_id" name="client_id" class="field w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-ink" required><option value="">Selecione um cliente</option>@foreach ($clients as $client)<option value="{{ $client->id }}" @selected($selectedClient?->id === $client->id)>{{ $client->name }}</option>@endforeach</select></div><button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-ocean px-5 py-3 text-sm font-extrabold text-white shadow-md transition hover:bg-cyan-800">Visualizar</button>
                </form>
            </div>

            @if ($selectedClient && $latest)
                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-2xl bg-ink p-5 text-white shadow-xl shadow-ink/15"><p class="text-xs font-bold uppercase tracking-wider text-bronze">Avaliações</p><p class="mt-3 text-3xl font-extrabold">{{ $assessments->count() }}</p><p class="mt-1 text-xs text-slate-300">histórico do cliente</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-lg shadow-slate-900/[.04]"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Peso atual</p><p class="mt-3 text-3xl font-extrabold text-ink">{{ $latest->weight !== null ? number_format($latest->weight, 1, ',', '.') : '—' }} <span class="text-sm font-bold text-slate-400">kg</span></p><p class="mt-1 text-xs text-slate-500">última avaliação</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-lg shadow-slate-900/[.04]"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Gordura corporal</p><p class="mt-3 text-3xl font-extrabold text-ocean">{{ $latest->body_fat_percentage !== null ? number_format($latest->body_fat_percentage, 1, ',', '.') : '—' }}<span class="text-sm">%</span></p><p class="mt-1 text-xs text-slate-500">protocolo de dobras</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-lg shadow-slate-900/[.04]"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Última leitura</p><p class="mt-3 text-2xl font-extrabold text-ink">{{ $latest->created_at->format('d/m/Y') }}</p><a href="{{ route('assessments.report', $latest) }}" class="mt-1 inline-flex text-xs font-bold text-ocean hover:text-cyan-800">Gerar Relatório em PDF</a></div>
                </div>

                <div class="mt-8 grid gap-6 xl:grid-cols-2">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/[.05] sm:p-6"><div class="flex items-start justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-wider text-ocean">Composição corporal</p><h2 class="mt-1 text-xl font-extrabold text-ink">Peso, gordura e massa magra</h2></div><span class="rounded-xl bg-cyan-50 px-3 py-2 text-xs font-bold text-ocean">kg / %</span></div><div class="chart-wrap mt-6"><canvas id="composition-chart"></canvas></div></section>
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/[.05] sm:p-6"><div class="flex items-start justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Medidas corporais</p><h2 class="mt-1 text-xl font-extrabold text-ink">Centro versus periferia</h2></div><span class="rounded-xl bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">cm</span></div><div class="chart-wrap mt-6"><canvas id="measurements-chart"></canvas></div></section>
                </div>
            @else
                <section class="mt-8 rounded-3xl border border-slate-200 bg-white px-6 py-20 text-center shadow-xl shadow-slate-900/[.04]"><span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-cyan-50 text-ocean"><svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 19V5m0 14h16M8 15l3-4 3 2 5-7"/></svg></span><h2 class="mt-5 text-xl font-extrabold text-ink">Selecione um cliente para começar</h2><p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Cadastre uma avaliação e selecione o cliente acima para visualizar sua evolução temporal.</p></section>
            @endif
        </main>
    </div>
    <script>
        const evolutionData = @json($chartData);
        const labels = evolutionData.map((item) => item.label);
        const values = (key) => evolutionData.map((item) => item[key]);
        const chartOptions = { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { labels: { usePointStyle: true, boxWidth: 8, font: { family: 'DM Sans' } } } }, scales: { x: { grid: { display: false }, ticks: { color: '#64748b', font: { family: 'DM Sans' } } }, y: { grid: { color: '#e2e8f0' }, ticks: { color: '#64748b', font: { family: 'DM Sans' } } } } };
        if (document.getElementById('composition-chart')) {
            new Chart(document.getElementById('composition-chart'), { type: 'line', data: { labels, datasets: [{ label: 'Peso', data: values('weight'), borderColor: '#102a43', backgroundColor: '#102a43', tension: .35, spanGaps: true }, { label: 'Gordura corporal', data: values('bodyFat'), borderColor: '#167d9a', backgroundColor: '#167d9a', tension: .35, spanGaps: true }, { label: 'Massa magra estimada', data: values('leanMass'), borderColor: '#10b981', backgroundColor: '#10b981', tension: .35, spanGaps: true }] }, options: chartOptions });
            new Chart(document.getElementById('measurements-chart'), { type: 'line', data: { labels, datasets: [{ label: 'Cintura', data: values('waist'), borderColor: '#f97316', backgroundColor: '#f97316', tension: .35, spanGaps: true }, { label: 'Abdômen', data: values('abdomen'), borderColor: '#e11d48', backgroundColor: '#e11d48', tension: .35, spanGaps: true }, { label: 'Braços (média)', data: values('arms'), borderColor: '#167d9a', backgroundColor: '#167d9a', tension: .35, spanGaps: true }, { label: 'Coxas (média)', data: values('thighs'), borderColor: '#10b981', backgroundColor: '#10b981', tension: .35, spanGaps: true }] }, options: chartOptions });
        }
    </script>
</body>
</html>
