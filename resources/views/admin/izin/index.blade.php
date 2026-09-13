@extends('layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')

<div class="page-header">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h1 class="page-title">
                Pengajuan Izin / Sakit
            </h1>

            <p class="page-description">
                Kelola dan verifikasi pengajuan izin atau sakit dari pegawai.
            </p>
        </div>

        <div class="text-muted" style="font-size: 13px;">
            <i class="bi bi-file-earmark-text me-1"></i>
            Manajemen Pengajuan
        </div>
    </div>
</div>


<div class="row g-3 mb-4">

    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-inner">
                <div>
                    <div class="stat-label">
                        Total Pengajuan
                    </div>

                    <div class="stat-value">
                        {{ $permitStats['total'] ?? 0 }}
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-files"></i>
                </div>
            </div>
        </div>
    </div>


    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-inner">
                <div>
                    <div class="stat-label">
                        Pending
                    </div>

                    <div class="stat-value">
                        {{ $permitStats['pending'] ?? 0 }}
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>


    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-inner">
                <div>
                    <div class="stat-label">
                        Disetujui
                    </div>

                    <div class="stat-value">
                        {{ $permitStats['disetujui'] ?? 0 }}
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>


    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-inner">
                <div>
                    <div class="stat-label">
                        Ditolak
                    </div>

                    <div class="stat-value">
                        {{ $permitStats['ditolak'] ?? 0 }}
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="app-card mb-4">

    <div class="app-card-header">
        <div>
            <h2 class="app-card-title">
                Filter Pengajuan
            </h2>

            <p class="app-card-subtitle">
                Cari dan filter pengajuan pegawai.
            </p>
        </div>
    </div>


    <form
        method="GET"
        action="{{ route('admin.izin.index') }}"
    >

        <div class="row g-3">

            <div class="col-lg-5">

                <label class="form-label">
                    Cari Pegawai
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Nama atau NIP pegawai..."
                    >

                </div>

            </div>


            <div class="col-lg-3">

                <label class="form-label">
                    Jenis
                </label>

                <select
                    name="jenis"
                    class="form-select"
                >

                    <option value="">
                        Semua Jenis
                    </option>

                    <option
                        value="izin"
                        {{ $jenis === 'izin' ? 'selected' : '' }}
                    >
                        Izin
                    </option>

                    <option
                        value="sakit"
                        {{ $jenis === 'sakit' ? 'selected' : '' }}
                    >
                        Sakit
                    </option>

                </select>

            </div>


            <div class="col-lg-3">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="pending"
                        {{ $status === 'pending' ? 'selected' : '' }}
                    >
                        Pending
                    </option>

                    <option
                        value="disetujui"
                        {{ $status === 'disetujui' ? 'selected' : '' }}
                    >
                        Disetujui
                    </option>

                    <option
                        value="ditolak"
                        {{ $status === 'ditolak' ? 'selected' : '' }}
                    >
                        Ditolak
                    </option>

                </select>

            </div>


            <div class="col-lg-1 d-flex align-items-end">

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                    title="Filter"
                >
                    <i class="bi bi-funnel-fill"></i>
                </button>

            </div>

        </div>

    </form>


    @if ($search || $jenis || $status)

        <div class="mt-3">

            <a
                href="{{ route('admin.izin.index') }}"
                class="btn btn-sm btn-outline-secondary"
            >
                <i class="bi bi-arrow-counterclockwise me-1"></i>
                Reset Filter
            </a>

        </div>

    @endif

</div>


