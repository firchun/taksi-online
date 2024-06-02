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

        // Fungsi untuk mengambil data dari API
        function getDataFromAPI() {
            var detailTaksiId = {{ $detail_taksi->id }};
            var currentLatitude, currentLongitude;
            var carIcon = L.icon({
                iconUrl: '{{ asset('img/mobil.png') }}',
                iconSize: [60, 60], // ukuran ikon dalam piksel
                // iconAnchor: [16, 16],
            });
            var currentMarker;

            function fetchCoordinates() {
                $.ajax({
                    type: "GET",
                    url: "/get-koordinat/" + {{ $detail_taksi->id }},
                    success: function(response) {

                        var data = response;
                        currentLatitude = data.latitude;
                        currentLongitude = data.longitude;
                        // console.log(response);
                        updateCurrentPosition();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
            }
            setInterval(fetchCoordinates, 5000);

            function updateCurrentPosition() {
                var currentPosition = L.latLng(currentLatitude, currentLongitude);

                if (currentMarker) {
                    map.removeLayer(currentMarker);
                }
                currentMarker = L.marker(currentPosition, {
                    draggable: false,
                    icon: carIcon
                }).addTo(map).bindPopup('Anda disini');

                // Perbarui peta secara eksplisit untuk memastikan perubahan diterapkan
                map.invalidateSize();
                // Lakukan AJAX request setelah mendapatkan posisi
                $.ajax({
                    url: '/rute-penjemputan/' + detailTaksiId,
                    method: 'GET',
                    success: function(data) {
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.Marker && layer !== currentMarker) {
                                map.removeLayer(layer);
                            }
                        });
                        data.forEach(function(item) {
                            var marker = L.marker([item.asal.latitude, item.asal
                                    .longitude
                                ], {
                                    draggable: false
                                }).addTo(map)
                                .bindPopup(item.asal.nama_lokasi);
                        });

                        // Membuat waypoints dari data yang diterima
                        var waypoints = data.map(function(item) {
                            return L.latLng(item.asal.latitude, item.asal.longitude);
                        });
                        waypoints.unshift(currentPosition);

                        // Menambahkan rute antara waypoint
                        var routingControl = L.Routing.control({
                            waypoints: waypoints,
                            routeWhileDragging: false,
                            show: false,
                            createMarker: function() {
                                return null;
                            }
                        }).addTo(map);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });

            }

            // Panggil updateCurrentPosition() untuk pertama kali
            updateCurrentPosition();

            // Atur interval untuk memanggil updateCurrentPosition() setiap 5 detik
            // setInterval(updateCurrentPosition, 5000);
        }



        // Panggil fungsi untuk mengambil data dari API
        getDataFromAPI();

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
