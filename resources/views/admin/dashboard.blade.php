@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="page-header">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h1 class="page-title">
                Dashboard Admin
            </h1>

            <p class="page-description">
                Monitoring kehadiran dan pengajuan pegawai.
            </p>
        </div>

        <div class="text-muted" style="font-size: 13px;">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>
</div>

<div class="row g-3 mb-4">

    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-inner">
                <div>
                    <div class="stat-label">
                        Total Pegawai
                    </div>

                    <div class="stat-value">
                        {{ $stats['pegawai'] ?? 0 }}
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-inner">
                <div>
                    <div class="stat-label">
                        Hadir Hari Ini
                    </div>

                    <div class="stat-value">
                        {{ $stats['hadir'] ?? 0 }}
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-inner">
                <div>
                    <div class="stat-label">
                        Belum Hadir
                    </div>

                    <div class="stat-value">
                        {{ max(
                            0,
                            ($stats['pegawai'] ?? 0) -
                            ($stats['hadir'] ?? 0)
                        ) }}
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-person-x-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-inner">
                <div>
                    <div class="stat-label">
                        Pengajuan Pending
                    </div>

                    <div class="stat-value">
                        {{ $permits->where('status', 'pending')->count() }}
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="app-card mb-4">

    <div class="app-card-header">
        <div>
            <h2 class="app-card-title">
                Filter Absensi
            </h2>

            <p class="app-card-subtitle">
                Pilih tanggal untuk melihat data absensi.
            </p>
        </div>

        <i class="bi bi-funnel-fill text-primary fs-5"></i>
    </div>

    <div class="app-card-body">

        <form
            method="GET"
            action="{{ route('admin.dashboard') }}"
        >

            <div class="row g-3 align-items-end">

                <div class="col-md-5">

                    <label
                        for="date"
                        class="form-label"
                    >
                        Tanggal
                    </label>

                    <input
                        type="date"
                        id="date"
                        name="date"
                        class="form-control"
                        value="{{ $date ?? now('Asia/Jakarta')->toDateString() }}"
                    >

                </div>

                <div class="col-md-4">

                    <label
                        for="search"
                        class="form-label"
                    >
                        Cari Pegawai
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        placeholder="Nama atau email pegawai..."
                        value="{{ request('search') }}"
                    >

                </div>

                <div class="col-md-3">

                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary flex-grow-1"
                        >
                            <i class="bi bi-search me-1"></i>
                            Tampilkan
                        </button>

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="btn btn-outline-secondary"
                            title="Reset filter"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="app-card mb-4">

    <div class="app-card-header">

        <div>
            <h2 class="app-card-title">
                Data Absensi
            </h2>

            <p class="app-card-subtitle">
                Data kehadiran pegawai pada
                {{ \Carbon\Carbon::parse($date ?? now('Asia/Jakarta')->toDateString())->translatedFormat('d F Y') }}.
            </p>
        </div>

        <span class="status-badge status-info">
            <i class="bi bi-calendar-check"></i>
            {{ $attendances->count() }} Data
        </span>

    </div>

    <div class="table-responsive">

        @if ($attendances->count())

            <table class="table">

                <thead>
                    <tr>
                        <th>Pegawai</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Lokasi</th>
                        <th>Foto Masuk / Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($attendances as $attendance)

                        <tr>

                            <td>

                                <div class="d-flex align-items-center gap-2">

                                    <div
                                        class="user-avatar"
                                        style="width: 36px; height: 36px;"
                                    >
                                        <i class="bi bi-person-fill"></i>
                                    </div>

                                    <div>

                                        <div
                                            class="fw-bold"
                                            style="font-size: 13px;"
                                        >
                                            {{ $attendance->user->name ?? 'Pegawai' }}
                                        </div>

                                        <div
                                            class="text-muted"
                                            style="font-size: 11px;"
                                        >
                                            {{ $attendance->user->email ?? '-' }}
                                        </div>

                                    </div>

                                </div>

                            </td>

                            <td>

                                @php
                                    $waktuMasuk =
                                        $attendance->waktu_masuk
                                        ?? $attendance->waktu_absen
                                        ?? null;
                                @endphp

                                @if ($waktuMasuk)

                                    <span class="status-badge status-success">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                        {{ \Carbon\Carbon::parse($waktuMasuk)->format('H:i') }}
                                    </span>

                                @else

                                    <span class="text-muted">-</span>

                                @endif

                            </td>

                            <td>

                                @if ($attendance->waktu_pulang)

                                    <span class="status-badge status-info">
                                        <i class="bi bi-box-arrow-right"></i>
                                        {{ \Carbon\Carbon::parse($attendance->waktu_pulang)->format('H:i') }}
                                    </span>

                                @else

                                    <span class="text-muted">-</span>

                                @endif

                            </td>

                            <td>

                                @if ($attendance->jarak_masuk !== null)

                                    <div class="fw-bold">
                                        {{ number_format($attendance->jarak_masuk, 1) }} m
                                    </div>

                                    @if ($attendance->latitude_masuk && $attendance->longitude_masuk)

                                        <a
                                            href="https://www.google.com/maps?q={{ $attendance->latitude_masuk }},{{ $attendance->longitude_masuk }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-primary"
                                            style="font-size: 10px;"
                                        >
                                            <i class="bi bi-map me-1"></i>
                                            Lihat lokasi
                                        </a>

                                    @endif

                                @else

                                    <span class="text-muted">-</span>

                                @endif

                            </td>

                            <td>

                                <div class="d-flex flex-column gap-1">

                                    @if ($attendance->foto_masuk || $attendance->foto)

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#photoModal"
                                            data-photo="{{ asset('storage/' . ($attendance->foto_masuk ?: $attendance->foto)) }}"
                                            data-name="{{ $attendance->user->name ?? 'Pegawai' }}"
                                            data-type="Absen Masuk"
                                        >
                                            <i class="bi bi-box-arrow-in-right me-1"></i>
                                            Masuk
                                        </button>

                                    @endif

                                    @if ($attendance->foto_pulang)

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#photoModal"
                                            data-photo="{{ asset('storage/' . $attendance->foto_pulang) }}"
                                            data-name="{{ $attendance->user->name ?? 'Pegawai' }}"
                                            data-type="Absen Pulang"
                                        >
                                            <i class="bi bi-box-arrow-right me-1"></i>
                                            Pulang
                                        </button>

                                    @endif

                                    @if (!$attendance->foto_masuk && !$attendance->foto && !$attendance->foto_pulang)
                                        <span class="text-muted">Tidak ada</span>
                                    @endif

                                </div>

                            </td>

                            <td>

                                @php
                                    $status = strtolower(
                                        $attendance->status ?? 'hadir'
                                    );
                                @endphp

                                @if ($status === 'hadir')

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Hadir
                                    </span>

                                @elseif ($status === 'terlambat')

                                    <span class="status-badge status-warning">
                                        <i class="bi bi-clock-fill"></i>
                                        Terlambat
                                    </span>

                                @else

                                    <span class="status-badge status-secondary">
                                        {{ $attendance->status }}
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty-state">

                <div class="empty-state-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>

                <div class="empty-state-title">
                    Belum ada data absensi
                </div>

                <div class="empty-state-text">
                    Tidak ditemukan data absensi untuk tanggal yang dipilih.
                </div>

            </div>

        @endif

    </div>

