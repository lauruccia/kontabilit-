<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $documents = Document::with('client', 'uploader')->latest()->paginate(20);
        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.documents.create', ['clients' => Client::orderBy('company_name')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:80'],
            'visibility' => ['required', 'in:internal,client'],
            'file' => ['required', 'file', 'max:10240'],
        ]);
        $file = $request->file('file');
        $path = $file->store('documents');
        Document::create([
            'client_id' => $data['client_id'] ?? null,
            'uploaded_by' => $request->user()->id,
            'name' => $data['name'],
            'type' => $data['type'],
            'visibility' => $data['visibility'],
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'hash' => hash_file('sha256', $file->getRealPath()),
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Documento caricato.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): View
    {
        return view('admin.documents.show', compact('document'));
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
    public function destroy(Document $document): RedirectResponse
    {
        $document->delete();
        return redirect()->route('admin.documents.index')->with('status', 'Documento archiviato.');
    }

    public function download(Document $document)
    {
        abort_unless(Storage::exists($document->path), 404);
        return Storage::download($document->path, $document->name);
    }
}
