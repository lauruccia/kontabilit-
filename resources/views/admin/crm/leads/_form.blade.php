@csrf
<div class="grid gap-5 lg:grid-cols-3">
    <label class="block">
        <span class="text-sm font-medium text-slate-700">Cliente collegato</span>
        <select name="client_id" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
            <option value="">Nessuno</option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $lead->client_id) == $client->id)>{{ $client->displayName() }}</option>
            @endforeach
        </select>
    </label>

    <label class="block">
        <span class="text-sm font-medium text-slate-700">Assegnato a</span>
        <select name="assigned_to" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
            <option value="">Non assegnato</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected(old('assigned_to', $lead->assigned_to) == $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
    </label>

    <label class="block">
        <span class="text-sm font-medium text-slate-700">Priorita</span>
        <select name="priority" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
            @foreach (['low' => 'Bassa', 'medium' => 'Media', 'high' => 'Alta', 'urgent' => 'Urgente'] as $value => $label)
                <option value="{{ $value }}" @selected(old('priority', $lead->priority ?: 'medium') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    @foreach (['company_name' => 'Azienda', 'contact_name' => 'Referente', 'email' => 'Email', 'phone' => 'Telefono', 'source' => 'Fonte'] as $field => $label)
        <label class="block">
            <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
            <input name="{{ $field }}" value="{{ old($field, $lead->{$field}) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
        </label>
    @endforeach

    <label class="block">
        <span class="text-sm font-medium text-slate-700">Stato</span>
        <select name="status" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
            @foreach (['new','contacted','qualified','proposal','won','lost'] as $status)
                <option value="{{ $status }}" @selected(old('status', $lead->status ?: 'new') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </label>

    <label class="block">
        <span class="text-sm font-medium text-slate-700">Valore stimato</span>
        <input name="estimated_value" value="{{ old('estimated_value', $lead->estimated_value ?? 0) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
    </label>

    <label class="block">
        <span class="text-sm font-medium text-slate-700">Prossimo follow-up</span>
        <input type="date" name="next_follow_up_at" value="{{ old('next_follow_up_at', optional($lead->next_follow_up_at)->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
    </label>
</div>

<label class="mt-5 block">
    <span class="text-sm font-medium text-slate-700">Note CRM</span>
    <textarea name="notes" rows="5" class="mt-1 w-full rounded-lg border-slate-300 text-sm">{{ old('notes', $lead->notes) }}</textarea>
</label>

<section class="mt-6 rounded-xl border border-[#d9e4df] bg-[#f3f7f5] p-5">
    <h3 class="text-sm font-semibold text-slate-950">Aggiungi attività follow-up</h3>
    <div class="mt-4 grid gap-4 lg:grid-cols-4">
        <input name="activity_title" placeholder="Titolo attività" class="rounded-lg border-slate-300 text-sm">
        <select name="activity_type" class="rounded-lg border-slate-300 text-sm">
            <option value="call">Telefonata</option>
            <option value="email">Email</option>
            <option value="meeting">Meeting</option>
            <option value="task">Task</option>
        </select>
        <input type="datetime-local" name="activity_due_at" class="rounded-lg border-slate-300 text-sm">
        <input name="activity_notes" placeholder="Note rapide" class="rounded-lg border-slate-300 text-sm">
    </div>
</section>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.crm.leads.index') }}" class="rounded-lg border px-4 py-2 text-sm">Annulla</a>
    <button class="rounded-lg bg-[#071111] px-4 py-2 text-sm font-semibold text-white">Salva lead</button>
</div>
