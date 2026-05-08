<x-app-layout>
    <x-slot name="title">
        Dashboard
    </x-slot>

    <div class="space-y-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($stats as $stat)
                @php
                    $badgeClasses = [
                        'blue' => 'bg-blue-50 text-blue-700',
                        'emerald' => 'bg-emerald-50 text-emerald-700',
                        'amber' => 'bg-amber-50 text-amber-700',
                        'rose' => 'bg-rose-50 text-rose-700',
                        'slate' => 'bg-slate-100 text-slate-600',
                    ][$stat['tone']] ?? 'bg-slate-100 text-slate-600';
                @endphp
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-3xl font-semibold tracking-normal text-slate-950">{{ $stat['value'] }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $badgeClasses }}">
                            {{ $stat['trend'] }}
                        </span>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-950">Entrate mensili</h2>
                        <p class="mt-1 text-sm text-slate-500">Il grafico userà i pagamenti reali dallo STEP 7.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Ultimi 6 mesi</span>
                </div>

                <div class="mt-8 flex h-64 items-end gap-3">
                    @foreach ($monthlyRevenue as $value)
                        <div class="flex flex-1 items-end rounded-t bg-slate-100">
                            <div class="w-full rounded-t bg-slate-950" style="height: {{ max(8, $value) }}%;"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-950">Contratti per stato</h2>
                <div class="mt-6 space-y-4">
                    @foreach ($contractsByStatus as $label => $count)
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-600">{{ $label }}</span>
                                <span class="font-semibold text-slate-950">{{ $count }}</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-slate-950" style="width: {{ $count ? min(100, $count * 10) : 2 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-950">Attività recenti</h2>
                <div class="mt-5 space-y-3">
                    @foreach ($recentActivities as $activity)
                        <div class="flex items-center gap-3 rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-700">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            {{ $activity }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-950">Scadenze imminenti</h2>
                <div class="mt-5 space-y-3">
                    @foreach ($upcomingDeadlines as $deadline)
                        <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-700">
                            <span>{{ $deadline }}</span>
                            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-medium text-slate-500">Da pianificare</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
