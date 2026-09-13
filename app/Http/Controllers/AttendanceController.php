<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Permit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        $attendances = Attendance::where('user_id', $user->id)
            ->latest('tanggal')
            ->latest('waktu_masuk')
            ->limit(10)
            ->get();

        $permits = Permit::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        $targetLocation = $user->location;

        return view('pegawai.absen', compact(
            'todayAttendance',
            'attendances',
            'permits',
            'targetLocation'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'action' => [
                'required',
                'in:check_in,check_out',
            ],
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],
            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],
            'accuracy' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'foto' => [
                'required',
                'string',
            ],
        ]);

        $user = auth()->user();

        $waktuServer = Carbon::now('Asia/Jakarta');
        $tanggal = $waktuServer->toDateString();

        $location = $user->location;

        if (!$location) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Anda belum memiliki lokasi/cabang yang ditentukan. Hubungi Admin.',
            ], 422);
        }

        if (!$location->is_active) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Lokasi/cabang Anda sedang tidak aktif. Hubungi Admin.',
            ], 422);
        }

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($data['action'] === 'check_in') {
            if ($attendance) {
                if (
                    $attendance->waktu_masuk &&
                    $attendance->waktu_pulang
                ) {
                    return response()->json([
                        'status' => 'error',
                        'message' =>
                            'Absensi hari ini sudah lengkap. Maksimal 2 kali absensi per hari.',
                    ], 422);
                }

                if ($attendance->waktu_masuk) {
                    return response()->json([
                        'status' => 'error',
                        'message' =>
                            'Anda sudah melakukan absen masuk hari ini.',
                    ], 422);
                }
            }
        }

        if ($data['action'] === 'check_out') {
            if (!$attendance) {
                return response()->json([
                    'status' => 'error',
                    'message' =>
                        'Anda belum melakukan absen masuk hari ini.',
                ], 422);
            }

            if (!$attendance->waktu_masuk) {
                return response()->json([
                    'status' => 'error',
                    'message' =>
                        'Anda belum melakukan absen masuk hari ini.',
                ], 422);
            }

            if ($attendance->waktu_pulang) {
                return response()->json([
                    'status' => 'error',
                    'message' =>
                        'Absensi hari ini sudah lengkap. Maksimal 2 kali absensi per hari.',
                ], 422);
            }
        }

        $jarakMetres = $this->hitungJarakHaversine(
            (float) $data['latitude'],
            (float) $data['longitude'],
            (float) $location->latitude,
            (float) $location->longitude
        );

        if ($jarakMetres > $location->radius_meter) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Absen gagal! Anda berada sekitar ' .
                    round($jarakMetres) .
                    ' meter dari lokasi ' .
                    $location->name .
                    '. Maksimal radius ' .
                    $location->radius_meter .
                    ' meter.',
            ], 422);
        }

        try {
            $fotoPath = $this->simpanFoto(
                $data['foto'],
                $waktuServer,
                $data['latitude'],
                $data['longitude'],
                $jarakMetres,
                $data['action'] === 'check_in'
                    ? 'MASUK'
                    : 'PULANG'
            );
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Foto gagal diproses: ' . $e->getMessage(),
            ], 422);
        }

        if ($data['action'] === 'check_in') {
            $status =
                $waktuServer->format('H:i:s') > '08:00:00'
                    ? 'terlambat'
                    : 'hadir';

            Attendance::create([
                'user_id' => Auth::id(),
                'tanggal' => $tanggal,

                'waktu_masuk' => $waktuServer,
                'waktu_pulang' => null,

                'latitude_masuk' => $data['latitude'],
                'longitude_masuk' => $data['longitude'],
                'jarak_masuk' => round($jarakMetres, 2),
                'accuracy_masuk' => $data['accuracy'] ?? null,
                'foto_masuk' => $fotoPath,

                'device_name_masuk' => $user->device_name,
                'device_platform_masuk' => $user->device_platform,
                'device_browser_masuk' => $user->device_browser,
                'device_ip_masuk' => $request->ip(),
                'device_id_masuk' => $user->device_id,

                'waktu_absen' => $waktuServer,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'jarak_meter' => round($jarakMetres, 2),
                'foto' => $fotoPath,

                'status' => $status,
            ]);

            return response()->json([
                'status' => 'success',
                'message' =>
                    'Absen masuk berhasil pada ' .
                    $waktuServer->format('H:i:s') .
                    ' WIB.',
            ]);
        }

        $attendance->update([
            'waktu_pulang' => $waktuServer,

            'latitude_pulang' => $data['latitude'],
            'longitude_pulang' => $data['longitude'],
            'jarak_pulang' => round($jarakMetres, 2),
            'accuracy_pulang' => $data['accuracy'] ?? null,
            'foto_pulang' => $fotoPath,

            'device_name_pulang' => $user->device_name,
            'device_platform_pulang' => $user->device_platform,
            'device_browser_pulang' => $user->device_browser,
            'device_ip_pulang' => $request->ip(),
            'device_id_pulang' => $user->device_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' =>
                'Absen pulang berhasil pada ' .
                $waktuServer->format('H:i:s') .
                ' WIB.',
        ]);
    }

    private function simpanFoto(
        string $foto,
        Carbon $waktuServer,
        float $latitude,
        float $longitude,
        float $jarakMetres,
        string $jenis
    ): string {
        if (!str_contains($foto, ';base64,')) {
            throw new \RuntimeException(
                'Format foto tidak valid.'
            );
        }

        $imageParts = explode(
            ';base64,',
            $foto,
            2
        );

        $imageDecoded = base64_decode(
            $imageParts[1],
            true
        );

        if ($imageDecoded === false) {
            throw new \RuntimeException(
                'Foto tidak dapat diproses.'
            );
        }

        if (strlen($imageDecoded) < 100) {
            throw new \RuntimeException(
                'Data foto terlalu kecil atau rusak.'
            );
        }

        $imageInfo = @getimagesizefromstring(
            $imageDecoded
        );

        if ($imageInfo === false) {
            throw new \RuntimeException(
                'Data bukan gambar yang valid.'
            );
        }

        $mime = $imageInfo['mime'] ?? '';

        if (!in_array($mime, [
            'image/jpeg',
            'image/png',
            'image/webp',
        ], true)) {
            throw new \RuntimeException(
                'Format gambar tidak didukung.'
            );
        }

        $namaFile =
            'absen_' .
            strtolower($jenis) .
            '_' .
            auth()->id() .
            '_' .
            $waktuServer->timestamp .
            '.jpg';

        $path =
            'absensi/' .
            $namaFile;

        Storage::disk('public')->put(
            $path,
            $imageDecoded
        );

        return $path;
    }

    private function hitungJarakHaversine(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        return $earthRadius *
            2 *
            atan2(
                sqrt($a),
                sqrt(1 - $a)
            );
    }
}
