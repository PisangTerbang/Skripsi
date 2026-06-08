<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class DosenPengaturanController extends Controller
{
    /**
     * Display pengaturan page
     */
    public function index()
    {
        $user = Auth::user();

        return view('dosen.pengaturan', [
            'user' => $user,
            'jumlahBimbingan' => $user->jumlahBimbingan(),
            'title' => 'Pengaturan Akun'
        ]);
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kuota_bimbingan' => 'nullable|integer|min:1|max:100',
        ], [
            'kuota_bimbingan.integer' => 'Kuota bimbingan harus berupa angka.',
            'kuota_bimbingan.min' => 'Kuota bimbingan minimal 1.',
            'kuota_bimbingan.max' => 'Kuota bimbingan maksimal 100.',
        ]);

        try {
            // Update basic info
            $user->name = $request->name;
            $user->email = $request->email;
            // Batas kuota mahasiswa bimbingan (kosong = tanpa batas)
            $user->kuota_bimbingan = $request->filled('kuota_bimbingan')
                ? (int) $request->kuota_bimbingan
                : null;

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                // Store new avatar
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $avatarPath;
            }

            $user->save();

            return back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();

            return back()->with('success', 'Password berhasil diubah!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }

    /**
     * Remove avatar
     */
    public function removeAvatar()
    {
        $user = Auth::user();

        try {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = null;
            $user->save();

            return back()->with('success', 'Foto profil berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus foto profil.');
        }
    }
}
