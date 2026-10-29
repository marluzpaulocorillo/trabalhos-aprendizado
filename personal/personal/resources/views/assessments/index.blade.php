<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>Histórico de clientes | Avalia</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-[#f5f7f4] text-[#153c3d] antialiased">
    <div class="min-h-screen bg-[linear-gradient(120deg,_#f5f7f4_0%,_#edf4f1_60%,_#f8f3e7_100%)]">
        <header class="mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-10">
            <a href="{{ route('assessments.index') }}" class="flex items-center gap-3" aria-label="Voltar para a página inicial">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#153c3d] text-lg font-bold text-[#f6c96a]">A</span>
                <span class="text-lg font-semibold tracking-tight">avalia<span class="text-[#d79224]">.</span></span>
            </a>
            <a href="{{ route('assessments.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#153c3d] px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-[#153c3d]/15 transition hover:bg-[#1d5050]">
                <span class="text-lg leading-none">+</span>
                Nova avaliação
            </a>
        </header>

        <main class="mx-auto max-w-7xl px-6 pb-16 pt-8 lg:px-10 lg:pt-14">
            <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#b06f16]">Acompanhamento</p>
                    <h1 class="mt-3 text-4xl font-semibold tracking-[-0.04em] text-[#153c3d] sm:text-5xl">Histórico de clientes</h1>
                    <p class="mt-4 text-base leading-7 text-[#557273]">Consulte as avaliações registradas e acompanhe a evolução corporal.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="rounded-2xl border border-[#d9e5e1] bg-white/75 px-5 py-4 text-left shadow-sm sm:min-w-40">
                        <p class="text-xs font-bold uppercase tracking-wider text-[#78908f]">Avaliações</p>
                        <p class="mt-1 text-2xl font-semibold text-[#153c3d]">{{ $assessments->count() }}</p>
                    </div>
                    <button type="button" id="open-delete-modal" class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-3 text-sm font-bold text-red-600 shadow-sm transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50" @disabled($assessments->isEmpty())>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Excluir avaliação
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div class="mt-8 flex items-center gap-3 rounded-2xl border border-[#b9ddd0] bg-[#e8f6ef] px-5 py-4 text-sm font-semibold text-[#21634e]" role="status">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#b9ddd0]">&#10003;</span>
                    {{ session('success') }}
                </div>
            @endif

            <section class="mt-8 overflow-hidden rounded-[1.75rem] border border-[#d9e5e1] bg-white/90 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[980px] text-left text-sm">
                        <thead class="border-b border-[#d9e5e1] bg-[#f2f7f5] text-[11px] uppercase tracking-[0.12em] text-[#6b8584]">
                            <tr>
                                <th scope="col" class="px-6 py-5 font-bold">Cliente</th>
                                <th scope="col" class="px-4 py-5 font-bold">Sexo</th>
                                <th scope="col" class="px-4 py-5 font-bold">Tríceps</th>
                                <th scope="col" class="px-4 py-5 font-bold">Peitoral</th>
                                <th scope="col" class="px-4 py-5 font-bold">Axilar média</th>
                                <th scope="col" class="px-4 py-5 font-bold">Subescapular</th>
                                <th scope="col" class="px-4 py-5 font-bold">Suprailíaca</th>
                                <th scope="col" class="px-4 py-5 font-bold">Abdominal</th>
                                <th scope="col" class="px-4 py-5 font-bold">Coxa</th>
                                <th scope="col" class="px-6 py-5 text-right font-bold">Gordura</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e5eeeb]">
                            @forelse ($assessments as $assessment)
                                <tr class="transition hover:bg-[#f8fbf9]">
                                    <td class="whitespace-nowrap px-6 py-5">
                                        <p class="font-bold text-[#153c3d]">{{ $assessment->name }}</p>
                                        <p class="mt-1 text-xs text-[#78908f]">{{ $assessment->age }} anos · {{ $assessment->created_at->format('d/m/Y') }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-5 text-[#476567]">{{ $assessment->gender === 'male' ? 'Masculino' : 'Feminino' }}</td>
                                    @foreach (['triceps', 'chest', 'midaxillary', 'subscapular', 'suprailiac', 'abdominal', 'thigh'] as $fold)
                                        <td class="whitespace-nowrap px-4 py-5 text-[#476567]">{{ number_format($assessment->{$fold}, 1, ',', '.') }} <span class="text-xs text-[#9aafad]">mm</span></td>
                                    @endforeach
                                    <td class="whitespace-nowrap px-6 py-5 text-right">
                                        <span class="inline-flex rounded-full bg-[#fff0cc] px-3 py-1.5 font-bold text-[#9a6515]">{{ number_format($assessment->body_fat_percentage, 2, ',', '.') }}%</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-20 text-center">
                                        <div class="mx-auto max-w-sm">
                                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#e7f1ee] text-2xl text-[#1c6b67]">+</span>
                                            <h2 class="mt-5 text-lg font-semibold text-[#153c3d]">Nenhuma avaliação ainda</h2>
                                            <p class="mt-2 text-sm leading-6 text-[#78908f]">Cadastre o primeiro cliente para começar seu histórico.</p>
                                            <a href="{{ route('assessments.create') }}" class="mt-6 inline-flex rounded-xl bg-[#153c3d] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#1d5050]">Cadastrar avaliação</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <p class="mt-5 text-xs text-[#78908f]">Medidas exibidas em milímetros. O percentual de gordura é uma estimativa calculada pelo protocolo configurado.</p>
        </main>
    </div>

    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[.16em] text-red-500">Ação irreversível</p>
                    <h2 id="delete-modal-title" class="mt-2 text-2xl font-extrabold text-[#153c3d]">Qual avaliação deseja excluir?</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Selecione um registro. Os dados apagados não poderão ser recuperados.</p>
                </div>
                <button type="button" id="close-delete-modal" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Fechar janela">&times;</button>
            </div>

            <div class="mt-6 max-h-72 space-y-2 overflow-y-auto pr-1">
                @foreach ($assessments as $assessment)
                    <label class="block cursor-pointer">
                        <input type="radio" name="assessment_to_delete" value="{{ $assessment->id }}" data-delete-url="{{ route('assessments.destroy', $assessment) }}" data-assessment-name="{{ $assessment->name }}" class="peer sr-only">
                        <span class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition peer-checked:border-red-300 peer-checked:bg-red-50 hover:border-slate-300">
                            <span><strong class="block text-sm text-[#153c3d]">{{ $assessment->name }}</strong><small class="text-xs text-slate-500">{{ $assessment->age }} anos · {{ $assessment->created_at->format('d/m/Y H:i') }}</small></span>
                            <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-bold text-slate-600 peer-checked:bg-red-100 peer-checked:text-red-700">{{ $assessment->protocol }} dobras</span>
                        </span>
                    </label>
                @endforeach
            </div>

            <div id="delete-confirmation" class="mt-5 hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <p>Tem certeza que deseja excluir <strong id="selected-assessment-name"></strong>?</p>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancel-delete" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Não, manter</button>
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
        document.querySelectorAll('input[name="assessment_to_delete"]').forEach((input) => input.addEventListener('change', () => {
            deleteForm.action = input.dataset.deleteUrl;
            selectedName.textContent = input.dataset.assessmentName;
            confirmation.classList.remove('hidden');
            confirmDelete.disabled = false;
        }));
    </script>
</body>
</html>
