@extends('layouts.app')

@section('title', 'Rekapan Absensi')

@section('content')

<style>
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .page-subtitle {
        color: #6b7280;
        margin: 0;
    }

    .filter-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .filter-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 16px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 .2rem rgba(99, 102, 241, .12);
    }

    .btn-filter {
        min-height: 44px;
        border-radius: 10px;
        font-weight: 600;
        padding: 0 18px;
    }

    .table-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
    }

    .table-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .table-subtitle {
        color: #6b7280;
        font-size: 13px;
        margin: 4px 0 0;
    }

    .table-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .rekap-table {
        width: 100%;
        min-width: 1100px;
        border-collapse: collapse;
        margin: 0;
    }

    .rekap-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .rekap-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #374151;
        font-size: 14px;
        vertical-align: middle;
    }

    .rekap-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .rekap-table tbody tr:hover {
        background: #fafafa;
    }

    .pegawai-name {
        font-weight: 600;
        color: #111827;
    }

    .pegawai-nip {
        font-size: 12px;
        color: #6b7280;
        margin-top: 3px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-hadir {
        background: #dcfce7;
        color: #166534;
    }

    .status-terlambat {
        background: #fef3c7;
        color: #92400e;
    }

    .status-izin {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-sakit {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-belum {
        background: #f1f5f9;
        color: #64748b;
    }

    .location-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        white-space: nowrap;
    }

    .distance {
        font-weight: 600;
        white-space: nowrap;
    }

    .detail-btn {
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .summary-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .summary-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 14px;
        min-width: 110px;
    }

    .summary-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 2px;
    }

    .summary-value {
        font-weight: 700;
        color: #1f2937;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }

    .empty-state i {
        font-size: 42px;
        margin-bottom: 12px;
        display: block;
    }

    .empty-state h5 {
        color: #374151;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .detail-section {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 14px;
    }

    .detail-section:last-child {
        margin-bottom: 0;
    }

    .detail-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 12px;
    }

    .detail-item {
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-item:last-child {
        border-bottom: 0;
    }

    .detail-label {
        color: #64748b;
        font-size: 12px;
        margin-bottom: 2px;
    }

    .detail-value {
        color: #1f2937;
        font-size: 13px;
        font-weight: 600;
        word-break: break-word;
    }

    .detail-photo {
        width: 100%;
        max-height: 280px;
        object-fit: contain;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
    }

    .coordinate-box {
        background: #f8fafc;
        border-radius: 10px;
        padding: 10px;
        height: 100%;
    }

    .coordinate-label {
        font-size: 10px;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 3px;
    }

    .coordinate-value {
        font-size: 12px;
        font-weight: 700;
        color: #1f2937;
        word-break: break-all;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 23px;
        }

        .filter-card {
            padding: 16px;
            border-radius: 12px;
        }

        .table-card {
            border-radius: 12px;
        }

        .table-header {
            padding: 16px;
        }

        .summary-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
    }

    .searchable-pegawai { position: relative; }
    .searchable-pegawai > i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#64748b; pointer-events:none; z-index:2; }
    .pegawai-search-input { padding-left:38px; }
    .export-btn { min-height:42px; border-radius:10px; padding:0 16px; display:inline-flex; align-items:center; justify-content:center; gap:7px; font-weight:700; white-space:nowrap; }
    .table-header { display:flex; align-items:center; justify-content:space-between; gap:16px; }
    .rekap-table { min-width:850px; }
    .detail-modal-dialog { max-width:1100px; margin:1rem auto; }
    .detail-modal-content { max-height:calc(100vh - 2rem); }
    .detail-modal-body { overflow-y:auto; max-height:calc(100vh - 150px); overscroll-behavior:contain; }
    .photo-scroll-box { height:190px; max-height:190px; overflow:auto; display:flex; align-items:center; justify-content:center; border:1px solid #e5e7eb; border-radius:10px; background:#f8fafc; padding:6px; }
    .detail-photo { display:block; width:auto !important; max-width:100% !important; max-height:175px !important; object-fit:contain; border-radius:8px; }
    @media (max-width:768px) {
        .filter-card { padding:14px; }
        .filter-card .row { row-gap:12px !important; }
        .filter-card .btn-filter { min-height:44px; }
        .table-header { align-items:stretch; flex-direction:column; gap:10px; }
        .table-header-actions, .export-btn { width:100%; }
        .export-btn { min-height:44px; }
        .rekap-table { min-width:820px; }
        .detail-modal-dialog { margin:.5rem; }
        .detail-modal-content { max-height:calc(100vh - 1rem); }
        .detail-modal-body { max-height:calc(100vh - 125px); padding:12px !important; }
        .photo-scroll-box { height:155px; max-height:155px; }
        .detail-photo { max-height:145px !important; }
    }
</style>

<div class="page-header">
    <h1 class="page-title">
        Rekapan Absensi
    </h1>

    <p class="page-subtitle">
        Lihat dan filter data absensi pegawai, termasuk izin dan sakit.
    </p>
</div>

<div class="filter-card">

    <div class="filter-title">
        <i class="bi bi-funnel me-2"></i>
        Filter Rekapan
    </div>

    <form
        method="GET"
        action="{{ route('rekapan') }}"
    >
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai }}">
            </div>
            <div class="col-12 col-md-4">
                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
            </div>
            <div class="col-12 col-md-4">
                <label for="pegawai_search" class="form-label">Pegawai</label>
                <div class="searchable-pegawai">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control pegawai-search-input" id="pegawai_search" list="pegawaiOptions" placeholder="Ketik nama atau NIP..." autocomplete="off" value="{{ $userId ? optional($users->firstWhere('id', $userId))->name : '' }}">
                </div>
                <datalist id="pegawaiOptions">
                    @foreach ($users as $user)
                        <option value="{{ $user->name }}{{ $user->nip ? ' — ' . $user->nip : '' }}" data-id="{{ $user->id }}"></option>
                    @endforeach
                </datalist>
                <input type="hidden" id="user_id" name="user_id" value="{{ $userId ?? '' }}">
            </div>
            
            <div class="col-12 text-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-search me-1"></i> Tampilkan
                </button>
                <a href="{{ route('rekapan') }}" class="btn btn-outline-secondary px-3 ms-2" title="Reset filter">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </div>
    </form>

    <hr class="my-4 text-muted">

    <div class="summary-row mt-0">

        <div class="summary-item">
            <div class="summary-label">
                Total Rekapan
            </div>

            <div class="summary-value">
                {{ $totalRekapan }}
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">
                Hadir
            </div>

            <div class="summary-value">
                {{ $totalHadir }}
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">
                Terlambat
            </div>

            <div class="summary-value">
                {{ $totalTerlambat }}
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">
                Izin
            </div>

            <div class="summary-value">
                {{ $totalIzin }}
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">
                Sakit
            </div>

            <div class="summary-value">
                {{ $totalSakit }}
            </div>
        </div>

    </div>

