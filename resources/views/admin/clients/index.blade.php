<x-app-layout>
    <x-slot name="title">Clienti</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-950">Clienti</h2>
                <p class="mt-1 text-sm text-slate-500">Anagrafica clienti, dati fiscali e stato commerciale.</p>
            </div>
            <a href="{{ route('admin.clients.create') }}" class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Nuovo cliente</a>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
        @endif

        <form class="grid gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-[1fr_180px_auto]">
            <input name="search" value="{{ request('search') }}" placeholder="Cerca per azienda, referente, email o P.IVA" class="rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
            <select name="status" class="rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
                <option value="">Tutti gli stati</option>
                @foreach (['lead' => 'Lead', 'active' => 'Attivo', 'suspended' => 'Sospeso', 'former' => 'Ex cliente'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Filtra</button>
        </form>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Cliente</th>
                        <th class="px-5 py-3">Contatti</th>
                        <th class="px-5 py-3">Fiscale</th>
                        <th class="px-5 py-3">Stato</th>
                        <th class="px-5 py-3 text-right">Azioni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($clients as $client)
                        <tr>
                            <td class="px-5 py-4">
                                <div class="font-medium text-slate-950">{{ $client->displayName() }}</div>
                                <div class="text-slate-500">{{ $client->contact_name }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                <div>{{ $client->email }}</div>
                                <div>{{ $client->phone ?: 'Telefono non indicato' }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                <div>P.IVA: {{ $client->vat_number ?: '-' }}</div>
                                <div>CF: {{ $client->tax_code ?: '-' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">{{ ucfirst($client->status) }}</span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.clients.show', $client) }}" class="font-medium text-slate-950 hover:underline">Apri</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">Nessun cliente registrato.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $clients->links() }}
    </div>
</x-app-layout>
