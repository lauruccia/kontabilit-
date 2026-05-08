<x-app-layout>
    <x-slot name="title">{{ $service->name }}</x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-950">{{ $service->name }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $service->is_active ? 'Servizio attivo' : 'Servizio non attivo' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.services.edit', $service) }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Modifica</a>
                <form method="POST" action="{{ route('admin.services.destroy', $service) }}">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-lg border border-rose-200 px-4 py-2 text-sm font-medium text-rose-700 hover:bg-rose-50">Archivia</button>
                </form>
            </div>
        </div>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <dl class="grid gap-4 sm:grid-cols-3">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Prezzo base</dt>
                    <dd class="mt-1 text-lg font-semibold text-slate-950">€ {{ number_format((float) $service->base_price, 2, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo prezzo</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ ['one_time' => 'Una tantum', 'monthly' => 'Mensile', 'annual' => 'Annuale'][$service->price_type] ?? $service->price_type }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">IVA</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $service->vat_rate }}%</dd>
                </div>
            </dl>
            <div class="mt-6 border-t border-slate-100 pt-6">
                <h3 class="text-base font-semibold text-slate-950">Descrizione</h3>
                <p class="mt-3 whitespace-pre-line text-sm text-slate-700">{{ $service->description ?: 'Nessuna descrizione.' }}</p>
            </div>
        </section>
    </div>
</x-app-layout>
