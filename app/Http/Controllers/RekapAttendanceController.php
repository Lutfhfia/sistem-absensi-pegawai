<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Permit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai = $request->input(
            'tanggal_mulai',
            Carbon::now('Asia/Jakarta')
                ->startOfMonth()
                ->toDateString()
        );

        $tanggalSelesai = $request->input(
            'tanggal_selesai',
            Carbon::now('Asia/Jakarta')
                ->endOfMonth()
                ->toDateString()
        );

        if ($tanggalMulai > $tanggalSelesai) {
            [$tanggalMulai, $tanggalSelesai] = [
                $tanggalSelesai,
                $tanggalMulai,
            ];
        }

        $userId = $request->input('user_id');

        $users = User::where('role', 'pegawai')
            ->with('location')
            ->orderBy('name')
            ->get();

        $attendanceQuery = Attendance::with([
            'user.location',
        ])
            ->whereBetween('tanggal', [
                $tanggalMulai,
                $tanggalSelesai,
            ])
            ->whereHas('user', function ($query) {
                $query->where('role', 'pegawai');
            })
            ->orderByDesc('tanggal')
            ->orderBy('waktu_masuk');

        if ($userId) {
            $attendanceQuery->where('user_id', $userId);
        }

        $attendances = $attendanceQuery->get();

        $permitQuery = Permit::with('approver')
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $tanggalSelesai)
            ->whereDate('tanggal_selesai', '>=', $tanggalMulai);

        if ($userId) {
            $permitQuery->where('user_id', $userId);
        }

        $permits = $permitQuery->get();
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

            $status = strtolower($attendance->status ?? 'hadir');

            $rows->push([
                'date' => $date,
                'user' => $user,
                'attendance' => $attendance,
                'permit' => $permit,
                'status' => $status,
            ]);
        }

        $usersById = $users->keyBy('id');

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

        $rows = $rows->sortByDesc('date')->values();

        $totalRekapan = $rows->count();
        $totalHadir = $rows->where('status', 'hadir')->count();
        $totalTerlambat = $rows->where('status', 'terlambat')->count();
        $totalIzin = $rows->where('status', 'izin')->count();
        $totalSakit = $rows->where('status', 'sakit')->count();

        return view('admin.rekapan', [
            'rows' => $rows,
            'users' => $users,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'userId' => $userId,
            'totalRekapan' => $totalRekapan,
            'totalHadir' => $totalHadir,
            'totalTerlambat' => $totalTerlambat,
            'totalIzin' => $totalIzin,
            'totalSakit' => $totalSakit,
        ]);
    }
}
