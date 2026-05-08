<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Firma contratto {{ $contract->number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
    <main class="mx-auto max-w-4xl px-4 py-8">
        <div class="mb-6 rounded-lg border bg-white p-6">
            <h1 class="text-2xl font-semibold">Firma contratto {{ $contract->number }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $contract->client->displayName() }}</p>
        </div>
        @if(session('status'))<div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">{{ session('status') }}</div>@endif
        <section class="rounded-lg border bg-white p-6">
            <div class="whitespace-pre-line text-sm leading-7">{{ $contract->rendered_content ?: $contract->terms }}</div>
            @if($contract->signature)
                <div class="mt-6 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-800">Contratto firmato il {{ $contract->signature->signed_at->format('d/m/Y H:i') }} tramite OTP email. Hash: {{ $contract->signature->document_hash }}</div>
            @else
                <div class="mt-8 grid gap-6 md:grid-cols-2">
                    <form method="POST" action="{{ route('public.contracts.otp', $contract->public_token) }}" class="rounded-lg border p-4">
                        @csrf
                        <h2 class="font-semibold">1. Richiedi OTP</h2>
                        <label class="mt-4 flex gap-2 text-sm"><input type="checkbox" name="accepted_terms" value="1"> Accetto condizioni contrattuali e privacy</label>
                        <button class="mt-4 rounded-lg bg-slate-950 px-4 py-2 text-sm text-white">Invia OTP</button>
                    </form>
                    <form method="POST" action="{{ route('public.contracts.verify', $contract->public_token) }}" class="rounded-lg border p-4">
                        @csrf
                        <h2 class="font-semibold">2. Inserisci OTP</h2>
                        <input name="otp" maxlength="6" class="mt-4 w-full rounded-lg border-slate-300 text-center text-xl tracking-widest" placeholder="000000">
                        @error('otp')<div class="mt-2 text-sm text-rose-600">{{ $message }}</div>@enderror
                        <button class="mt-4 rounded-lg bg-emerald-700 px-4 py-2 text-sm text-white">Firma contratto</button>
                    </form>
                </div>
            @endif
        </section>
    </main>
</body>
</html>
