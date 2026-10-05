<?php

namespace App\Http\Controllers;

use App\Models\TblUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show Account Profile edit form (matching screenshot).
     */
    public function edit(Request $request)
    {
        $userId = session('user_id') ?? (Auth::check() ? Auth::id() : null);
        $user = null;
        if ($userId) {
            $user = TblUser::find($userId);
        }
        if (!$user) {
            $user = TblUser::first();
        }

        if (!$user) {
            return redirect()->route('home')->with('error', 'User profile tidak ditemukan.');
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Update Account Profile data.
     */
    public function update(Request $request)
    {
        $userId = session('user_id') ?? (Auth::check() ? Auth::id() : null);
        $user = null;
        if ($userId) {
            $user = TblUser::find($userId);
        }
        if (!$user) {
            $user = TblUser::first();
        }

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'username' => 'required|string|max:50|unique:tbl_user,username,' . $user->iduser . ',iduser',
            'telp' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:4',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'nullable',
        ]);

        $user->nama = $request->input('nama');
        $user->username = $request->input('username');
        $user->telp = $request->input('telp');

        // Update password if filled
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Ensure upload directory exists
        $uploadDir = public_path('uploads/profile');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        // Handle photo removal
        if ($request->filled('remove_photo') && $request->input('remove_photo') == '1') {
            if ($user->photo && File::exists($uploadDir . '/' . $user->photo)) {
                File::delete($uploadDir . '/' . $user->photo);
            }
            $user->photo = null;
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && File::exists($uploadDir . '/' . $user->photo)) {
                File::delete($uploadDir . '/' . $user->photo);
            }

            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'profile_' . $user->iduser . '_' . time() . '.' . $extension;
            $file->move($uploadDir, $filename);
            $user->photo = $filename;
        }

        $user->save();

        // Update session display name if applicable
        session(['user_name' => $user->nama, 'username' => $user->username]);

        return redirect()->route('profile.edit')->with('success', 'Data akun profil berhasil diperbarui!');
    }
}