</div>

<div class="table-card">

    <div class="table-header">

        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">

            <div>
                <h2 class="table-title">
                    Data Rekapan
                </h2>

                <p class="table-subtitle">
                    {{ \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y') }}
                    s/d
                    {{ \Carbon\Carbon::parse($tanggalSelesai)->translatedFormat('d F Y') }}
                </p>
            </div>

            <div class="table-header-actions">

                <a
                    href="{{ route('rekapan.export', [
                        'tanggal_mulai' => $tanggalMulai,
                        'tanggal_selesai' => $tanggalSelesai,
                        'user_id' => $userId,
                    ]) }}"
                    class="btn btn-success export-btn"
                >
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>

            </div>

        </div>

    </div>

    @if (count($rows))

        <div class="table-responsive">

            <table class="rekap-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pegawai</th>
                        <th>Lokasi</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($rows as $row)

                        @php
                            $user = $row['user'];
                            $attendance = $row['attendance'];
                            $permit = $row['permit'];
                            $status = $row['status'];
                        @endphp

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($row['date'])->format('d/m/Y') }}
                            </td>

                            <td>
                                <div class="pegawai-name">
                                    {{ $user->name }}
                                </div>

                                @if ($user->nip)
                                    <div class="pegawai-nip">
                                        NIP: {{ $user->nip }}
                                    </div>
                                @endif
                            </td>

                            <td>

                                @if ($user->location)

                                    <span class="location-badge">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        {{ $user->location->name }}
                                    </span>

                                @else

                                    <span class="status-badge status-belum">
                                        <i class="bi bi-geo-alt"></i>
                                        Belum Ditentukan
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if ($attendance?->waktu_masuk)

                                    {{ $attendance->waktu_masuk->format('H:i:s') }}

                                @else

                                    -

                                @endif

                            </td>

                            <td>

                                @if ($attendance?->waktu_pulang)

                                    {{ $attendance->waktu_pulang->format('H:i:s') }}

                                @else

                                    -

                                @endif

                            </td>

                            <td>

                                @if ($status === 'hadir')

                                    <span class="status-badge status-hadir">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Hadir
                                    </span>

                                @elseif ($status === 'terlambat')

                                    <span class="status-badge status-terlambat">
                                        <i class="bi bi-clock-history"></i>
                                        Terlambat
                                    </span>

                                @elseif ($status === 'izin')

                                    <span class="status-badge status-izin">
                                        <i class="bi bi-calendar-event"></i>
                                        Izin
                                    </span>

                                @elseif ($status === 'sakit')

                                    <span class="status-badge status-sakit">
                                        <i class="bi bi-heart-pulse-fill"></i>
                                        Sakit
                                    </span>

                                @else

                                    <span class="status-badge status-belum">
                                        <i class="bi bi-dash-circle"></i>
                                        Belum Hadir
                                    </span>

                                @endif

                            </td>

                            <td>

                                @php
                                    $detailData = [
                                        'name' => $user->name,
                                        'nip' => $user->nip ?? '-',
                                        'date' => \Carbon\Carbon::parse($row['date'])->format('d/m/Y'),
                                        'location' => $user->location->name ?? 'Belum Ditentukan',
                                        'status' => $status,
                                        'checkin' => $attendance?->waktu_masuk?->format('d/m/Y H:i:s') ?? '-',
                                        'checkout' => $attendance?->waktu_pulang?->format('d/m/Y H:i:s') ?? '-',
                                        'latIn' => $attendance?->latitude_masuk ?? '-',
                                        'lngIn' => $attendance?->longitude_masuk ?? '-',
                                        'accuracyIn' => $attendance?->accuracy_masuk !== null
                                            ? $attendance->accuracy_masuk . ' m'
                                            : '-',
                                        'distanceIn' => $attendance?->jarak_masuk !== null
                                            ? number_format($attendance->jarak_masuk, 2, ',', '.') . ' m'
                                            : '-',
                                        'latOut' => $attendance?->latitude_pulang ?? '-',
                                        'lngOut' => $attendance?->longitude_pulang ?? '-',
                                        'accuracyOut' => $attendance?->accuracy_pulang !== null
                                            ? $attendance->accuracy_pulang . ' m'
                                            : '-',
                                        'distanceOut' => $attendance?->jarak_pulang !== null
                                            ? number_format($attendance->jarak_pulang, 2, ',', '.') . ' m'
                                            : '-',
                                        'photoIn' => $attendance?->foto_masuk
                                            ? asset('storage/' . $attendance->foto_masuk)
                                            : '',
                                        'photoOut' => $attendance?->foto_pulang
                                            ? asset('storage/' . $attendance->foto_pulang)
                                            : '',
                                        'deviceIn' => $attendance?->device_name_masuk
                                            ?? $user->device_name
                                            ?? '-',
                                        'platformIn' => $attendance?->device_platform_masuk
                                            ?? $user->device_platform
                                            ?? '-',
                                        'browserIn' => $attendance?->device_browser_masuk
                                            ?? $user->device_browser
                                            ?? '-',
                                        'ipIn' => $attendance?->device_ip_masuk
                                            ?? $user->device_ip
                                            ?? '-',
                                        'deviceOut' => $attendance?->waktu_pulang
                                            ? ($attendance->device_name_pulang ?? $user->device_name ?? '-')
                                            : '-',
                                        'platformOut' => $attendance?->waktu_pulang
                                            ? ($attendance->device_platform_pulang ?? $user->device_platform ?? '-')
                                            : '-',
                                        'browserOut' => $attendance?->waktu_pulang
                                            ? ($attendance->device_browser_pulang ?? $user->device_browser ?? '-')
                                            : '-',
                                        'ipOut' => $attendance?->waktu_pulang
                                            ? ($attendance->device_ip_pulang ?? $user->device_ip ?? '-')
                                            : '-',
                                        'jenis' => $permit?->jenis
                                            ? ucfirst($permit->jenis)
                                            : '-',
                                        'tanggalMulai' => $permit?->tanggal_mulai?->format('d/m/Y') ?? '-',
                                        'tanggalSelesai' => $permit?->tanggal_selesai?->format('d/m/Y') ?? '-',
                                        'alasan' => $permit?->alasan ?? '-',
                                        'statusPengajuan' => $permit?->status
                                            ? ucfirst($permit->status)
                                            : '-',
                                        'approver' => $permit?->approver?->name ?? '-',
                                        'approvedAt' => $permit?->approved_at
                                            ? $permit->approved_at->format('d/m/Y H:i:s')
                                            : '-',
                                        'permitLat' => $permit?->latitude ?? '-',
                                        'permitLng' => $permit?->longitude ?? '-',
                                        'permitAccuracy' => $permit?->accuracy !== null
                                            ? $permit->accuracy . ' m'
                                            : '-',
                                        'permitIp' => $permit?->ip_address ?? '-',
                                        'permitFile' => $permit?->berkas
                                            ? route('admin.izin.file.download', $permit->id)
                                            : '',
                                        'permitFileType' => $permit?->berkas
                                            ? strtolower(pathinfo($permit->berkas, PATHINFO_EXTENSION))
                                            : '',
                                        'permitFileUrl' => $permit?->berkas
                                            ? route('admin.izin.file', $permit->id)
                                            : '',
                                    ];
                                @endphp

                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm detail-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailModal"
                                    data-detail="{{ json_encode($detailData) }}"
                                >
    <i class="bi bi-eye me-1"></i>
    Detail
