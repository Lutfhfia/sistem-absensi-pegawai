@extends('layouts.app')

@section('title', 'Absensi Pegawai')

@section('content')

    <div class="page-header">

        <h1 class="page-title">
            Absensi Pegawai
        </h1>

        <p class="page-description">
            Lakukan absensi masuk dan pulang menggunakan kamera serta lokasi
            perangkat Anda.
        </p>

    </div>


    @if (!$targetLocation)

        <div class="alert alert-warning">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <strong>Lokasi absensi belum ditentukan.</strong>

            <div class="mt-1">
                Akun Anda belum memiliki lokasi/cabang yang ditugaskan.
                Silakan hubungi Admin.
            </div>

        </div>

    @elseif (!$targetLocation->is_active)

        <div class="alert alert-danger">

            <i class="bi bi-geo-alt-fill me-2"></i>

            <strong>Lokasi absensi tidak aktif.</strong>

            <div class="mt-1">
                Lokasi <strong>{{ $targetLocation->name }}</strong>
                sedang tidak dapat digunakan untuk absensi.
            </div>

        </div>

    @else

        <div class="alert alert-light border">

            <i class="bi bi-geo-alt-fill text-primary me-2"></i>

            Anda ditugaskan pada lokasi:

            <strong>
                {{ $targetLocation->name }}
            </strong>

            <span class="text-muted">
                — Radius {{ $targetLocation->radius_meter }} meter
            </span>

        </div>

    @endif


    <!-- STATISTIK ABSENSI -->

    <div class="row g-3 mb-4">

        <div class="col-6 col-lg-3">

            <div class="stat-card">

                <div class="stat-card-inner">

                    <div>

                        <div class="stat-label">
                            Hari Ini
                        </div>

                        <div class="stat-value">
                            {{ $todayAttendance ? '1' : '0' }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-6 col-lg-3">

            <div class="stat-card">

                <div class="stat-card-inner">

                    <div>

                        <div class="stat-label">
                            Absen Masuk
                        </div>

                        <div class="stat-value" style="font-size: 17px;">

                            @if ($todayAttendance && $todayAttendance->waktu_masuk)

                                {{ \Carbon\Carbon::parse($todayAttendance->waktu_masuk)->format('H:i') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-6 col-lg-3">

            <div class="stat-card">

                <div class="stat-card-inner">

                    <div>

                        <div class="stat-label">
                            Absen Pulang
                        </div>

                        <div class="stat-value" style="font-size: 17px;">

                            @if ($todayAttendance && $todayAttendance->waktu_pulang)

                                {{ \Carbon\Carbon::parse($todayAttendance->waktu_pulang)->format('H:i') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-6 col-lg-3">

            <div class="stat-card">

                <div class="stat-card-inner">

                    <div>

                        <div class="stat-label">
                            Status
                        </div>

                        <div class="stat-value" style="font-size: 17px;">

                            @if ($todayAttendance)

                                @if ($todayAttendance->waktu_pulang)
                                    Selesai
                                @else
                                    Aktif
                                @endif

                            @else

                                Belum Absen

                            @endif

                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-person-check"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ALERT -->

    <div
        id="cameraAlert"
        class="alert d-none"
        role="alert"
    ></div>


    <!-- ABSENSI -->

    <div class="row g-4">


        <!-- CAMERA -->

        <div class="col-lg-7">

            <div class="app-card">

                <div class="app-card-header">

                    <div>

                        <h2 class="app-card-title">
                            Kamera Absensi
                        </h2>

                        <p class="app-card-subtitle">
                            Pastikan wajah terlihat jelas sebelum mengambil foto.
                        </p>

                    </div>

                    <span
                        id="cameraStatusBadge"
                        class="status-badge status-secondary"
                    >
                        <i class="bi bi-camera"></i>
                        Kamera belum aktif
                    </span>

                </div>


                <div class="app-card-body">

                    <div class="camera-container mb-3">

                        <video
                            id="camera"
                            class="camera-video"
                            autoplay
                            playsinline
                            muted
                        ></video>

                        <div class="camera-overlay">

                            <div class="camera-corner top-left"></div>

                            <div class="camera-corner top-right"></div>

                            <div class="camera-corner bottom-left"></div>

                            <div class="camera-corner bottom-right"></div>

                        </div>

                    </div>


                    <div class="d-flex flex-wrap gap-2">

                        <button
                            type="button"
                            id="startCameraButton"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-camera-fill me-1"></i>

                            Aktifkan Kamera

                        </button>


                        <button
                            type="button"
                            id="switchCameraButton"
                            class="btn btn-outline-secondary"
                            disabled
                        >

                            <i class="bi bi-arrow-repeat me-1"></i>

                            Ganti Kamera

                        </button>

                    </div>


                    <div class="mt-3">

                        <small class="text-muted">

                            Gunakan kamera depan jika melakukan absensi
                            menggunakan HP.

                        </small>

                    </div>

                </div>

            </div>

        </div>


        <!-- LOCATION + ACTION -->

        <div class="col-lg-5">


            <!-- LOCATION -->

            <div class="app-card mb-4">

                <div class="app-card-header">

                    <div>

                        <h2 class="app-card-title">
                            Lokasi Anda
                        </h2>

                        <p class="app-card-subtitle">
                            Sistem akan memvalidasi jarak dari lokasi yang
                            ditugaskan.
                        </p>

                    </div>

                    <i class="bi bi-geo-alt-fill text-primary fs-5"></i>

                </div>


                <div class="app-card-body">

                    <div
                        id="gpsStatus"
                        class="gps-status"
                    >

                        <div class="gps-status-icon">

                            <i
                                id="gpsIcon"
                                class="bi bi-geo-alt"
                            ></i>

                        </div>


                        <div>

                            <div
                                id="gpsTitle"
                                class="gps-status-title"
                            >
                                Lokasi belum diperiksa
                            </div>

                            <div
                                id="gpsText"
                                class="gps-status-text"
                            >
                                Tekan tombol cek lokasi untuk mendapatkan
                                koordinat perangkat.
                            </div>

                        </div>

                    </div>


                    <!-- COORDINATES -->

                    <div class="row g-2 mt-3">

                        <div class="col-6">

                            <div class="bg-light rounded-3 p-3">

                                <div
                                    class="text-muted"
                                    style="font-size: 10px;"
                                >
                                    LATITUDE
                                </div>

                                <div
                                    id="latitudeText"
                                    class="fw-bold mt-1"
                                    style="font-size: 12px;"
                                >
                                    -
                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="bg-light rounded-3 p-3">

                                <div
                                    class="text-muted"
                                    style="font-size: 10px;"
                                >
                                    LONGITUDE
                                </div>

                                <div
                                    id="longitudeText"
                                    class="fw-bold mt-1"
                                    style="font-size: 12px;"
                                >
                                    -
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="mt-3">

                        <button
                            type="button"
                            id="checkLocationButton"
                            class="btn btn-outline-primary w-100"
                            {{ !$targetLocation || !$targetLocation->is_active ? 'disabled' : '' }}
                        >

                            <i class="bi bi-crosshair me-1"></i>

                            Cek Lokasi Saya

                        </button>

                    </div>

                </div>

            </div>


            <!-- ATTENDANCE ACTION -->

            <div class="app-card">

                <div class="app-card-header">

                    <div>

                        <h2 class="app-card-title">
                            Aksi Absensi
                        </h2>

                        <p class="app-card-subtitle">
                            Pilih jenis absensi yang ingin dilakukan.
                        </p>

                    </div>

                </div>


                <div class="app-card-body">


                    <!-- MASUK -->

                    <button
                        type="button"
                        id="checkInButton"
                        class="btn btn-primary w-100 mb-2"
                        disabled
                        @if ($todayAttendance && $todayAttendance->waktu_masuk)
                            style="display: none;"
                        @endif
                    >

                        <i class="bi bi-box-arrow-in-right me-1"></i>

                        Absen Masuk

                    </button>


                    <!-- PULANG -->

                    <button
                        type="button"
                        id="checkOutButton"
                        class="btn btn-outline-danger w-100"
                        disabled
                        @if (!$todayAttendance || !$todayAttendance->waktu_masuk || $todayAttendance->waktu_pulang)
                            style="display: none;"
                        @endif
                    >

                        <i class="bi bi-box-arrow-right me-1"></i>

                        Absen Pulang

                    </button>


                    <div
                        id="attendanceRequirement"
                        class="text-center text-muted mt-3"
                        style="font-size: 11px;"
                    >

                        Aktifkan kamera dan cek lokasi terlebih dahulu.

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- RIWAYAT ABSENSI -->

    <div class="app-card mt-4">

        <div class="app-card-header">

            <div>

                <h2 class="app-card-title">
                    Riwayat Absensi
                </h2>

                <p class="app-card-subtitle">
                    Riwayat absensi Anda.
                </p>

            </div>

            <i class="bi bi-clock-history text-primary fs-5"></i>

        </div>


        <div class="table-responsive">

            @if (isset($attendances) && $attendances->count())

                <table class="table">

                    <thead>

                        <tr>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Masuk
                            </th>

                            <th>
                                Pulang
                            </th>

                            <th>
                                Jarak
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($attendances as $attendance)

                            <tr>

                                <td>

                                    <div class="fw-bold">

                                        {{ \Carbon\Carbon::parse($attendance->waktu_masuk ?? $attendance->waktu_absen)->format('d/m/Y') }}

                                    </div>

                                    <small class="text-muted">

                                        {{ \Carbon\Carbon::parse($attendance->waktu_masuk ?? $attendance->waktu_absen)->translatedFormat('l') }}

                                    </small>

                                </td>


                                <td>

                                    @if ($attendance->waktu_masuk)

                                        <span class="status-badge status-success">

                                            <i class="bi bi-check-circle"></i>

                                            {{ \Carbon\Carbon::parse($attendance->waktu_masuk)->format('H:i') }}

                                        </span>

                                    @elseif ($attendance->waktu_absen)

                                        <span class="status-badge status-success">

                                            <i class="bi bi-check-circle"></i>

                                            {{ \Carbon\Carbon::parse($attendance->waktu_absen)->format('H:i') }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if ($attendance->waktu_pulang)

                                        <span class="status-badge status-info">

                                            <i class="bi bi-check-circle"></i>

                                            {{ \Carbon\Carbon::parse($attendance->waktu_pulang)->format('H:i') }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if ($attendance->jarak_masuk !== null)

                                        {{ number_format($attendance->jarak_masuk, 1) }}
                                        meter

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @php

                                        $status = $attendance->status ?? 'hadir';

                                    @endphp


                                    @if (strtolower($status) === 'hadir')

                                        <span class="status-badge status-success">

                                            <i class="bi bi-check-circle"></i>

                                            Hadir

                                        </span>

                                    @elseif (strtolower($status) === 'terlambat')

                                        <span class="status-badge status-warning">

                                            <i class="bi bi-clock"></i>

                                            Terlambat

                                        </span>

                                    @else

                                        <span class="status-badge status-secondary">

                                            {{ ucfirst($status) }}

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

                        Belum ada riwayat absensi

                    </div>

                    <div class="empty-state-text">

                        Data absensi Anda akan muncul di sini.

                    </div>

                </div>

            @endif

        </div>

    </div>


    <canvas
        id="photoCanvas"
        class="d-none"
    ></canvas>


@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const video =
        document.getElementById('camera');

    const canvas =
        document.getElementById('photoCanvas');

    const startCameraButton =
        document.getElementById('startCameraButton');

    const switchCameraButton =
        document.getElementById('switchCameraButton');

    const checkLocationButton =
        document.getElementById('checkLocationButton');

    const checkInButton =
        document.getElementById('checkInButton');

    const checkOutButton =
        document.getElementById('checkOutButton');

    const cameraStatusBadge =
        document.getElementById('cameraStatusBadge');

    const gpsStatus =
        document.getElementById('gpsStatus');

    const gpsTitle =
        document.getElementById('gpsTitle');

    const gpsText =
        document.getElementById('gpsText');

    const gpsIcon =
        document.getElementById('gpsIcon');

    const latitudeText =
        document.getElementById('latitudeText');

    const longitudeText =
        document.getElementById('longitudeText');

    const attendanceRequirement =
        document.getElementById('attendanceRequirement');

    const cameraAlert =
        document.getElementById('cameraAlert');


    let cameraStream = null;

    let currentFacingMode = 'user';

    let cameraReady = false;

    let locationReady = false;

    let insideRadius = false;

    let currentLatitude = null;

    let currentLongitude = null;

    let currentAccuracy = null;


    const targetLocation = {
    id: {{ $targetLocation ? $targetLocation->id : 'null' }},
    name: @json($targetLocation ? $targetLocation->name : null),
    latitude: {{ $targetLocation ? (float) $targetLocation->latitude : 'null' }},
    longitude: {{ $targetLocation ? (float) $targetLocation->longitude : 'null' }},
    radius: {{ $targetLocation ? (int) $targetLocation->radius_meter : 'null' }},
    is_active: {{ $targetLocation && $targetLocation->is_active ? 'true' : 'false' }}
};


    function showAlert(message, type = 'danger') {

        cameraAlert.className =
            'alert alert-' + type;

        cameraAlert.innerHTML =
            '<i class="bi bi-info-circle-fill me-2"></i>' +
            message;

        cameraAlert.classList.remove('d-none');

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

    }


    function hideAlert() {

        cameraAlert.classList.add('d-none');

    }


    function updateAttendanceButtons() {

        const ready =
    cameraReady &&
    locationReady &&
    insideRadius &&
    targetLocation.id !== null &&
    targetLocation.is_active;


        const hasAttendance =
            {{ $todayAttendance ? 'true' : 'false' }};

        const hasCheckIn =
            {{ $todayAttendance && $todayAttendance->waktu_masuk ? 'true' : 'false' }};

        const hasCheckOut =
            {{ $todayAttendance && $todayAttendance->waktu_pulang ? 'true' : 'false' }};


        if (hasAttendance && hasCheckOut) {

            checkInButton.disabled = true;
            checkOutButton.disabled = true;

            attendanceRequirement.innerHTML =
                '<i class="bi bi-check-circle-fill text-success me-1"></i>' +
                'Absensi hari ini sudah lengkap.';

            return;

        }


        if (hasCheckIn) {

            checkInButton.disabled = true;
            checkOutButton.disabled = !ready;

            if (ready) {

                attendanceRequirement.innerHTML =
                    '<i class="bi bi-check-circle-fill text-success me-1"></i>' +
                    'Lokasi valid. Anda dapat melakukan absen pulang.';

            } else {

                attendanceRequirement.innerHTML =
                    'Aktifkan kamera dan pastikan lokasi berada dalam radius.';

            }

            return;

        }


        checkInButton.disabled = !ready;
        checkOutButton.disabled = true;


        if (ready) {

            attendanceRequirement.innerHTML =
                '<i class="bi bi-check-circle-fill text-success me-1"></i>' +
                'Kamera dan lokasi siap. Anda dapat melakukan absen masuk.';

        } else if (targetLocation.id === null) {

            attendanceRequirement.innerHTML =
                'Akun Anda belum memiliki lokasi absensi. Hubungi Admin.';

        } else if (!targetLocation.is_active) {

            attendanceRequirement.innerHTML =
                'Lokasi absensi Anda sedang tidak aktif.';

        } else if (!cameraReady && !locationReady) {

            attendanceRequirement.innerHTML =
                'Aktifkan kamera dan cek lokasi terlebih dahulu.';

        } else if (!cameraReady) {

            attendanceRequirement.innerHTML =
                'Aktifkan kamera terlebih dahulu.';

        } else if (!locationReady) {

            attendanceRequirement.innerHTML =
                'Cek lokasi Anda terlebih dahulu.';

        } else if (!insideRadius) {

            attendanceRequirement.innerHTML =
                'Anda berada di luar radius absensi.';

        }

    }


    async function startCamera() {

        hideAlert();

        if (
            !navigator.mediaDevices ||
            !navigator.mediaDevices.getUserMedia
        ) {

            showAlert(
                'Browser Anda tidak mendukung akses kamera.',
                'danger'
            );

            return;

        }


        try {

            if (cameraStream) {

                cameraStream
                    .getTracks()
                    .forEach(function (track) {

                        track.stop();

                    });

            }


            cameraStream =
                await navigator.mediaDevices.getUserMedia({

                    video: {

                        facingMode: {
                            ideal: currentFacingMode
                        },

                        width: {
                            ideal: 1280
                        },

                        height: {
                            ideal: 720
                        }

                    },

                    audio: false

                });


            video.srcObject =
                cameraStream;


            await video.play();


            cameraReady = true;


            cameraStatusBadge.className =
                'status-badge status-success';


            cameraStatusBadge.innerHTML =
                '<i class="bi bi-camera-fill"></i>' +
                ' Kamera aktif';


            startCameraButton.innerHTML =
                '<i class="bi bi-camera-fill me-1"></i>' +
                ' Kamera Aktif';


            switchCameraButton.disabled =
                false;


            updateAttendanceButtons();

        } catch (error) {

            console.error(error);

            cameraReady = false;


            cameraStatusBadge.className =
                'status-badge status-danger';


            cameraStatusBadge.innerHTML =
                '<i class="bi bi-camera-video-off"></i>' +
                ' Kamera gagal';


            let message =
                'Kamera tidak dapat digunakan.';


            if (error.name === 'NotAllowedError') {

                message =
                    'Izin kamera ditolak. Silakan izinkan akses kamera pada browser.';

            } else if (error.name === 'NotFoundError') {

                message =
                    'Kamera tidak ditemukan pada perangkat ini.';

            } else if (error.name === 'NotReadableError') {

                message =
                    'Kamera sedang digunakan oleh aplikasi lain.';

            }


            showAlert(
                message,
                'danger'
            );


            updateAttendanceButtons();

        }

    }


    async function switchCamera() {

        if (!cameraReady) {
            return;
        }


        currentFacingMode =
            currentFacingMode === 'user'
                ? 'environment'
                : 'user';


        await startCamera();

    }


    function checkLocation() {

        hideAlert();


        if (targetLocation.id === null) {

            showAlert(
                'Akun Anda belum memiliki lokasi/cabang yang ditentukan. Hubungi Admin.',
                'warning'
            );

            return;

        }


        if (!targetLocation.is_active) {

            showAlert(
                'Lokasi absensi Anda sedang tidak aktif.',
                'warning'
            );

            return;

        }


        if (!navigator.geolocation) {

            showAlert(
                'Browser Anda tidak mendukung GPS/location.',
                'danger'
            );

            return;

        }


        checkLocationButton.disabled =
            true;


        checkLocationButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>' +
            'Mengambil lokasi...';


        gpsTitle.innerText =
            'Mencari lokasi...';


        gpsText.innerText =
            'Mohon tunggu beberapa detik.';


        gpsIcon.className =
            'bi bi-arrow-repeat';


        navigator.geolocation.getCurrentPosition(

            function (position) {

                currentLatitude =
                    position.coords.latitude;

                currentLongitude =
                    position.coords.longitude;

                currentAccuracy =
                    position.coords.accuracy;


                latitudeText.innerText =
                    currentLatitude.toFixed(7);


                longitudeText.innerText =
                    currentLongitude.toFixed(7);


                const distanceMeters =
                    hitungJarakHaversine(
                        currentLatitude,
                        currentLongitude,
                        targetLocation.latitude,
                        targetLocation.longitude
                    );


                locationReady =
                    true;


                insideRadius =
                    distanceMeters <= targetLocation.radius;


                if (insideRadius) {

                    gpsTitle.innerText =
                        'Dalam Radius (' +
                        Math.round(distanceMeters) +
                        ' m)';


                    gpsText.innerText =
                        'Lokasi valid. Anda berada di area ' +
                        targetLocation.name +
                        ' dengan radius maksimal ' +
                        targetLocation.radius +
                        ' meter.';


                    gpsIcon.className =
                        'bi bi-check-circle-fill';


                    gpsStatus.style.background =
                        '#f0fdf4';


                    gpsStatus.style.borderColor =
                        '#bbf7d0';


                    gpsIcon.style.color =
                        '#198754';


                } else {

                    gpsTitle.innerText =
                        'Di Luar Radius (' +
                        Math.round(distanceMeters) +
                        ' m)';


                    gpsText.innerText =
                        'Anda berada sekitar ' +
                        Math.round(distanceMeters) +
                        ' meter dari ' +
                        targetLocation.name +
                        '. Maksimal radius ' +
                        targetLocation.radius +
                        ' meter.';


                    gpsIcon.className =
                        'bi bi-exclamation-triangle-fill';


                    gpsStatus.style.background =
                        '#fff5f5';


                    gpsStatus.style.borderColor =
                        '#fecaca';


                    gpsIcon.style.color =
                        '#dc3545';

                }


                checkLocationButton.disabled =
                    false;


                checkLocationButton.innerHTML =
                    '<i class="bi bi-arrow-repeat me-1"></i>' +
                    'Perbarui Lokasi';


                updateAttendanceButtons();

            },

            function (error) {

                locationReady =
                    false;

                insideRadius =
                    false;


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


                gpsTitle.innerText =
                    'Lokasi gagal diperoleh';


                gpsText.innerText =
                    message;


                gpsIcon.className =
                    'bi bi-geo-alt';


                gpsStatus.style.background =
                    '#fff5f5';


                gpsStatus.style.borderColor =
                    '#fecaca';


                gpsIcon.style.color =
                    '#dc3545';


                checkLocationButton.disabled =
                    false;


                checkLocationButton.innerHTML =
                    '<i class="bi bi-crosshair me-1"></i>' +
                    'Coba Lagi';


                showAlert(
                    message,
                    'danger'
                );


                updateAttendanceButtons();

            },

            {

                enableHighAccuracy: true,

                timeout: 15000,

                maximumAge: 0

            }

        );

    }


    function hitungJarakHaversine(
        lat1,
        lon1,
        lat2,
        lon2
    ) {

        const earthRadius =
            6371000;


        const dLat =
            (lat2 - lat1) *
            Math.PI / 180;


        const dLon =
            (lon2 - lon1) *
            Math.PI / 180;


        const a =
            Math.sin(dLat / 2) *
            Math.sin(dLat / 2) +

            Math.cos(lat1 * Math.PI / 180) *
            Math.cos(lat2 * Math.PI / 180) *

            Math.sin(dLon / 2) *
            Math.sin(dLon / 2);


        const c =
            2 *
            Math.atan2(
                Math.sqrt(a),
                Math.sqrt(1 - a)
            );


        return Math.round(
            earthRadius * c
        );

    }


    function capturePhoto() {

        if (
            !cameraReady ||
            !cameraStream
        ) {

            showAlert(
                'Kamera belum aktif.',
                'warning'
            );

            return null;

        }


        const width =
            video.videoWidth;


        const height =
            video.videoHeight;


        if (!width || !height) {

            showAlert(
                'Kamera belum siap mengambil gambar. Silakan tunggu sebentar.',
                'warning'
            );

            return null;

        }


        canvas.width =
            width;

        canvas.height =
            height;


        const context =
            canvas.getContext('2d');


        context.drawImage(
            video,
            0,
            0,
            width,
            height
        );


        return canvas.toDataURL(
            'image/jpeg',
            0.85
        );

    }


    async function submitAttendance(type) {

        hideAlert();


        if (!cameraReady) {

            showAlert(
                'Silakan aktifkan kamera terlebih dahulu.',
                'warning'
            );

            return;

        }


        if (!locationReady) {

            showAlert(
                'Silakan cek lokasi terlebih dahulu.',
                'warning'
            );

            return;

        }


        if (!insideRadius) {

            showAlert(
                'Anda berada di luar radius absensi.',
                'warning'
            );

            return;

        }


        const photo =
            capturePhoto();


        if (!photo) {
            return;
        }


        const button =
            type === 'masuk'
                ? checkInButton
                : checkOutButton;


        const originalButtonHtml =
            button.innerHTML;


        button.disabled =
            true;


        checkInButton.disabled =
            true;

        checkOutButton.disabled =
            true;


        button.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>' +
            'Memproses absensi...';


        try {

            const response =
                await fetch(
                    "{{ route('absen.store') }}",
                    {

                        method: 'POST',

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    ?.getAttribute(
                                        'content'
                                    )
                                    ||
                                "{{ csrf_token() }}"

                        },

                        body: JSON.stringify({

                            action:
                                type === 'masuk'
                                    ? 'check_in'
                                    : 'check_out',

                            latitude:
                                currentLatitude,

                            longitude:
                                currentLongitude,

                            accuracy:
                                currentAccuracy,

                            foto:
                                photo

                        })

                    }
                );


            const data =
                await response
                    .json()
                    .catch(function () {
                        return {};
                    });


            if (!response.ok) {

                let message =
                    data.message ||
                    data.errors?.action?.[0] ||
                    data.errors?.foto?.[0] ||
                    data.errors?.latitude?.[0] ||
                    data.errors?.longitude?.[0] ||
                    'Absensi gagal diproses.';


                throw new Error(message);

            }


            showAlert(
                data.message ||
                'Absensi berhasil disimpan.',
                'success'
            );


            button.innerHTML =
                '<i class="bi bi-check-circle-fill me-1"></i>' +
                'Berhasil';


            setTimeout(function () {

                window.location.reload();

            }, 1200);


        } catch (error) {

            console.error(error);


            showAlert(
                error.message ||
                'Terjadi kesalahan saat menyimpan absensi.',
                'danger'
            );


            button.innerHTML =
                originalButtonHtml;


            updateAttendanceButtons();

        }

    }


    startCameraButton.addEventListener(
        'click',
        startCamera
    );


    switchCameraButton.addEventListener(
        'click',
        switchCamera
    );


    if (checkLocationButton) {

        checkLocationButton.addEventListener(
            'click',
            checkLocation
        );

    }


    checkInButton.addEventListener(
        'click',
        function () {

            submitAttendance('masuk');

        }
    );


    checkOutButton.addEventListener(
        'click',
        function () {

            submitAttendance('pulang');

        }
    );


    window.addEventListener(
        'beforeunload',
        function () {

            if (cameraStream) {

                cameraStream
                    .getTracks()
                    .forEach(function (track) {

                        track.stop();

                    });

            }

        }
    );


    updateAttendanceButtons();

});

</script>

@endpush
