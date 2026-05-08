<x-app-layout>
    <x-slot name="title">{{ $client->displayName() }}</x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-950">{{ $client->displayName() }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $client->email }} · {{ ucfirst($client->status) }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.clients.edit', $client) }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Modifica</a>
                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-lg border border-rose-200 px-4 py-2 text-sm font-medium text-rose-700 hover:bg-rose-50">Archivia</button>
                </form>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                <h3 class="text-base font-semibold text-slate-950">Dati cliente</h3>
                <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        'Referente' => $client->contact_name,
                        'Telefono' => $client->phone,
                        'Partita IVA' => $client->vat_number,
                        'Codice fiscale' => $client->tax_code,
                        'PEC' => $client->pec,
                        'SDI' => $client->sdi,
                        'Indirizzo' => trim(collect([$client->address, $client->postal_code, $client->city, $client->province])->filter()->join(' ')),
                        'Tipo' => $client->type === 'company' ? 'Azienda' : 'Persona',
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</dt>
                            <dd class="mt-1 text-sm text-slate-800">{{ $value ?: '-' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>

            <aside class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-950">Storico collegato</h3>
                <div class="mt-5 space-y-3 text-sm text-slate-600">
                    <div class="rounded-lg bg-slate-50 p-3">Contratti: disponibile dallo STEP 4</div>
                    <div class="rounded-lg bg-slate-50 p-3">Pagamenti: disponibile dallo STEP 7</div>
                    <div class="rounded-lg bg-slate-50 p-3">Documenti: disponibile dallo STEP 9</div>
                </div>
            </aside>
        </div>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-950">Note interne</h3>
            <p class="mt-3 whitespace-pre-line text-sm text-slate-700">{{ $client->internal_notes ?: 'Nessuna nota interna.' }}</p>
        </section>
    </div>
</x-app-layout>
