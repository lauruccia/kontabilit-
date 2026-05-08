<x-app-layout>
    <x-slot name="title">Nuovo lead CRM</x-slot>
    <form method="POST" action="{{ route('admin.crm.leads.store') }}" class="rounded-xl border border-[#d9e4df] bg-white p-6 shadow-sm">
        @include('admin.crm.leads._form')
    </form>
</x-app-layout>
