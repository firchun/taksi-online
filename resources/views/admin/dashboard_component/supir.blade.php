        @php
            $taksi = App\Models\Taksi::where('id_user', Auth::user()->id);
            $cek_taksi = $taksi->count();
            $detail_taksi = $taksi->first();
            $pesanan = App\Models\Pemesanan::with(['user', 'mobil'])
                ->where('id_taksi', $taksi->first()->id ?? null)
                ->where('pesanan_selesai', 0)
                ->get();
            $cek_full = false;
            if ($pesanan) {
                $cek_full = $pesanan->sum('jumlah_penumpang') ?? 0 == $detail_taksi->jumlah_penumpang ? true : false;
            }
        @endphp
        @if ($cek_taksi == 0)
            <div class="alert alert-warning alert-dismissible" role="alert">
                Anda Belum mendaftarkan mobil anda, silahkan mengisi form pendaftaran mobil untuk mulai mencari
                pelanggan
                anda..
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @else
            <div class="btn-group mb-3">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="bx bx-home"></i>
                    Home
                </a>
                <a href="{{ route('ulasan') }}" class="btn btn-success">
                    <i class="bx bx-message"></i>
                    Ulasan
                </a>
            </div>
            @if ($detail_taksi->status == 'Full')
                <div class="alert alert-warning alert-dismissible" role="alert">
                    Penumpang anda telah penuh dan menunggu penjemputan, segera lakukan penjemputan
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @elseif ($detail_taksi->status == 'Tersedia')
                <div class="card mb-3">
                    <h5 class="card-header">
                        @if ($detail_taksi->aktif == 0)
                            Mulai untuk Online?
                        @else
                            Anda sedang Online
                        @endif
                    </h5>
                    <div class="card-body">
                        <div class="mb-3 col-12 mb-0">
                            @if ($detail_taksi->aktif == 0)
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading fw-bold mb-1">Apakah anda ingin memulai mencari pelanggan ?
                                    </h6>
                                    <p class="mb-0">Silahkan klik tombol dibawah untuk mencari pelanggan anda. dengan
                                        menekan
                                        tombol di bawah, status anda akan online pada aplikasi dan siap untuk mencari
                                        pelanggan
                                    </p>
                                </div>
                            @else
                                <div class="alert alert-primary">
                                    <h6 class="alert-heading fw-bold mb-1">Apakah anda ingin berhenti untuk mencari
                                        pelanggan?
                                    </h6>
                                    <p class="mb-0">Silahkan klik tombol dibawah untuk mengubah ke offline. dengan
                                        menekan
                                        tombol di bawah, status anda akan offline pada aplikasi dan berhenti untuk
                                        menerima
                                        pelanggan
                                    </p>
                                </div>
                            @endif
                        </div>
                        <form action="{{ route('mobil.status', $detail_taksi->id) }}" method="POST">
                            @csrf
                            @if ($detail_taksi->aktif == 0)
                                <button type="submit" class="btn btn-success ">Nyalakan status Online</button>
                            @else
                                <button type="submit" class="btn btn-warning">Matikan status Online</button>
                            @endif
                        </form>
                    </div>
                </div>
            @endif
        @endif

        <div class="row justify-content-center">
            @if ($pesanan->count() != 0)
                <div class="col-12 d-flex justify-content-center mb-3">
                    <a href="{{ url('/tracking/supir') }}" class="btn btn-primary btn-lg"><i class="bx bx-map-alt"> </i>
                        Lihat
                        Rute Penjemputan</a>
                </div>
            @endif
            <div class="col-md-8">
                @if ($cek_taksi == 0)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Form Pendaftaran Mobil</h4>
                        </div>
                        <form action="{{ route('mobil.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                                <div class="mb-3">
                                    <label>Plat Nomor Mobil <span class="text-danger">*</span></label>
                                    <input type="text" name="plat_nomor" class="form-control"
                                        placeholder="Isi Nomor Plat Mobil" required>
                                </div>
                                <div class="mb-3">
                                    <label>Merek Mobil <span class="text-danger">*</span></label>
                                    <input type="text" name="merek" class="form-control"
                                        placeholder="Isi Merek Mobil" required>
                                </div>
                                <div class="mb-3">
                                    <label>Warna <span class="text-danger">*</span></label>
                                    <input type="text" name="warna" class="form-control"
                                        placeholder="Isi Warna Mobil" required>
                                </div>
                                <div class="mb-3">
                                    <label>Jumlah Penumpang <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_penumpang" class="form-control" value="2"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label>Foto SIM Supir <span class="text-danger">*</span></label>
                                    <input type="file" name="foto_sim" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Foto Mobil (Tampak Depan) <span class="text-danger">*</span></label>
                                    <input type="file" name="foto_depan" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Foto Mobil (Tampak Samping) <span class="text-danger">*</span></label>
                                    <input type="file" name="foto_samping" class="form-control" required>
                                </div>
                                <hr>
                                <h6>Rute Yang dilayani</h6>
                                <div class="mb-3">
                                    <label>Pilih Beberapa Rute <span class="text-danger">*</span></label>
                                    <select name="id_rute[]" class="form-control" required multiple>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Daftarkan mobil</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Pemesanan Mobil</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Pemesan</th>
                                            <th>Penumpang</th>
                                            <th>Rute</th>
                                            <th>Sampai</th>
                                            <th>Selesai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pesanan as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->user->name }}<br><small
                                                        class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                                </td>
                                                <td>{{ $item->jumlah_penumpang }} Orang</td>
                                                <td>Dari : <strong>{{ $item->asal->nama_lokasi }}</strong> ke
                                                    <strong>{{ $item->tujuan->nama_lokasi }}</strong>
                                                </td>
                                                <td>
                                                    <a href="https://wa.me/{{ $item->user->no_hp }}?text=Hai%2C%20{{ $item->user->name }}%0AMobil%20pesanan%20anda%20telah%20sampai%20di%20lokasi%20penjemputan.%0A%0A------------------------------------------------%0Aketerangan%20mobil%20%3A%0Ano.%20Plat%20%3A%20{{ $item->mobil->plat_nomor }}%0AMerek%20Mobil%20%3A%20{{ $item->mobil->merek . ' ' . $item->mobil->warna }}%0Asupir%20%3A%20{{ $item->mobil->supir->name }}"
                                                        target="__blank" class="btn btn-sm btn-success">Sampai di
                                                        lokasi
                                                        penjemputan</a><br>
                                                    <small class="text-muted">Klik tombol ini jika telah sampai di
                                                        lokasi penjemputan</small>
                                                </td>


                                                <td>
                                                    @if ($item->pesanan_selesai == 0)
                                                        <a href="{{ route('pesanan-selesai', $item->id) }}"
                                                            class="btn btn-sm btn-primary">Pesanan
                                                            Selesai</a><br>
                                                        <small class=" text-muted">Klik tombol ini jika Pesanan telah
                                                            selesai</small>
                                                    @else
                                                        <small class="text-muted">Telah Selesai</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if ($cek_taksi != 0)
                <div class="col-md-4 ">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Detail Mobil</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-borderless">
                                <tr>
                                    <td><strong>No. Plat</strong></td>
                                    <td>{{ $detail_taksi->plat_nomor }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Merek</strong></td>
                                    <td>{!! $detail_taksi->merek . ' - ' . $detail_taksi->warna !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Penumpang</strong></td>
                                    <td>{{ $detail_taksi->jumlah_penumpang }} Orang</td>
                                </tr>
                                <tr>
                                    <td><strong>Tampak Depan</strong></td>
                                    <td><img src="{{ Storage::url($detail_taksi->foto_depan) }}"
                                            style="width: 100px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tampak Samping</strong></td>
                                    <td><img src="{{ Storage::url($detail_taksi->foto_samping) }}"
                                            style="width: 100px;">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>Rute Mobil</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mobil.add-rute') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_taksi" value="{{ $detail_taksi->id }}">
                                <div class="input-group">
                                    <select class="form-select" name="id_rute"
                                        aria-label="Example select with button addon" required>
                                        @foreach (App\Models\Rute::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-primary" type="submit">Tambah</button>
                                </div>
                            </form>
                            <ul class="list-group mt-2">
                                @foreach (App\Models\RuteTaksi::where('id_taksi', $detail_taksi->id)->get() as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $item->rute->nama_lokasi }}
                                        <form action="{{ route('mobil.delete-rute', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger delete-button"><i
                                                    class="bx bx-trash"></i></button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @push('js')
            <script>
                $(document).ready(function() {
                    $.ajax({
                        url: '/rute-list',
                        method: 'GET',
                        success: function(data) {
                            var select = $('select[name="id_rute[]"]');
                            select.empty(); // Bersihkan opsi sebelum menambahkan yang baru
                            $.each(data, function(index, route) {
                                select.append('<option value="' + route.id + '">' + route.nama_lokasi +
                                    '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching routes:', error);
                        }
                    });
                });
            </script>
        @endpush
