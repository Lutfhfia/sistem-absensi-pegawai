<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Permit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapAttendanceExportController extends Controller
{
    public function export(Request $request)
    {
        $tanggalMulai = $request->input(
            'tanggal_mulai',
            Carbon::now('Asia/Jakarta')->startOfMonth()->toDateString()
        );

        $tanggalSelesai = $request->input(
            'tanggal_selesai',
            Carbon::now('Asia/Jakarta')->endOfMonth()->toDateString()
        );

        if ($tanggalMulai > $tanggalSelesai) {
            [$tanggalMulai, $tanggalSelesai] = [
                $tanggalSelesai,
                $tanggalMulai,
            ];
        }

        $userId = $request->input('user_id');

        $query = Attendance::with([
            'user.location',
        ])
            ->whereBetween('tanggal', [
                $tanggalMulai,
                $tanggalSelesai,
            ])
            ->whereHas('user', function ($query) {
                $query->where('role', 'pegawai');
            })
            ->orderBy('tanggal')
            ->orderBy('waktu_masuk');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $attendances = $query->get();

        $permitsQuery = Permit::where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $tanggalSelesai)
            ->whereDate('tanggal_selesai', '>=', $tanggalMulai);

        if ($userId) {
            $permitsQuery->where('user_id', $userId);
        }

        $permits = $permitsQuery->get();

        $users = \App\Models\User::where('role', 'pegawai')->with('location')->get();
        $usersById = $users->keyBy('id');
        $permitByUser = $permits->groupBy('user_id');

        $rows = collect();

        foreach ($attendances as $attendance) {
            $user = $attendance->user;
            $date = $attendance->tanggal
                ? $attendance->tanggal->toDateString()
                : optional($attendance->waktu_masuk)->toDateString();

            $permit = null;
            if ($date && isset($permitByUser[$attendance->user_id])) {
                $permit = $permitByUser[$attendance->user_id]
                    ->first(function ($item) use ($date) {
                        return $item->tanggal_mulai->toDateString() <= $date
                            && $item->tanggal_selesai->toDateString() >= $date;
                    });
            }

            $rows->push([
                'date' => $date,
                'user' => $user,
                'attendance' => $attendance,
                'permit' => $permit,
                'status' => strtolower($attendance->status ?? 'hadir'),
            ]);
        }

        foreach ($permits as $permit) {
            $startDate = max($permit->tanggal_mulai->toDateString(), $tanggalMulai);
            $endDate = min($permit->tanggal_selesai->toDateString(), $tanggalSelesai);
            
            $currentDate = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            
            while ($currentDate->lte($end)) {
                $dateStr = $currentDate->toDateString();
                
                $exists = $rows->contains(function ($row) use ($permit, $dateStr) {
                    return $row['user']->id === $permit->user_id && $row['date'] === $dateStr;
                });
                
                if (!$exists) {
                    $user = $usersById->get($permit->user_id);
                    if ($user) {
                        $rows->push([
                            'date' => $dateStr,
                            'user' => $user,
                            'attendance' => null,
                            'permit' => $permit,
                            'status' => strtolower($permit->jenis),
                        ]);
                    }
                }
                
                $currentDate->addDay();
            }
        }

        $rows = $rows->sortBy('date')->values();

        $filename = 'rekapan_absensi_' . $tanggalMulai . '_' . $tanggalSelesai . '.csv';

        return response()->streamDownload(function () use (
            $rows,
            $tanggalMulai,
            $tanggalSelesai
        ) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'REKAPAN ABSENSI PEGAWAI',
            ], ';');

            fputcsv($handle, [
                'Periode',
                Carbon::parse($tanggalMulai)->format('d/m/Y') . ' s/d ' .
                Carbon::parse($tanggalSelesai)->format('d/m/Y'),
            ], ';');

            fputcsv($handle, [
                'Dicetak',
                Carbon::now('Asia/Jakarta')->format('d/m/Y H:i:s') . ' WIB',
            ], ';');

            fputcsv($handle, [], ';');

            fputcsv($handle, [
                'No',
                'Tanggal',
                'NIP',
                'Pegawai',
                'Lokasi',
                'Masuk',
                'Jarak Masuk (m)',
                'Accuracy Masuk (m)',
                'Latitude Masuk',
                'Longitude Masuk',
                'Pulang',
                'Jarak Pulang (m)',
                'Accuracy Pulang (m)',
                'Latitude Pulang',
                'Longitude Pulang',
                'Status',
                'Foto Masuk',
                'Foto Pulang',
                'Device Masuk',
                'Platform Masuk',
                'Browser Masuk',
                'IP Masuk',
                'Device Pulang',
                'Platform Pulang',
                'Browser Pulang',
                'IP Pulang',
                'Jenis Izin/Sakit',
                'Periode Izin/Sakit',
            ], ';');

            foreach ($rows as $index => $row) {
                $attendance = $row['attendance'];
                $permit = $row['permit'];
                $user = $row['user'];

                fputcsv($handle, [
                    $index + 1,
                    Carbon::parse($row['date'])->format('d/m/Y'),
                    $user->nip ?? '-',
                    $user->name ?? '-',
                    $user->location?->name ?? '-',
                    $attendance?->waktu_masuk?->format('H:i:s') ?? '-',
                    $attendance?->jarak_masuk !== null
                        ? number_format((float) $attendance->jarak_masuk, 2, ',', '')
                        : '-',
                    $attendance?->accuracy_masuk !== null
                        ? number_format((float) $attendance->accuracy_masuk, 2, ',', '')
                        : '-',
                    $attendance?->latitude_masuk ?? '-',
                    $attendance?->longitude_masuk ?? '-',
                    $attendance?->waktu_pulang?->format('H:i:s') ?? '-',
                    $attendance?->jarak_pulang !== null
                        ? number_format((float) $attendance->jarak_pulang, 2, ',', '')
                        : '-',
                    $attendance?->accuracy_pulang !== null
                        ? number_format((float) $attendance->accuracy_pulang, 2, ',', '')
                        : '-',
                    $attendance?->latitude_pulang ?? '-',
                    $attendance?->longitude_pulang ?? '-',
                    ucfirst($row['status']),
                    $attendance?->foto_masuk
                        ? asset('storage/' . $attendance->foto_masuk)
                        : '-',
                    $attendance?->foto_pulang
                        ? asset('storage/' . $attendance->foto_pulang)
                        : '-',
                    $attendance?->device_name_masuk ?? $user->device_name ?? '-',
                    $attendance?->device_platform_masuk ?? $user->device_platform ?? '-',
                    $attendance?->device_browser_masuk ?? $user->device_browser ?? '-',
                    $attendance?->device_ip_masuk ?? $user->device_ip ?? '-',
                    $attendance?->waktu_pulang ? ($attendance->device_name_pulang ?? $user->device_name ?? '-') : '-',
                    $attendance?->waktu_pulang ? ($attendance->device_platform_pulang ?? $user->device_platform ?? '-') : '-',
                    $attendance?->waktu_pulang ? ($attendance->device_browser_pulang ?? $user->device_browser ?? '-') : '-',
                    $attendance?->waktu_pulang ? ($attendance->device_ip_pulang ?? $user->device_ip ?? '-') : '-',
                    $permit?->jenis ? ucfirst($permit->jenis) : '-',
                    $permit
                        ? $permit->tanggal_mulai->format('d/m/Y') . ' - ' . $permit->tanggal_selesai->format('d/m/Y')
                        : '-',
                ], ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
