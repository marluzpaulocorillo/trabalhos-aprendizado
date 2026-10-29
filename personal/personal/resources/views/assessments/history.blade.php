<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="description" content="Marluz Personal: profilaxia e qualidade de vida.">
    <title>Histórico de clientes | Marluz Personal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        ink: '#0b1f33',
                        ocean: '#07845b',
                        bronze: '#b87838'
                    }
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        .text-cyan-300, .text-cyan-200 { color: #b87838 !important; }
        .bg-cyan-50 { background-color: #ecfdf5 !important; }
        .border-cyan-100, .border-cyan-200 { border-color: rgba(7,132,91,.25) !important; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_90%_0%,_#e9f5ef_0,_transparent_28%),linear-gradient(135deg,#fbfcfa_0%,#eef7f2_100%)]">
        <nav class="border-b border-slate-200/80 bg-white/85 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8 lg:px-10">
                <a href="{{ route('assessments.index') }}" class="flex items-center gap-3" aria-label="Marluz Personal, início">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-ink text-xs font-extrabold text-bronze">MPC</span>
                    <span><span class="block font-[Manrope] text-lg font-extrabold tracking-tight text-ink">Marluz Personal</span><span class="block text-[9px] font-bold uppercase tracking-[.14em] text-ocean">Profilaxia e qualidade de vida</span></span>
                </a>
                <div class="flex items-center gap-1 text-sm font-semibold text-slate-600">
                    <a href="{{ route('assessments.index') }}" class="hidden rounded-lg px-3 py-2 transition hover:bg-emerald-50 hover:text-emerald-800 sm:block">Início</a>
                    <a href="{{ route('assessments.create') }}" class="rounded-lg bg-emerald-600 px-3 py-2 text-white shadow-sm transition hover:bg-emerald-700">Nova avaliação</a>
                    <a href="{{ route('history') }}" class="rounded-lg bg-emerald-50 px-3 py-2 text-emerald-800">Histórico</a>
                    <a href="{{ route('assessments.evolution') }}" class="rounded-lg px-3 py-2 transition hover:bg-emerald-50 hover:text-emerald-800">Evolução</a>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-5 py-10 sm:px-8 lg:px-10 lg:py-14">
            <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
                <div>
                    <div class="mb-4 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[.16em] text-ocean">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Acompanhamento
                    </div>
                    <h1 class="text-4xl font-extrabold tracking-[-.04em] text-ink sm:text-5xl">Histórico de clientes</h1>
                    <p class="mt-4 max-w-xl text-base leading-7 text-slate-600">Uma visão clara das avaliações salvas e dos principais resultados calculados.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-ocean">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4V9m2 10H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z"/></svg>
                        </span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Avaliações</p>
                            <p class="text-xl font-extrabold text-ink">{{ $assessments->count() }}</p>
                        </div>
                    </div>
                    <button type="button" id="open-delete-modal" class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-3 text-sm font-bold text-red-600 shadow-sm transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50" @disabled($assessments->isEmpty())>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Excluir avaliação
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div class="mt-8 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800" role="status">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-200 text-emerald-700">&#10003;</span>
                    {{ session('success') }}
                </div>
            @endif

            <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-900/[.05]">
                <div class="flex flex-col justify-between gap-2 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:px-6">
                    <div>
                        <h2 class="font-[Manrope] text-lg font-extrabold text-ink">Avaliações registradas</h2>
                        <p class="mt-1 text-sm text-slate-500">Ordenadas da mais recente para a mais antiga.</p>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Dobras em mm · perímetros em cm</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs font-extrabold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th scope="col" class="px-4 py-3 sm:px-6">Cliente</th>
                                <th scope="col" class="px-4 py-3">Sexo</th>
                                <th scope="col" class="px-4 py-3">Idade</th>
                                <th scope="col" class="px-4 py-3">Soma das dobras</th>
                                <th scope="col" class="px-4 py-3">IMC</th>
                                <th scope="col" class="px-4 py-3">Perimetria</th>
                                <th scope="col" class="px-4 py-3 text-right">Gordura corporal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($assessments as $assessment)
                                @php
                                    $foldSum = collect([
                                        $assessment->triceps,
                                        $assessment->chest,
                                        $assessment->midaxillary,
                                        $assessment->subscapular,
                                        $assessment->suprailiac,
                                        $assessment->abdominal,
                                        $assessment->thigh,
                                    ])->filter(fn ($fold) => $fold !== null)->sum();
                                    $ratioThreshold = $assessment->gender === 'male' ? 0.90 : 0.85;
                                    $ratioHasRisk = $assessment->waist_hip_ratio !== null && $assessment->waist_hip_ratio >= $ratioThreshold;
                                    $asymmetryPairs = [
                                        ['Braço relaxado', $assessment->braco_relaxado_d, $assessment->braco_relaxado_e],
                                        ['Braço contraído', $assessment->braco_contraido_d, $assessment->braco_contraido_e],
                                        ['Antebraço', $assessment->antebraco_d, $assessment->antebraco_e],
                                        ['Coxa proximal', $assessment->coxa_proximal_d, $assessment->coxa_proximal_e],
                                        ['Coxa medial', $assessment->coxa_medial_d, $assessment->coxa_medial_e],
                                        ['Panturrilha', $assessment->panturrilha_d, $assessment->panturrilha_e],
                                    ];
                                    $detectedAsymmetries = collect($asymmetryPairs)->filter(fn (array $pair): bool => $pair[1] !== null && $pair[2] !== null && abs($pair[1] - $pair[2]) > 1.0);
                                    $fatMass = $assessment->fat_mass ?? ($assessment->weight !== null ? $assessment->weight * ($assessment->body_fat_percentage / 100) : null);
                                    $leanMass = $assessment->lean_mass ?? ($assessment->weight !== null && $fatMass !== null ? $assessment->weight - $fatMass : null);
                                @endphp
                                <tr class="transition duration-200 hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-4 py-5 sm:px-6">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-ink text-xs font-extrabold text-cyan-200">{{ strtoupper(substr($assessment->name, 0, 1)) }}</span>
                                            <div>
                                                <p class="font-bold text-ink">{{ $assessment->name }}</p>
                                                <p class="mt-0.5 text-xs text-slate-400">{{ $assessment->created_at->format('d/m/Y') }} · protocolo {{ $assessment->protocol ?? '7' }} dobras</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-5">
                                        @if ($assessment->gender === 'male')
                                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">Masculino</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-bronze/10 px-3 py-1 text-xs font-bold text-bronze">Feminino</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-5 font-medium text-slate-600">{{ $assessment->age }} anos</td>
                                    <td class="whitespace-nowrap px-4 py-5 font-semibold text-slate-700">{{ number_format($foldSum, 2, ',', '.') }} <span class="text-xs font-normal text-slate-400">mm</span></td>
                                    <td class="whitespace-nowrap px-4 py-5"><span class="text-lg font-extrabold text-emerald-700">{{ $assessment->imc !== null ? number_format($assessment->imc, 2, ',', '.') : '—' }}</span> <span class="text-xs font-normal text-slate-400">kg/m²</span></td>
                                    <td class="min-w-[270px] px-4 py-5">
                                        @if ($assessment->waist_hip_ratio !== null)
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-extrabold {{ $ratioHasRisk ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">RCQ {{ number_format($assessment->waist_hip_ratio, 2, ',', '.') }} · {{ $ratioHasRisk ? 'Risco alto' : 'Seguro' }}</span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-400">RCQ não calculada</span>
                                        @endif
                                        @if ($assessment->navy_body_fat_percentage !== null)
                                            <p class="mt-2 text-xs font-semibold text-slate-600">Navy: <span class="font-extrabold text-ocean">{{ number_format($assessment->navy_body_fat_percentage, 2, ',', '.') }}%</span></p>
                                        @endif
                                        @if ($assessment->basal_metabolic_rate !== null)
                                            <p class="mt-2 text-xs font-semibold text-slate-600">TMB: <span class="font-extrabold text-emerald-700">{{ number_format($assessment->basal_metabolic_rate, 0, ',', '.') }} kcal/dia</span></p>
                                        @endif
                                        @if ($detectedAsymmetries->isNotEmpty())
                                            <div class="mt-2 space-y-1">
                                                @foreach ($detectedAsymmetries as $asymmetry)
                                                    <p class="text-xs font-semibold text-amber-700">Atenção: Assimetria detectada no {{ $asymmetry[0] }}</p>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-5 text-right"><span class="text-lg font-extrabold text-ocean">{{ number_format($assessment->body_fat_percentage, 2, ',', '.') }}%</span><span class="mt-1 block text-xs font-semibold text-rose-700">Gorda: {{ $fatMass !== null ? number_format($fatMass, 2, ',', '.') . ' kg' : '—' }}</span><span class="block text-xs font-semibold text-emerald-700">Magra: {{ $leanMass !== null ? number_format($leanMass, 2, ',', '.') . ' kg' : '—' }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-20 text-center">
                                        <div class="mx-auto flex max-w-sm flex-col items-center">
                                            <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-cyan-50 text-ocean"><svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 13h6m-3-3v6m8 1V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2z"/></svg></span>
                                            <h2 class="mt-5 text-lg font-extrabold text-ink">Seu histórico começa aqui</h2>
                                            <p class="mt-2 text-sm leading-6 text-slate-500">Cadastre o primeiro cliente para acompanhar os resultados de composição corporal.</p>
                                            <a href="{{ route('assessments.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-ocean px-5 py-3 text-sm font-bold text-white shadow-md transition duration-200 hover:scale-105 hover:bg-cyan-800">Cadastrar primeiro cliente <span aria-hidden="true">&#8594;</span></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/55 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.16em] text-red-500">Ação irreversível</p>
                    <h2 id="delete-modal-title" class="mt-2 text-2xl font-extrabold text-ink">Excluir avaliação</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Selecione um cliente e confirme a exclusão.</p>
                </div>
                <button type="button" id="close-delete-modal" class="rounded-lg p-2 text-2xl leading-none text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Fechar janela">&times;</button>
            </div>

            <div class="mt-6 max-h-72 space-y-2 overflow-y-auto pr-1">
                @foreach ($assessments as $assessment)
                    <label class="block cursor-pointer">
                        <input type="radio" name="assessment_to_delete" value="{{ $assessment->id }}" data-delete-url="{{ route('assessments.destroy', $assessment) }}" data-assessment-name="{{ $assessment->name }}" class="peer sr-only">
                        <span class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition peer-checked:border-red-300 peer-checked:bg-red-50 hover:border-slate-300">
                            <span><strong class="block text-sm text-ink">{{ $assessment->name }}</strong><small class="text-xs text-slate-500">{{ $assessment->age }} anos · {{ $assessment->created_at->format('d/m/Y H:i') }}</small></span>
                            <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-bold text-slate-600">{{ $assessment->protocol ?? '7' }} dobras</span>
                        </span>
                    </label>
                @endforeach
            </div>

            <div id="delete-confirmation" class="mt-5 hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" aria-live="polite">
                Tem certeza que deseja excluir <strong id="selected-assessment-name"></strong>?
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancel-delete" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Cancelar</button>
                <form id="delete-form" method="POST" class="contents">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="confirm-delete" disabled class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-40">Sim, excluir</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('delete-modal');
        const deleteForm = document.getElementById('delete-form');
        const confirmDelete = document.getElementById('confirm-delete');
        const confirmation = document.getElementById('delete-confirmation');
        const selectedName = document.getElementById('selected-assessment-name');

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
            document.querySelectorAll('input[name="assessment_to_delete"]').forEach((input) => input.checked = false);
            confirmDelete.disabled = true;
            confirmation.classList.add('hidden');
            deleteForm.removeAttribute('action');
        }

        document.getElementById('open-delete-modal')?.addEventListener('click', () => {
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
        });
        document.getElementById('close-delete-modal')?.addEventListener('click', closeDeleteModal);
        document.getElementById('cancel-delete')?.addEventListener('click', closeDeleteModal);
        deleteModal?.addEventListener('click', (event) => {
            if (event.target === deleteModal) closeDeleteModal();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !deleteModal.classList.contains('hidden')) closeDeleteModal();
        });
        document.querySelectorAll('input[name="assessment_to_delete"]').forEach((input) => input.addEventListener('change', () => {
            deleteForm.action = input.dataset.deleteUrl;
            selectedName.textContent = input.dataset.assessmentName;
            confirmation.classList.remove('hidden');
            confirmDelete.disabled = false;
        }));
    </script>
</body>
</html>
