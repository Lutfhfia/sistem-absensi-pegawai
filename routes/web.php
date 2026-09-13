<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminLocationController;
use App\Http\Controllers\RekapAttendanceController;
use App\Http\Controllers\RekapAttendanceExportController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)->name('login');

Route::post(
    '/login',
    [AuthController::class, 'login']
)->name('login.process');

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->name('logout');


Route::middleware('auth')->group(function () {

    // Lokasi Absensi

    Route::get(
        '/admin/locations/cities/{province}',
        [AdminLocationController::class, 'cities']
    )->name('admin.locations.cities');

    Route::get(
        '/admin/locations',
        [AdminLocationController::class, 'index']
    )->name('admin.locations.index');

    Route::post(
        '/admin/locations',
        [AdminLocationController::class, 'store']
    )->name('admin.locations.store');

    Route::put(
        '/admin/locations/{location}',
        [AdminLocationController::class, 'update']
    )->name('admin.locations.update');

    Route::delete(
        '/admin/locations/{location}',
        [AdminLocationController::class, 'destroy']
    )->name('admin.locations.destroy');


    // Pegawai

    Route::get(
        '/absen',
        [AttendanceController::class, 'index']
    )->name('absen');

    Route::post(
        '/absen/store',
        [AttendanceController::class, 'store']
    )->name('absen.store');

    Route::get(
        '/izin',
        [PermitController::class, 'index']
    )->name('izin.index');

    Route::post(
        '/izin/store',
        [PermitController::class, 'store']
    )->name('izin.store');


    // Admin

    Route::middleware('admin')->group(function () {

        Route::get(
            '/admin/dashboard',
            [AdminController::class, 'dashboard']
        )->name('admin.dashboard');



        // Pengajuan Izin

        Route::get(
            '/admin/izin',
            [AdminController::class, 'permits']
        )->name('admin.izin.index');

        Route::get(
            '/admin/izin/{id}/file',
            [AdminController::class, 'permitFile']
        )->name('admin.izin.file');

        Route::get(
            '/admin/izin/{id}/download',
            [AdminController::class, 'permitFileDownload']
        )->name('admin.izin.file.download');

        Route::patch(
            '/admin/izin/{id}/status',
            [AdminController::class, 'updatePermitStatus']
        )->name('admin.izin.status');


        // Rekapan

        Route::get(
            '/admin/rekapan',
            [RekapAttendanceController::class, 'index']
        )->name('rekapan');

        // export laporan
            Route::get(
            '/admin/rekapan/export',
            [RekapAttendanceExportController::class, 'export']
        )->name('rekapan.export');


        // Manajemen User

        Route::get(
            '/admin/users',
            [AdminUserController::class, 'index']
        )->name('admin.users.index');

        Route::post(
            '/admin/users',
            [AdminUserController::class, 'store']
        )->name('admin.users.store');

        Route::put(
            '/admin/users/{user}',
            [AdminUserController::class, 'update']
        )->name('admin.users.update');

        Route::patch(
            '/admin/users/{user}/password',
            [AdminUserController::class, 'resetPassword']
        )->name('admin.users.password');

        Route::patch(
            '/admin/users/{user}/device',
            [AdminUserController::class, 'resetDevice']
        )->name('admin.users.device');

        Route::delete(
            '/admin/users/{user}',
            [AdminUserController::class, 'destroy']
        )->name('admin.users.destroy');

    });

});