<div class="app-card">

    <div class="app-card-header">

        <div>
            <h2 class="app-card-title">
                Daftar Pengajuan
            </h2>

            <p class="app-card-subtitle">
                {{ $permits->count() }} pengajuan ditemukan.
            </p>
        </div>

        <span class="status-badge status-warning">
            <i class="bi bi-hourglass-split"></i>
            {{ $permitStats['pending'] ?? 0 }} Pending
        </span>

    </div>


    <div class="table-responsive">

        @if ($permits->count())

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>
                            Pegawai
                        </th>

                        <th>
                            Jenis
                        </th>

                        <th>
                            Periode
                        </th>

                        <th>
                            Alasan
                        </th>

                        <th>
                            Lampiran
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Aksi
                        </th>

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
                                    NIP:
                                    {{ $permit->user->nip ?? '-' }}
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

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-secondary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#reasonModal"
                                    data-reason="{{ $permit->alasan }}"
                                    data-name="{{ $permit->user->name ?? 'Pegawai' }}"
                                    data-jenis="{{ ucfirst($permit->jenis) }}"
                                >
                                    <i class="bi bi-eye me-1"></i>
                                    Lihat
                                </button>

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
                                        Tidak ada
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if ($permitStatus === 'pending')

                                    <span class="status-badge status-warning">
                                        <i class="bi bi-hourglass-split"></i>
                                        Pending
                                    </span>

                                @elseif ($permitStatus === 'disetujui')

                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Disetujui
                                    </span>

                                    @if ($permit->approver)

                                        <div
                                            class="text-muted mt-1"
                                            style="font-size: 11px;"
                                        >
                                            Oleh:
                                            {{ $permit->approver->name }}
                                        </div>

                                    @endif

                                @elseif ($permitStatus === 'ditolak')

                                    <span class="status-badge status-danger">
                                        <i class="bi bi-x-circle-fill"></i>
                                        Ditolak
                                    </span>

                                    @if ($permit->approver)

                                        <div
                                            class="text-muted mt-1"
                                            style="font-size: 11px;"
                                        >
                                            Oleh:
                                            {{ $permit->approver->name }}
                                        </div>

                                    @endif

                                @else

                                    <span class="status-badge status-secondary">
                                        {{ ucfirst($permitStatus) }}
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if ($permitStatus === 'pending')

                                    <div class="d-flex gap-1">

                                        <form
                                            method="POST"
                                            action="{{ route('admin.izin.status', $permit->id) }}"
                                            onsubmit="return confirm('Setujui pengajuan ini?')"
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
                                            >
                                                <i class="bi bi-check-lg"></i>
                                            </button>

                                        </form>


                                        <form
                                            method="POST"
                                            action="{{ route('admin.izin.status', $permit->id) }}"
                                            onsubmit="return confirm('Tolak pengajuan ini?')"
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
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>

                                        </form>

                                    </div>

                                @else

                                    <form
                                        method="POST"
                                        action="{{ route('admin.izin.status', $permit->id) }}"
                                        onsubmit="return confirm('Kembalikan status menjadi pending?')"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="status"
                                            value="pending"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-warning"
                                            title="Kembalikan ke Pending"
                                        >
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty-state py-5">

                <div class="empty-state-icon">
                    <i class="bi bi-file-earmark-x"></i>
                </div>

                <div class="empty-state-title">
                    Belum ada pengajuan
                </div>

                <div class="empty-state-text">
                    Tidak ditemukan pengajuan sesuai filter yang dipilih.
                </div>

            </div>

        @endif

    </div>

</div>


