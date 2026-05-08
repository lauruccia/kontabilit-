<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gruppo Kosmos | Client Hub</title>
    <meta name="description" content="Area riservata ai clienti Gruppo Kosmos per gestione clienti, contabilità operativa, documenti e attività.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-[#071111] text-[#edf4ef] antialiased">
    <div class="min-h-screen overflow-hidden">
        <header class="relative z-20 border-b border-white/10 bg-[#071111]/92 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-lg border border-white/10 bg-white/5 p-2">
                        <img src="{{ asset('images/gruppo-kosmos-logo.png') }}" alt="Logo Gruppo Kosmos" class="h-full w-full object-contain">
                    </span>
                    <span>
                        <span class="block text-base font-extrabold tracking-normal text-white">Gruppo Kosmos</span>
                        <span class="block text-xs font-semibold uppercase tracking-wide text-[#8ca98b]">Area Clienti</span>
                    </span>
                </a>

                <nav class="hidden items-center gap-7 text-sm font-semibold text-white/70 md:flex">
                    <a href="#operativita" class="hover:text-white">Operativita</a>
                    <a href="#ecosistema" class="hover:text-white">Ecosistema</a>
                    <a href="https://gruppokosmos.it/" target="_blank" rel="noopener noreferrer" class="hover:text-white">Sito Gruppo Kosmos</a>
                    <a href="#accesso" class="hover:text-white">Area clienti</a>
                </nav>

                <div class="flex items-center gap-2">
                    @auth
                        @if (auth()->user()->hasRole('client'))
                            <a href="{{ route('client.dashboard') }}" class="rounded-lg bg-[#8ca98b] px-4 py-2 text-sm font-semibold text-[#071111] hover:bg-[#a6c1a4]">Area cliente</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="rounded-lg bg-[#8ca98b] px-4 py-2 text-sm font-semibold text-[#071111] hover:bg-[#a6c1a4]">Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg border border-white/15 bg-white/8 px-4 py-2 text-sm font-semibold text-white hover:bg-white/14">Accesso clienti</a>
                    @endauth
                </div>
            </div>
        </header>

        <main>
            <section class="relative">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_78%_18%,rgba(140,169,139,0.24),transparent_32%),radial-gradient(circle_at_12%_78%,rgba(54,82,94,0.42),transparent_34%),linear-gradient(135deg,#071111_0%,#142528_48%,#283e42_100%)]"></div>
                <div class="relative mx-auto grid min-h-[calc(100vh-78px)] max-w-7xl items-center gap-10 px-5 py-14 lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:py-20">
                    <div class="max-w-3xl">
                        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-[#8ca98b]/35 bg-white/8 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-[#c7d8c5] shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-[#8ca98b]"></span>
                            Accesso riservato ai clienti Gruppo Kosmos
                        </div>

                        <h1 class="max-w-4xl text-5xl font-extrabold leading-tight tracking-normal text-white sm:text-6xl lg:text-7xl">
                            L'area riservata per seguire clienti, attivita e amministrazione.
                        </h1>

                        <p class="mt-7 max-w-2xl text-lg leading-8 text-white/72">
                            Un ambiente protetto dedicato ai clienti Gruppo Kosmos, con dati, comunicazioni, documenti e pagamenti sempre organizzati e accessibili.
                        </p>

                        <div id="accesso" class="mt-9 flex flex-col gap-3 sm:flex-row">
                            @auth
                                <a href="{{ auth()->user()->hasRole('client') ? route('client.dashboard') : route('dashboard') }}" class="rounded-lg bg-[#8ca98b] px-5 py-3 text-center text-sm font-bold text-[#071111] shadow-lg shadow-black/20 hover:bg-[#a6c1a4]">
                                    Entra nell'area riservata
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-lg bg-[#8ca98b] px-5 py-3 text-center text-sm font-bold text-[#071111] shadow-lg shadow-black/20 hover:bg-[#a6c1a4]">
                                    Accesso clienti Kosmos
                                </a>
                            @endauth
                            <a href="https://gruppokosmos.it/" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-white/15 bg-white/8 px-5 py-3 text-center text-sm font-bold text-white hover:bg-white/14">
                                Vai al sito del gruppo
                            </a>
                            <a href="#operativita" class="rounded-lg border border-white/15 bg-white/8 px-5 py-3 text-center text-sm font-bold text-white hover:bg-white/14">
                                Scopri il flusso
                            </a>
                        </div>
                    </div>

                    <div class="relative lg:justify-self-end">
                        <div class="absolute -inset-8 rounded-[2rem] border border-[#8ca98b]/20"></div>
                        <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-[#0b1718] p-5 shadow-2xl shadow-black/35">
                            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-[#8ca98b]">Kosmos desk</p>
                                    <p class="mt-1 text-lg font-bold text-white">Area cliente</p>
                                </div>
                                <div class="rounded-lg bg-[#8ca98b] px-3 py-1.5 text-xs font-extrabold text-[#071111]">LIVE</div>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                @foreach ([
                                    ['label' => 'Clienti attivi', 'value' => 'Centralizzati'],
                                    ['label' => 'Pagamenti', 'value' => 'Monitorati'],
                                    ['label' => 'Documenti', 'value' => 'Riservati'],
                                    ['label' => 'Scadenze', 'value' => 'In evidenza'],
                                ] as $item)
                                    <div class="rounded-lg border border-white/10 bg-white/[0.06] p-4">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-white/50">{{ $item['label'] }}</p>
                                        <p class="mt-2 text-xl font-bold text-white">{{ $item['value'] }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-5 rounded-lg bg-[#edf4ef] p-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-extrabold text-[#071111]">Pipeline cliente</p>
                                    <p class="text-xs font-bold text-[#456047]">Aggiornato</p>
                                </div>
                                <div class="mt-4 space-y-3">
                                    @foreach ([
                                        ['label' => 'Anagrafica e contatti', 'width' => '92%'],
                                        ['label' => 'Amministrazione e pagamenti', 'width' => '76%'],
                                        ['label' => 'Documenti condivisi', 'width' => '68%'],
                                    ] as $row)
                                        <div>
                                            <div class="mb-1 flex justify-between text-xs font-semibold text-[#31494d]">
                                                <span>{{ $row['label'] }}</span>
                                            </div>
                                            <div class="h-2 rounded-full bg-[#c9d7d2]">
                                                <div class="h-2 rounded-full bg-[#486b75]" style="width: {{ $row['width'] }}"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="operativita" class="bg-[#071111] py-16 text-white">
                <div class="mx-auto max-w-7xl px-5 lg:px-8">
                    <div class="max-w-3xl">
                        <p class="text-sm font-bold uppercase tracking-wide text-[#8ca98b]">Servizi digitali Gruppo Kosmos</p>
                        <h2 class="mt-3 text-3xl font-extrabold tracking-normal sm:text-4xl">Un punto unico per restare allineati con il team Kosmos.</h2>
                    </div>

                    <div class="mt-10 grid gap-4 md:grid-cols-3">
                        @foreach ([
                            ['title' => 'Profilo cliente', 'text' => 'Dati, referenti e informazioni operative ordinati in un unico spazio riservato.'],
                            ['title' => 'Amministrazione', 'text' => 'Pagamenti, scadenze e documenti sempre consultabili quando servono.'],
                            ['title' => 'Comunicazioni', 'text' => 'Messaggi e aggiornamenti raccolti in modo chiaro tra cliente e team Kosmos.'],
                        ] as $card)
                            <article class="rounded-lg border border-white/10 bg-white/[0.06] p-6">
                                <h3 class="text-lg font-bold">{{ $card['title'] }}</h3>
                                <p class="mt-3 text-sm leading-6 text-white/70">{{ $card['text'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="ecosistema" class="bg-[#edf4ef] py-16 text-[#071111]">
                <div class="mx-auto grid max-w-7xl gap-10 px-5 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wide text-[#486b75]">Ecosistema Kosmos</p>
                        <h2 class="mt-3 text-3xl font-extrabold text-[#071111] sm:text-4xl">Pensata per un gruppo che unisce strategia, tecnologia e crescita.</h2>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach (['KAgency', 'KMoney', 'KSM', 'KosmoLab'] as $brand)
                            <div class="rounded-lg border border-[#486b75]/15 bg-white/70 p-5">
                                <p class="text-lg font-extrabold text-[#071111]">{{ $brand }}</p>
                                <p class="mt-2 text-sm leading-6 text-[#31494d]">Processi, clienti e attivita collegati in una visione condivisa.</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
