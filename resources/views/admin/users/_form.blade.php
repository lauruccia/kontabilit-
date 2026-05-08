@csrf
<div class="grid gap-5 lg:grid-cols-2">
    <label class="block"><span class="text-sm font-medium">Nome</span><input name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></label>
    <label class="block"><span class="text-sm font-medium">Email</span><input name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></label>
    <label class="block"><span class="text-sm font-medium">Telefono</span><input name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></label>
    <label class="block"><span class="text-sm font-medium">Ruolo operativo</span><input name="job_title" value="{{ old('job_title', $user->job_title) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></label>
    <label class="block"><span class="text-sm font-medium">Cliente collegato</span><select name="client_id" class="mt-1 w-full rounded-lg border-slate-300 text-sm"><option value="">Nessuno</option>@foreach($clients as $client)<option value="{{ $client->id }}" @selected(old('client_id', $user->client_id)==$client->id)>{{ $client->displayName() }}</option>@endforeach</select></label>
    <label class="flex items-center gap-2 pt-7"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true)) class="rounded border-slate-300"> Utente attivo</label>
    <label class="block"><span class="text-sm font-medium">Password</span><input type="password" name="password" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></label>
    <label class="block"><span class="text-sm font-medium">Conferma password</span><input type="password" name="password_confirmation" class="mt-1 w-full rounded-lg border-slate-300 text-sm"></label>
</div>

<section class="mt-6 rounded-xl border border-[#d9e4df] bg-[#f3f7f5] p-5">
    <h3 class="text-sm font-semibold text-slate-950">Ruoli</h3>
    <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($roles as $role)
            <label class="flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-sm">
                <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked(in_array($role->name, old('roles', $user->exists ? $user->roles->pluck('name')->all() : []), true)) class="rounded border-slate-300">
                {{ $role->name }}
            </label>
        @endforeach
    </div>
</section>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.users.index') }}" class="rounded-lg border px-4 py-2 text-sm">Annulla</a>
    <button class="rounded-lg bg-[#071111] px-4 py-2 text-sm font-semibold text-white">Salva utente</button>
</div>
