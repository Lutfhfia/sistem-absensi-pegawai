<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Permit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $date = $request->input(
            'date',
            Carbon::now('Asia/Jakarta')->toDateString()
        );

        $search = trim($request->input('search', ''));

        $attendanceQuery = Attendance::with([
            'user.location',
        ])->whereDate('tanggal', $date);

        if ($search !== '') {
            $attendanceQuery->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $attendances = $attendanceQuery
            ->orderByDesc('waktu_masuk')
            ->get();

        $totalPegawai = User::where('role', 'pegawai')->count();

        $totalHadir = Attendance::whereDate('tanggal', $date)
            ->whereNotNull('waktu_masuk')
            ->whereHas('user', function ($query) {
                $query->where('role', 'pegawai');
            })
            ->count();

        $totalBelumHadir = max(
            $totalPegawai - $totalHadir,
            0
        );

        $totalPending = Permit::where('status', 'pending')->count();

        $totalIzin = Permit::where('jenis', 'izin')
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $date)
            ->whereDate('tanggal_selesai', '>=', $date)
            ->count();

        $totalSakit = Permit::where('jenis', 'sakit')
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $date)
            ->whereDate('tanggal_selesai', '>=', $date)
            ->count();

        $permits = Permit::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $stats = [
            'pegawai' => $totalPegawai,
            'hadir' => $totalHadir,
            'belum_hadir' => $totalBelumHadir,
            'pending' => $totalPending,
            'izin' => $totalIzin,
            'sakit' => $totalSakit,
        ];

        return view(
            'admin.dashboard',
            compact(
                'attendances',
                'permits',
                'stats',
                'date',
                'search'
            )
        );
    }

    public function permits(Request $request)
    {
        $status = $request->input('status');
        $jenis = $request->input('jenis');
        $search = trim($request->input('search', ''));

        $query = Permit::with([
            'user',
            'approver',
        ])->latest();

        if ($status && in_array($status, [
            'pending',
            'disetujui',
            'ditolak',
        ], true)) {
            $query->where('status', $status);
        }

        if ($jenis && in_array($jenis, [
            'izin',
            'sakit',
        ], true)) {
            $query->where('jenis', $jenis);
        }

        if ($search !== '') {
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $permits = $query->get();

        $permitStats = [
            'total' => Permit::count(),
            'pending' => Permit::where('status', 'pending')->count(),
            'disetujui' => Permit::where('status', 'disetujui')->count(),
            'ditolak' => Permit::where('status', 'ditolak')->count(),
        ];

        return view(
            'admin.izin.index',
            compact(
                'permits',
                'permitStats',
                'status',
                'jenis',
                'search'
            )
        );
    }

    public function updatePermitStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,disetujui,ditolak',
            ],
        ]);

        $permit = Permit::findOrFail($id);
        $status = $validated['status'];

        $permit->update([
            'status' => $status,
            'approved_by' => $status === 'pending'
                ? null
                : auth()->id(),
            'approved_at' => $status === 'pending'
                ? null
                : now('Asia/Jakarta'),
        ]);

        return back()->with(
            'success',
            'Status pengajuan berhasil diperbarui.'
        );
    }

    public function permitFile($id)
    {
        $permit = Permit::findOrFail($id);

        if (!$permit->berkas) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        $path = $permit->berkas;

        if (Storage::disk('public')->exists($path)) {
            return response()->file(
                Storage::disk('public')->path($path)
            );
        }

        if (Storage::disk('local')->exists($path)) {
            return response()->file(
                Storage::disk('local')->path($path)
            );
        }

        abort(404, 'File lampiran tidak ditemukan.');
    }

    public function permitFileDownload($id)
    {
        $permit = Permit::findOrFail($id);

        if (!$permit->berkas) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        $path = $permit->berkas;
        $originalName = 'lampiran-' . $permit->jenis . '-' . $permit->user->name . '-' . now()->format('Ymd');
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $fileName = \Str::slug($originalName) . '.' . $extension;

        if (Storage::disk('public')->exists($path)) {
            return response()->download(
                Storage::disk('public')->path($path),
                $fileName
            );
        }

        if (Storage::disk('local')->exists($path)) {
            return response()->download(
                Storage::disk('local')->path($path),
                $fileName
            );
        }

        abort(404, 'File lampiran tidak ditemukan.');
    }
}
