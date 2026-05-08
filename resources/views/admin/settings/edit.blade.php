<x-app-layout>
    <x-slot name="title">Impostazioni</x-slot>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        <x-status-alert />

        @php
            $textFields = [
                'agency_name' => 'Nome agency',
                'agency_email' => 'Email mittente',
                'brand_color' => 'Colore brand',
                'otp_ttl_minutes' => 'Durata OTP minuti',
                'otp_max_attempts' => 'Tentativi OTP',
                'stripe_public_key' => 'Stripe public key',
                'stripe_secret_key' => 'Stripe secret key',
            ];

            $defaults = [
                'otp_ttl_minutes' => 10,
                'otp_max_attempts' => 5,
            ];

            $textareaFields = [
                'pdf_header' => 'Intestazione PDF',
                'pdf_footer' => 'Pie di pagina PDF',
                'standard_terms' => 'Condizioni standard',
                'privacy_policy' => 'Privacy policy',
            ];
        @endphp

        <div class="grid gap-5 lg:grid-cols-2">
            @foreach ($textFields as $key => $label)
                <label class="block">
                    <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                    <input
                        name="{{ $key }}"
                        value="{{ old($key, $settings[$key] ?? ($defaults[$key] ?? '')) }}"
                        class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950"
                    >
                </label>
            @endforeach
        </div>

        @foreach ($textareaFields as $key => $label)
            <label class="block">
                <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                <textarea
                    name="{{ $key }}"
                    rows="4"
                    class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-slate-950 focus:ring-slate-950"
                >{{ old($key, $settings[$key] ?? '') }}</textarea>
            </label>
        @endforeach

        <button class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Salva impostazioni
        </button>
    </form>
</x-app-layout>
