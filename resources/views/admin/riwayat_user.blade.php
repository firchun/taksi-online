@extends('layouts.backend.admin')

@section('content')
    <div class="text-center mb-3">
        <h4>Selamat datang kembali <span class="text-primary">{{ Auth::user()->name }}</span></h4>
        <div class="d-flex justify-content-center">
            <a href="{{ url('/home') }}" class="btn btn-outline-primary m-1">Dashboard</a>
            <a href="{{ url('/riwayat-user') }}" class="btn btn-primary m-1">Data Booking</a>
        </div>
    </div>
    <hr>
    <div class="container">
        <div class="card">
            <div class="table-responsive">

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Status</th>
                            <th>Tanggal Booking</th>
                            <th>Penumpang</th>
                            <th>Rute</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemesanan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($item->pesanan_selesai == 0)
                                        <span class="text-success">Masih Dalam Penjemputan</span>
                                    @else
                                        @php
                                            $check_ulasan = App\Models\Ulasan::where('id_pemesanan', $item->id)
                                                ->where('id_user', Auth::id())
                                                ->count();
                                            if ($check_ulasan != 0) {
                                                $ulasan = App\Models\Ulasan::where('id_pemesanan', $item->id)
                                                    ->where('id_user', Auth::id())
                                                    ->first();
                                            }
                                        @endphp
                                        @if ($check_ulasan == 0)
                                            <small class=" text-muted">Penjemputan telah selesai, silahkan memberikan ulasan
                                                dan
                                                rating</small>
                                            <br>
                                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#ulasan-{{ $item->id }}">Beri
                                                Ulasan dan rating</a>
                                        @else
                                            <div class="rating d-flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span
                                                        class="bx bx-star {{ $i <= $ulasan->rating ? 'text-warning' : '' }}"></span>
                                                @endfor
                                            </div>
                                            <p>Ulasan : " <i>{{ $ulasan->ulasan }}</i> "</p>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format('d F Y') }}<br><small
                                        class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                </td>
                                <td>{{ $item->jumlah_penumpang }} Orang</td>
                                <td>
                                    Dari : <strong>{{ $item->asal->nama_lokasi }}</strong> ke
                                    <strong>{{ $item->tujuan->nama_lokasi }}</strong>
                                </td>
                                <td class="text-danger">
                                    Rp {{ number_format(75000 * $item->jumlah_penumpang) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @foreach ($pemesanan as $item)
        @if ($item->pesanan_selesai != 0)
            <div class="modal fade" id="ulasan-{{ $item->id }}" tabindex="-1" aria-labelledby="customersModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userModalLabel">Ulasan pada Mobil : {{ $item->mobil->merek }}
                                ({{ $item->mobil->plat_nomor }})
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ulasan.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_pemesanan" value="{{ $item->id }}">
                                <input type="hidden" name="id_taksi" value="{{ $item->id_taksi }}">
                                <input type="hidden" name="id_user" value="{{ Auth::id() }}">
                                <div class="mb-3">
                                    <label>Rating</label><br>
                                    <small>Minimal rating 1 dan maksimal 5</small>
                                    <input type="number" max="5" class="form-control" name="rating" required>
                                </div>
                                <div class="mb-3">
                                    <label>Ulasan</label>
                                    <textarea name="ulasan" class="form-control" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
