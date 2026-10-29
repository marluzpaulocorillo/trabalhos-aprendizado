<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="description" content="Marluz Personal: profilaxia e qualidade de vida.">
    <title>Marluz Personal | Composição corporal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['DM Sans', 'ui-sans-serif', 'system-ui'] },
                    colors: { ink: '#0b1f33', ocean: '#07845b', bronze: '#b87838', mint: '#e9f5ef' }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        .soft-grid { background-image: linear-gradient(rgba(11,31,51,.045) 1px, transparent 1px), linear-gradient(90deg, rgba(11,31,51,.045) 1px, transparent 1px); background-size: 32px 32px; }
        .text-cyan-300, .text-cyan-200 { color: #b87838 !important; }
        .bg-cyan-100 { background-color: #e9f5ef !important; }
        .border-cyan-100, .border-cyan-200 { border-color: rgba(7,132,91,.25) !important; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_85%_8%,_#e9f5ef_0,_transparent_30%),linear-gradient(135deg,#fbfcfa_0%,#eef7f2_100%)]">
        <div class="soft-grid pointer-events-none absolute inset-0 opacity-60"></div>
        <nav class="relative z-10 border-b border-slate-200/80 bg-white/80 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8 lg:px-10">
                <a href="{{ route('assessments.index') }}" class="flex items-center gap-3" aria-label="Marluz Personal, início">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-ink text-xs font-extrabold text-bronze shadow-lg shadow-slate-900/10">MPC</span>
                    <span><span class="block font-[Manrope] text-lg font-extrabold tracking-tight text-ink">Marluz Personal</span><span class="block text-[9px] font-bold uppercase tracking-[.14em] text-ocean">Profilaxia e qualidade de vida</span></span>
                </a>
                <div class="hidden items-center gap-1 text-sm font-semibold text-slate-600 sm:flex">
                    <a href="{{ route('assessments.index') }}" class="rounded-lg bg-emerald-50 px-4 py-2 text-emerald-800">Início</a>
                    <a href="{{ route('assessments.create') }}" class="rounded-lg px-4 py-2 transition hover:bg-emerald-50 hover:text-emerald-800">Nova avaliação</a>
                    <a href="{{ route('history') }}" class="rounded-lg px-4 py-2 transition hover:bg-emerald-50 hover:text-emerald-800">Histórico</a>
                </div>
                <a href="{{ route('assessments.create') }}" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-900/20 transition duration-200 hover:scale-105 hover:bg-emerald-700 sm:hidden">Nova avaliação</a>
            </div>
        </nav>

        <main class="relative z-10 mx-auto grid max-w-7xl items-center gap-12 px-5 py-12 sm:px-8 lg:grid-cols-[1.08fr_.92fr] lg:px-10 lg:py-20">
            <section class="max-w-2xl">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-bronze/30 bg-bronze/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[.16em] text-bronze shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Inteligência em composição corporal
                </div>
                <h1 class="max-w-3xl text-4xl font-extrabold leading-[1.06] tracking-[-.045em] text-ink sm:text-6xl lg:text-7xl">Avaliações precisas para decisões mais <span class="text-ocean">inteligentes.</span></h1>
                <p class="mt-7 max-w-xl text-lg leading-8 text-slate-600">Centralize suas avaliações físicas, calcule o percentual de gordura e acompanhe cada evolução com clareza clínica.</p>
                <div class="mt-9 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('assessments.create') }}" class="group flex items-center justify-between rounded-2xl bg-ink px-5 py-5 text-white shadow-xl shadow-ink/15 transition duration-200 hover:scale-105 hover:bg-slate-800">
                        <span><span class="block text-xs font-semibold uppercase tracking-wider text-bronze">Começar agora</span><span class="mt-1 block text-base font-bold">Nova avaliação</span></span>
                        <svg class="h-6 w-6 text-bronze transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('history') }}" class="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white/85 px-5 py-5 text-ink shadow-lg shadow-slate-900/5 transition duration-200 hover:scale-105 hover:border-emerald-500">
                        <span><span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Acompanhe</span><span class="mt-1 block text-base font-bold">Histórico de clientes</span></span>
                        <svg class="h-6 w-6 text-ocean transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="mt-10 flex items-center gap-4 text-sm text-slate-500"><span class="flex -space-x-2"><span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-cyan-100 text-xs font-bold text-ocean">%</span><span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-emerald-100 text-xs font-bold text-emerald-700">7</span><span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-slate-200 text-xs font-bold text-ink">+</span></span><span>Protocolo Jackson &amp; Pollock<br><strong class="text-slate-700">7 pontos de medição</strong></span></div>
            </section>

            <section class="relative" aria-label="Resumo da avaliação">
                <div class="absolute -right-5 -top-5 h-24 w-24 rounded-full border-8 border-bronze/30"></div>
                <div class="relative rounded-[2rem] border border-white bg-ink p-6 text-white shadow-2xl shadow-ink/20 sm:p-9">
                    <div class="flex items-start justify-between border-b border-white/10 pb-7"><div><p class="text-xs font-bold uppercase tracking-[.18em] text-cyan-200">Visão geral</p><p class="mt-3 text-3xl font-extrabold tracking-tight">Uma leitura completa.</p></div><div class="rounded-xl bg-white/10 p-3 text-cyan-300"><svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5m0 14h16M8 16v-5m4 5V7m4 9v-8"/></svg></div></div>
                    <div class="grid grid-cols-2 gap-3 py-7 sm:grid-cols-4"><div class="rounded-xl bg-white/10 p-4"><p class="text-2xl font-extrabold text-cyan-300">07</p><p class="mt-1 text-xs text-slate-300">dobras</p></div><div class="rounded-xl bg-white/10 p-4"><p class="text-2xl font-extrabold text-emerald-300">02</p><p class="mt-1 text-xs text-slate-300">fórmulas</p></div><div class="rounded-xl bg-white/10 p-4"><p class="text-2xl font-extrabold text-amber-300">01</p><p class="mt-1 text-xs text-slate-300">histórico</p></div><div class="rounded-xl bg-white/10 p-4"><p class="text-2xl font-extrabold text-white">∞</p><p class="mt-1 text-xs text-slate-300">evolução</p></div></div>
                    <div class="flex items-center gap-3 rounded-xl border border-emerald-300/20 bg-emerald-300/10 p-4"><span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-300/20 text-emerald-300"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span><div><p class="text-sm font-bold">Dados organizados</p><p class="text-xs text-slate-300">Prontos para orientar sua próxima decisão.</p></div></div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
