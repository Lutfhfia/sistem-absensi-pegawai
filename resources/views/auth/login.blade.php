<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Absensi Pegawai</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .login-card {
            width: 100%;
            max-width: 1050px;
            min-height: 620px;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }

        .brand-panel {
            position: relative;
            min-height: 620px;
            padding: 50px;
            color: #ffffff;
            background:
                linear-gradient(
                    135deg,
                    #0d6efd 0%,
                    #0b5ed7 45%,
                    #084298 100%
                );
            overflow: hidden;
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            top: -100px;
            right: -100px;
        }

        .brand-panel::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            bottom: -80px;
            left: -80px;
        }

        .brand-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-icon {
            width: 82px;
            height: 82px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 22px;
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .brand-icon i {
            font-size: 42px;
        }

        .brand-title {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 18px;
        }

        .brand-description {
            max-width: 430px;
            font-size: 16px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 30px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.95);
        }

        .feature-item i {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            font-size: 14px;
        }

        .login-panel {
            min-height: 620px;
            padding: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .mobile-brand {
            display: none;
        }

        .login-heading {
            margin-bottom: 8px;
            font-size: 32px;
            font-weight: 800;
            color: #1f2937;
        }

        .login-subheading {
            margin-bottom: 32px;
            color: #6b7280;
            font-size: 15px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            z-index: 5;
            font-size: 18px;
        }

        .form-control-custom {
            height: 52px;
            border-radius: 12px;
            border: 1px solid #dbe2ea;
            padding-left: 48px;
            padding-right: 16px;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control-custom {
            padding-right: 52px;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #9ca3af;
            font-size: 18px;
            cursor: pointer;
            z-index: 5;
        }

        .toggle-password:hover {
            color: #0d6efd;
        }

        .remember-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 18px;
            margin-bottom: 25px;
        }

        .form-check {
            margin: 0;
        }

        .form-check-input {
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-check-label {
            cursor: pointer;
            color: #6b7280;
            font-size: 14px;
        }

        .login-button {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 12px;
            background: #0d6efd;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .login-button:hover {
            background: #0b5ed7;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.25);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .login-footer {
            margin-top: 28px;
            text-align: center;
            color: #9ca3af;
            font-size: 13px;
            line-height: 1.6;
        }

        .alert-custom {
            border: none;
            border-radius: 12px;
            font-size: 14px;
        }

        .alert-custom ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .security-info {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px;
            margin-top: 20px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .security-info i {
            color: #0d6efd;
            font-size: 18px;
            margin-top: 1px;
        }

        .security-info-text {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.6;
        }

        @media (max-width: 991.98px) {
            .login-card {
                max-width: 700px;
            }

            .brand-panel {
                display: none;
            }

            .login-panel {
                min-height: auto;
                padding: 45px 35px;
            }

            .mobile-brand {
                display: flex;
                align-items: center;
                gap: 14px;
                margin-bottom: 35px;
            }

            .mobile-brand-icon {
                width: 54px;
                height: 54px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 15px;
                background: #0d6efd;
                color: #ffffff;
            }

            .mobile-brand-icon i {
                font-size: 26px;
            }

            .mobile-brand-title {
                font-size: 20px;
                font-weight: 800;
                color: #1f2937;
            }

            .mobile-brand-subtitle {
                font-size: 12px;
                color: #9ca3af;
                margin-top: 2px;
            }
        }

        @media (max-width: 575.98px) {
            .login-wrapper {
                padding: 15px;
            }

            .login-card {
                min-height: auto;
                border-radius: 18px;
            }

            .login-panel {
                padding: 30px 22px;
            }

            .login-heading {
                font-size: 28px;
            }

            .login-subheading {
                margin-bottom: 25px;
            }

            .remember-wrapper {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <div class="row g-0 h-100">

            <!-- LEFT BRAND PANEL -->
            <div class="col-lg-6">

                <div class="brand-panel">

                    <div class="brand-content">

                        <div class="brand-icon">
                            <i class="bi bi-fingerprint"></i>
                        </div>

                        <h1 class="brand-title">
                            Absensi Pegawai
                        </h1>

                        <p class="brand-description">
                            Sistem absensi pegawai berbasis lokasi dan kamera
                            untuk membantu proses pencatatan kehadiran menjadi
                            lebih cepat, aman, dan terkontrol.
                        </p>

                        <div class="feature-list">

                            <div class="feature-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>
                                    Validasi lokasi menggunakan GPS
                                </span>
                            </div>

                            <div class="feature-item">
                                <i class="bi bi-camera-fill"></i>
                                <span>
                                    Foto absensi melalui kamera perangkat
                                </span>
                            </div>

                            <div class="feature-item">
                                <i class="bi bi-shield-check"></i>
                                <span>
                                    Sistem keamanan perangkat pegawai
                                </span>
                            </div>

                            <div class="feature-item">
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>
                                    Monitoring absensi oleh administrator
                                </span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- RIGHT LOGIN PANEL -->
            <div class="col-lg-6">

                <div class="login-panel">

                    <div class="login-form-wrapper">

                        <!-- MOBILE BRAND -->
                        <div class="mobile-brand">

                            <div class="mobile-brand-icon">
                                <i class="bi bi-fingerprint"></i>
                            </div>

                            <div>
                                <div class="mobile-brand-title">
                                    Absensi Pegawai
                                </div>

                                <div class="mobile-brand-subtitle">
                                    Sistem Kehadiran Pegawai
                                </div>
                            </div>

                        </div>


                        <!-- LOGIN HEADING -->
                        <h2 class="login-heading">
                            Selamat Datang
                        </h2>

                        <p class="login-subheading">
                            Silakan masuk menggunakan akun Anda untuk
                            melanjutkan.
                        </p>


                        <!-- SUCCESS MESSAGE -->
                        @if (session('success'))
                            <div class="alert alert-success alert-custom mb-4">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-check-circle-fill"></i>

                                    <div>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            </div>
                        @endif


                        <!-- ERROR MESSAGE -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-custom mb-4">

                                <div class="d-flex align-items-start gap-2">

                                    <i class="bi bi-exclamation-triangle-fill"></i>

                                    <div>
                                        <strong>
                                            Login gagal.
                                        </strong>

                                        <ul class="mt-1">
                                            @foreach ($errors->all() as $error)
                                                <li>
                                                    {{ $error }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                </div>

                            </div>
                        @endif


                        <!-- LOGIN FORM -->
                        <form
                            id="loginForm"
                            method="POST"
                            action="{{ route('login') }}"
                        >

                            @csrf


                            <!-- EMAIL -->
                            <div class="mb-3">

                                <label
                                    for="email"
                                    class="form-label"
                                >
                                    Email
                                </label>

                                <div class="input-group-custom">

                                    <i class="bi bi-envelope input-icon"></i>

                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control form-control-custom @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="Masukkan email"
                                        autocomplete="email"
                                        required
                                        autofocus
                                    >

                                </div>

                            </div>


                            <!-- PASSWORD -->
                            <div class="mb-3">

                                <label
                                    for="password"
                                    class="form-label"
                                >
                                    Password
                                </label>

                                <div class="password-wrapper">

                                    <div class="input-group-custom">

                                        <i class="bi bi-lock input-icon"></i>

                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            class="form-control form-control-custom @error('password') is-invalid @enderror"
                                            placeholder="Masukkan password"
                                            autocomplete="current-password"
                                            required
                                        >

                                    </div>

                                    <button
                                        type="button"
                                        class="toggle-password"
                                        id="togglePassword"
                                        aria-label="Tampilkan password"
                                    >
                                        <i
                                            class="bi bi-eye"
                                            id="togglePasswordIcon"
                                        ></i>
                                    </button>

                                </div>

                            </div>


                            <!-- DEVICE ID -->
                            <input
    type="hidden"
    name="device_id"
    id="device_id"
>

<input
    type="hidden"
    name="device_name"
    id="device_name"
>

<input
    type="hidden"
    name="device_platform"
    id="device_platform"
>

<input
    type="hidden"
    name="device_browser"
    id="device_browser"
>

<input
    type="hidden"
    name="device_latitude"
    id="device_latitude"
>

<input
    type="hidden"
    name="device_longitude"
    id="device_longitude"
>


                            <!-- REMEMBER -->
                            <div class="remember-wrapper">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="remember"
                                        value="1"
                                        id="remember"
                                        {{ old('remember') ? 'checked' : '' }}
                                    >

                                    <label
                                        class="form-check-label"
                                        for="remember"
                                    >
                                        Ingat saya
                                    </label>

                                </div>

                            </div>


                            <!-- LOGIN BUTTON -->
                            <button
                                type="submit"
                                class="login-button"
                                id="loginButton"
                            >

                                <span id="loginButtonText">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    Masuk ke Sistem
                                </span>

                                <span
                                    id="loginButtonLoading"
                                    class="d-none"
                                >
                                    <span
                                        class="spinner-border spinner-border-sm me-2"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>

                                    Memproses...
                                </span>

                            </button>


                            <!-- SECURITY INFO -->
                            <div class="security-info">

                                <i class="bi bi-shield-lock-fill"></i>

                                <div class="security-info-text">
                                    Perangkat yang digunakan untuk login akan
                                    dikenali oleh sistem sebagai bagian dari
                                    keamanan akun pegawai.
                                </div>

                            </div>

                        </form>


                        <!-- FOOTER -->
                        <div class="login-footer">

                            <div>
                                &copy; {{ date('Y') }} Absensi Pegawai
                            </div>

                            <div>
                                Sistem Informasi Kehadiran Pegawai
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- BOOTSTRAP JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const loginButtonText = document.getElementById('loginButtonText');
    const loginButtonLoading = document.getElementById('loginButtonLoading');

    const deviceInput = document.getElementById('device_id');
    const deviceNameInput = document.getElementById('device_name');
    const devicePlatformInput = document.getElementById('device_platform');
    const deviceBrowserInput = document.getElementById('device_browser');
    const latitudeInput = document.getElementById('device_latitude');
    const longitudeInput = document.getElementById('device_longitude');


    // Device ID
    let deviceId = localStorage.getItem('attendance_device_id');

    if (!deviceId) {

        if (
            window.crypto &&
            typeof window.crypto.randomUUID === 'function'
        ) {
            deviceId = window.crypto.randomUUID();
        } else {
            deviceId =
                'device-' +
                Date.now() +
                '-' +
                Math.random()
                    .toString(36)
                    .substring(2, 15);
        }

        localStorage.setItem(
            'attendance_device_id',
            deviceId
        );
    }

    deviceInput.value = deviceId;


    // Informasi perangkat
    const userAgent = navigator.userAgent;

    let platform = navigator.platform || 'Unknown';
    let deviceName = 'Perangkat Tidak Dikenal';
    let browser = 'Browser Tidak Dikenal';


    // Platform
    if (/Android/i.test(userAgent)) {
        platform = 'Android';
    } else if (/iPhone|iPad|iPod/i.test(userAgent)) {
        platform = 'iOS';
    } else if (/Windows/i.test(userAgent)) {
        platform = 'Windows';
    } else if (/Macintosh|Mac OS X/i.test(userAgent)) {
        platform = 'macOS';
    } else if (/Linux/i.test(userAgent)) {
        platform = 'Linux';
    }


    // Nama perangkat
    if (/iPhone/i.test(userAgent)) {

        deviceName = 'iPhone';

    } else if (/iPad/i.test(userAgent)) {

        deviceName = 'iPad';

    } else if (/Android/i.test(userAgent)) {

        const androidMatch = userAgent.match(
            /Android[^;]*;\s*(?:[a-z]{2}-[A-Z]{2};\s*)?([^;)]+)/
        );

        if (androidMatch && androidMatch[1]) {
            deviceName = androidMatch[1].trim();
        } else {
            deviceName = 'Android Device';
        }

    } else if (/Windows/i.test(userAgent)) {

        deviceName = 'Windows PC';

    } else if (/Macintosh/i.test(userAgent)) {

        deviceName = 'Mac';

    } else if (/Linux/i.test(userAgent)) {

        deviceName = 'Linux Device';
    }


    // Browser
    if (/Edg\//i.test(userAgent)) {

        browser = 'Microsoft Edge';

    } else if (/OPR\//i.test(userAgent)) {

        browser = 'Opera';

    } else if (/Chrome\//i.test(userAgent)) {

        browser = 'Google Chrome';

    } else if (/Firefox\//i.test(userAgent)) {

        browser = 'Mozilla Firefox';

    } else if (/Safari\//i.test(userAgent)) {

        browser = 'Safari';
    }


    deviceNameInput.value = deviceName;
    devicePlatformInput.value = platform;
    deviceBrowserInput.value = browser;


    // Show / hide password
    const togglePassword =
        document.getElementById('togglePassword');

    const passwordInput =
        document.getElementById('password');

    const togglePasswordIcon =
        document.getElementById('togglePasswordIcon');

    togglePassword.addEventListener('click', function () {

        const isPassword =
            passwordInput.getAttribute('type') === 'password';

        passwordInput.setAttribute(
            'type',
            isPassword ? 'text' : 'password'
        );

        if (isPassword) {

            togglePasswordIcon.classList.remove('bi-eye');

            togglePasswordIcon.classList.add('bi-eye-slash');

            togglePassword.setAttribute(
                'aria-label',
                'Sembunyikan password'
            );

        } else {

            togglePasswordIcon.classList.remove('bi-eye-slash');

            togglePasswordIcon.classList.add('bi-eye');

            togglePassword.setAttribute(
                'aria-label',
                'Tampilkan password'
            );
        }
    });


    // GPS wajib
    function getLocation() {

        return new Promise(function (resolve, reject) {

            if (!navigator.geolocation) {

                reject(
                    'Perangkat atau browser tidak mendukung GPS.'
                );

                return;
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {

                    const latitude =
                        position.coords.latitude;

                    const longitude =
                        position.coords.longitude;

                    if (
                        latitude === null ||
                        longitude === null ||
                        latitude === undefined ||
                        longitude === undefined
                    ) {

                        reject(
                            'Lokasi GPS tidak berhasil diperoleh.'
                        );

                        return;
                    }

                    latitudeInput.value = latitude;
                    longitudeInput.value = longitude;

                    resolve();

                },
                function (error) {

                    let message =
                        'Lokasi wajib diaktifkan untuk login.';

                    if (error.code === 1) {
                        message =
                            'Izin lokasi ditolak. Silakan aktifkan lokasi dan izinkan browser mengakses lokasi Anda.';
                    } else if (error.code === 2) {
                        message =
                            'Lokasi tidak tersedia. Pastikan GPS/lokasi perangkat aktif.';
                    } else if (error.code === 3) {
                        message =
                            'Pengambilan lokasi terlalu lama. Pastikan GPS aktif lalu coba lagi.';
                    }

                    reject(message);

                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        });
    }


    // Login
    loginForm.addEventListener('submit', async function (event) {

        event.preventDefault();

        loginButton.disabled = true;

        loginButtonText.classList.add('d-none');

        loginButtonLoading.classList.remove('d-none');


        try {

            await getLocation();

            loginForm.submit();

        } catch (error) {

            loginButton.disabled = false;

            loginButtonText.classList.remove('d-none');

            loginButtonLoading.classList.add('d-none');

            alert(error);
        }

    });

});
</script>

</body>
</html>
