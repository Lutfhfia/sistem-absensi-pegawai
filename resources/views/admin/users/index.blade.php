@extends('layouts.app')

@section('title', 'Manajemen User')

@section('topbar-title', 'Manajemen User')

@section('content')

<div class="page-header">

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

        <div>

            <h1 class="page-title">
                Manajemen User
            </h1>

            <p class="page-description">
                Kelola akun pegawai dan administrator sistem.
            </p>

        </div>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalTambahUser"
        >
            <i class="bi bi-person-plus-fill me-1"></i>
            Tambah User
        </button>

    </div>

</div>


<div class="app-card">

    <div class="app-card-header">

        <div>

            <h2 class="app-card-title">
                Daftar User
            </h2>

            <p class="app-card-subtitle">
                Total {{ $users->count() }} user terdaftar
            </p>

        </div>

        <div class="user-search">

            <div class="input-group">

                <span class="input-group-text bg-white">
                    <i class="bi bi-search"></i>
                </span>

                <input
                    type="text"
                    id="searchUser"
                    class="form-control"
                    placeholder="Cari user..."
                >

            </div>

        </div>

    </div>


    <div class="app-card-body p-0">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>NIP</th>

                        <th>Nama</th>

                        <th>Email</th>

                        <th>Role</th>

                        <th>Lokasi</th>

                        <th>Perangkat</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody id="userTable">

                    @forelse ($users as $user)

                        <tr class="user-row">

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>
                                {{ $user->nip ?? '-' }}
                            </td>


                            <td>

                                <div class="fw-bold">
                                    {{ $user->name }}
                                </div>

                            </td>


                            <td>
                                {{ $user->email }}
                            </td>


                            <td>

                                @if ($user->role === 'super_admin')

                                    <span class="status-badge status-info">

                                        <i class="bi bi-shield-fill-check"></i>

                                        Super Admin

                                    </span>

                                @else

                                    <span class="status-badge status-secondary">

                                        <i class="bi bi-person-fill"></i>

                                        Pegawai

                                    </span>

                                @endif

                            </td>


                            <td>

                                @if ($user->location)

                                    <span class="status-badge status-info">

                                        <i class="bi bi-geo-alt-fill"></i>

                                        {{ $user->location->name }}

                                    </span>

                                @else

                                    <span class="status-badge status-secondary">

                                        <i class="bi bi-geo-alt"></i>

                                        Belum Ditentukan

                                    </span>

                                @endif

                            </td>


                            <td>

                                @if ($user->device_id)

                                    <span class="status-badge status-success">

                                        <i class="bi bi-phone-fill"></i>

                                        Terdaftar

                                    </span>

                                @else

                                    <span class="status-badge status-secondary">

                                        <i class="bi bi-phone"></i>

                                        Belum Terdaftar

                                    </span>

                                @endif

                            </td>


                            <td>

                                <div class="d-flex gap-1">

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditUser{{ $user->id }}"
                                        title="Edit User"
                                    >
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>


                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalPassword{{ $user->id }}"
                                        title="Reset Password"
                                    >
                                        <i class="bi bi-key-fill"></i>
                                    </button>


                                    @if ($user->device_id)

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDevice{{ $user->id }}"
                                            title="Reset Device"
                                        >
                                            <i class="bi bi-phone-fill"></i>
                                        </button>

                                    @endif


                                    @if ($user->id !== auth()->id())

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDelete{{ $user->id }}"
                                            title="Hapus User"
                                        >
                                            <i class="bi bi-trash-fill"></i>
                                        </button>

                                    @endif

                                </div>

                            </td>

                        </tr>


                        <!-- MODAL EDIT USER -->

                        <div
                            class="modal fade"
                            id="modalEditUser{{ $user->id }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >

                            <div class="modal-dialog modal-dialog-centered">

                                <div class="modal-content border-0 shadow">

                                    <form
                                        action="{{ route('admin.users.update', $user) }}"
                                        method="POST"
                                    >

                                        @csrf

                                        @method('PUT')


                                        <div class="modal-header">

                                            <h5 class="modal-title fw-bold">
                                                Edit User
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            <div class="mb-3">

                                                <label class="form-label">
                                                    NIP
                                                </label>

                                                <input
                                                    type="text"
                                                    name="nip"
                                                    class="form-control"
                                                    value="{{ $user->nip }}"
                                                    maxlength="50"
                                                >

                                            </div>


                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Nama Lengkap
                                                </label>

                                                <input
                                                    type="text"
                                                    name="name"
                                                    class="form-control"
                                                    value="{{ $user->name }}"
                                                    required
                                                >

                                            </div>


                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Email
                                                </label>

                                                <input
                                                    type="email"
                                                    name="email"
                                                    class="form-control"
                                                    value="{{ $user->email }}"
                                                    required
                                                >

                                            </div>


                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Role
                                                </label>

                                                <select
                                                    name="role"
                                                    class="form-select"
                                                    required
                                                >

                                                    <option
                                                        value="pegawai"
                                                        {{ $user->role === 'pegawai' ? 'selected' : '' }}
                                                    >
                                                        Pegawai
                                                    </option>

                                                    <option
                                                        value="super_admin"
                                                        {{ $user->role === 'super_admin' ? 'selected' : '' }}
                                                    >
                                                        Super Admin
                                                    </option>

                                                </select>

                                            </div>


                                            <div>

                                                <label class="form-label">
                                                    Lokasi / Cabang
                                                </label>

                                                <select
                                                    name="location_id"
                                                    class="form-select"
                                                >

                                                    <option value="">
                                                        -- Tidak ada lokasi --
                                                    </option>

                                                    @foreach ($locations as $location)

                                                        <option
                                                            value="{{ $location->id }}"
                                                            {{ $user->location_id == $location->id ? 'selected' : '' }}
                                                        >
                                                            {{ $location->name }}
                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-light"
                                                data-bs-dismiss="modal"
                                            >
                                                Batal
                                            </button>

                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                            >
                                                <i class="bi bi-check-lg me-1"></i>
                                                Simpan Perubahan
                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>


                        <!-- MODAL RESET PASSWORD -->

                        <div
                            class="modal fade"
                            id="modalPassword{{ $user->id }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >

                            <div class="modal-dialog modal-dialog-centered">

                                <div class="modal-content border-0 shadow">

                                    <form
                                        action="{{ route('admin.users.password', $user) }}"
                                        method="POST"
                                    >

                                        @csrf

                                        @method('PATCH')


                                        <div class="modal-header">

                                            <h5 class="modal-title fw-bold">
                                                Reset Password
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            <div class="alert alert-light border">

                                                <i class="bi bi-person-fill me-1"></i>

                                                Password untuk

                                                <strong>
                                                    {{ $user->name }}
                                                </strong>

                                            </div>


                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Password Baru
                                                </label>

                                                <input
                                                    type="password"
                                                    name="password"
                                                    class="form-control"
                                                    placeholder="Minimal 8 karakter"
                                                    minlength="8"
                                                    required
                                                >

                                            </div>


                                            <div>

                                                <label class="form-label">
                                                    Konfirmasi Password
                                                </label>

                                                <input
                                                    type="password"
                                                    name="password_confirmation"
                                                    class="form-control"
                                                    placeholder="Ulangi password"
                                                    minlength="8"
                                                    required
                                                >

                                            </div>

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-light"
                                                data-bs-dismiss="modal"
                                            >
                                                Batal
                                            </button>

                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                            >
                                                <i class="bi bi-key-fill me-1"></i>
                                                Reset Password
                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>


                        <!-- MODAL RESET DEVICE -->

                        @if ($user->device_id)

                            <div
                                class="modal fade"
                                id="modalDevice{{ $user->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content border-0 shadow">

                                        <form
                                            action="{{ route('admin.users.device', $user) }}"
                                            method="POST"
                                        >

                                            @csrf

                                            @method('PATCH')


                                            <div class="modal-header">

                                                <h5 class="modal-title fw-bold">
                                                    Reset Perangkat
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body text-center">

                                                <div class="mb-3">

                                                    <i
                                                        class="bi bi-phone-fill text-primary"
                                                        style="font-size: 45px;"
                                                    ></i>

                                                </div>


                                                <h6 class="fw-bold">
                                                    Reset perangkat user?
                                                </h6>


                                                <p class="text-muted small mb-0">

                                                    Perangkat yang terdaftar untuk

                                                    <strong>
                                                        {{ $user->name }}
                                                    </strong>

                                                    akan dihapus.

                                                    User dapat mendaftarkan
                                                    perangkat baru saat login.

                                                </p>

                                            </div>


                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-light"
                                                    data-bs-dismiss="modal"
                                                >
                                                    Batal
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="btn btn-primary"
                                                >
                                                    <i class="bi bi-phone me-1"></i>
                                                    Reset Perangkat
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif


                        <!-- MODAL DELETE -->

                        @if ($user->id !== auth()->id())

                            <div
                                class="modal fade"
                                id="modalDelete{{ $user->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content border-0 shadow">

                                        <form
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST"
                                        >

                                            @csrf

                                            @method('DELETE')


                                            <div class="modal-header">

                                                <h5 class="modal-title fw-bold text-danger">
                                                    Hapus User
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body text-center">

                                                <div class="mb-3">

                                                    <i
                                                        class="bi bi-trash-fill text-danger"
                                                        style="font-size: 45px;"
                                                    ></i>

                                                </div>


                                                <h6 class="fw-bold">
                                                    Hapus user ini?
                                                </h6>


                                                <p class="text-muted small mb-0">

                                                    Akun

                                                    <strong>
                                                        {{ $user->name }}
                                                    </strong>

                                                    akan dihapus secara permanen.

                                                </p>

                                            </div>


                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-light"
                                                    data-bs-dismiss="modal"
                                                >
                                                    Batal
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    <i class="bi bi-trash-fill me-1"></i>
                                                    Hapus User
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif

                    @empty

                        <tr>

                            <td colspan="8">

                                <div class="empty-state">

                                    <div class="empty-state-icon">

                                        <i class="bi bi-people"></i>

                                    </div>


                                    <div class="empty-state-title">
                                        Belum ada user
                                    </div>


                                    <div class="empty-state-text">
                                        Silakan tambahkan user baru.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- MODAL TAMBAH USER -->

