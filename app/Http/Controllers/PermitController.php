<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use Illuminate\Http\Request;

class PermitController extends Controller
{
    public function index()
    {
        $permits = Permit::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('pegawai.izin', compact('permits'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis' => [
                'required',
                'in:izin,sakit',
            ],

            'tanggal_mulai' => [
                'required',
                'date_format:Y-m-d',
            ],

            'tanggal_selesai' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:tanggal_mulai',
            ],

            'alasan' => [
                'required',
                'string',
                'min:3',
                'max:2000',
            ],

            'berkas' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:4096',
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'accuracy' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        $path = $request
            ->file('berkas')
            ->store('berkas_izin', 'public');

        Permit::create([
            'user_id' => auth()->id(),
            'jenis' => $data['jenis'],
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'alasan' => $data['alasan'],
            'berkas' => $path,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'accuracy' => $data['accuracy'] ?? null,
            'ip_address' => $request->ip(),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('izin.index')
            ->with(
                'success',
                'Pengajuan izin/sakit berhasil dikirim.'
            );
    }
}
