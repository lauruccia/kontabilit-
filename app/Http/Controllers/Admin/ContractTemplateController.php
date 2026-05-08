<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $templates = ContractTemplate::latest()->paginate(15);
        return view('admin.contract-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.contract-templates.create', ['template' => new ContractTemplate()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $template = ContractTemplate::create($this->validated($request));
        return redirect()->route('admin.contract-templates.show', $template)->with('status', 'Template creato.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContractTemplate $contractTemplate): View
    {
        return view('admin.contract-templates.show', ['template' => $contractTemplate]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContractTemplate $contractTemplate): View
    {
        return view('admin.contract-templates.edit', ['template' => $contractTemplate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContractTemplate $contractTemplate): RedirectResponse
    {
        $contractTemplate->update($this->validated($request));
        return redirect()->route('admin.contract-templates.show', $contractTemplate)->with('status', 'Template aggiornato.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractTemplate $contractTemplate): RedirectResponse
    {
        $contractTemplate->delete();
        return redirect()->route('admin.contract-templates.index')->with('status', 'Template archiviato.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
