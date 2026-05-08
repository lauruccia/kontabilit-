<x-app-layout>
    <x-slot name="title">Area cliente</x-slot>
    <div class="space-y-6"><x-status-alert />
        <section class="rounded-lg border bg-white p-6"><h2 class="text-xl font-semibold">{{ $client->displayName() }}</h2><p class="text-sm text-slate-500">{{ $client->email }} · {{ $client->phone }}</p></section>
        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-lg border bg-white p-5"><h3 class="font-semibold">Contratti</h3>@foreach($client->contracts as $contract)<div class="mt-3 text-sm"><a class="underline" href="{{ $contract->public_token ? route('public.contracts.show',$contract->public_token) : '#' }}">{{ $contract->number }}</a> · {{ $contract->status }}</div>@endforeach</section>
            <section class="rounded-lg border bg-white p-5"><h3 class="font-semibold">Preventivi</h3>@foreach($client->quotes as $quote)<div class="mt-3 text-sm">{{ $quote->number }} · € {{ number_format((float)$quote->total,2,',','.') }} · {{ $quote->status }}</div>@endforeach</section>
            <section class="rounded-lg border bg-white p-5"><h3 class="font-semibold">Pagamenti</h3>@foreach($client->payments as $payment)<div class="mt-3 text-sm">{{ $payment->number }} · {{ $payment->status }}</div>@endforeach</section>
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
            <form method="POST" action="{{ route('client.documents.store') }}" enctype="multipart/form-data" class="rounded-lg border bg-white p-5">@csrf<h3 class="font-semibold">Carica documento</h3><input name="name" placeholder="Nome documento" class="mt-4 w-full rounded-lg border-slate-300"><input type="file" name="file" class="mt-3 w-full rounded-lg border-slate-300"><button class="mt-4 rounded-lg bg-slate-950 px-4 py-2 text-sm text-white">Carica</button></form>
            <form method="POST" action="{{ route('client.messages.store') }}" class="rounded-lg border bg-white p-5">@csrf<h3 class="font-semibold">Messaggio alla web agency</h3><input name="subject" placeholder="Oggetto" class="mt-4 w-full rounded-lg border-slate-300"><textarea name="body" rows="4" placeholder="Messaggio" class="mt-3 w-full rounded-lg border-slate-300"></textarea><button class="mt-4 rounded-lg bg-slate-950 px-4 py-2 text-sm text-white">Invia</button></form>
        </div>
    </div>
</x-app-layout>
