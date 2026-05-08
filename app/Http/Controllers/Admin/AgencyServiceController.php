<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgencyServiceRequest;
use App\Http\Requests\UpdateAgencyServiceRequest;
use App\Models\AgencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AgencyServiceController extends Controller
{
    public function index(): View
    {
        $services = AgencyService::query()
            ->when(request()->filled('active'), fn ($query) => $query->where('is_active', request()->boolean('active')))
            ->when(request('search'), fn ($query, string $search) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create', ['service' => new AgencyService(['vat_rate' => 22, 'is_active' => true])]);
    }

    public function store(StoreAgencyServiceRequest $request): RedirectResponse
    {
        AgencyService::create($request->validated());

        return redirect()->route('admin.services.index')->with('status', 'Servizio creato correttamente.');
    }

    public function show(AgencyService $service): View
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(AgencyService $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(UpdateAgencyServiceRequest $request, AgencyService $service): RedirectResponse
    {
        $service->update($request->validated());

        return redirect()->route('admin.services.show', $service)->with('status', 'Servizio aggiornato correttamente.');
    }

    public function destroy(AgencyService $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Servizio archiviato.');
    }
}
