<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('name')->paginate(25);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form', ['managedUser' => new User()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['owner', 'co_owner'])],
            'aktif' => ['required', 'boolean'],
        ]);
        $validated['aktif'] = (bool) $validated['aktif'];
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('users.form', ['managedUser' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['owner', 'co_owner'])],
            'aktif' => ['required', 'boolean'],
        ]);
        $validated['aktif'] = (bool) $validated['aktif'];

        $this->ensureAnActiveOwnerRemains($user, $validated['role'], $validated['aktif']);
        if ($user->is(Auth::user()) && !$validated['aktif']) {
            throw ValidationException::withMessages([
                'aktif' => 'Anda tidak dapat menonaktifkan akun sendiri.',
            ]);
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }
        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        $active = !$user->aktif;
        if ($user->is(Auth::user()) && !$active) {
            throw ValidationException::withMessages([
                'aktif' => 'Anda tidak dapat menonaktifkan akun sendiri.',
            ]);
        }
        $this->ensureAnActiveOwnerRemains($user, $user->role, $active);
        $user->update(['aktif' => $active]);

        return redirect()->route('users.index')->with(
            'success',
            $active ? 'Akun berhasil diaktifkan.' : 'Akun berhasil dinonaktifkan.'
        );
    }

    private function ensureAnActiveOwnerRemains(User $user, string $role, bool $active): void
    {
        if ($user->role === 'owner' && $user->aktif && ($role !== 'owner' || !$active)) {
            $anotherActiveOwner = User::query()
                ->whereKeyNot($user->id)
                ->where('role', 'owner')
                ->where('aktif', true)
                ->exists();
            if (!$anotherActiveOwner) {
                throw ValidationException::withMessages([
                    'role' => 'Sistem harus memiliki minimal satu Owner aktif.',
                ]);
            }
        }
    }
}
