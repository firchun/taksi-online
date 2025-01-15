@extends('layouts.backend.admin')
@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 500px;
        }

        .leaflet-control-container .leaflet-routing-container-hide {
            display: none;
        }

        .list-group-item-action:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-zKzv2C/ikdN08e0O/zyvImcw5D+crFw2SmO6ImE8eW58bDO0w9gKSCsTlXzVF7wIw8I5p4MghgPHejwXmjeO2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    @php
        $detail_taksi = App\Models\Taksi::where('id_user', Auth::user()->id)->first();
        $pesanan = App\Models\Pemesanan::where('id_taksi', $detail_taksi->id)
            ->where('pesanan_selesai', 0)
            ->get();
    @endphp
    <a href="{{ url('/home') }}" class="btn btn-secondary mb-3"><i class="bx bx-arrow-back"></i> Kembali ke dashboard</a>
    <div class="row">
        <div class="col-lg-8">

            <div class="card mb-3">
                <div class="card-header">
                    <h5>Rute Penjemputan</h5>
                </div>
                <div class="card-body">
                    <div id="map" style="border-radius: 10px"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Rute</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($pesanan as $item)
                            <li class="list-group-item">
                                <span class="text-primary">
                                    Dari : <strong>{{ $item->asal->nama_lokasi }}</strong> ke
                                    <strong>{{ $item->tujuan->nama_lokasi }}</strong></span>
                                <br>Penumpang : {{ $item->user->name }}<br>
                                <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <script>
        var map = L.map('map').setView([-8.3692728, 140.4600467], 10);

        // Tambahkan layer tile untuk peta dasar
        var baseLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        // Tambahkan layer tile untuk label
        var labelLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=h&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        // Variabel global untuk marker dan rute
        var currentMarker;
        var routingControl;
        var carIcon = L.icon({
            iconUrl: '{{ asset('img/mobil.png') }}',
            iconSize: [60, 60] // ukuran ikon dalam piksel
        });

        // Fungsi untuk mengambil data dari API
        function getDataFromAPI() {
            var detailTaksiId = {{ $detail_taksi->id }};
            var currentLatitude, currentLongitude;

            // Fungsi untuk mendapatkan koordinat dari API
            function fetchCoordinates() {
                $.ajax({
                    type: "GET",
                    url: "/get-koordinat/" + detailTaksiId,
                    success: function(response) {
                        if (response.latitude && response.longitude) {
                            currentLatitude = response.latitude;
                            currentLongitude = response.longitude;
                            updateCurrentPosition();
                        } else {
                            console.warn('Invalid coordinates received from API.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching coordinates:", error);
                    }
                });
            }

            // Fungsi untuk memperbarui posisi kendaraan dan rute
            function updateCurrentPosition() {
                var currentPosition = L.latLng(currentLatitude, currentLongitude);

                // Hapus marker kendaraan sebelumnya jika ada
                if (currentMarker) {
                    map.removeLayer(currentMarker);
                }

                // Tambahkan marker kendaraan ke peta
                currentMarker = L.marker(currentPosition, {
                    draggable: false,
                    icon: carIcon
                }).addTo(map).bindPopup('Anda disini');

                // Panggil API untuk mendapatkan data rute
                $.ajax({
                    url: '/rute-penjemputan/' + detailTaksiId,
                    method: 'GET',
                    success: function(data) {
                        // Hapus marker lokasi penjemputan sebelumnya
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.Marker && layer !== currentMarker) {
                                map.removeLayer(layer);
                            }
                        });

                        // Tambahkan marker lokasi penjemputan
                        data.forEach(function(item) {
                            if (item.asal && item.asal.latitude && item.asal.longitude && item.asal
                                .nama_lokasi) {
                                L.marker([item.asal.latitude, item.asal.longitude], {
                                    draggable: false
                                }).addTo(map).bindPopup(item.asal.nama_lokasi);
                            } else {
                                console.warn('Invalid route item:', item);
                            }
                        });

                        // Perbarui rute di peta
                        updateRoute(currentPosition, data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching route data:', error);
                    }
                });
            }

            // Fungsi untuk memperbarui rute di peta
            function updateRoute(currentPosition, data) {
                if (data && Array.isArray(data) && data.length > 0) {
                    // Hapus rute sebelumnya jika ada
                    if (routingControl) {
                        map.removeControl(routingControl);
                    }

                    // Membuat waypoints dari data API
                    var waypoints = data.map(function(item) {
                        if (item.asal && item.asal.latitude && item.asal.longitude) {
                            return L.latLng(item.asal.latitude, item.asal.longitude);
                        }
                    }).filter(Boolean); // Hapus waypoint yang undefined

                    // Tambahkan posisi saat ini sebagai waypoint pertama
                    waypoints.unshift(currentPosition);

                    // Tambahkan kontrol rute ke peta
                    routingControl = L.Routing.control({
                        waypoints: waypoints,
                        routeWhileDragging: false,
                        show: false,
                        createMarker: function() {
                            return null; // Tidak membuat marker otomatis
                        }
                    }).addTo(map);
                } else {
                    console.warn('No valid route data received.');
                }
            }

            // Panggil fetchCoordinates setiap 5 detik
            setInterval(fetchCoordinates, 5000);
        }

        // Panggil fungsi untuk mengambil data dari API
        getDataFromAPI();

        // Tangani error routing
        map.on('routing_error', function(e) {
            console.error('Routing error:', e.error);
        });
    </script>
    //kirim lokasi setiap 5 detik
    <script>
        $(document).ready(function() {
            // Fungsi untuk mengirim data ke server
            function sendData(latitude, longitude) {
                var id_taksi = {{ $detail_taksi->id }};
                $.ajax({
                    type: 'POST',
                    url: '/send-koordinat', // Ganti dengan URL endpoint server Anda
                    data: {
                        id_taksi: id_taksi,
                        latitude: latitude,
                        longitude: longitude
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // console.log("Data terkirim: " + response.message);
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                    }
                });
            }

            // Fungsi untuk mendapatkan lokasi dan mengirim data
            function getLocationAndSend() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;
                        sendData(latitude, longitude);
                    });
                } else {
                    console.error("Geolocation tidak didukung oleh browser ini.");
                }
            }

            // Memanggil fungsi getLocationAndSend setiap 5 detik
            setInterval(getLocationAndSend, 5000);
        });
    </script>
@endpush
