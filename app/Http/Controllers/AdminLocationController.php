<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminLocationController extends Controller
{
    public function index()
    {
        $locations = AttendanceLocation::latest()->get();

        $provinces = [];

        try {
            $response = Http::timeout(10)
                ->get('https://wilayah.id/api/provinces.json');

            if ($response->successful()) {
                $provinces = $response->json('data', []);
            }
        } catch (\Throwable $e) {
            $provinces = [];
        }

        return view('admin.locations.index', [
            'locations' => $locations,
            'provinces' => $provinces,
        ]);
    }

    public function cities($province)
    {
        try {
            $response = Http::timeout(10)
                ->get(
                    'https://wilayah.id/api/regencies/' .
                    $province .
                    '.json'
                );

            if (!$response->successful()) {
                return response()->json([
                    'data' => [],
                    'message' => 'Data kota gagal diambil.'
                ], 502);
            }

            return response()->json([
                'data' => $response->json('data', [])
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'data' => [],
                'message' => 'Tidak dapat terhubung ke server wilayah.'
            ], 502);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meter' => ['required', 'integer', 'min:1', 'max:10000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        AttendanceLocation::create([
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'radius_meter' => $validated['radius_meter'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with(
            'success',
            'Lokasi absensi berhasil ditambahkan.'
        );
    }

    public function update(
        Request $request,
        AttendanceLocation $location
    ) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meter' => ['required', 'integer', 'min:1', 'max:10000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $location->update([
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'radius_meter' => $validated['radius_meter'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with(
            'success',
            'Lokasi absensi berhasil diperbarui.'
        );
    }

    public function destroy(AttendanceLocation $location)
    {
        if ($location->users()->exists()) {
            return back()->with(
                'error',
                'Lokasi tidak dapat dihapus karena masih digunakan oleh user.'
            );
        }

        $location->delete();

        return back()->with(
            'success',
            'Lokasi absensi berhasil dihapus.'
        );
    }
}
