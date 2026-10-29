<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="description" content="Marluz Personal: profilaxia e qualidade de vida.">
    <title>Nova avaliação | Marluz Personal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['DM Sans', 'ui-sans-serif', 'system-ui'] }, colors: { ink: '#0b1f33', ocean: '#07845b', bronze: '#b87838' } } } };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif}h1,h2,h3{font-family:'Manrope',sans-serif}.field{transition:border-color .2s,box-shadow .2s}.field:focus{border-color:#07845b;box-shadow:0 0 0 4px rgba(7,132,91,.12);outline:0}.protocol-option:checked+span,input[name="gender"]:checked+span{border-color:#059669;background:#ecfdf5;box-shadow:0 0 0 4px rgba(16,185,129,.12)}.text-cyan-300,.text-cyan-200{color:#b87838!important}.bg-cyan-50{background-color:#ecfdf5!important}.border-cyan-100,.border-cyan-200{border-color:rgba(7,132,91,.25)!important}.fold-field[hidden]{display:none}</style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_90%_0%,_#e9f5ef_0,_transparent_28%),linear-gradient(135deg,#fbfcfa_0%,#eef7f2_100%)]">
        <nav class="border-b border-slate-200/80 bg-white/85 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8 lg:px-10">
                <a href="{{ route('assessments.index') }}" class="flex items-center gap-3" aria-label="Marluz Personal, início"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-ink text-xs font-extrabold text-bronze">MPC</span><span><span class="block font-[Manrope] text-lg font-extrabold tracking-tight text-ink">Marluz Personal</span><span class="block text-[9px] font-bold uppercase tracking-[.14em] text-ocean">Profilaxia e qualidade de vida</span></span></a>
                <div class="flex items-center gap-1 text-sm font-semibold text-slate-600"><a href="{{ route('assessments.index') }}" class="hidden rounded-lg px-3 py-2 transition hover:bg-emerald-50 hover:text-emerald-800 sm:block">Início</a><a href="{{ route('assessments.create') }}" class="rounded-lg bg-emerald-50 px-3 py-2 text-emerald-800">Nova avaliação</a><a href="{{ route('history') }}" class="rounded-lg px-3 py-2 transition hover:bg-emerald-50 hover:text-emerald-800">Histórico</a><a href="{{ route('assessments.evolution') }}" class="rounded-lg px-3 py-2 transition hover:bg-emerald-50 hover:text-emerald-800">Evolução</a></div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-5 py-10 sm:px-8 lg:px-10 lg:py-14">
            <div class="mb-9 max-w-2xl"><div class="mb-4 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[.16em] text-ocean"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Nova avaliação</div><h1 class="text-4xl font-extrabold tracking-[-.04em] text-ink sm:text-5xl">Registre uma nova leitura.</h1><p class="mt-4 text-base leading-7 text-slate-600">Preencha os dados com atenção. As sete medidas devem ser informadas em milímetros (mm).</p></div>

            @if ($errors->any())
                <div class="mb-7 rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-800" role="alert"><div class="flex gap-3"><svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"/></svg><div><p class="font-bold">Não foi possível salvar ainda</p><ul class="mt-2 list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div></div>
            @endif

            <form method="POST" action="{{ route('assessments.store') }}" class="space-y-7">
                @csrf
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/[.04] sm:p-8">
                    <div class="mb-7 flex items-start gap-4"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-bronze/10 text-sm font-extrabold text-bronze">00</span><div><h2 class="text-xl font-extrabold text-ink">Protocolo de avaliação</h2><p class="mt-1 text-sm text-slate-500">Escolha o número de pontos antes de informar as medidas.</p></div></div>
                    <fieldset>
                        <legend class="sr-only">Selecione o protocolo</legend>
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach ([3 => ['3 Dobras', 'Avaliação rápida'], 5 => ['5 Dobras', 'Leitura intermediária'], 7 => ['7 Dobras', 'Leitura completa']] as $protocol => $details)
                                <label class="cursor-pointer"><input type="radio" name="protocol" value="{{ $protocol }}" class="peer sr-only protocol-option" @checked(old('protocol', '7') == $protocol) required><span class="block rounded-2xl border border-slate-200 bg-slate-50 p-4 transition peer-checked:border-ocean peer-checked:bg-cyan-50 peer-checked:ring-4 peer-checked:ring-cyan-100 hover:border-cyan-300"><span class="flex items-center justify-between"><span class="text-base font-extrabold text-ink">{{ $details[0] }}</span><span class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 text-xs font-bold text-slate-400 peer-checked:border-ocean peer-checked:bg-ocean peer-checked:text-white">{{ $protocol }}</span></span><span class="mt-2 block text-xs text-slate-500">{{ $details[1] }}</span></span></label>
                            @endforeach
                        </div>
                    </fieldset>
                </section>
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/[.04] sm:p-8">
                    <div class="mb-8 flex items-start gap-4"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-sm font-extrabold text-emerald-700">01</span><div><h2 class="text-xl font-extrabold text-ink">Dados pessoais</h2><p class="mt-1 text-sm text-slate-500">Identifique o cliente da avaliação.</p></div></div>
                    <div class="grid gap-6 lg:grid-cols-[1.2fr_.6fr_1fr]">
                        <div><label for="name" class="mb-2 block text-sm font-bold text-slate-700">Nome completo</label><input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name" placeholder="Ex.: Marina Oliveira" class="field w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-ink placeholder:text-slate-400"></div>
                        <div><label for="age" class="mb-2 block text-sm font-bold text-slate-700">Idade</label><div class="relative"><input id="age" type="number" name="age" value="{{ old('age') }}" required min="10" max="120" placeholder="28" class="field w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 pr-14 text-ink"><span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-semibold text-slate-400">anos</span></div></div>
                        <fieldset><legend class="mb-2 block text-sm font-bold text-slate-700">Sexo</legend><div class="grid grid-cols-2 gap-3"><label class="cursor-pointer"><input type="radio" name="gender" value="male" class="peer sr-only" @checked(old('gender') === 'male') required><span class="flex items-center gap-3 rounded-xl border border-slate-300 bg-slate-50 px-3 py-3.5 text-sm font-semibold text-slate-600 transition peer-checked:border-ocean peer-checked:bg-cyan-50 peer-checked:text-ocean hover:border-cyan-300"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-700"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a5 5 0 100-10 5 5 0 000 10zm-7 7a7 7 0 0114 0"/></svg></span>Masculino</span></label><label class="cursor-pointer"><input type="radio" name="gender" value="female" class="peer sr-only" @checked(old('gender') === 'female')><span class="flex items-center gap-3 rounded-xl border border-slate-300 bg-slate-50 px-3 py-3.5 text-sm font-semibold text-slate-600 transition peer-checked:border-pink-400 peer-checked:bg-pink-50 peer-checked:text-pink-700 hover:border-pink-300"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-pink-100 text-pink-700"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a5 5 0 100-10 5 5 0 000 10zm0 0v7m-3 0h6"/></svg></span>Feminino</span></label></div></fieldset>
                    </div>
                    <div class="mt-6 grid gap-6 sm:grid-cols-2">
                        <div><label for="weight" class="mb-2 block text-sm font-bold text-slate-700">Peso</label><div class="relative"><input id="weight" type="number" name="weight" value="{{ old('weight') }}" required min="0.1" step="0.01" placeholder="70,00" class="field w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 pr-14 text-ink"><span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-semibold text-slate-400">kg</span></div></div>
                        <div><label for="height" class="mb-2 block text-sm font-bold text-slate-700">Altura</label><div class="relative"><input id="height" type="number" name="height" value="{{ old('height') }}" required min="0.1" step="0.01" placeholder="1,75" class="field w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 pr-14 text-ink"><span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-semibold text-slate-400">m</span></div></div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/[.04] sm:p-8">
                    <div class="mb-8 flex items-start gap-4"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-sm font-extrabold text-emerald-700">02</span><div><h2 id="fold-heading" class="text-xl font-extrabold text-ink">Medidas das 7 dobras cutâneas</h2><p class="mt-1 text-sm text-slate-500">Os pontos exibidos mudam conforme protocolo e sexo.</p></div></div>
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach (['triceps' => ['Tríceps', 'Parte posterior do braço'], 'chest' => ['Peitoral', 'Região do tórax'], 'midaxillary' => ['Axilar média', 'Linha lateral do tronco'], 'subscapular' => ['Subescapular', 'Abaixo da escápula'], 'suprailiac' => ['Suprailíaca', 'Acima do quadril'], 'abdominal' => ['Abdominal', 'Lateral do abdômen'], 'thigh' => ['Coxa', 'Região anterior da coxa']] as $field => $details)
                            <div class="fold-field rounded-2xl border border-slate-200 bg-slate-50/70 p-4" data-fold="{{ $field }}"><label for="{{ $field }}" class="block text-sm font-bold text-slate-700">{{ $details[0] }} <span class="font-normal text-slate-400">(mm)</span></label><p class="mt-1 text-xs text-slate-500">{{ $details[1] }}</p><div class="relative mt-3"><input id="{{ $field }}" type="number" name="{{ $field }}" value="{{ old($field) }}" min="0" step="0.1" placeholder="0,0" class="field w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 pr-12 text-ink"><span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-bold text-slate-400">mm</span></div></div>
                        @endforeach
                    </div>
                </section>

                @php
                    $perimetryPairs = [
                        ['braco_relaxado_d', 'braco_relaxado_e', 'Braço relaxado'],
                        ['braco_contraido_d', 'braco_contraido_e', 'Braço contraído'],
                        ['antebraco_d', 'antebraco_e', 'Antebraço'],
                        ['coxa_proximal_d', 'coxa_proximal_e', 'Coxa proximal'],
                        ['coxa_medial_d', 'coxa_medial_e', 'Coxa medial'],
                        ['panturrilha_d', 'panturrilha_e', 'Panturrilha'],
                    ];
                @endphp
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/[.04] sm:p-8">
                    <div class="mb-8 flex items-start gap-4"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-bronze/10 text-sm font-extrabold text-bronze">03</span><div><h2 class="text-xl font-extrabold text-ink">Perimetria corporal</h2><p class="mt-1 text-sm text-slate-500">Registre os perímetros em centímetros para acompanhar medidas, simetria e estimativa Navy.</p></div></div>
                    <div class="space-y-4">
                        <details class="group rounded-2xl border border-slate-200 bg-slate-50/70 p-4" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-base font-extrabold text-ink"><span>Tronco</span><span class="text-xl font-normal text-slate-400 transition group-open:rotate-45">+</span></summary>
                            <div class="mt-5 grid gap-5 sm:grid-cols-3">
                                @foreach (['torax' => 'Tórax', 'cintura' => 'Cintura', 'abdomen' => 'Abdômen'] as $field => $label)
                                    <div><label for="{{ $field }}" class="mb-2 block text-sm font-bold text-slate-700">{{ $label }}</label><div class="relative"><input id="{{ $field }}" type="number" name="{{ $field }}" value="{{ old($field) }}" min="0" step="0.1" placeholder="0,0" class="field w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 pr-12 text-ink"><span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-bold text-slate-400">cm</span></div></div>
                                @endforeach
                            </div>
                        </details>
                        <details class="group rounded-2xl border border-slate-200 bg-slate-50/70 p-4" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-base font-extrabold text-ink"><span>Membros superiores</span><span class="text-xl font-normal text-slate-400 transition group-open:rotate-45">+</span></summary>
                            <div class="mt-5 grid gap-4 lg:grid-cols-3">
                                @foreach (array_slice($perimetryPairs, 0, 3) as [$rightField, $leftField, $label])
                                    <div class="rounded-xl border border-slate-200 bg-white p-3"><p class="mb-3 text-sm font-bold text-slate-700">{{ $label }}</p><div class="grid grid-cols-2 gap-3"><div><label for="{{ $rightField }}" class="mb-1 block text-[11px] font-bold uppercase tracking-wide text-slate-400">Direito</label><input id="{{ $rightField }}" type="number" name="{{ $rightField }}" value="{{ old($rightField) }}" min="0" step="0.1" placeholder="0,0" class="field w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-ink"><span class="sr-only">cm</span></div><div><label for="{{ $leftField }}" class="mb-1 block text-[11px] font-bold uppercase tracking-wide text-slate-400">Esquerdo</label><input id="{{ $leftField }}" type="number" name="{{ $leftField }}" value="{{ old($leftField) }}" min="0" step="0.1" placeholder="0,0" class="field w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-ink"><span class="sr-only">cm</span></div></div></div>
                                @endforeach
                            </div>
                        </details>
                        <details class="group rounded-2xl border border-slate-200 bg-slate-50/70 p-4" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-base font-extrabold text-ink"><span>Membros inferiores</span><span class="text-xl font-normal text-slate-400 transition group-open:rotate-45">+</span></summary>
                            <div class="mt-5 grid gap-4 lg:grid-cols-3">
                                @foreach (array_slice($perimetryPairs, 3) as [$rightField, $leftField, $label])
                                    <div class="rounded-xl border border-slate-200 bg-white p-3"><p class="mb-3 text-sm font-bold text-slate-700">{{ $label }}</p><div class="grid grid-cols-2 gap-3"><div><label for="{{ $rightField }}" class="mb-1 block text-[11px] font-bold uppercase tracking-wide text-slate-400">Direito</label><input id="{{ $rightField }}" type="number" name="{{ $rightField }}" value="{{ old($rightField) }}" min="0" step="0.1" placeholder="0,0" class="field w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-ink"><span class="sr-only">cm</span></div><div><label for="{{ $leftField }}" class="mb-1 block text-[11px] font-bold uppercase tracking-wide text-slate-400">Esquerdo</label><input id="{{ $leftField }}" type="number" name="{{ $leftField }}" value="{{ old($leftField) }}" min="0" step="0.1" placeholder="0,0" class="field w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-ink"><span class="sr-only">cm</span></div></div></div>
                                @endforeach
                            </div>
                        </details>
                        <details class="group rounded-2xl border border-slate-200 bg-slate-50/70 p-4" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-base font-extrabold text-ink"><span>Referência e quadril</span><span class="text-xl font-normal text-slate-400 transition group-open:rotate-45">+</span></summary>
                            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                                @foreach (['quadril' => 'Quadril', 'pescoco' => 'Pescoço'] as $field => $label)
                                    <div><label for="{{ $field }}" class="mb-2 block text-sm font-bold text-slate-700">{{ $label }}</label><div class="relative"><input id="{{ $field }}" type="number" name="{{ $field }}" value="{{ old($field) }}" min="0" step="0.1" placeholder="0,0" class="field w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 pr-12 text-ink"><span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-bold text-slate-400">cm</span></div></div>
                                @endforeach
                            </div>
                        </details>
                    </div>
                </section>

                <div class="rounded-3xl border border-cyan-100 bg-cyan-50/70 p-5 sm:p-6"><div class="flex flex-col items-center justify-between gap-5 sm:flex-row"><div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-ocean shadow-sm"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-.658-.052-1.303-.152-1.933z"/></svg></span><p class="text-sm leading-6 text-slate-600">Revise as medidas antes de calcular.<br><strong class="text-slate-800">O resultado será salvo no histórico.</strong></p></div><button type="submit" class="inline-flex w-full items-center justify-center gap-3 rounded-xl bg-emerald-600 px-7 py-4 text-sm font-extrabold text-white shadow-lg shadow-emerald-900/15 transition duration-200 hover:scale-105 hover:bg-emerald-700 sm:w-auto">Calcular e salvar <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></button></div></div>
            </form>
        </main>
    </div>
    <script>
        const protocolFolds = {
            '3-male': ['chest', 'abdominal', 'thigh'],
            '3-female': ['triceps', 'suprailiac', 'thigh'],
            '5-male': ['triceps', 'subscapular', 'suprailiac', 'abdominal', 'thigh'],
            '5-female': ['triceps', 'subscapular', 'suprailiac', 'abdominal', 'thigh'],
            '7-male': ['triceps', 'chest', 'midaxillary', 'subscapular', 'abdominal', 'suprailiac', 'thigh'],
            '7-female': ['triceps', 'chest', 'midaxillary', 'subscapular', 'abdominal', 'suprailiac', 'thigh']
        };

        function updateFoldFields() {
            const protocol = document.querySelector('input[name="protocol"]:checked')?.value || '7';
            const gender = document.querySelector('input[name="gender"]:checked')?.value || 'male';
            const visibleFolds = protocolFolds[`${protocol}-${gender}`];

            document.querySelectorAll('.fold-field').forEach((field) => {
                const visible = visibleFolds.includes(field.dataset.fold);
                const input = field.querySelector('input');
                field.hidden = !visible;
                input.required = visible;
                if (!visible) input.value = '';
            });

            document.getElementById('fold-heading').textContent = `Medidas das ${protocol} dobras cutâneas`;
        }

        document.querySelectorAll('.protocol-option, input[name="gender"]').forEach((input) => input.addEventListener('change', updateFoldFields));
        updateFoldFields();
    </script>
</body>
</html>