</div>

<div class="app-card">

    <div class="app-card-header">

        <div>

            <h2 class="app-card-title">
                Pengajuan Izin / Sakit
            </h2>

            <p class="app-card-subtitle">
                Kelola pengajuan yang masuk dari pegawai.
            </p>

        </div>

        <span class="status-badge status-warning">

            <i class="bi bi-hourglass-split"></i>

            {{ $permits->where('status', 'pending')->count() }} Pending

        </span>

    </div>

    <div class="table-responsive">

        @if ($permits->count())

            <table class="table">

                <thead>

                    <tr>
                        <th>Pegawai</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Alasan</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach ($permits as $permit)

                        @php
                            $permitStatus = strtolower(
                                $permit->status ?? 'pending'
                            );
                        @endphp

                        <tr>

                            <td>

                                <div class="fw-bold">
                                    {{ $permit->user->name ?? 'Pegawai' }}
                                </div>

                                <small class="text-muted">
                                    {{ $permit->user->email ?? '-' }}
                                </small>

                            </td>

                            <td>

                                @if ($permit->jenis === 'sakit')

                                    <span class="status-badge status-danger">
                                        <i class="bi bi-heart-pulse-fill"></i>
                                        Sakit
                                    </span>

                                @else

                                    <span class="status-badge status-info">
                                        <i class="bi bi-file-earmark-text"></i>
                                        Izin
                                    </span>

                                @endif

                            </td>

                            <td>

                                <div>
                                    {{ $permit->tanggal_mulai
                                        ? \Carbon\Carbon::parse($permit->tanggal_mulai)->format('d/m/Y')
                                        : '-' }}
                                </div>

                                @if ($permit->tanggal_selesai)

                                    <div
                                        class="text-muted"
                                        style="font-size: 11px;"
                                    >
                                        s/d
                                        {{ \Carbon\Carbon::parse($permit->tanggal_selesai)->format('d/m/Y') }}
                                    </div>

                                @endif

                            </td>

                            <td>

                                <div
                                    style="
                                        max-width: 250px;
                                        white-space: normal;
                                        line-height: 1.5;
                                    "
                                >
                                    {{ $permit->alasan ?? '-' }}
                                </div>

                            </td>

                            <td>

                                @if ($permit->berkas)

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#permitFileModal"
                                        data-file="{{ route('admin.izin.file', $permit->id) }}"
                                        data-download="{{ route('admin.izin.file.download', $permit->id) }}"
                                        data-type="{{ strtolower(pathinfo($permit->berkas, PATHINFO_EXTENSION)) }}"
                                        data-name="{{ $permit->user->name ?? 'Pegawai' }}"
                                        data-jenis="{{ ucfirst($permit->jenis) }}"
                                    >
                                        <i class="bi bi-paperclip me-1"></i>
                                        Lihat
                                    </button>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if ($permitStatus === 'disetujui')

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Disetujui
                                    </span>

                                @elseif ($permitStatus === 'ditolak')

                                    <span class="status-badge status-danger">
                                        <i class="bi bi-x-circle-fill"></i>
                                        Ditolak
                                    </span>

                                @else

                                    <span class="status-badge status-warning">
                                        <i class="bi bi-hourglass-split"></i>
                                        Pending
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if ($permitStatus === 'pending')

                                    <div class="d-flex gap-1">

                                        <form
                                            method="POST"
                                            action="{{ route('admin.izin.status', $permit->id) }}"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="disetujui"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-success"
                                                title="Setujui"
                                                onclick="return confirm('Setujui pengajuan ini?')"
                                            >
                                                <i class="bi bi-check-lg"></i>
                                            </button>

                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.izin.status', $permit->id) }}"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="ditolak"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Tolak"
                                                onclick="return confirm('Tolak pengajuan ini?')"
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>

                                        </form>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        Selesai
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty-state">

                <div class="empty-state-icon">
                    <i class="bi bi-inbox"></i>
                </div>

                <div class="empty-state-title">
                    Belum ada pengajuan
                </div>

                <div class="empty-state-text">
                    Pengajuan izin atau sakit akan muncul di sini.
                </div>

            </div>

        @endif

    </div>

