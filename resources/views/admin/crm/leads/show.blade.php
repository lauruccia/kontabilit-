<x-app-layout>
    <x-slot name="title">{{ $lead->displayName() }}</x-slot>
    <div class="space-y-6">
        <x-status-alert />
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-950">{{ $lead->displayName() }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $lead->contact_name }} · {{ $lead->status }} · {{ $lead->assignee?->name ?: 'Non assegnato' }}</p>
            </div>
            <a href="{{ route('admin.crm.leads.edit', $lead) }}" class="rounded-lg border px-4 py-2 text-sm font-medium">Modifica</a>
        </div>

        <section class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-xl border border-[#d9e4df] bg-white p-6 lg:col-span-2">
                <h3 class="font-semibold">Dettagli lead</h3>
                <dl class="mt-5 grid gap-4 sm:grid-cols-2 text-sm">
                    <div><dt class="text-slate-500">Email</dt><dd class="font-medium">{{ $lead->email ?: '-' }}</dd></div>
                    <div><dt class="text-slate-500">Telefono</dt><dd class="font-medium">{{ $lead->phone ?: '-' }}</dd></div>
                    <div><dt class="text-slate-500">Fonte</dt><dd class="font-medium">{{ $lead->source ?: '-' }}</dd></div>
                    <div><dt class="text-slate-500">Valore</dt><dd class="font-medium">€ {{ number_format((float) $lead->estimated_value, 2, ',', '.') }}</dd></div>
                </dl>
                <p class="mt-6 whitespace-pre-line text-sm text-slate-700">{{ $lead->notes ?: 'Nessuna nota.' }}</p>
            </div>

            <div class="rounded-xl border border-[#d9e4df] bg-white p-6">
                <h3 class="font-semibold">Opportunità</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($lead->opportunities as $opportunity)
                        <div class="rounded-lg bg-[#f3f7f5] p-3 text-sm">{{ $opportunity->title }} · € {{ number_format((float) $opportunity->amount, 0, ',', '.') }}</div>
                    @empty
                        <p class="text-sm text-slate-500">Nessuna opportunità.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-[#d9e4df] bg-white p-6">
            <h3 class="font-semibold">Attività</h3>
            <div class="mt-4 space-y-3">
                @forelse ($lead->activities as $activity)
                    <div class="flex items-center justify-between rounded-lg bg-[#f3f7f5] p-3 text-sm">
                        <span><span class="font-medium">{{ $activity->title }}</span><span class="text-slate-500"> · {{ $activity->type }} · {{ optional($activity->due_at)->format('d/m/Y H:i') ?: '-' }}</span></span>
                        @if ($activity->status !== 'completed')
                            <form method="POST" action="{{ route('admin.crm.activities.complete', $activity) }}">@csrf<button class="rounded-lg border px-3 py-1.5 text-xs">Completa</button></form>
                        @else
                            <span class="rounded-full bg-[#e8f3e8] px-2 py-1 text-xs text-[#456047]">completata</span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Nessuna attività.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