<div
    class="modal fade"
    id="modalTambahUser"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <form
                action="{{ route('admin.users.store') }}"
                method="POST"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title fw-bold">
                        Tambah User
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            NIP
                        </label>

                        <input
                            type="text"
                            name="nip"
                            class="form-control"
                            placeholder="Masukkan NIP"
                            maxlength="50"
                            value="{{ old('nip') }}"
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Masukkan nama lengkap"
                            value="{{ old('name') }}"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="contoh@email.com"
                            value="{{ old('email') }}"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Ulangi password"
                            minlength="8"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Role
                        </label>

                        <select
                            name="role"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Pilih Role
                            </option>

                            <option value="pegawai">
                                Pegawai
                            </option>

                            <option value="super_admin">
                                Super Admin
                            </option>

                        </select>

                    </div>


                    <div>

                        <label class="form-label">
                            Lokasi / Cabang
                        </label>

                        <select
                            name="location_id"
                            class="form-select"
                        >

                            <option value="">
                                -- Pilih Lokasi / Cabang --
                            </option>

                            @foreach ($locations as $location)

                                <option
                                    value="{{ $location->id }}"
                                    {{ old('location_id') == $location->id ? 'selected' : '' }}
                                >
                                    {{ $location->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-person-plus-fill me-1"></i>
                        Simpan User
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@endsection


@push('head')

<style>

    .user-search {
        width: 230px;
    }

    .modal-content {
        border-radius: 16px;
    }

    .modal-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f4;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #edf0f4;
    }

    @media (max-width: 767.98px) {

        .user-search {
            width: 100%;
        }

        .app-card-header {
            align-items: stretch;
            flex-direction: column;
        }

        .modal-dialog {
            margin: 10px;
        }

    }

</style>

@endpush


@push('scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const searchInput = document.getElementById('searchUser');

        const rows = document.querySelectorAll('.user-row');


        if (!searchInput) {
            return;
        }


        searchInput.addEventListener('input', function () {

            const keyword = this.value
                .toLowerCase()
                .trim();


            rows.forEach(function (row) {

                const text = row.textContent.toLowerCase();

                row.style.display = text.includes(keyword)
                    ? ''
                    : 'none';

            });

        });

    });

</script>

@endpush
