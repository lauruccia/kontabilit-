<x-app-layout>
    <x-slot name="title">Dashboard operativa</x-slot>

    <div class="space-y-6">
        <section class="rounded-xl border border-[#d9e4df] bg-[#071111] p-6 text-white shadow-sm">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-[#8ca98b]">Gruppo Kosmos Client Hub</p>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-normal sm:text-3xl">Panoramica clienti e amministrazione</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/65">Controlla clienti, documenti, pagamenti e scadenze operative da un unico punto.</p>
                </div>

                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach ($quickActions as $action)
                        <a href="{{ route($action['route']) }}" class="rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3 hover:bg-white/[0.1]">
                            <span class="block text-sm font-bold text-white">{{ $action['label'] }}</span>
                            <span class="mt-1 block text-xs text-white/55">{{ $action['description'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($stats as $stat)
                @php
                    $badgeClasses = [
                        'blue' => 'bg-[#e7f0f2] text-[#486b75]',
                        'emerald' => 'bg-[#e8f3e8] text-[#456047]',
                        'amber' => 'bg-amber-50 text-amber-700',
                        'rose' => 'bg-rose-50 text-rose-700',
                        'slate' => 'bg-slate-100 text-slate-600',
                    ][$stat['tone']] ?? 'bg-slate-100 text-slate-600';
                @endphp
                <article class="rounded-xl border border-[#d9e4df] bg-white p-5 shadow-sm">
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

        <section class="grid gap-6 xl:grid-cols-[1.35fr_1fr]">
            <div class="rounded-xl border border-[#d9e4df] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-950">Entrate registrate</h2>
                        <p class="mt-1 text-sm text-slate-500">Andamento incassi degli ultimi 6 mesi.</p>
                    </div>
                    <span class="rounded-full bg-[#e8f3e8] px-3 py-1 text-xs font-medium text-[#456047]">Pagamenti saldati</span>
                </div>

                <div class="mt-8 flex h-64 items-end gap-3">
                    @foreach ($monthlyRevenue as $month)
                        <div class="flex flex-1 flex-col items-center justify-end gap-2">
                            <div class="flex h-52 w-full items-end rounded-t bg-[#edf4ef]">
                                <div class="w-full rounded-t bg-[#486b75]" style="height: {{ $month['height'] }}%;"></div>
                            </div>
                            <span class="text-xs font-medium text-slate-500">{{ $month['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-[#d9e4df] bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-950">Contratti per stato</h2>
                <div class="mt-6 space-y-4">
                    @foreach ($contractsByStatus as $label => $count)
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-600">{{ str_replace('_', ' ', ucfirst($label)) }}</span>
                                <span class="font-semibold text-slate-950">{{ $count }}</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-[#edf4ef]">
                                <div class="h-2 rounded-full bg-[#8ca98b]" style="width: {{ $count ? min(100, $count * 12) : 2 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1fr_1fr]">
            <div class="rounded-xl border border-[#d9e4df] bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-950">Pipeline CRM</h2>
                        <p class="mt-1 text-sm text-slate-500">Valore opportunità aperte: € {{ number_format((float) $openOpportunitiesValue, 2, ',', '.') }}</p>
                    </div>
                    <a href="{{ route('admin.crm.leads.index') }}" class="rounded-lg border px-3 py-2 text-sm font-medium">Apri CRM</a>
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    @foreach (['new' => 'Nuovi', 'contacted' => 'Contatti', 'qualified' => 'Qualificati', 'proposal' => 'Proposta', 'won' => 'Vinti', 'lost' => 'Persi'] as $key => $label)
                        @php $row = $crmPipeline[$key] ?? null; @endphp
                        <div class="rounded-lg bg-[#f3f7f5] p-4">
                            <p class="text-xs font-bold uppercase tracking-wide text-[#486b75]">{{ $label }}</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $row->count ?? 0 }}</p>
                            <p class="mt-1 text-xs text-slate-500">€ {{ number_format((float) ($row->value ?? 0), 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-[#d9e4df] bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-950">Attività CRM urgenti</h2>
                <div class="mt-5 space-y-3">
                    @forelse ($crmActivities as $activity)
                        <div class="flex items-center justify-between gap-3 rounded-lg bg-[#f3f7f5] px-4 py-3 text-sm">
                            <div>
                                <div class="font-semibold text-slate-950">{{ $activity->title }}</div>
                                <div class="text-slate-500">{{ $activity->lead?->displayName() ?: 'Lead' }} · {{ $activity->assignee?->name ?: 'Non assegnata' }}</div>
                            </div>
                            <div class="text-right text-xs font-medium text-[#486b75]">{{ optional($activity->due_at)->format('d/m H:i') ?: '-' }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nessuna attività CRM aperta.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-xl border border-[#d9e4df] bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-950">Clienti recenti</h2>
                <div class="mt-5 space-y-3">
                    @forelse ($recentClients as $client)
                        <a href="{{ route('admin.clients.show', $client) }}" class="block rounded-lg bg-[#f3f7f5] px-4 py-3 text-sm hover:bg-[#edf4ef]">
                            <span class="block font-semibold text-slate-950">{{ $client->displayName() }}</span>
                            <span class="text-slate-500">{{ $client->email }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Nessun cliente registrato.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-[#d9e4df] bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-950">Pagamenti aperti</h2>
                <div class="mt-5 space-y-3">
                    @forelse ($openPayments as $payment)
                        <a href="{{ route('admin.payments.show', $payment) }}" class="flex items-center justify-between rounded-lg bg-[#f3f7f5] px-4 py-3 text-sm hover:bg-[#edf4ef]">
                            <span>
                                <span class="block font-semibold text-slate-950">{{ $payment->client->displayName() }}</span>
                                <span class="text-slate-500">{{ $payment->number }}</span>
                            </span>
                            <span class="font-semibold text-[#486b75]">€ {{ number_format((float) $payment->amount, 2, ',', '.') }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Nessun pagamento aperto.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-[#d9e4df] bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-950">Scadenze prossime</h2>
                <div class="mt-5 space-y-3">
                    @forelse ($upcomingDeadlines as $deadline)
                        <a href="{{ route('admin.reminders.show', $deadline) }}" class="block rounded-lg bg-[#f3f7f5] px-4 py-3 text-sm hover:bg-[#edf4ef]">
                            <span class="block font-semibold text-slate-950">{{ $deadline->title }}</span>
                            <span class="text-slate-500">{{ optional($deadline->due_date)->format('d/m/Y') }} · {{ $deadline->client?->displayName() ?: 'Generale' }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Nessuna scadenza registrata.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
