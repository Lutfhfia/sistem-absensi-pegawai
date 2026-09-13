@extends('layouts.app')

@section('title', 'Pengajuan Izin / Sakit')

@section('content')

<div class="page-header">
    <h1 class="page-title">
        Pengajuan Izin / Sakit
    </h1>

    <p class="page-description">
        Ajukan izin atau sakit jika Anda tidak dapat melakukan absensi di kantor.
    </p>
</div>

@if (session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Pengajuan gagal
        </div>

        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="row g-4">

    <!-- FORM PENGAJUAN -->
    <div class="col-lg-7">

        <div class="app-card">

            <div class="app-card-header">
                <div>
                    <h2 class="app-card-title">
                        Form Pengajuan
                    </h2>

                    <p class="app-card-subtitle">
                        Lengkapi data pengajuan izin atau sakit Anda.
                    </p>
                </div>

                <i class="bi bi-file-earmark-text text-primary fs-5"></i>
            </div>


            <div class="app-card-body">

                <form
                    method="POST"
                    action="{{ route('izin.store') }}"
                    enctype="multipart/form-data"
                    id="permitForm"
                >

                    @csrf

                    <div class="row g-3">

                        <!-- JENIS -->
                        <div class="col-md-6">

                            <label for="jenis" class="form-label">
                                Jenis Pengajuan
                            </label>

                            <select
                                name="jenis"
                                id="jenis"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Pilih jenis pengajuan
                                </option>

                                <option value="izin">
                                    Izin
                                </option>

                                <option value="sakit">
                                    Sakit
                                </option>
                            </select>

                        </div>


                        <!-- TANGGAL MULAI -->
                        <div class="col-md-6">

    <label for="tanggal_mulai" class="form-label">
        Tanggal Mulai
    </label>

    <div class="input-group">

        <input
            type="date"
            name="tanggal_mulai"
            id="tanggal_mulai"
            class="form-control"
            value="{{ old('tanggal_mulai') }}"
            required
        >

        <span class="input-group-text">
            <i class="bi bi-calendar3"></i>
        </span>

    </div>

</div>


                        <!-- TANGGAL SELESAI -->


<div class="col-md-6">

    <label for="tanggal_selesai" class="form-label">
        Tanggal Selesai
    </label>

    <div class="input-group">

        <input
            type="date"
            name="tanggal_selesai"
            id="tanggal_selesai"
            class="form-control"
            value="{{ old('tanggal_selesai') }}"
            required
        >

        <span class="input-group-text">
            <i class="bi bi-calendar3"></i>
        </span>

    </div>

</div>
                        


                        <!-- LAMPIRAN -->
                        <div class="col-md-6">

                            <label for="berkas" class="form-label">
                                Lampiran
                            </label>

                            <input
                                type="file"
                                name="berkas"
                                id="berkas"
                                class="form-control"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required
                            >

                            <div class="form-text">
                                PDF, JPG, JPEG atau PNG. Maksimal 4 MB.
                            </div>

                        </div>


                        <!-- ALASAN -->
                        <div class="col-12">

                            <label for="alasan" class="form-label">
                                Alasan / Keterangan
                            </label>

                            <textarea
                                name="alasan"
                                id="alasan"
                                class="form-control"
                                rows="5"
                                maxlength="2000"
                                placeholder="Tuliskan alasan pengajuan..."
                                required
                            >{{ old('alasan') }}</textarea>

                        </div>

                    </div>


                    <!-- GPS -->
                    <div class="mt-4">

                        <div class="permit-location-card">

                            <div class="permit-location-icon">
                                <i
                                    id="locationIcon"
                                    class="bi bi-geo-alt"
                                ></i>
                            </div>

                            <div class="flex-grow-1">

                                <div
                                    id="locationTitle"
                                    class="permit-location-title"
                                >
                                    Lokasi belum diperiksa
                                </div>

                                <div
                                    id="locationText"
                                    class="permit-location-text"
                                >
                                    GPS wajib diaktifkan saat mengirim
                                    pengajuan. Tidak ada batas radius untuk
                                    izin atau sakit.
                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- KOORDINAT -->
                    <div class="row g-2 mt-2">

                        <div class="col-md-6">

                            <div class="coordinate-box">

                                <div class="coordinate-label">
                                    LATITUDE
                                </div>

                                <div
                                    id="latitudeText"
                                    class="coordinate-value"
                                >
                                    -
                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="coordinate-box">

                                <div class="coordinate-label">
                                    LONGITUDE
                                </div>

                                <div
                                    id="longitudeText"
                                    class="coordinate-value"
                                >
                                    -
                                </div>

                            </div>

                        </div>

                    </div>


                    <input
                        type="hidden"
                        name="latitude"
                        id="latitude"
                    >

                    <input
                        type="hidden"
                        name="longitude"
                        id="longitude"
                    >

                    <input
                        type="hidden"
                        name="accuracy"
                        id="accuracy"
                    >


                    <!-- BUTTON GPS -->
                    <button
                        type="button"
                        id="checkLocationButton"
                        class="btn btn-outline-primary w-100 mt-3"
                    >
                        <i class="bi bi-crosshair me-1"></i>
                        Ambil Lokasi Saya
                    </button>


                    <!-- SUBMIT -->
                    <button
                        type="submit"
                        id="submitButton"
                        class="btn btn-primary w-100 mt-3"
                        disabled
                    >
                        <i class="bi bi-send me-1"></i>
                        Kirim Pengajuan
                    </button>


                    <div
                        id="submitRequirement"
                        class="text-center text-muted mt-2"
                        style="font-size: 11px;"
                    >
                        Ambil lokasi GPS terlebih dahulu.
                    </div>

                </form>

            </div>

        </div>

    </div>


    <!-- INFORMASI -->
    <div class="col-lg-5">

        <div class="app-card mb-4">

            <div class="app-card-header">

                <div>
                    <h2 class="app-card-title">
                        Informasi Pengajuan
                    </h2>

                    <p class="app-card-subtitle">
                        Ketentuan pengajuan izin / sakit.
                    </p>
                </div>

                <i class="bi bi-info-circle text-primary fs-5"></i>

            </div>


            <div class="app-card-body">

                <div class="info-item">

                    <div class="info-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <div>
                        <div class="info-title">
                            GPS Tetap Wajib
                        </div>

                        <div class="info-text">
                            Lokasi Anda akan dicatat saat pengajuan dikirim.
                        </div>
                    </div>

                </div>


                <div class="info-item">

                    <div class="info-icon">
                        <i class="bi bi-radar"></i>
                    </div>

                    <div>
                        <div class="info-title">
                            Tidak Ada Batas Radius
                        </div>

                        <div class="info-text">
                            Pengajuan izin atau sakit dapat dilakukan dari
                            luar area kantor.
                        </div>
                    </div>

                </div>


                <div class="info-item">

                    <div class="info-icon">
                        <i class="bi bi-paperclip"></i>
                    </div>

                    <div>
                        <div class="info-title">
                            Lampiran
                        </div>

                        <div class="info-text">
                            Lampiran wajib berupa PDF, JPG, JPEG atau PNG.
                        </div>
                    </div>

                </div>


                <div class="info-item">

                    <div class="info-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div>
                        <div class="info-title">
                            Menunggu Persetujuan
                        </div>

                        <div class="info-text">
                            Pengajuan akan berstatus pending sampai diproses
                            oleh Admin.
                        </div>
                    </div>

                </div>

            </div>

        </div>


        <div class="app-card">

            <div class="app-card-header">

                <div>
                    <h2 class="app-card-title">
                        Status Pengajuan
                    </h2>

                    <p class="app-card-subtitle">
                        Riwayat pengajuan Anda.
                    </p>
                </div>

                <i class="bi bi-list-check text-primary fs-5"></i>

            </div>


            <div class="table-responsive">

                @if ($permits->count())

                    <table class="table">

                        <thead>
                            <tr>
                                <th>
                                    Jenis
                                </th>

                                <th>
                                    Periode
                                </th>

                                <th>
                                    Status
                                </th>
                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($permits as $permit)

                                <tr>

                                    <td>

                                        @if ($permit->jenis === 'izin')

                                            <span class="status-badge status-info">
                                                <i class="bi bi-envelope"></i>
                                                Izin
                                            </span>

                                        @else

                                            <span class="status-badge status-warning">
                                                <i class="bi bi-heart-pulse"></i>
                                                Sakit
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <div class="fw-bold">
                                            {{ $permit->tanggal_mulai->format('d/m/Y') }}
                                        </div>

                                        @if (
                                            $permit->tanggal_selesai &&
                                            $permit->tanggal_selesai->format('Y-m-d') !==
                                            $permit->tanggal_mulai->format('Y-m-d')
                                        )

                                            <small class="text-muted">
                                                s/d
                                                {{ $permit->tanggal_selesai->format('d/m/Y') }}
                                            </small>

                                        @endif

                                    </td>


                                    <td>

                                        @if ($permit->status === 'pending')

                                            <span class="status-badge status-warning">
                                                <i class="bi bi-clock"></i>
                                                Pending
                                            </span>

                                        @elseif ($permit->status === 'disetujui')

                                            <span class="status-badge status-success">
                                                <i class="bi bi-check-circle"></i>
                                                Disetujui
                                            </span>

                                        @else

                                            <span class="status-badge status-danger">
                                                <i class="bi bi-x-circle"></i>
                                                Ditolak
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
                            <i class="bi bi-file-earmark-x"></i>
                        </div>

                        <div class="empty-state-title">
                            Belum ada pengajuan
                        </div>

                        <div class="empty-state-text">
                            Pengajuan izin atau sakit Anda akan muncul di sini.
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


@endsection


@push('head')

<style>

.permit-location-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
}