</div>

<div
    class="modal fade"
    id="photoModal"
    tabindex="-1"
    aria-labelledby="photoModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content border-0 rounded-4 overflow-hidden">

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="photoModalLabel"
                    >
                        Foto Absensi
                    </h5>

                    <div
                        id="photoEmployeeName"
                        class="text-muted"
                        style="font-size: 12px;"
                    ></div>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>

            <div class="modal-body text-center bg-light p-3">

                <img
                    id="attendancePhoto"
                    src=""
                    alt="Foto absensi"
                    class="img-fluid rounded-3"
                    style="
                        max-height: 70vh;
                        object-fit: contain;
                    "
                >

            </div>

        </div>

    </div>

</div>

<div
    class="modal fade"
    id="permitFileModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Lampiran Pengajuan</h5>
                    <small class="text-muted" id="permitFileInfo"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="permitImageContainer" style="display:none;">
                    <img
                        id="permitImage"
                        src=""
                        alt="Lampiran"
                        class="img-fluid rounded"
                        style="width: 100%; max-height: 65vh; object-fit: contain;"
                    >
                </div>
                <div id="permitPdfContainer" style="display:none;">
                    <iframe
                        id="permitPdf"
                        src=""
                        style="width: 100%; height: 65vh; border: 0;"
                    ></iframe>
                </div>
                <div id="permitOtherContainer" style="display:none;" class="py-5">
                    <i class="bi bi-file-earmark-x" style="font-size: 50px;"></i>
                    <p class="mt-3 mb-0">Format file tidak dapat ditampilkan langsung.</p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="permitDownloadBtn" class="btn btn-primary" download>
                    <i class="bi bi-download me-1"></i>
                    Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const photoModal = document.getElementById('photoModal');

    if (!photoModal) {
        return;
    }

    photoModal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        if (!button) {
            return;
        }

        const photo = button.getAttribute('data-photo');
        const name = button.getAttribute('data-name');

        const image = document.getElementById('attendancePhoto');
        const employeeName = document.getElementById('photoEmployeeName');

        image.src = photo || '';
        employeeName.textContent = name || 'Pegawai';
    });

    photoModal.addEventListener('hidden.bs.modal', function () {
        const image = document.getElementById('attendancePhoto');
        image.src = '';
    });

    const fileModal = document.getElementById('permitFileModal');
    if (fileModal) {
        fileModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const file = button.getAttribute('data-file');
            const type = (button.getAttribute('data-type') || '').toLowerCase();
            const name = button.getAttribute('data-name') || 'Pegawai';
            const jenis = button.getAttribute('data-jenis') || '-';

            const imageContainer = document.getElementById('permitImageContainer');
            const pdfContainer = document.getElementById('permitPdfContainer');
            const otherContainer = document.getElementById('permitOtherContainer');
            
            const image = document.getElementById('permitImage');
            const pdf = document.getElementById('permitPdf');
            const downloadBtn = document.getElementById('permitDownloadBtn');
            const info = document.getElementById('permitFileInfo');

            imageContainer.style.display = 'none';
            pdfContainer.style.display = 'none';
            otherContainer.style.display = 'none';

            image.removeAttribute('src');
            pdf.removeAttribute('src');

            downloadBtn.href = button.getAttribute('data-download');
            info.textContent = name + ' • ' + jenis;

            if (['jpg', 'jpeg', 'png', 'webp'].includes(type)) {
                image.src = file;
                imageContainer.style.display = 'block';
            } else if (type === 'pdf') {
                pdf.src = file;
                pdfContainer.style.display = 'block';
            } else {
                otherContainer.style.display = 'block';
            }
        });

        fileModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('permitImage').removeAttribute('src');
            document.getElementById('permitPdf').removeAttribute('src');
        });
    }

});
</script>

@endpush
