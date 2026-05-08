<x-app-layout>
    <x-slot name="title">Servizi</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-950">Servizi</h2>
                <p class="mt-1 text-sm text-slate-500">Catalogo configurabile per preventivi e contratti.</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Nuovo servizio</a>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
        @endif

        <form class="grid gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-[1fr_180px_auto]">
            <input name="search" value="{{ request('search') }}" placeholder="Cerca servizio" class="rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
            <select name="active" class="rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
                <option value="">Tutti</option>
                <option value="1" @selected(request('active') === '1')>Attivi</option>
                <option value="0" @selected(request('active') === '0')>Non attivi</option>
            </select>
            <button class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Filtra</button>
        </form>

        <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
            @forelse ($services as $service)
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-slate-950">{{ $service->name }}</h3>
                            <p class="mt-2 line-clamp-3 text-sm text-slate-500">{{ $service->description ?: 'Nessuna descrizione.' }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $service->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $service->is_active ? 'Attivo' : 'Non attivo' }}
                        </span>
                    </div>
                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                        <div>
                            <div class="text-lg font-semibold text-slate-950">€ {{ number_format((float) $service->base_price, 2, ',', '.') }}</div>
                            <div class="text-xs uppercase tracking-wide text-slate-500">{{ str_replace('_', ' ', $service->price_type) }} · IVA {{ $service->vat_rate }}%</div>
                        </div>
                        <a href="{{ route('admin.services.show', $service) }}" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Apri</a>
                    </div>
                </article>
            @empty
                <div class="rounded-lg border border-slate-200 bg-white p-10 text-center text-sm text-slate-500 lg:col-span-2 xl:col-span-3">Nessun servizio configurato.</div>
            @endforelse
        </div>

        {{ $services->links() }}
    </div>
</x-app-layout>
