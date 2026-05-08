<x-app-layout>
    <x-slot name="title">Utenti e ruoli</x-slot>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-950">Utenti e ruoli</h2>
                <p class="mt-1 text-sm text-slate-500">Superadmin, admin, segreteria, agenti, operatori e clienti.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="rounded-lg bg-[#071111] px-4 py-2 text-sm font-semibold text-white">Nuovo utente</a>
        </div>
        <x-status-alert />
        <div class="overflow-hidden rounded-xl border border-[#d9e4df] bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-[#f3f7f5] text-left text-xs uppercase text-slate-500"><tr><th class="px-5 py-3">Utente</th><th class="px-5 py-3">Ruoli</th><th class="px-5 py-3">Cliente</th><th class="px-5 py-3">Stato</th><th class="px-5 py-3 text-right">Azioni</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-5 py-4"><div class="font-medium text-slate-950">{{ $user->name }}</div><div class="text-slate-500">{{ $user->email }} · {{ $user->job_title ?: '-' }}</div></td>
                            <td class="px-5 py-4">{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="px-5 py-4">{{ $user->client?->displayName() ?: '-' }}</td>
                            <td class="px-5 py-4"><span class="rounded-full px-2 py-1 text-xs {{ $user->is_active ? 'bg-[#e8f3e8] text-[#456047]' : 'bg-slate-100 text-slate-500' }}">{{ $user->is_active ? 'attivo' : 'disattivo' }}</span></td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('admin.users.show', $user) }}" class="font-medium underline">Apri</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</x-app-layout>
