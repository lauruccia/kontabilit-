<x-app-layout>
    <x-slot name="title">{{ $user->name }}</x-slot>
    <div class="space-y-6">
        <x-status-alert />
        <div class="flex items-center justify-between">
            <div><h2 class="text-xl font-semibold">{{ $user->name }}</h2><p class="text-sm text-slate-500">{{ $user->email }} · {{ $user->roles->pluck('name')->join(', ') }}</p></div>
            <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border px-4 py-2 text-sm">Modifica</a>
        </div>
        <section class="rounded-xl border border-[#d9e4df] bg-white p-6 text-sm">
            <dl class="grid gap-4 sm:grid-cols-2">
                <div><dt class="text-slate-500">Telefono</dt><dd class="font-medium">{{ $user->phone ?: '-' }}</dd></div>
                <div><dt class="text-slate-500">Ruolo operativo</dt><dd class="font-medium">{{ $user->job_title ?: '-' }}</dd></div>
                <div><dt class="text-slate-500">Cliente collegato</dt><dd class="font-medium">{{ $user->client?->displayName() ?: '-' }}</dd></div>
                <div><dt class="text-slate-500">Stato</dt><dd class="font-medium">{{ $user->is_active ? 'Attivo' : 'Disattivo' }}</dd></div>
            </dl>
        </section>
    </div>
</x-app-layout>
