<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::with('location')
            ->latest()
            ->get();

        $locations = AttendanceLocation::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact(
            'users',
            'locations'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => [
                'nullable',
                'string',
                'max:50',
                'unique:users,nip',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'role' => [
                'required',
                'in:pegawai,super_admin',
            ],
            'location_id' => [
                'nullable',
                'exists:attendance_locations,id',
            ],
        ]);

        User::create([
            'nip' => $validated['nip'] ?? null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'location_id' => $validated['location_id'] ?? null,
        ]);

        return back()->with(
            'success',
            'User berhasil ditambahkan.'
        );
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nip' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'nip')
                    ->ignore($user->id),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],
            'role' => [
                'required',
                'in:pegawai,super_admin',
            ],
            'location_id' => [
                'nullable',
                'exists:attendance_locations,id',
            ],
        ]);

        $user->update([
            'nip' => $validated['nip'] ?? null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'location_id' => $validated['location_id'] ?? null,
        ]);

        return back()->with(
            'success',
            'Data user berhasil diperbarui.'
        );
    }

    public function resetPassword(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user->update([
            'password' => Hash::make(
                $validated['password']
            ),
        ]);

        return back()->with(
            'success',
            'Password user berhasil direset.'
        );
    }

    public function resetDevice(User $user)
    {
        $user->update([
            'device_id' => null,
            'device_name' => null,
            'device_platform' => null,
            'device_browser' => null,
            'device_ip' => null,
            'device_latitude' => null,
            'device_longitude' => null,
            'device_last_login' => null,
        ]);

        return back()->with(
            'success',
            'Perangkat user berhasil direset.'
        );
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'Anda tidak dapat menghapus akun yang sedang digunakan.'
            );
        }

        $user->delete();

        return back()->with(
            'success',
            'User berhasil dihapus.'
        );
    }
}
