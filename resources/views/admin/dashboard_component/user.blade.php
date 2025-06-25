@include('layouts.backend.alert')
<div class="text-center mb-3">
    <h4>Selamat datang kembali <span class="text-primary">{{ Auth::user()->name }}</span></h4>
    <div class="d-flex justify-content-center">
        <a href="{{ url('/home') }}" class="btn btn-primary m-1">Dashboard</a>
        <a href="{{ url('/riwayat-user') }}" class="btn btn-outline-primary m-1">Data Booking</a>
    </div>
</div>
<hr>
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-12">
        <form method="GET" action="{{ route('home') }}">
            <div class="input-group mb-3">
                <input type="search" class="form-control form-control-lg" id="searchTaksi" name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari taksi berdasarkan merek, plat nomor, atau supir...">
                <button class="btn btn-primary" type="submit" id="btnSearchTaksi">
                    <i class="bx bx-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>
<div class="row justify-content-center">

    @foreach ($taksi as $item)
        @php
            $penumpang = App\Models\Pemesanan::where('id_taksi', $item->id)
                ->where('pesanan_selesai', 0)
                ->sum('jumlah_penumpang');
        @endphp
        <div class="col-sm-6 col-lg-4">
            <div class="card p-2 h-100 shadow-none border border-primary">
                <div class="rounded-2 text-center mb-3">
                    <div id="carouselExample-{{ $item->id }}" class="carousel slide" data-bs-ride="carousel">

                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="{{ Storage::url($item->foto_depan) }}" alt="First slide"
                                    style="height: 200px; width:100%; object-fit:cover;">

                            </div>
                            <div class="carousel-item ">
                                <img class="d-block w-100" src="{{ Storage::url($item->foto_samping) }}"
                                    alt="Second slide" style="height: 200px; width:100%; object-fit:cover;">

                            </div>
                            @if ($item->foto_dalam != null)
                                <div class="carousel-item ">
                                    <img class="d-block w-100" src="{{ Storage::url($item->foto_dalam) }}"
                                        alt="Second slide" style="height: 200px; width:100%; object-fit:cover;">
                                </div>
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#carouselExample-{{ $item->id }}" role="button"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExample-{{ $item->id }}" role="button"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div>
                <div class="card-body p-3 pt-2">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span
                            class="badge bg-label-{{ $item->aktif == 1 ? 'success' : 'danger' }}">{{ $item->aktif == 1 ? 'ONLINE' : 'OFFLINE' }}</span>
                        <h6 class="d-flex align-items-center justify-content-center gap-1 mb-0">
                            {{ $penumpang }}/{{ $item->jumlah_penumpang }}<span class="text-warning"><i
                                    class="bx bxs-user me-1"></i></span>
                        </h6>
                    </div>
                    <h5>{{ $item->merek }} - {{ $item->plat_nomor }}</h5>
                    <p class="d-flex align-items-center mb-1"><i class="bx bx-user me-2"></i>Supir :
                        {{ $item->supir->name }}
                    </p>
                    <p class="d-flex align-items-center mb-1"><i class="bx bx-file me-2"></i>Status : <span
                            class="badge bg-label-{{ $item->status == 'Tersedia' ? 'success' : 'danger' }}">{{ $item->status }}</span>
                    </p>
                    <div class="d-flex align-items-center flex-wrap">

                        <div class="mb-3">
                            Rute :
                            @foreach (App\Models\RuteTaksi::where('id_taksi', $item->id)->get() as $rute)
                                <a href="javascript:;" class="me-2"><span
                                        class="badge bg-label-primary">{{ $rute->rute->nama_lokasi }}</span></a>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="pe-xl-3 pe-xxl-0">
                        <div class="btn-group" role="group">

                            <button class="btn btn-md btn-info btn-block" type="button" data-bs-toggle="modal"
                                data-bs-target="#pesanan{{ $item->id }}">
                                <span class="me-2">Penumpang </span></i>
                            </button>
                            @if ($item->status == 'Full')
                                <a href="{{ url('/tracking/user') }}" class="btn btn-md btn-primary"><i
                                        class="bx bx-map-alt">
                                    </i>
                                    Rute</a>
                            @endif

                            <button class="btn btn-md btn-label-primary btn-block open-booking-modal" type="button"
                                data-bs-toggle="modal" data-bs-target="#booking{{ $item->id }}"
                                data-id-taksi="{{ $item->id }}" @if ($item->status == 'Full') disabled @endif>
                                <span class="me-2">Booking</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- modal  --}}
        <div class="modal fade" id="booking{{ $item->id }}" tabindex="-1" aria-labelledby="customersModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Booking Mobil : {{ $item->plat_nomor }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pesanan.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <img src="{{ asset('img/layout_mobil.jpg') }}" alt="Foto layout"
                                        class="img-fluid rounded-3">
                                </div>
                                <div class="col-lg-6">
                                    <h4>Formulir pemesanan :</h4>
                                    <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="id_taksi" value="{{ $item->id }}">
                                    <div class="mb-3">
                                        <label>Nama Penumpang</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}"
                                            readonly>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label>Lokasi Asal</label>
                                                <select name="id_rute_asal" class="form-select" required>
                                                    <option>Pilih Lokasi</option>
                                                    @foreach (App\Models\RuteTaksi::where('id_taksi', $item->id)->get() as $rute)
                                                        <option value="{{ $rute->id_rute }}">
                                                            {{ $rute->rute->nama_lokasi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label>Lokasi Tujuan</label>
                                                <select name="id_rute_tujuan" class="form-select" required>
                                                    <option>Pilih Lokasi</option>
                                                    @foreach (App\Models\RuteTaksi::where('id_taksi', $item->id)->get() as $rute)
                                                        <option value="{{ $rute->id_rute }}">
                                                            {{ $rute->rute->nama_lokasi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label>Detail Lokasi Penjemputan</label>
                                                <textarea name="detail_penjemputan" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label><strong>Pilih Tanggal (7 Hari ke Depan):</strong></label>
                                        <input type="text" id="tanggal" name="tanggal_pemesanan"
                                            class="form-control" placeholder="Pilih Tanggal">
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex flex-wrap gap-2 mt-2 justify-content-center">
                                            @php
                                                $days = [
                                                    'Minggu',
                                                    'Senin',
                                                    'Selasa',
                                                    'Rabu',
                                                    'Kamis',
                                                    'Jumat',
                                                    'Sabtu',
                                                ];
                                            @endphp

                                            <!-- Hidden input yang akan dikirim ke server -->
                                            <input type="hidden" name="hari" id="hari">

                                            <!-- Radio yang hanya untuk tampilan (readonly) -->
                                            @foreach ($days as $day)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        id="hari-{{ strtolower($day) }}" value="{{ $day }}"
                                                        disabled>
                                                    <label class="form-check-label"
                                                        for="hari-{{ strtolower($day) }}">{{ $day }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Jumlah Penumpang</label>
                                        <input type="number" class="form-control" value="1"
                                            name="jumlah_penumpang" id="jumlah-penumpang-{{ $item->id }}"
                                            min="1"
                                            max="{{ App\Models\Taksi::find($item->id)->jumlah_penumpang - $penumpang }}"
                                            required>
                                    </div>
                                    <!-- Pilihan Kursi -->
                                    <div class="mb-3">
                                        <label>Pilih Kursi</label>
                                        <div id="kursi-container" class="p-2 border border-warning"
                                            style="border-radius: 10px;">

                                        </div>
                                        <small class="text-danger" id="kursi-warning" style="display: none;">Maksimal
                                            kursi
                                            sesuai jumlah penumpang!</small>
                                    </div>
                                    <!-- Daftar Nama Penumpang -->
                                    <div class="mb-3 p-3 border border-primary rounded"
                                        id="daftar-penumpang-{{ $item->id }}">
                                        <label>Daftar Penumpang</label>
                                        <div id="penumpang-list-{{ $item->id }}">
                                            {{-- Input penumpang akan di-generate via JS --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Booking mobil
                                sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="pesanan{{ $item->id }}" tabindex="-1"
            aria-labelledby="customersModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Penumpang Mobil : {{ $item->plat_nomor }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse (App\Models\Pemesanan::where('id_taksi', $item->id)->where('pesanan_selesai', 0)->get() as $penumpang)
                                <li class="list-group-item list-group-item-action">
                                    <strong>{{ $penumpang->user->name }} ({{ $penumpang->jumlah_penumpang }}
                                        Orang)</strong><br>
                                    <small>Dari : {{ $penumpang->asal->nama_lokasi }} , Menuju :
                                        {{ $penumpang->tujuan->nama_lokasi }}</small><br>
                                    <small>
                                        Penumpang:
                                        @foreach (App\Models\Penumpang::where('id_pemesanan', $penumpang->id)->get() as $listPenumpang)
                                            {{ $listPenumpang->nama }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </small><br>
                                    <small>
                                        Nomor Kursi:
                                        <b>
                                            @php
                                                $kursiMapping = [
                                                    'DP' => '1. Depan (Samping Sopir)',
                                                    'TL' => '2. Tengah Kiri',
                                                    'BS' => '3. Bench Seat',
                                                    'TK' => '4. Tengah Kanan',
                                                    'BL' => '5. Belakang Kiri',
                                                    'BT' => '6. Belakang Tengah',
                                                    'BK' => '7. Belakang Kanan',
                                                ];
                                                $nomorKursi = json_decode($penumpang->nomor_kursi, true) ?? [];
                                            @endphp
                                            @foreach ($nomorKursi as $kursi)
                                                {{ $kursiMapping[$kursi] ?? 'Tidak diketahui' }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </b>
                                    </small>
                                </li>
                            @empty
                                <li class="list-group-item text-danger">Tidak ada penumpang saat ini.</li>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@push('css')
    <style>
        #kursi-container {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("#tanggal", {
            dateFormat: "Y-m-d",
            minDate: "today",
            maxDate: new Date().fp_incr(6), // 6 hari ke depan
            disableMobile: true,
        });
    </script>
    <script>
        const tanggalInput = document.getElementById('tanggal');
        const hariHidden = document.getElementById('hari');

        tanggalInput.addEventListener('change', function() {
            const tanggal = new Date(this.value);
            const hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const hari = hariIndo[tanggal.getDay()];
            hariHidden.value = hari;

            // (Opsional) Tandai radio agar aktif sesuai hari
            document.querySelectorAll('input[type="radio"]').forEach(el => {
                el.checked = (el.value === hari);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            @foreach ($taksi as $item)
                $('#jumlah-penumpang-{{ $item->id }}').on('input', function() {
                    let jumlah = parseInt($(this).val());
                    if (jumlah > 1) {
                        $('#daftar-penumpang-{{ $item->id }}').show();
                    } else {
                        $('#daftar-penumpang-{{ $item->id }}').hide();
                    }
                }).trigger('input'); // trigger saat load
            @endforeach
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".open-booking-modal").forEach(button => {
                button.addEventListener("click", function() {
                    let idTaksi = this.getAttribute("data-id-taksi"); // Ambil ID Taksi dari tombol
                    fetchKursiTersedia(idTaksi);
                });
            });
        });

        function fetchKursiTersedia(idTaksi) {
            let kursiContainer = document.querySelector(`#booking${idTaksi} #kursi-container`);
            let kursiWarning = document.querySelector(`#booking${idTaksi} #kursi-warning`);

            if (!kursiContainer) {
                console.error(`Element kursi-container untuk taksi ${idTaksi} tidak ditemukan!`);
                return;
            }

            fetch(`/kursi-tersedia/${idTaksi}`)
                .then(response => response.json())
                .then(data => {
                    kursiContainer.innerHTML = ""; // Kosongkan sebelum menambahkan kursi baru
                    if (data.message) {
                        kursiContainer.innerHTML = `<p class="text-danger">${data.message}</p>`;
                        return;
                    }
                    if (!data.kursi_tersedia || data.kursi_tersedia.length === 0) {
                        kursiContainer.innerHTML = "<p class='text-danger'>Tidak ada kursi tersedia.</p>";
                        return;
                    }

                    data.kursi_tersedia.forEach(kursi => {
                        let div = document.createElement("div");
                        div.classList.add("form-check");
                        div.innerHTML = `
                            <input class="form-check-input kursi-checkbox" type="checkbox" name="nomor_kursi[]" value="${kursi.kode}" id="kursi-${kursi.kode}">
                            <label class="form-check-label" for="kursi-${kursi.kode}">${kursi.keterangan}</label>
                        `;
                        kursiContainer.appendChild(div);
                    });
                    addCheckboxEventListeners(idTaksi);
                })
                .catch(error => console.error("Error fetching kursi:", error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($taksi as $item)
                (function() {
                    const jumlahInput = document.getElementById('jumlah-penumpang-{{ $item->id }}');
                    const penumpangList = document.getElementById('penumpang-list-{{ $item->id }}');

                    function renderInputs() {
                        const jumlah = parseInt(jumlahInput.value);
                        const jumlahField = Math.max(jumlah - 1, 0);

                        // Kosongkan dulu
                        penumpangList.innerHTML = '';

                        for (let i = 1; i <= jumlahField; i++) {
                            const div = document.createElement('div');
                            div.classList.add('input-group', 'mb-2');
                            div.innerHTML = `
                        <input type="text" name="nama[{{ $item->id }}][]" class="form-control" placeholder="Nama Penumpang ${i}">
                    `;
                            penumpangList.appendChild(div);
                        }
                    }

                    // Jalankan saat pertama kali halaman dimuat
                    renderInputs();

                    // Jalankan saat input jumlah berubah
                    jumlahInput.addEventListener('input', renderInputs);
                })();
            @endforeach
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const penumpangList = document.getElementById('penumpang-list');
            const addPenumpangBtn = document.getElementById('add-penumpang');

            addPenumpangBtn.addEventListener('click', function() {
                const newPenumpang = document.createElement('div');
                newPenumpang.classList.add('input-group', 'mb-2');
                newPenumpang.innerHTML = `
                <input type="text" name="nama_penumpang[]" class="form-control" placeholder="Nama Penumpang">
                <button type="button" class="btn btn-outline-danger remove-penumpang">Hapus</button>
            `;
                penumpangList.appendChild(newPenumpang);

                // Tambahkan event listener untuk tombol "Hapus"
                newPenumpang.querySelector('.remove-penumpang').addEventListener('click', function() {
                    penumpangList.removeChild(newPenumpang);
                });
            });

            // Event listener untuk tombol "Hapus" pada elemen awal
            penumpangList.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-penumpang')) {
                    e.target.closest('.input-group').remove();
                }
            });
        });
    </script>
@endpush
