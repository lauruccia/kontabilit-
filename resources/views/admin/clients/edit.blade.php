<x-app-layout>
    <x-slot name="title">Modifica cliente</x-slot>

    <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @method('PUT')
        @include('admin.clients._form')
    </form>
</x-app-layout>
