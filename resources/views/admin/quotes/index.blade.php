<x-app-layout>
    <x-slot name="title">Preventivi</x-slot>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div><h2 class="text-xl font-semibold text-slate-950">Preventivi</h2><p class="text-sm text-slate-500">Bozze, invii, accettazioni e PDF.</p></div>
            <a class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white" href="{{ route('admin.quotes.create') }}">Nuovo preventivo</a>
        </div>
        <x-status-alert />
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-5 py-3">Numero</th><th class="px-5 py-3">Cliente</th><th class="px-5 py-3">Totale</th><th class="px-5 py-3">Stato</th><th class="px-5 py-3 text-right">Azioni</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($quotes as $quote)
                        <tr><td class="px-5 py-4 font-medium">{{ $quote->number }}</td><td class="px-5 py-4">{{ $quote->client->displayName() }}</td><td class="px-5 py-4">€ {{ number_format((float) $quote->total, 2, ',', '.') }}</td><td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-2 py-1 text-xs">{{ $quote->status }}</span></td><td class="px-5 py-4 text-right"><a class="font-medium text-slate-950 hover:underline" href="{{ route('admin.quotes.show', $quote) }}">Apri</a></td></tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">Nessun preventivo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $quotes->links() }}
    </div>
</x-app-layout>
