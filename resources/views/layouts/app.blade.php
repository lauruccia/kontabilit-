<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gruppo Kosmos Client Hub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900">
        <div class="min-h-screen bg-[#f3f7f5]">
            <div class="flex min-h-screen">
                <aside class="hidden w-72 shrink-0 border-r border-[#d9e4df] bg-[#071111] px-5 py-6 lg:block">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg border border-white/10 bg-white/5 p-2">
                            <img src="{{ asset('images/gruppo-kosmos-logo.png') }}" alt="Gruppo Kosmos" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <div class="text-sm font-semibold uppercase tracking-wide text-[#8ca98b]">Gruppo Kosmos</div>
                            <div class="text-lg font-semibold text-white">Client Hub</div>
                        </div>
                    </div>

                    <nav class="mt-8 space-y-1">
                        @php
                            $links = [
                                ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z'],
                                ['label' => 'Clienti', 'route' => 'admin.clients.index', 'active' => 'admin.clients.*', 'icon' => 'M16 11c1.66 0 3-1.57 3-3.5S17.66 4 16 4s-3 1.57-3 3.5S14.34 11 16 11ZM8 11c1.66 0 3-1.57 3-3.5S9.66 4 8 4 5 5.57 5 7.5 6.34 11 8 11Zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4Zm8 0c-.31 0-.66.02-1.03.05 1.16.84 2.03 1.97 2.03 3.45V20h7v-3c0-2.66-5.33-4-8-4Z'],
                                ['label' => 'CRM', 'route' => 'admin.crm.leads.index', 'active' => 'admin.crm.*', 'icon' => 'M4 4h16v4H4V4Zm0 6h7v10H4V10Zm9 0h7v4h-7v-4Zm0 6h7v4h-7v-4Z'],
                                ['label' => 'Utenti e ruoli', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-8 9a8 8 0 0 1 16 0H4Zm16-9a3 3 0 1 0 0-6v6Zm-1 9h5a6 6 0 0 0-7.7-5.76A9.96 9.96 0 0 1 19 21Z'],
                                ['label' => 'Servizi', 'route' => 'admin.services.index', 'active' => 'admin.services.*', 'icon' => 'M4 4h16v4H4V4Zm0 6h16v10H4V10Zm3 3v2h6v-2H7Z'],
                                ['label' => 'Preventivi', 'route' => 'admin.quotes.index', 'active' => 'admin.quotes.*', 'icon' => 'M6 2h9l5 5v15H6V2Zm8 1.5V8h4.5L14 3.5ZM8 12h8v2H8v-2Zm0 4h8v2H8v-2Z'],
                                ['label' => 'Contratti', 'route' => 'admin.contracts.index', 'active' => 'admin.contracts.*', 'icon' => 'M5 3h10l4 4v14H5V3Zm9 1.5V8h3.5L14 4.5ZM8 12h8v2H8v-2Zm0 4h6v2H8v-2Z'],
                                ['label' => 'Pagamenti', 'route' => 'admin.payments.index', 'active' => 'admin.payments.*', 'icon' => 'M3 6h18v12H3V6Zm2 3v6h14V9H5Zm2 4h5v1H7v-1Z'],
                                ['label' => 'Documenti', 'route' => 'admin.documents.index', 'active' => 'admin.documents.*', 'icon' => 'M6 2h8l4 4v16H6V2Zm7 1.5V7h3.5L13 3.5ZM8 11h8v2H8v-2Zm0 4h8v2H8v-2Z'],
                                ['label' => 'Scadenze', 'route' => 'admin.reminders.index', 'active' => 'admin.reminders.*', 'icon' => 'M7 2v2H5a2 2 0 0 0-2 2v14h18V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2H7Zm12 8H5v8h14v-8Z'],
                                ['label' => 'Impostazioni', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*', 'icon' => 'M19.43 12.98c.04-.32.07-.65.07-.98s-.02-.66-.07-.98l2.11-1.65-2-3.46-2.49 1a7.28 7.28 0 0 0-1.69-.98L15 3h-4l-.36 2.93c-.6.23-1.16.56-1.69.98l-2.49-1-2 3.46 2.11 1.65c-.04.32-.07.65-.07.98s.02.66.07.98l-2.11 1.65 2 3.46 2.49-1c.53.42 1.09.75 1.69.98L11 21h4l.36-2.93c.6-.23 1.16-.56 1.69-.98l2.49 1 2-3.46-2.11-1.65ZM13 15.5A3.5 3.5 0 1 1 13 8a3.5 3.5 0 0 1 0 7.5Z'],
                            ];
                        @endphp

                        @foreach ($links as $link)
                            @php $isActive = request()->routeIs($link['active'] ?? $link['route']); @endphp
                            <a @if ($link['route']) href="{{ route($link['route']) }}" @else href="#" aria-disabled="true" @endif
                               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition {{ $link['route'] && $isActive ? 'bg-[#9fbf9d] text-[#071111] shadow-sm' : 'text-[#d9e6df] hover:bg-white/10 hover:text-white' }}">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $link['icon'] }}"/></svg>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </aside>

                <div class="flex min-w-0 flex-1 flex-col">
                    <header class="border-b border-[#d9e4df] bg-white">
                        <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-[#486b75]">Pannello operativo Kosmos</p>
                                <h1 class="text-lg font-semibold text-slate-950">{{ $title ?? 'Dashboard' }}</h1>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="hidden text-right sm:block">
                                    <div class="text-sm font-medium text-slate-950">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-slate-500">{{ Auth::user()->getRoleNames()->join(', ') ?: 'Utente' }}</div>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 hover:border-slate-300 hover:bg-slate-50" type="submit">
                                        Esci
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
