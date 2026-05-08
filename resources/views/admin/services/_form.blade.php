@csrf

<div class="grid gap-5 lg:grid-cols-2">
    <label class="block lg:col-span-2">
        <span class="text-sm font-medium text-slate-700">Nome servizio</span>
        <input name="name" value="{{ old('name', $service->name) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
        @error('name') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
    </label>

    <label class="block">
        <span class="text-sm font-medium text-slate-700">Prezzo base</span>
        <input name="base_price" type="number" step="0.01" min="0" value="{{ old('base_price', $service->base_price ?? 0) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
        @error('base_price') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
    </label>

    <label class="block">
        <span class="text-sm font-medium text-slate-700">Tipo prezzo</span>
        <select name="price_type" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
            @foreach (['one_time' => 'Una tantum', 'monthly' => 'Mensile', 'annual' => 'Annuale'] as $value => $label)
                <option value="{{ $value }}" @selected(old('price_type', $service->price_type ?: 'one_time') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('price_type') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
    </label>

    <label class="block">
        <span class="text-sm font-medium text-slate-700">IVA applicabile</span>
        <input name="vat_rate" type="number" step="0.01" min="0" max="100" value="{{ old('vat_rate', $service->vat_rate ?? 22) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">
        @error('vat_rate') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
    </label>

    <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3">
        <input name="is_active" type="checkbox" value="1" class="rounded border-slate-300 text-slate-950 focus:ring-slate-950" @checked(old('is_active', $service->is_active ?? true))>
        <span class="text-sm font-medium text-slate-700">Servizio attivo</span>
    </label>
</div>

<label class="mt-5 block">
    <span class="text-sm font-medium text-slate-700">Descrizione</span>
    <textarea name="description" rows="5" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950">{{ old('description', $service->description) }}</textarea>
    @error('description') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
</label>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('admin.services.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annulla</a>
    <button class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Salva servizio</button>
</div>
