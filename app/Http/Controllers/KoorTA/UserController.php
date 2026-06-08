<?php

namespace App\Http\Controllers\KoorTA;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'all');
        $search = $request->get('search', '');

        $query = User::query();

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(20)->withQueryString();

        $stats = [
            'total' => User::count(),
            'mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'dosen' => User::where('role', 'dosen')->count(),
            'ka_lab' => User::where('role', 'ka_lab')->count(),
            'prodi' => User::where('role', 'prodi')->count(),
            'koordinator_ta' => User::where('role', 'koordinator_ta')->count(),
        ];

        return view('koor-ta.users.index', compact('users', 'stats', 'role', 'search'));
    }

    public function create()
    {
        return view('koor-ta.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:mahasiswa,dosen,ka_lab,prodi,koordinator_ta',
            'nim' => 'nullable|string|max:20|unique:users,nim',
        ], [
            'email.unique' => 'Email sudah digunakan',
            'nim.unique' => 'NIM sudah digunakan',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'nim' => $validated['nim'] ?? null,
        ]);

        return redirect()->route('koor-ta.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('koor-ta.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:mahasiswa,dosen,ka_lab,prodi,koordinator_ta',
            'nim' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
        ], [
            'email.unique' => 'Email sudah digunakan',
            'nim.unique' => 'NIM sudah digunakan',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'nim' => $validated['nim'] ?? null,
        ]);

        return redirect()->route('koor-ta.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('koor-ta.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function resetPassword(User $user)
    {
        // Reset ke password default: nim atau email
        $newPassword = $user->nim ?? explode('@', $user->email)[0];

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return back()->with('success', "Password berhasil direset ke: {$newPassword}");
    }
}
