<div class="text-center mb-3">
    <h4>Selamat datang kembali <span class="text-primary">{{ Auth::user()->name }}</span></h4>
</div>
<hr>
<div class="row justify-content-center">
    @foreach (App\Models\Taksi::where('aktif', 1)->get() as $item)
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-header pb-1">
                    <h5>Mobil : {{ $item->plat_nomor }} <span class="badge bg-success">Online</span></h5>
                </div>
                <div class="card-body py-0">
                    <div class="d-flex align-items-center">
                        <img src="{{ Storage::url($item->foto_depan) }}" style="width: 100px;">
                        <div class="p-2 " style="margin-left: 20px;">
                            <strong>{{ $item->merek }} - {{ $item->warna }}</strong><br>
                            <span>Supir : {{ $item->supir->name }}</span><br>
                            <button type="button" class="btn btn-md btn-success mt-2" data-bs-toggle="modal"
                                data-bs-target="#booking{{ $item->id }}">Booking Mobil</button>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-1" style="font-size :14px;">
                    <span>Rute :
                        <div class="row">

                            @foreach (App\Models\RuteTaksi::where('id_taksi', $item->id)->get() as $rute)
                                <div class="col border border-primary px-2 bg-primary text-white mx-2"
                                    style="border-radius: 5px;">
                                    {{ $rute->rute->nama_lokasi }}
                                </div>
                            @endforeach
                        </div>
                    </span>
                </div>
            </div>
        </div>
        {{-- modal  --}}
        <div class="modal fade" id="booking{{ $item->id }}" tabindex="-1" aria-labelledby="customersModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Booking Mobil : {{ $item->plat_nomor }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <img src="{{ Storage::url($item->foto_depan) }}"
                                    style="width:100%; height:200px; object-fit:cover;">
                            </div>
                            <div class="col-md-6">
                                <img src="{{ Storage::url($item->foto_samping) }}"
                                    style="width:100%;height:200px; object-fit:cover;">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">

                                <h4>Detail Mobil :</h4>
                                <strong>No. Plat : </strong> {{ $item->plat_nomor }}<br>
                                <strong>Merek : </strong> {{ $item->merek }}<br>
                                <strong>Warna : </strong> {{ $item->warna }}<br>
                                <strong>Supir : </strong> {{ $item->supir->name }}<br>
                            </div>
                            <div class="col-md-6">
                                <h4>Rute yang dilayani :</h4>
                                <ul>
                                    @foreach (App\Models\RuteTaksi::where('id_taksi', $item->id)->get() as $rute)
                                        <li>{{ $rute->rute->nama_lokasi }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <hr>
                        <h4>Formulir pemesanan :</h4>
                        <div class="mb-3">
                            <label>Nama Penumpang</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Lokasi Asal</label>
                            <select name="id_rute_asal" class="form-select" required>
                                <option>Pilih Lokasi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Lokasi Tujuan</label>
                            <select name="id_rute_tujuan" class="form-select" required>
                                <option>Pilih Lokasi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jumlah Penumpang</label>
                            <input type="number" class="form-control" value="1" name="jumlah">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Booking mobil
                            sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
