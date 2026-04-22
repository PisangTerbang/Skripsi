<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PengaturanController extends Controller
{
    /**
     * Display pengaturan page
     */
    public function index()
    {
        $user = Auth::user();

        return view('mahasiswa.pengaturan', [
            'user' => $user,
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
            'nim' => 'nullable|string|max:20|unique:users,nim,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Update basic info
            $user->name = $request->name;
            $user->email = $request->email;
            $user->nim = $request->nim;

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
