@csrf
<div class="grid gap-5 lg:grid-cols-4">
    <label class="block lg:col-span-2"><span class="text-sm font-medium">Cliente</span><select name="client_id" class="mt-1 w-full rounded-lg border-slate-300">@foreach($clients as $client)<option value="{{ $client->id }}" @selected(old('client_id', $quote->client_id)==$client->id)>{{ $client->displayName() }}</option>@endforeach</select></label>
    <label class="block"><span class="text-sm font-medium">Numero</span><input name="number" value="{{ old('number', $quote->number) }}" class="mt-1 w-full rounded-lg border-slate-300"></label>
    <label class="block"><span class="text-sm font-medium">Stato</span><select name="status" class="mt-1 w-full rounded-lg border-slate-300">@foreach(['draft','sent','accepted','rejected','expired'] as $status)<option value="{{ $status }}" @selected(old('status', $quote->status ?: 'draft')===$status)>{{ $status }}</option>@endforeach</select></label>
    <label class="block"><span class="text-sm font-medium">Emissione</span><input type="date" name="issued_at" value="{{ old('issued_at', optional($quote->issued_at)->format('Y-m-d') ?: now()->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-slate-300"></label>
    <label class="block"><span class="text-sm font-medium">Validita</span><input type="date" name="valid_until" value="{{ old('valid_until', optional($quote->valid_until)->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-slate-300"></label>
</div>
<div class="mt-6 rounded-lg border border-slate-200">
    <div class="grid grid-cols-12 gap-3 border-b border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold uppercase text-slate-500"><div class="col-span-4">Descrizione</div><div class="col-span-2">Servizio</div><div>Qta</div><div>Prezzo</div><div>Sconto</div><div>IVA</div><div class="col-span-2">Tipo</div></div>
    @for($i=0; $i<5; $i++)
        @php $item = old("items.$i", $quote->items[$i] ?? null); @endphp
        <div class="grid grid-cols-12 gap-3 px-4 py-3">
            <input name="items[{{ $i }}][description]" value="{{ data_get($item,'description') }}" class="col-span-4 rounded-lg border-slate-300 text-sm" placeholder="Descrizione riga">
            <select name="items[{{ $i }}][agency_service_id]" class="col-span-2 rounded-lg border-slate-300 text-sm"><option value="">-</option>@foreach($services as $service)<option value="{{ $service->id }}" @selected(data_get($item,'agency_service_id')==$service->id)>{{ $service->name }}</option>@endforeach</select>
            <input name="items[{{ $i }}][quantity]" value="{{ data_get($item,'quantity', $i===0 ? 1 : '') }}" class="rounded-lg border-slate-300 text-sm">
            <input name="items[{{ $i }}][unit_price]" value="{{ data_get($item,'unit_price', $i===0 ? 0 : '') }}" class="rounded-lg border-slate-300 text-sm">
            <input name="items[{{ $i }}][discount]" value="{{ data_get($item,'discount', 0) }}" class="rounded-lg border-slate-300 text-sm">
            <input name="items[{{ $i }}][vat_rate]" value="{{ data_get($item,'vat_rate', 22) }}" class="rounded-lg border-slate-300 text-sm">
            <div class="col-span-2 text-xs text-slate-500">Compila solo righe usate</div>
        </div>
    @endfor
</div>
<label class="mt-5 block"><span class="text-sm font-medium">Note</span><textarea name="notes" rows="4" class="mt-1 w-full rounded-lg border-slate-300">{{ old('notes', $quote->notes) }}</textarea></label>
<div class="mt-6 flex justify-end gap-3"><a href="{{ route('admin.quotes.index') }}" class="rounded-lg border px-4 py-2 text-sm">Annulla</a><button class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white">Salva</button></div>
