<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClientAreaController extends Controller
{
    public function dashboard(Request $request): View
    {
        $client = $request->user()->client;
        abort_unless($client, 403);

        return view('client.dashboard', [
            'client' => $client->load('quotes', 'contracts', 'payments', 'documents'),
        ]);
    }

    public function storeMessage(Request $request): RedirectResponse
    {
        $client = $request->user()->client;
        abort_unless($client, 403);
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);
        Message::create($data + ['client_id' => $client->id, 'user_id' => $request->user()->id]);

        return back()->with('status', 'Messaggio inviato.');
    }

    public function uploadDocument(Request $request): RedirectResponse
    {
        $client = $request->user()->client;
        abort_unless($client, 403);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:10240'],
        ]);
        $file = $request->file('file');
        Document::create([
            'client_id' => $client->id,
            'uploaded_by' => $request->user()->id,
            'name' => $data['name'],
            'type' => 'client_upload',
            'visibility' => 'client',
            'path' => $file->store('documents/client'),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'hash' => hash_file('sha256', $file->getRealPath()),
        ]);

        return back()->with('status', 'Documento caricato.');
    }

    public function downloadDocument(Request $request, Document $document)
    {
        abort_unless($request->user()->client_id === $document->client_id && $document->visibility === 'client', 403);
        abort_unless(Storage::exists($document->path), 404);

        return Storage::download($document->path, $document->name);
    }
}
