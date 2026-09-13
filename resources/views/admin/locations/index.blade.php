@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Lokasi Absensi</h4>
            <p class="text-muted mb-0">Kelola titik lokasi dan radius absensi pegawai berbasis GPS.</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahLokasi">
            <i class="bi bi-plus-lg me-1"></i> Tambah Lokasi
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3" style="width: 60px;">No</th>
                        <th>Nama Lokasi</th>
                        <th>Alamat</th>
                        <th>Koordinat</th>
                        <th>Radius</th>
                        <th>Status</th>
                        <th class="text-end px-3" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $location)
                        <tr>
                            <td class="px-3 text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <strong class="text-dark">{{ $location->name }}</strong>
                            </td>
                            <td>
                                <span class="text-secondary small">{{ $location->address ?: '-' }}</span>
                            </td>
                            <td>
                                <code class="text-primary bg-light px-2 py-1 rounded small">
                                    {{ number_format($location->latitude, 6) }}, {{ number_format($location->longitude, 6) }}
                                </code>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info fw-semibold px-2 py-1">
                                    {{ number_format($location->radius_meter) }} meter
                                </span>
                            </td>
                            <td>
                                @if($location->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold px-2 py-1">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-end px-3">
                                <div class="btn-group btn-group-sm">
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary"
                                        title="Edit Lokasi"
                                        onclick="editLocation(
                                            {{ $location->id }},
                                            @js($location->name),
                                            @js($location->address),
                                            {{ $location->latitude }},
                                            {{ $location->longitude }},
                                            {{ $location->radius_meter }},
                                            {{ $location->is_active ? 'true' : 'false' }}
                                        )"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form
                                        action="{{ route('admin.locations.destroy', $location) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-geo-alt fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada lokasi absensi yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- MODAL TAMBAH LOKASI --}}
{{-- ========================================================= --}}
<div class="modal fade" id="modalTambahLokasi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('admin.locations.store') }}" method="POST" id="formTambahLokasi">
                @csrf
                <div class="modal-header border-bottom py-2 px-3 bg-light">
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-primary">
                            <i class="bi bi-geo-alt-fill me-1"></i> Tambah Lokasi Absensi
                        </h6>
                        <small class="text-muted" style="font-size: 11px;">Input koordinat manual di bawah atau gunakan peta & GPS.</small>
                    </div>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3">
                    <div class="row g-3">
                        {{-- KOLOM FORM KIRI --}}
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label fw-semibold small mb-1">Nama Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Contoh: Kantor Pusat" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold small mb-1">Radius Absensi <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="radius_meter" id="tambah_radius" class="form-control" value="100" min="5" max="10000" required>
                                        <span class="input-group-text bg-light text-muted">meter</span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label class="form-label fw-semibold small mb-1">Provinsi</label>
                                    <select id="tambah_province" class="form-select form-select-sm">
                                        <option value="">-- Pilih --</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province['code'] }}" data-name="{{ $province['name'] }}">
                                                {{ $province['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label class="form-label fw-semibold small mb-1">Kota / Kab.</label>
                                    <select id="tambah_city" class="form-select form-select-sm" disabled>
                                        <option value="">Pilih provinsi...</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold small mb-1">Alamat Lengkap</label>
                                    <textarea name="address" id="tambah_address" class="form-control form-control-sm" rows="2" placeholder="Ketik alamat atau terisi otomatis..."></textarea>
                                </div>

                                {{-- KOORDINAT DI BAWAH ALAMAT (BISA DIINPUTKAN MANUAL) --}}
                                <div class="col-6">
                                    <label class="form-label fw-semibold small mb-1 text-primary">
                                        <i class="bi bi-geo me-1"></i> Latitude <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="any" name="latitude" id="tambah_latitude" class="form-control form-control-sm" placeholder="Contoh: -6.200000" required>
                                </div>

                                <div class="col-6">
                                    <label class="form-label fw-semibold small mb-1 text-primary">
                                        <i class="bi bi-geo me-1"></i> Longitude <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="any" name="longitude" id="tambah_longitude" class="form-control form-control-sm" placeholder="Contoh: 106.816666" required>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="tambah_active" checked>
                                        <label class="form-check-label fw-semibold small" for="tambah_active">Status Lokasi Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM PETA KANAN (PREVIEW MINI MAP) --}}
                        <div class="col-md-6 location-map-column">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-semibold small mb-0 text-dark">
                                    <i class="bi bi-map text-primary me-1"></i> Peta & Pin Lokasi
                                </label>
                                <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 fw-semibold" style="font-size: 11px; height: 24px;" id="btnGpsSayaTambah" title="Ambil koordinat GPS perangkat">
                                    <i class="bi bi-crosshair me-1"></i> Ambil GPS Saya
                                </button>
                            </div>

                            <div class="map-fixed-wrapper">
                                <div id="mapTambah" class="leaflet-map-element"></div>
                            </div>
                            <div class="text-muted text-center mt-1" style="font-size: 10px;">
                                <i class="bi bi-info-circle me-1"></i> Anda bisa ketik koordinat di samping atau klik langsung pada peta.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-2 px-3 bg-light">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm" id="btnSimpanTambah">
                        <i class="bi bi-save me-1"></i> Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- MODAL EDIT LOKASI --}}
{{-- ========================================================= --}}
<div class="modal fade" id="modalEditLokasi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" id="formEditLokasi">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom py-2 px-3 bg-light">
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-primary">
                            <i class="bi bi-pencil-square me-1"></i> Edit Lokasi Absensi
                        </h6>
                        <small class="text-muted" style="font-size: 11px;">Ubah data lokasi atau input koordinat manual.</small>
                    </div>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3">
                    <div class="row g-3">
                        {{-- KOLOM FORM EDIT KIRI --}}
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label fw-semibold small mb-1">Nama Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit_name" class="form-control form-control-sm" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold small mb-1">Radius Absensi <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="radius_meter" id="edit_radius" class="form-control" min="5" max="10000" required>
                                        <span class="input-group-text bg-light text-muted">meter</span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold small mb-1">Alamat Lengkap</label>
                                    <textarea name="address" id="edit_address" class="form-control form-control-sm" rows="2"></textarea>
                                </div>

                                {{-- KOORDINAT DI BAWAH ALAMAT (BISA DIINPUTKAN MANUAL) --}}
                                <div class="col-6">
                                    <label class="form-label fw-semibold small mb-1 text-primary">
                                        <i class="bi bi-geo me-1"></i> Latitude <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="any" name="latitude" id="edit_latitude" class="form-control form-control-sm" required>
                                </div>

                                <div class="col-6">
                                    <label class="form-label fw-semibold small mb-1 text-primary">
                                        <i class="bi bi-geo me-1"></i> Longitude <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" step="any" name="longitude" id="edit_longitude" class="form-control form-control-sm" required>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="edit_active">
                                        <label class="form-check-label fw-semibold small" for="edit_active">Status Lokasi Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM PETA EDIT KANAN --}}
                        <div class="col-md-6 location-map-column">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-semibold small mb-0 text-dark">
                                    <i class="bi bi-map text-primary me-1"></i> Peta & Pin Lokasi
                                </label>
                                <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 fw-semibold" style="font-size: 11px; height: 24px;" id="btnGpsSayaEdit" title="Ambil koordinat GPS perangkat">
                                    <i class="bi bi-crosshair me-1"></i> Ambil GPS Saya
                                </button>
                            </div>

                            <div class="map-fixed-wrapper">
                                <div id="mapEdit" class="leaflet-map-element"></div>
                            </div>
                            <div class="text-muted text-center mt-1" style="font-size: 10px;">
                                <i class="bi bi-info-circle me-1"></i> Anda bisa ketik koordinat di samping atau klik langsung pada peta.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-2 px-3 bg-light">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    /* KOTAK PETA TERKUNCI & TIDAK BISA MELUAP */
    .map-fixed-wrapper {
        position: relative !important;
        width: 100% !important;
        height: 220px !important;
        max-height: 220px !important;
        min-height: 220px !important;
        background: #e5e7eb;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        overflow: hidden !important;
        box-shadow: inset 0 1px 3px rgba(0,0,0,.06);
        z-index: 0 !important;
    }

    .leaflet-map-element {
        width: 100% !important;
        height: 220px !important;
        max-height: 220px !important;
        min-height: 220px !important;
        background: #e5e9ec;
        position: relative !important;
        z-index: 0 !important;
    }


    .location-map-column {
        position: sticky;
        top: 0;
        align-self: flex-start;
    }

    .location-map-column .leaflet-container {
        width: 100% !important;
        height: 220px !important;
        max-height: 220px !important;
        border-radius: 9px;
        overflow: hidden !important;
        font-family: inherit;
    }

    .leaflet-pane,
    .leaflet-control-container {
        z-index: 1 !important;
    }

    .leaflet-top,
    .leaflet-bottom {
        z-index: 2 !important;
    }

    @media (max-width: 767.98px) {
        .location-map-column {
            position: relative;
            top: auto;
        }
        .map-fixed-wrapper,
        .leaflet-map-element,
        .location-map-column .leaflet-container {
            height: 200px !important;
            max-height: 200px !important;
            min-height: 200px !important;
        }
    }
    /* KONTROL ZOOM KECIL */
    .leaflet-control-zoom {
        margin: 6px !important;
        border: 1px solid rgba(0,0,0,0.1) !important;
        box-shadow: 0 1px 4px rgba(0,0,0,0.15) !important;
    }
    .leaflet-control-zoom a {
        width: 24px !important;
        height: 24px !important;
        line-height: 24px !important;
        font-size: 13px !important;
    }

    /* CUSTOM PIN MARKER */
    .custom-leaflet-pin {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-pin-inner {
        position: relative;
        width: 26px;
        height: 26px;
        background: #dc3545;
        border: 2px solid #ffffff;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        box-shadow: 0 2px 6px rgba(0,0,0,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-pin-inner i {
        color: #ffffff;
        font-size: 12px;
        transform: rotate(45deg);
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Fix Leaflet default icon paths
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    });

    const redPinIcon = L.divIcon({
        className: 'custom-leaflet-pin',
        html: '<div class="custom-pin-inner"><i class="bi bi-geo-alt-fill"></i></div>',
        iconSize: [26, 36],
        iconAnchor: [13, 36],
        popupAnchor: [0, -32]
    });

    let mapTambah = null;
    let markerTambah = null;
    let circleTambah = null;

    let mapEdit = null;
    let markerEdit = null;
    let circleEdit = null;

    document.addEventListener('DOMContentLoaded', function () {
        initModalTambahEvents();
        initModalEditEvents();
    });

    // =========================================================================
    // MODAL TAMBAH
    // =========================================================================
    function initModalTambahEvents() {
        const modalTambahEl = document.getElementById('modalTambahLokasi');

        modalTambahEl.addEventListener('shown.bs.modal', function () {
            initTambahMap();
            setTimeout(() => { if (mapTambah) mapTambah.invalidateSize(); }, 100);
            setTimeout(() => { if (mapTambah) mapTambah.invalidateSize(true); }, 300);
            setTimeout(() => { if (mapTambah) mapTambah.invalidateSize(true); }, 700);
        });

        // Event listener saat user ketik manual Latitude & Longitude
        const latInput = document.getElementById('tambah_latitude');
        const lngInput = document.getElementById('tambah_longitude');
        latInput.addEventListener('input', () => onManualCoordinateChange('tambah'));
        lngInput.addEventListener('input', () => onManualCoordinateChange('tambah'));

        // Event radius
        document.getElementById('tambah_radius').addEventListener('input', function () {
            const rad = Number(this.value) || 100;
            if (circleTambah) circleTambah.setRadius(rad);
        });

        // Event Provinsi dropdown
        document.getElementById('tambah_province').addEventListener('change', function () {
            if (!this.value) {
                resetCityDropdown();
                return;
            }
            fetchCities(this.value);
        });

        // Event Kota dropdown
        document.getElementById('tambah_city').addEventListener('change', function () {
            if (!this.value) return;
            const selectedText = this.options[this.selectedIndex].text;
            searchAndPanCity(selectedText);
        });

        // Tombol Ambil GPS Saya
        const btnGps = document.getElementById('btnGpsSayaTambah');
        if (btnGps) {
            btnGps.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    alert('Browser tidak mendukung GPS.');
                    return;
                }
                btnGps.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mendeteksi...';
                btnGps.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    function (pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        document.getElementById('tambah_latitude').value = lat.toFixed(8);
                        document.getElementById('tambah_longitude').value = lng.toFixed(8);

                        initTambahMap();
                        if (mapTambah) {
                            mapTambah.setView([lat, lng], 17);
                            setPinTambah(lat, lng);
                        }
                        btnGps.innerHTML = '<i class="bi bi-check-lg text-success me-1"></i> GPS Didapat';
                        btnGps.disabled = false;
                    },
                    function (err) {
                        alert('Gagal mendeteksi GPS: ' + err.message);
                        btnGps.innerHTML = '<i class="bi bi-crosshair me-1"></i> Ambil GPS Saya';
                        btnGps.disabled = false;
                    },
                    { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
                );
            });
        }
    }

    function initTambahMap() {
        if (mapTambah) {
            mapTambah.invalidateSize();
            return;
        }

        const latVal = parseFloat(document.getElementById('tambah_latitude').value) || -6.200000;
        const lngVal = parseFloat(document.getElementById('tambah_longitude').value) || 106.816666;

        mapTambah = L.map('mapTambah', {
            center: [latVal, lngVal],
            zoom: 14,
            minZoom: 5,
            maxZoom: 19,
            zoomControl: true,
            scrollWheelZoom: true,
            dragging: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapTambah);

        mapTambah.on('click', function (e) {
            document.getElementById('tambah_latitude').value = Number(e.latlng.lat).toFixed(8);
            document.getElementById('tambah_longitude').value = Number(e.latlng.lng).toFixed(8);
            setPinTambah(e.latlng.lat, e.latlng.lng);
        });

        setPinTambah(latVal, lngVal);
    }

    function setPinTambah(lat, lng) {
        const radius = Number(document.getElementById('tambah_radius').value) || 100;

        if (!markerTambah) {
            markerTambah = L.marker([lat, lng], {
                icon: redPinIcon,
                draggable: true
            }).addTo(mapTambah);

            markerTambah.on('dragend', function (e) {
                const pos = e.target.getLatLng();
                document.getElementById('tambah_latitude').value = Number(pos.lat).toFixed(8);
                document.getElementById('tambah_longitude').value = Number(pos.lng).toFixed(8);
                if (circleTambah) circleTambah.setLatLng(pos);
            });
        } else {
            markerTambah.setLatLng([lat, lng]);
        }

        if (!circleTambah) {
            circleTambah = L.circle([lat, lng], {
                radius: radius,
                color: '#0d6efd',
                fillColor: '#0d6efd',
                fillOpacity: 0.18,
                weight: 2
            }).addTo(mapTambah);
        } else {
            circleTambah.setLatLng([lat, lng]);
            circleTambah.setRadius(radius);
        }
    }

    // =========================================================================
    // MANUAL COORDINATE CHANGE EVENT
    // =========================================================================
    function onManualCoordinateChange(mode) {
        const lat = parseFloat(document.getElementById(mode + '_latitude').value);
        const lng = parseFloat(document.getElementById(mode + '_longitude').value);

        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            if (mode === 'tambah') {
                initTambahMap();
                if (mapTambah) {
                    mapTambah.setView([lat, lng], 16);
                    setPinTambah(lat, lng);
                }
            } else {
                if (mapEdit) {
                    mapEdit.setView([lat, lng], 16);
                    if (markerEdit) markerEdit.setLatLng([lat, lng]);
                    if (circleEdit) circleEdit.setLatLng([lat, lng]);
                }
            }
        }
    }

    async function searchAndPanCity(cityName) {
        const provinceSelect = document.getElementById('tambah_province');
        const provinceName = provinceSelect.options[provinceSelect.selectedIndex]?.dataset?.name || '';
        const query = cityName + ', ' + provinceName + ', Indonesia';

        try {
            const res = await fetch('https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&countrycodes=id&q=' + encodeURIComponent(query), {
                headers: { 'Accept-Language': 'id' }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                document.getElementById('tambah_latitude').value = lat.toFixed(8);
                document.getElementById('tambah_longitude').value = lng.toFixed(8);

                initTambahMap();
                if (mapTambah) {
                    mapTambah.invalidateSize();
                    mapTambah.flyTo([lat, lng], 14, { duration: 0.8 });
                    setPinTambah(lat, lng);
                }
            }
        } catch (err) {
            console.error(err);
        }
    }

    async function fetchCities(provinceCode) {
        const citySelect = document.getElementById('tambah_city');
        citySelect.disabled = true;
        citySelect.innerHTML = '<option value="">Memuat kota...</option>';

        try {
            const res = await fetch('{{ route('admin.locations.cities', ['province' => '__PROVINCE__']) }}'.replace('__PROVINCE__', encodeURIComponent(provinceCode)), {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) {
                throw new Error('Gagal mengambil data kota.');
            }

            const json = await res.json();
            const cities = json.data || [];

            citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
            cities.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city.code;
                opt.dataset.name = city.name;
                opt.textContent = city.name;
                citySelect.appendChild(opt);
            });
            citySelect.disabled = false;
        } catch (e) {
            citySelect.innerHTML = '<option value="">Gagal memuat</option>';
        }
    }

    function resetCityDropdown() {
        const citySelect = document.getElementById('tambah_city');
        citySelect.disabled = true;
        citySelect.innerHTML = '<option value="">Pilih provinsi...</option>';
    }

    // =========================================================================
    // MODAL EDIT
    // =========================================================================
    function initModalEditEvents() {
        const modalEditEl = document.getElementById('modalEditLokasi');

        modalEditEl.addEventListener('shown.bs.modal', function () {
            if (mapEdit) {
                setTimeout(() => { mapEdit.invalidateSize(); }, 100);
                setTimeout(() => { mapEdit.invalidateSize(true); }, 300);
                setTimeout(() => { mapEdit.invalidateSize(true); }, 700);
            }
        });

        // Event input manual koordinat edit
        document.getElementById('edit_latitude').addEventListener('input', () => onManualCoordinateChange('edit'));
        document.getElementById('edit_longitude').addEventListener('input', () => onManualCoordinateChange('edit'));

        // Event radius edit
        document.getElementById('edit_radius').addEventListener('input', function () {
            const rad = Number(this.value) || 100;
            if (circleEdit) circleEdit.setRadius(rad);
        });

        // Tombol Ambil GPS Saya Edit
        const btnGpsEdit = document.getElementById('btnGpsSayaEdit');
        if (btnGpsEdit) {
            btnGpsEdit.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    alert('Browser tidak mendukung GPS.');
                    return;
                }
                btnGpsEdit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mendeteksi...';
                btnGpsEdit.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    function (pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        document.getElementById('edit_latitude').value = lat.toFixed(8);
                        document.getElementById('edit_longitude').value = lng.toFixed(8);

                        if (mapEdit) {
                            mapEdit.setView([lat, lng], 17);
                            const position = [lat, lng];
                            if (markerEdit) markerEdit.setLatLng(position);
                            if (circleEdit) circleEdit.setLatLng(position);
                        }
                        btnGpsEdit.innerHTML = '<i class="bi bi-check-lg text-success me-1"></i> GPS Didapat';
                        btnGpsEdit.disabled = false;
                    },
                    function (err) {
                        alert('Gagal mendeteksi GPS: ' + err.message);
                        btnGpsEdit.innerHTML = '<i class="bi bi-crosshair me-1"></i> Ambil GPS Saya';
                        btnGpsEdit.disabled = false;
                    },
                    { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
                );
            });
        }
    }

    function editLocation(id, name, address, lat, lng, radius, isActive) {
        document.getElementById('formEditLokasi').action = '/admin/locations/' + id;
        document.getElementById('edit_name').value = name || '';
        document.getElementById('edit_address').value = address || '';
        document.getElementById('edit_latitude').value = Number(lat).toFixed(8);
        document.getElementById('edit_longitude').value = Number(lng).toFixed(8);
        document.getElementById('edit_radius').value = radius || 100;
        document.getElementById('edit_active').checked = Boolean(isActive);

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditLokasi'));
        modal.show();

        setTimeout(() => {
            initEditMap(Number(lat), Number(lng), Number(radius) || 100);
        }, 150);
    }

    function initEditMap(lat, lng, radius) {
        const position = [lat, lng];

        if (!mapEdit) {
            mapEdit = L.map('mapEdit', {
                center: position,
                zoom: 16,
                minZoom: 5,
                maxZoom: 19,
                zoomControl: true,
                scrollWheelZoom: true,
                dragging: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapEdit);

            markerEdit = L.marker(position, {
                icon: redPinIcon,
                draggable: true
            }).addTo(mapEdit);

            circleEdit = L.circle(position, {
                radius: radius,
                color: '#0d6efd',
                fillColor: '#0d6efd',
                fillOpacity: 0.18,
                weight: 2
            }).addTo(mapEdit);

            markerEdit.on('dragend', function (e) {
                const pos = e.target.getLatLng();
                document.getElementById('edit_latitude').value = Number(pos.lat).toFixed(8);
                document.getElementById('edit_longitude').value = Number(pos.lng).toFixed(8);
                if (circleEdit) circleEdit.setLatLng(pos);
            });

            mapEdit.on('click', function (e) {
                const clickPos = e.latlng;
                document.getElementById('edit_latitude').value = Number(clickPos.lat).toFixed(8);
                document.getElementById('edit_longitude').value = Number(clickPos.lng).toFixed(8);
                markerEdit.setLatLng(clickPos);
                if (circleEdit) circleEdit.setLatLng(clickPos);
            });
        } else {
            mapEdit.setView(position, 16);
            markerEdit.setLatLng(position);
            circleEdit.setLatLng(position);
            circleEdit.setRadius(radius);
        }

        setTimeout(() => {
            if (mapEdit) mapEdit.invalidateSize(true);
        }, 250);
    }
</script>
@endpush
