@csrf

<div class="grid gap-5 lg:grid-cols-2">
    <label class="block">
        <span class="text-sm font-medium text-slate-700">Tipo cliente</span>
        <select name="type" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
            @foreach (['company' => 'Azienda', 'person' => 'Persona'] as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $client->type ?: 'company') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
    </label>

    <label class="block">
        <span class="text-sm font-medium text-slate-700">Stato</span>
        <select name="status" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
            @foreach (['lead' => 'Lead', 'active' => 'Attivo', 'suspended' => 'Sospeso', 'former' => 'Ex cliente'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $client->status ?: 'lead') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
    </label>

    @foreach ([
        'company_name' => 'Nome azienda',
        'contact_name' => 'Referente',
        'email' => 'Email',
        'phone' => 'Telefono',
        'vat_number' => 'Partita IVA',
        'tax_code' => 'Codice fiscale',
        'pec' => 'PEC',
        'sdi' => 'SDI',
        'address' => 'Indirizzo',
        'city' => 'Citta',
        'province' => 'Provincia',
        'postal_code' => 'CAP',
    ] as $field => $label)
        <label class="block">
            <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
            <input name="{{ $field }}" value="{{ old($field, $client->{$field}) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
            @error($field) <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
        </label>
    @endforeach
</div>

<label class="mt-5 block">
    <span class="text-sm font-medium text-slate-700">Note interne</span>
    <textarea name="internal_notes" rows="5" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">{{ old('internal_notes', $client->internal_notes) }}</textarea>
    @error('internal_notes') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
</label>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('admin.clients.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annulla</a>
    <button class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Salva cliente</button>
</div>
