<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show profile management page.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    /**
     * Update user basic profile info (name & username).
     */
    public function updateInfo(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
        ]);

        $user->update([
            'name' => $validated['name'],
            'username' => strtolower(trim($validated['username'])),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update profile avatar photo.
     */
    public function updateAvatar(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ], [
            'avatar.required' => 'File foto wajib dipilih.',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.max' => 'Ukuran foto maksimal 4MB.',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
        }

        return redirect()->route('profile.edit')->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Update account password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diubah!');
    }
}
