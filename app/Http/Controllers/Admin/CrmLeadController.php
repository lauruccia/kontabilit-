<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\CrmActivity;
use App\Models\CrmLead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmLeadController extends Controller
{
    public function index(): View
    {
        $leads = CrmLead::with('assignee')
            ->when(request('status'), fn ($query, string $status) => $query->where('status', $status))
            ->when(request('search'), function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('company_name', 'like', "%{$search}%")
                        ->orWhere('contact_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pipeline = CrmLead::query()
            ->selectRaw('status, count(*) as count, sum(estimated_value) as value')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return view('admin.crm.leads.index', compact('leads', 'pipeline'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.crm.leads.create', [
            'lead' => new CrmLead(['status' => 'new', 'priority' => 'medium']),
            'users' => User::role(['superadmin', 'admin', 'agent', 'operator'])->orderBy('name')->get(),
            'clients' => Client::orderBy('company_name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $lead = CrmLead::create($this->validated($request));
        $this->maybeCreateActivity($request, $lead);

        return redirect()->route('admin.crm.leads.show', $lead)->with('status', 'Lead CRM creato.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CrmLead $lead): View
    {
        return view('admin.crm.leads.show', [
            'lead' => $lead->load('assignee', 'client', 'opportunities', 'activities.assignee'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CrmLead $lead): View
    {
        return view('admin.crm.leads.edit', [
            'lead' => $lead,
            'users' => User::role(['superadmin', 'admin', 'agent', 'operator'])->orderBy('name')->get(),
            'clients' => Client::orderBy('company_name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CrmLead $lead): RedirectResponse
    {
        $lead->update($this->validated($request));
        $this->maybeCreateActivity($request, $lead);

        return redirect()->route('admin.crm.leads.show', $lead)->with('status', 'Lead CRM aggiornato.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CrmLead $lead): RedirectResponse
    {
        $lead->delete();
        return redirect()->route('admin.crm.leads.index')->with('status', 'Lead archiviato.');
    }

    public function completeActivity(CrmActivity $activity): RedirectResponse
    {
        $activity->forceFill(['status' => 'completed', 'completed_at' => now()])->save();
        return back()->with('status', 'Attivita completata.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:80'],
            'source' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:new,contacted,qualified,proposal,won,lost'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'estimated_value' => ['nullable', 'numeric', 'min:0'],
            'next_follow_up_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function maybeCreateActivity(Request $request, CrmLead $lead): void
    {
        if (! $request->filled('activity_title')) {
            return;
        }

        CrmActivity::create([
            'crm_lead_id' => $lead->id,
            'client_id' => $lead->client_id,
            'assigned_to' => $lead->assigned_to,
            'created_by' => $request->user()->id,
            'type' => $request->input('activity_type', 'follow_up'),
            'title' => $request->input('activity_title'),
            'due_at' => $request->input('activity_due_at'),
            'status' => 'open',
            'notes' => $request->input('activity_notes'),
        ]);
    }
}