</button>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="empty-state">

            <i class="bi bi-calendar-x"></i>

            <h5>
                Belum Ada Data Rekapan
            </h5>

            <p class="mb-0">
                Tidak ada data pada periode atau pegawai yang dipilih.
            </p>

        </div>

    @endif

</div>


<div
    class="modal fade"
    id="detailModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-xl modal-dialog-scrollable detail-modal-dialog">

        <div class="modal-content detail-modal-content">

            <div class="modal-header">

                <div>
                    <h5 class="modal-title fw-bold">
                        Detail Rekapan
                    </h5>

                    <small
                        class="text-muted"
                        id="detailModalSubtitle"
                    >
                        -
                    </small>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>

            <div class="modal-body detail-modal-body">

                <div class="detail-section">

                    <div class="detail-section-title">
                        <i class="bi bi-person-fill me-1"></i>
                        Informasi Pegawai
                    </div>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Nama
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailName"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    NIP
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailNip"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Lokasi
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailLocation"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Tanggal
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailDate"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Status
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailStatus"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


                <div id="attendanceWrapper">
                <div
                    class="detail-section"
                    id="attendanceDetailSection"
                >

                    <div class="detail-section-title">
                        <i class="bi bi-fingerprint me-1"></i>
                        Detail Absensi
                    </div>

                    <div class="row g-3">

                        <div class="col-lg-6">

                            <div class="border rounded-3 p-3">

                                <div class="fw-bold mb-3">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    Absen Masuk
                                </div>

                                <div class="mb-3">
                                    <div class="detail-label">
                                        Waktu
                                    </div>

                                    <div
                                        class="detail-value"
                                        id="detailCheckIn"
                                    >
                                        -
                                    </div>
                                </div>

                                <div class="row g-2">

                                    <div class="col-6">
                                        <div class="coordinate-box">
                                            <div class="coordinate-label">
                                                Latitude
                                            </div>

                                            <div
                                                class="coordinate-value"
                                                id="detailLatIn"
                                            >
                                                -
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="coordinate-box">
                                            <div class="coordinate-label">
                                                Longitude
                                            </div>

                                            <div
                                                class="coordinate-value"
                                                id="detailLngIn"
                                            >
                                                -
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="mt-3">
                                    <div class="detail-label">
                                        Jarak dari Lokasi
                                    </div>

                                    <div
                                        class="detail-value"
                                        id="detailDistanceIn"
                                    >
                                        -
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="detail-label">
                                        Accuracy GPS
                                    </div>

                                    <div
                                        class="detail-value"
                                        id="detailAccuracyIn"
                                    >
                                        -
                                    </div>
                                </div>

                            </div>

                        </div>


                        <div class="col-lg-6">

                            <div class="border rounded-3 p-3">

                                <div class="fw-bold mb-3">
                                    <i class="bi bi-box-arrow-right me-1"></i>
                                    Absen Pulang
                                </div>

                                <div class="mb-3">
                                    <div class="detail-label">
                                        Waktu
                                    </div>

                                    <div
                                        class="detail-value"
                                        id="detailCheckOut"
                                    >
                                        -
                                    </div>
                                </div>

                                <div class="row g-2">

                                    <div class="col-6">
                                        <div class="coordinate-box">
                                            <div class="coordinate-label">
                                                Latitude
                                            </div>

                                            <div
                                                class="coordinate-value"
                                                id="detailLatOut"
                                            >
                                                -
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="coordinate-box">
                                            <div class="coordinate-label">
                                                Longitude
                                            </div>

                                            <div
                                                class="coordinate-value"
                                                id="detailLngOut"
                                            >
                                                -
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="mt-3">
                                    <div class="detail-label">
                                        Jarak dari Lokasi
                                    </div>

                                    <div
                                        class="detail-value"
                                        id="detailDistanceOut"
                                    >
                                        -
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="detail-label">
                                        Accuracy GPS
                                    </div>

                                    <div
                                        class="detail-value"
                                        id="detailAccuracyOut"
                                    >
                                        -
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="detail-section">

                    <div class="detail-section-title">
                        <i class="bi bi-phone me-1"></i>
                        Perangkat Absen Masuk
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Device</div>
                                <div class="detail-value" id="detailDeviceIn">-</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Platform</div>
                                <div class="detail-value" id="detailPlatformIn">-</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Browser</div>
                                <div class="detail-value" id="detailBrowserIn">-</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">IP Address</div>
                                <div class="detail-value" id="detailIpIn">-</div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="detail-section">

                    <div class="detail-section-title">
                        <i class="bi bi-phone-fill me-1"></i>
                        Perangkat Absen Pulang
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Device</div>
                                <div class="detail-value" id="detailDeviceOut">-</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Platform</div>
                                <div class="detail-value" id="detailPlatformOut">-</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Browser</div>
                                <div class="detail-value" id="detailBrowserOut">-</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">IP Address</div>
                                <div class="detail-value" id="detailIpOut">-</div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="detail-section">
                    <div class="detail-section-title">
                        <i class="bi bi-clock-history me-1"></i>
                        Login Terakhir
                    </div>

                    <div class="detail-value" id="detailLastLogin">-</div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="detail-section">
                            <div class="detail-section-title">
                                <i class="bi bi-camera-fill me-1"></i>
                                Foto Absen Masuk
                            </div>
                            <div class="photo-scroll-box"><div id="photoInContainer" class="w-100 h-100 d-flex align-items-center justify-content-center"><span class="text-muted">Tidak ada foto masuk.</span></div></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-section">
                            <div class="detail-section-title">
                                <i class="bi bi-camera-fill me-1"></i>
                                Foto Absen Pulang
                            </div>
                            <div class="photo-scroll-box"><div id="photoOutContainer" class="w-100 h-100 d-flex align-items-center justify-content-center"><span class="text-muted">Tidak ada foto pulang.</span></div></div>
                        </div>
                    </div>

                </div>
                </div> <!-- End attendanceWrapper -->

                <div
                    class="detail-section d-none"
                    id="permitDetailSection"
                >

                    <div class="detail-section-title">
                        <i class="bi bi-file-earmark-text me-1"></i>
                        Detail Pengajuan
                    </div>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Jenis
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailJenis"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Tanggal Mulai
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailTanggalMulai"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Tanggal Selesai
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailTanggalSelesai"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Status Pengajuan
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailStatusPengajuan"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Disetujui Oleh
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailApprover"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Waktu Persetujuan
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailApprovedAt"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Alasan
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailAlasan"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="coordinate-box">
                                <div class="coordinate-label">
                                    Latitude Pengajuan
                                </div>

                                <div
                                    class="coordinate-value"
                                    id="detailPermitLat"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="coordinate-box">
                                <div class="coordinate-label">
                                    Longitude Pengajuan
                                </div>

                                <div
                                    class="coordinate-value"
                                    id="detailPermitLng"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Accuracy
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailPermitAccuracy"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    IP Address
                                </div>

                                <div
                                    class="detail-value"
                                    id="detailPermitIp"
                                >
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div id="permitFileContainer"></div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>
</div>


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const detailModal =
        document.getElementById('detailModal');

    if (!detailModal) {
        return;
    }

    detailModal.addEventListener(
        'show.bs.modal',
        function (event) {

            const button =
                event.relatedTarget;

            if (!button) {
                return;
            }

            const data =
                JSON.parse(
                    button.getAttribute(
                        'data-detail'
                    )
                );

            document.getElementById(
                'detailModalSubtitle'
            ).textContent =
                `${data.name} • ${data.date}`;

            document.getElementById(
                'detailName'
            ).textContent =
                data.name;

            document.getElementById(
                'detailNip'
            ).textContent =
                data.nip;

            document.getElementById(
                'detailLocation'
            ).textContent =
                data.location;

            document.getElementById(
                'detailDate'
            ).textContent =
                data.date;

            document.getElementById(
                'detailStatus'
            ).textContent =
                formatStatus(data.status);

            document.getElementById(
                'detailCheckIn'
            ).textContent =
                data.checkin;

            document.getElementById(
                'detailCheckOut'
            ).textContent =
                data.checkout;

            document.getElementById(
                'detailLatIn'
            ).textContent =
                data.latIn;

            document.getElementById(
                'detailLngIn'
            ).textContent =
                data.lngIn;

            document.getElementById(
                'detailAccuracyIn'
            ).textContent =
                data.accuracyIn;

            document.getElementById(
                'detailDistanceIn'
            ).textContent =
                data.distanceIn;

            document.getElementById(
                'detailLatOut'
            ).textContent =
                data.latOut;

            document.getElementById(
                'detailLngOut'
            ).textContent =
                data.lngOut;

            document.getElementById(
                'detailAccuracyOut'
            ).textContent =
                data.accuracyOut;

            document.getElementById(
                'detailDistanceOut'
            ).textContent =
                data.distanceOut;

            document.getElementById(
                'detailDeviceIn'
            ).textContent =
                data.deviceIn;

            document.getElementById(
                'detailPlatformIn'
            ).textContent =
                data.platformIn;

            document.getElementById(
                'detailBrowserIn'
            ).textContent =
                data.browserIn;

            document.getElementById(
                'detailIpIn'
            ).textContent =
                data.ipIn;

            document.getElementById(
                'detailDeviceOut'
            ).textContent =
                data.deviceOut;

            document.getElementById(
                'detailPlatformOut'
            ).textContent =
                data.platformOut;

            document.getElementById(
                'detailBrowserOut'
            ).textContent =
                data.browserOut;

            document.getElementById(
                'detailIpOut'
            ).textContent =
                data.ipOut;

            const photoIn =
                document.getElementById(
                    'photoInContainer'
                );

            const photoOut =
                document.getElementById(
                    'photoOutContainer'
                );

            if (data.photoIn) {

                photoIn.innerHTML = `
                    <img
                        src="${data.photoIn}"
                        class="detail-photo"
                        alt="Foto Absen Masuk"
                    >
                `;

            } else {

                photoIn.innerHTML = `
                    <div class="text-muted py-4 text-center">
                        Tidak ada foto masuk.
                    </div>
                `;
            }

            if (data.photoOut) {

                photoOut.innerHTML = `
                    <img
                        src="${data.photoOut}"
                        class="detail-photo"
                        alt="Foto Absen Pulang"
                    >
                `;

            } else {

                photoOut.innerHTML = `
                    <div class="text-muted py-4 text-center">
                        Belum melakukan absen pulang.
                    </div>
                `;
            }

            const attendanceWrapper =
                document.getElementById(
                    'attendanceWrapper'
                );

            const permitSection =
                document.getElementById(
                    'permitDetailSection'
                );

            if (
                data.status === 'izin' ||
                data.status === 'sakit'
            ) {

                attendanceWrapper
                    .classList
                    .add('d-none');

                permitSection
                    .classList
                    .remove('d-none');

                document.getElementById(
                    'detailJenis'
                ).textContent =
                    data.jenis;

                document.getElementById(
                    'detailTanggalMulai'
                ).textContent =
                    data.tanggalMulai;

                document.getElementById(
                    'detailTanggalSelesai'
                ).textContent =
                    data.tanggalSelesai;

                document.getElementById(
                    'detailAlasan'
                ).textContent =
                    data.alasan;

                document.getElementById(
                    'detailStatusPengajuan'
                ).textContent =
                    data.statusPengajuan;

                document.getElementById(
                    'detailApprover'
                ).textContent =
                    data.approver;

                document.getElementById(
                    'detailApprovedAt'
                ).textContent =
                    data.approvedAt;

                document.getElementById(
                    'detailPermitLat'
                ).textContent =
                    data.permitLat;

                document.getElementById(
                    'detailPermitLng'
                ).textContent =
                    data.permitLng;

                document.getElementById(
                    'detailPermitAccuracy'
                ).textContent =
                    data.permitAccuracy;

                document.getElementById(
                    'detailPermitIp'
                ).textContent =
                    data.permitIp;

                const fileContainer =
                    document.getElementById(
                        'permitFileContainer'
                    );

                fileContainer.innerHTML = '';

                if (data.permitFile) {
                    let fileHtml = '';
                    if (['jpg', 'jpeg', 'png', 'webp'].includes(data.permitFileType)) {
                        fileHtml = `
                            <div class="mb-3">
                                <img src="${data.permitFileUrl}" class="img-fluid rounded border" style="width: 100%; max-height: 50vh; object-fit: contain;">
                            </div>
                        `;
                    } else if (data.permitFileType === 'pdf') {
                        fileHtml = `
                            <div class="mb-3">
                                <iframe src="${data.permitFileUrl}" style="width: 100%; height: 50vh; border: 0;" class="rounded border"></iframe>
                            </div>
                        `;
                    }

                    fileContainer.innerHTML = fileHtml + `
                        <a
                            href="${data.permitFile}"
                            class="btn btn-outline-primary btn-sm w-100"
                            download
                        >
                            <i class="bi bi-download me-1"></i>
                            Download Lampiran
                        </a>
                    `;
                }

            } else {

                attendanceWrapper
                    .classList
                    .remove('d-none');

                permitSection
                    .classList
                    .add('d-none');
            }

        }
    );

    function formatStatus(status) {

        if (status === 'hadir') {
            return 'Hadir';
        }

        if (status === 'terlambat') {
            return 'Terlambat';
        }

        if (status === 'izin') {
            return 'Izin';
        }

        if (status === 'sakit') {
            return 'Sakit';
        }

        return 'Belum Hadir';
    }

});

    const pegawaiSearch = document.getElementById('pegawai_search');
    const pegawaiHidden = document.getElementById('user_id');
    const pegawaiOptions = document.querySelectorAll('#pegawaiOptions option');
    if (pegawaiSearch && pegawaiHidden) {
        pegawaiSearch.addEventListener('input', function () {
            const value = this.value.trim().toLowerCase();
            let matchedId = '';
            pegawaiOptions.forEach(function (option) {
                if (option.value.trim().toLowerCase() === value) matchedId = option.dataset.id || '';
            });
            pegawaiHidden.value = value === '' ? '' : matchedId;
        });
    }
</script>

@endpush

@endsection