<div
    class="modal fade"
    id="reasonModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <div>

                    <h5 class="modal-title">
                        Alasan Pengajuan
                    </h5>

                    <small
                        class="text-muted"
                        id="reasonApplicant"
                    ></small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <div class="mb-3">

                    <span
                        class="status-badge status-info"
                        id="reasonType"
                    ></span>

                </div>

                <div
                    id="reasonContent"
                    style="
                        white-space: pre-line;
                        line-height: 1.7;
                    "
                ></div>

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

                    <h5 class="modal-title">
                        Lampiran Pengajuan
                    </h5>

                    <small
                        class="text-muted"
                        id="permitFileInfo"
                    ></small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body text-center">

                <div
                    id="permitImageContainer"
                    style="display:none;"
                >

                    <img
                        id="permitImage"
                        src=""
                        alt="Lampiran"
                        class="img-fluid rounded"
                        style="
                            width: 100%;
                            max-height: 65vh;
                            object-fit: contain;
                        "
                    >

                </div>


                <div
                    id="permitPdfContainer"
                    style="display:none;"
                >

                    <iframe
                        id="permitPdf"
                        src=""
                        style="
                            width: 100%;
                            height: 65vh;
                            border: 0;
                        "
                    ></iframe>

                </div>


                <div
                    id="permitOtherContainer"
                    style="display:none;"
                    class="py-5"
                >

                    <i
                        class="bi bi-file-earmark-x"
                        style="font-size: 50px;"
                    ></i>

                    <p class="mt-3 mb-0">
                        Format file tidak dapat ditampilkan langsung.
                    </p>

                </div>

            </div>


            <div class="modal-footer">

                <a
                    href="#"
                    id="permitDownloadBtn"
                    class="btn btn-primary"
                    download
                >
                    <i class="bi bi-download me-1"></i>
                    Download
                </a>

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


@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const reasonModal = document.getElementById('reasonModal');

    if (reasonModal) {

        reasonModal.addEventListener(
            'show.bs.modal',
            function (event) {

                const button = event.relatedTarget;

                const reason =
                    button.getAttribute('data-reason') || '-';

                const name =
                    button.getAttribute('data-name') || 'Pegawai';

                const jenis =
                    button.getAttribute('data-jenis') || '-';

                document.getElementById(
                    'reasonApplicant'
                ).textContent = name;

                document.getElementById(
                    'reasonType'
                ).textContent = jenis;

                document.getElementById(
                    'reasonContent'
                ).textContent = reason;
            }
        );

    }


    const fileModal =
        document.getElementById('permitFileModal');

    if (fileModal) {

        fileModal.addEventListener(
            'show.bs.modal',
            function (event) {

                const button = event.relatedTarget;

                const file =
                    button.getAttribute('data-file');

                const type =
                    (
                        button.getAttribute('data-type') || ''
                    ).toLowerCase();

                const name =
                    button.getAttribute('data-name') || 'Pegawai';

                const jenis =
                    button.getAttribute('data-jenis') || '-';


                const imageContainer =
                    document.getElementById(
                        'permitImageContainer'
                    );

                const pdfContainer =
                    document.getElementById(
                        'permitPdfContainer'
                    );

                const otherContainer =
                    document.getElementById(
                        'permitOtherContainer'
                    );

                const image =
                    document.getElementById(
                        'permitImage'
                    );

                const pdf =
                    document.getElementById(
                        'permitPdf'
                    );

                const downloadBtn =
                    document.getElementById(
                        'permitDownloadBtn'
                    );

                const info =
                    document.getElementById(
                        'permitFileInfo'
                    );


                imageContainer.style.display = 'none';
                pdfContainer.style.display = 'none';
                otherContainer.style.display = 'none';

                image.removeAttribute('src');
                pdf.removeAttribute('src');

                const downloadUrl = button.getAttribute('data-download');
                downloadBtn.href = downloadUrl;

                info.textContent =
                    name + ' • ' + jenis;


                if (
                    type === 'jpg' ||
                    type === 'jpeg' ||
                    type === 'png' ||
                    type === 'webp'
                ) {

                    image.src = file;

                    imageContainer.style.display =
                        'block';

                } else if (type === 'pdf') {

                    pdf.src = file;

                    pdfContainer.style.display =
                        'block';

                } else {

                    otherContainer.style.display =
                        'block';

                }

            }
        );


        fileModal.addEventListener(
            'hidden.bs.modal',
            function () {

                document.getElementById(
                    'permitImage'
                ).removeAttribute('src');

                document.getElementById(
                    'permitPdf'
                ).removeAttribute('src');

            }
        );

    }

});
</script>

@endpush
