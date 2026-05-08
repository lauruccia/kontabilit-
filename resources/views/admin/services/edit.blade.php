<x-app-layout>
    <x-slot name="title">Modifica servizio</x-slot>

    <form method="POST" action="{{ route('admin.services.update', $service) }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @method('PUT')
        @include('admin.services._form')
    </form>
</x-app-layout>
