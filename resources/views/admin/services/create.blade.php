<x-app-layout>
    <x-slot name="title">Nuovo servizio</x-slot>

    <form method="POST" action="{{ route('admin.services.store') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @include('admin.services._form')
    </form>
</x-app-layout>
