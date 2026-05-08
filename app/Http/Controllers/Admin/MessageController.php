<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $messages = Message::with('client', 'user')->latest()->paginate(20);
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.messages.create', ['clients' => Client::orderBy('company_name')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Message::create($request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'is_internal' => ['nullable', 'boolean'],
        ]) + ['user_id' => $request->user()->id, 'is_internal' => $request->boolean('is_internal')]);

        return redirect()->route('admin.messages.index')->with('status', 'Messaggio salvato.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message): View
    {
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message): RedirectResponse
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('status', 'Messaggio eliminato.');
    }
}
