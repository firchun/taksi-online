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
                                        <a href="{{ route('pesanan-selesai', $item->id) }}"
                                            class="btn btn-sm btn-primary">Beri
                                            Ulasan dan rating</a><br>
                                        <small class=" text-muted">Penjemputan telah selesai, silahkan memberikan ulasan dan
                                            rating</small>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
