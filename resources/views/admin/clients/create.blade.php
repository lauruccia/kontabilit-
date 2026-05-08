<x-app-layout>
    <x-slot name="title">Nuovo cliente</x-slot>

    <form method="POST" action="{{ route('admin.clients.store') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @include('admin.clients._form')
    </form>
</x-app-layout>
