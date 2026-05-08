<x-app-layout>
    <x-slot name="title">CRM</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-950">CRM commerciale</h2>
                <p class="mt-1 text-sm text-slate-500">Lead, opportunita, assegnazioni e follow-up del team Kosmos.</p>
            </div>
            <a href="{{ route('admin.crm.leads.create') }}" class="rounded-lg bg-[#071111] px-4 py-2 text-sm font-semibold text-white hover:bg-black">Nuovo lead</a>
        </div>

        <x-status-alert />

        <section class="grid gap-4 md:grid-cols-3 xl:grid-cols-6">
            @foreach (['new' => 'Nuovi', 'contacted' => 'Contattati', 'qualified' => 'Qualificati', 'proposal' => 'Proposta', 'won' => 'Vinti', 'lost' => 'Persi'] as $key => $label)
                @php $row = $pipeline[$key] ?? null; @endphp
                <article class="rounded-xl border border-[#d9e4df] bg-white p-4 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-[#486b75]">{{ $label }}</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $row->count ?? 0 }}</p>
                    <p class="mt-1 text-xs text-slate-500">€ {{ number_format((float) ($row->value ?? 0), 0, ',', '.') }}</p>
                </article>
            @endforeach
        </section>

        <form class="grid gap-3 rounded-xl border border-[#d9e4df] bg-white p-4 shadow-sm md:grid-cols-[1fr_180px_auto]">
            <input name="search" value="{{ request('search') }}" placeholder="Cerca azienda, referente o email" class="rounded-lg border-slate-300 text-sm">
            <select name="status" class="rounded-lg border-slate-300 text-sm">
                <option value="">Tutti gli stati</option>
                @foreach (['new','contacted','qualified','proposal','won','lost'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium">Filtra</button>
        </form>

        <div class="overflow-hidden rounded-xl border border-[#d9e4df] bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-[#f3f7f5] text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Lead</th>
                        <th class="px-5 py-3">Assegnato</th>
                        <th class="px-5 py-3">Valore</th>
                        <th class="px-5 py-3">Follow-up</th>
                        <th class="px-5 py-3">Stato</th>
                        <th class="px-5 py-3 text-right">Azioni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($leads as $lead)
                        <tr>
                            <td class="px-5 py-4"><div class="font-medium text-slate-950">{{ $lead->displayName() }}</div><div class="text-slate-500">{{ $lead->contact_name }} · {{ $lead->email ?: '-' }}</div></td>
                            <td class="px-5 py-4 text-slate-600">{{ $lead->assignee?->name ?: 'Non assegnato' }}</td>
                            <td class="px-5 py-4 font-semibold text-[#486b75]">€ {{ number_format((float) $lead->estimated_value, 2, ',', '.') }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ optional($lead->next_follow_up_at)->format('d/m/Y') ?: '-' }}</td>
                            <td class="px-5 py-4"><span class="rounded-full bg-[#edf4ef] px-2.5 py-1 text-xs font-medium text-[#456047]">{{ $lead->status }}</span></td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('admin.crm.leads.show', $lead) }}" class="font-medium underline">Apri</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">Nessun lead CRM.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $leads->links() }}
    </div>
</x-app-layout>