.permit-location-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 11px;
    background: #eef5ff;
    color: #0d6efd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.permit-location-title {
    font-size: 13px;
    font-weight: 700;
    color: #374151;
}

.permit-location-text {
    margin-top: 3px;
    font-size: 11px;
    line-height: 1.5;
    color: #6b7280;
}

.coordinate-box {
    padding: 11px 13px;
    background: #f8fafc;
    border: 1px solid #edf0f4;
    border-radius: 10px;
}

.coordinate-label {
    font-size: 9px;
    font-weight: 700;
    color: #9ca3af;
    letter-spacing: .4px;
}

.coordinate-value {
    margin-top: 4px;
    font-size: 12px;
    font-weight: 700;
    color: #374151;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 13px 0;
    border-bottom: 1px solid #edf0f4;
}

.info-item:first-child {
    padding-top: 0;
}

.info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-icon {
    width: 36px;
    height: 36px;
    min-width: 36px;
    border-radius: 9px;
    background: #eef5ff;
    color: #0d6efd;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-title {
    font-size: 12px;
    font-weight: 700;
    color: #374151;
}

.info-text {
    margin-top: 3px;
    font-size: 11px;
    line-height: 1.5;
    color: #9ca3af;
}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const checkLocationButton =
        document.getElementById('checkLocationButton');

    const submitButton =
        document.getElementById('submitButton');

    const submitRequirement =
        document.getElementById('submitRequirement');

    const locationIcon =
        document.getElementById('locationIcon');

    const locationTitle =
        document.getElementById('locationTitle');

    const locationText =
        document.getElementById('locationText');

    const latitudeText =
        document.getElementById('latitudeText');

    const longitudeText =
        document.getElementById('longitudeText');

    const latitudeInput =
        document.getElementById('latitude');

    const longitudeInput =
        document.getElementById('longitude');

    const accuracyInput =
        document.getElementById('accuracy');

    let locationReady = false;


    function updateSubmitButton() {

        if (locationReady) {

            submitButton.disabled = false;

            submitRequirement.innerHTML =
                '<i class="bi bi-check-circle-fill text-success me-1"></i>' +
                'Lokasi GPS siap. Anda dapat mengirim pengajuan.';

        } else {

            submitButton.disabled = true;

            submitRequirement.innerText =
                'Ambil lokasi GPS terlebih dahulu.';

        }

    }


    function checkLocation() {

        if (!navigator.geolocation) {

            locationReady = false;

            locationTitle.innerText =
                'GPS tidak didukung';

            locationText.innerText =
                'Browser Anda tidak mendukung pengambilan lokasi.';

            updateSubmitButton();

            return;
        }


        checkLocationButton.disabled = true;

        checkLocationButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>' +
            'Mengambil lokasi...';

        locationTitle.innerText =
            'Mencari lokasi...';

        locationText.innerText =
            'Mohon tunggu beberapa detik.';

        locationIcon.className =
            'bi bi-arrow-repeat';


        navigator.geolocation.getCurrentPosition(

            function (position) {

                const latitude =
                    position.coords.latitude;

                const longitude =
                    position.coords.longitude;

                const accuracy =
                    position.coords.accuracy;


                latitudeInput.value =
                    latitude;

                longitudeInput.value =
                    longitude;

                accuracyInput.value =
                    accuracy;


                latitudeText.innerText =
                    latitude.toFixed(7);

                longitudeText.innerText =
                    longitude.toFixed(7);


                locationReady = true;


                locationTitle.innerText =
                    'Lokasi berhasil diperoleh';

                locationText.innerText =
                    'Lokasi Anda akan dicatat sebagai bagian dari data pengajuan.';

                locationIcon.className =
                    'bi bi-check-circle-fill';

                locationIcon.style.color =
                    '#198754';


                checkLocationButton.disabled =
                    false;

                checkLocationButton.innerHTML =
                    '<i class="bi bi-arrow-repeat me-1"></i>' +
                    'Perbarui Lokasi';


                updateSubmitButton();

            },

            function (error) {

                locationReady = false;


                let message =
                    'Lokasi tidak dapat diperoleh.';


                if (error.code === 1) {

                    message =
                        'Izin lokasi ditolak. Silakan izinkan akses lokasi pada browser.';

                } else if (error.code === 2) {

                    message =
                        'Lokasi tidak tersedia. Pastikan GPS perangkat aktif.';

                } else if (error.code === 3) {

                    message =
                        'Waktu pengambilan lokasi habis. Silakan coba lagi.';

                }


                locationTitle.innerText =
                    'Lokasi gagal diperoleh';

                locationText.innerText =
                    message;

                locationIcon.className =
                    'bi bi-exclamation-triangle-fill';

                locationIcon.style.color =
                    '#dc3545';


                checkLocationButton.disabled =
                    false;

                checkLocationButton.innerHTML =
                    '<i class="bi bi-crosshair me-1"></i>' +
                    'Coba Lagi';


                updateSubmitButton();

            },

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }

        );

    }



    checkLocationButton.addEventListener(
        'click',
        checkLocation
    );

    updateSubmitButton();

});

</script>

@endpush
