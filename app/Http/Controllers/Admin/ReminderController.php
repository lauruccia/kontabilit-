<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Reminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $reminders = Reminder::with('client')->orderBy('due_date')->paginate(20);
        return view('admin.reminders.index', compact('reminders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.reminders.create', ['reminder' => new Reminder(['due_date' => now()->addDays(7)]), 'clients' => Client::orderBy('company_name')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Reminder::create($this->validated($request));
        return redirect()->route('admin.reminders.index')->with('status', 'Scadenza creata.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reminder $reminder): View
    {
        return view('admin.reminders.show', compact('reminder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reminder $reminder): View
    {
        return view('admin.reminders.edit', ['reminder' => $reminder, 'clients' => Client::orderBy('company_name')->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reminder $reminder): RedirectResponse
    {
        $reminder->update($this->validated($request));
        return redirect()->route('admin.reminders.index')->with('status', 'Scadenza aggiornata.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reminder $reminder): RedirectResponse
    {
        $reminder->delete();
        return redirect()->route('admin.reminders.index')->with('status', 'Scadenza eliminata.');
    }

    public function complete(Reminder $reminder): RedirectResponse
    {
        $reminder->forceFill(['status' => 'completed', 'completed_at' => now()])->save();
        return back()->with('status', 'Scadenza completata.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'type' => ['required', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'date'],
            'status' => ['required', 'in:future,upcoming,expired,completed'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
