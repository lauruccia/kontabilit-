<x-app-layout>
    <x-slot name="title">Modifica lead CRM</x-slot>
    <form method="POST" action="{{ route('admin.crm.leads.update', $lead) }}" class="rounded-xl border border-[#d9e4df] bg-white p-6 shadow-sm">
        @method('PUT')
        @include('admin.crm.leads._form')
    </form>
</x-app-layout>
