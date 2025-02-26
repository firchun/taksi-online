<div class="text-center mb-3">
    <h4>Selamat datang kembali <span class="text-primary">{{ Auth::user()->name }}</span></h4>
    <div class="d-flex justify-content-center">
        <a href="{{ url('/home') }}" class="btn btn-primary m-1">Dashboard</a>
        <a href="{{ url('/riwayat-user') }}" class="btn btn-outline-primary m-1">Data Booking</a>
    </div>
</div>
<hr>
<div class="row justify-content-center">
    @foreach (App\Models\Taksi::all() as $item)
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

                            <button class="btn btn-md btn-label-primary  btn-block" type="button"
                                data-bs-toggle="modal" data-bs-target="#booking{{ $item->id }}"
                                @if ($item->status == 'Full') disabled @endif>
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
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Booking Mobil : {{ $item->plat_nomor }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pesanan.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <h4>Formulir pemesanan :</h4>
                            <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id_taksi" value="{{ $item->id }}">
                            <div class="mb-3">
                                <label>Nama Penumpang</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label>Lokasi Asal</label>
                                <select name="id_rute_asal" class="form-select" required>
                                    <option>Pilih Lokasi</option>
                                    @foreach (App\Models\RuteTaksi::where('id_taksi', $item->id)->get() as $rute)
                                        <option value="{{ $rute->id_rute }}">{{ $rute->rute->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Lokasi Tujuan</label>
                                <select name="id_rute_tujuan" class="form-select" required>
                                    <option>Pilih Lokasi</option>
                                    @foreach (App\Models\RuteTaksi::where('id_taksi', $item->id)->get() as $rute)
                                        <option value="{{ $rute->id_rute }}">{{ $rute->rute->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Jumlah Penumpang</label>
                                <input type="number" class="form-control" value="1" name="jumlah_penumpang"
                                    id="jumlah-penumpang" min="1">
                            </div>
                            <!-- Pilihan Kursi -->
                            <div class="mb-3">
                                <label>Pilih Kursi</label>
                                <div id="kursi-container">

                                </div>
                                <small class="text-danger" id="kursi-warning" style="display: none;">Maksimal kursi
                                    sesuai jumlah penumpang!</small>
                            </div>
                            <!-- Daftar Nama Penumpang -->
                            <div class="mb-3 p-3 border border-primary rounded">
                                <label>Daftar Penumpang</label>
                                <div id="penumpang-list">
                                    <div class="input-group mb-2">
                                        <input type="text" name="nama[]" class="form-control"
                                            placeholder="Nama Penumpang">
                                        <button type="button"
                                            class="btn btn-outline-danger remove-penumpang">Hapus</button>
                                    </div>
                                </div>
                                <button type="button" id="add-penumpang" class="btn btn-outline-primary">Tambah
                                    Penumpang</button>
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
                                        Orang)</strong><br><small>Dari :
                                        {{ $penumpang->asal->nama_lokasi }} , Menuju :
                                        {{ $penumpang->tujuan->nama_lokasi }}</small><br>
                                    <small>
                                        Penumpang : @foreach (App\Models\Penumpang::where('id_pemesanan', $penumpang->id)->get() as $listPenumpang)
                                            {{ $listPenumpang->nama . ', ' }}
                                        @endforeach
                                    </small>
                                </li>
                            @empty
                                <li class="list-group-item list-group-item-action">
                                    <strong class="text-center text-danger">Belum ada penumpang</strong>
                                </li>
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
@endpush
@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetchKursiTersedia();
        });

        function fetchKursiTersedia() {
            const jumlahPenumpangInput = document.getElementById("jumlah-penumpang");
            const kursiContainer = document.getElementById("kursi-container");
            const kursiWarning = document.getElementById("kursi-warning");
            const idTaksi = document.querySelector("input[name='id_taksi']").value;

            if (!kursiContainer || !idTaksi) {
                console.error("Element tidak ditemukan! Pastikan ID atau Name sudah benar.");
                return;
            }

            // AJAX untuk mendapatkan kursi yang tersedia
            fetch(`/kursi-tersedia/${idTaksi}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Data kursi tersedia:", data);
                    if (!data.kursi_tersedia || data.kursi_tersedia.length === 0) {
                        kursiContainer.innerHTML = "<p class='text-danger'>Tidak ada kursi tersedia.</p>";
                        return;
                    }

                    kursiContainer.innerHTML = ""; // Kosongkan sebelum menambahkan kursi baru
                    data.kursi_tersedia.forEach(kursi => {
                        let div = document.createElement("div");
                        div.classList.add("form-check");
                        div.innerHTML = `
                        <input class="form-check-input kursi-checkbox" type="checkbox" name="nomor_kursi[]" value="${kursi}" id="kursi-${kursi}">
                        <label class="form-check-label" for="kursi-${kursi}">${kursi}</label>
                    `;
                        kursiContainer.appendChild(div);
                    });

                    // Panggil event listener untuk checkbox setelah elemen dibuat
                    addCheckboxEventListeners();
                })
                .catch(error => console.error("Error fetching kursi:", error));
        }

        function addCheckboxEventListeners() {
            const jumlahPenumpangInput = document.getElementById("jumlah-penumpang");
            const kursiWarning = document.getElementById("kursi-warning");
            const kursiCheckboxes = document.querySelectorAll(".kursi-checkbox");

            function updateCheckboxLimit() {
                let jumlahPenumpang = parseInt(jumlahPenumpangInput.value) || 1;
                let checkedKursi = document.querySelectorAll(".kursi-checkbox:checked");

                if (checkedKursi.length > jumlahPenumpang) {
                    kursiWarning.style.display = "block";
                } else {
                    kursiWarning.style.display = "none";
                }

                kursiCheckboxes.forEach(checkbox => {
                    if (checkedKursi.length >= jumlahPenumpang && !checkbox.checked) {
                        checkbox.disabled = true;
                    } else {
                        checkbox.disabled = false;
                    }
                });
            }

            jumlahPenumpangInput.addEventListener("input", updateCheckboxLimit);
            kursiCheckboxes.forEach(checkbox => {
                checkbox.addEventListener("change", updateCheckboxLimit);
            });
        }
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
