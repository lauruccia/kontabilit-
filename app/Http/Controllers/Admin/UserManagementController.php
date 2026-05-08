<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles', 'client')->orderBy('name')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.users.create', [
            'user' => new User(['is_active' => true]),
            'roles' => Role::orderBy('name')->get(),
            'clients' => Client::orderBy('company_name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, true);
        $user = User::create($data + ['password' => Hash::make($data['password'])]);
        $user->syncRoles($request->input('roles', []));

        return redirect()->route('admin.users.show', $user)->with('status', 'Utente creato.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        return view('admin.users.show', ['user' => $user->load('roles', 'client')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
            'clients' => Client::orderBy('company_name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, false, $user);
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        $user->syncRoles($request->input('roles', []));

        return redirect()->route('admin.users.show', $user)->with('status', 'Utente aggiornato.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 422, 'Non puoi disattivare il tuo utente.');
        $user->forceFill(['is_active' => false])->save();

        return redirect()->route('admin.users.index')->with('status', 'Utente disattivato.');
    }

    private function validated(Request $request, bool $creating, ?User $user = null): array
    {
        return $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'.($user ? ','.$user->id : '')],
            'phone' => ['nullable', 'string', 'max:80'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
            'password' => [$creating ? 'required' : 'nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,name'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
