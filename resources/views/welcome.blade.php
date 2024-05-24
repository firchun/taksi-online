@extends('layouts.frontend.app')

@section('content')
    <section class="banner-area py-7">
        <!-- Content -->
        <div class="container">
            <div class="row  align-items-center">
                <div class="col-md-12 col-lg-7 text-center text-lg-left">
                    <div class="main-banner">
                        <!-- Heading -->
                        <h1 class="display-4 mb-4 font-weight-normal">
                            Solusi perjalanan anda tanpa ribet
                        </h1>

                        <!-- Subheading -->
                        <p class="lead mb-4">
                            <b>{{ env('APP_NAME') }}</b> memberikan pengalaman yang lebih terjangkau tanpa mengorbankan
                            kualitas.
                            Bergabunglah dengan
                            kami hari ini dan temukan penawaran eksklusif serta diskon yang akan membuat perjalanan Anda
                            lebih menyenangkan dan hemat.
                        </p>

                        <!-- Button -->
                        <p class="mb-0">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-circled">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <div class="banner-img-block">
                        <img src="{{ asset('frontend_theme') }}/images/banner-img-5.png" alt="banner-img" class="img-fluid">
                    </div>
                </div>
            </div> <!-- / .row -->
        </div> <!-- / .container -->
    </section>
    <section class="section bg-grey" id="layanan">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-3 col-sm-6 col-md-6">
                    <div class="text-center feature-block">
                        <div class="img-icon-block mb-4">
                            <i class="ti-thumb-up"></i>
                        </div>
                        <h4 class="mb-2">Terpercaya</h4>
                        <p>Kami menawarkan perjalanan yang aman dan nyaman untuk pelanggan setia kami.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-md-6">
                    <div class="text-center feature-block">
                        <div class="img-icon-block mb-4">
                            <i class="ti-wallet"></i>
                        </div>
                        <h4 class="mb-2">Hemat</h4>
                        <p>Kami menawarkan kemudahan akses dan menghemat dana anda untuk dapat bepergian antar kota</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-md-6">
                    <div class="text-center feature-block">
                        <div class="img-icon-block mb-4">
                            <i class="ti-dashboard"></i>
                        </div>
                        <h4 class="mb-2">Cepat</h4>
                        <p>kamu mengutamakan kenyamanan pengguna dengan kecepatan kami dalam respon pesanana pelanggan setia
                            kami.</p>
                    </div>
                </div>
            </div>
        </div> <!-- / .container -->
    </section>
    <section class="section" id="rute">
        <div class="container">
            <div class="section-heading text-center">
                <!-- Heading -->
                <h2 class="section-title">
                    Rute Perjalanan Mobil Kami
                </h2>

                <!-- Subheading -->
                <p>
                    Berikut rute perjalanan yang lalui kami
                </p>
            </div>
            <div class="row">

                <div class="col-lg-8 col-md-8">
                    <div id="map" style="border-radius: 10px"></div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h3>Daftar Rute</h3>
                    <div class="list-group">
                        @foreach (App\Models\Rute::all() as $item)
                            <a href="#rute" class="list-group-item list-group-item-action"><i class="fas fa-map-pin">
                                </i>
                                {{ $item->nama_lokasi }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section bg-grey" id="harga">
        <!-- Content -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 text-center">
                    <div class="section-heading">
                        <!-- Heading -->
                        <h2 class="section-title">
                            Harga Layanan Kami
                        </h2>

                        <!-- Subheading -->
                        <p>
                            Kami menawarkan biaya yang sesuai dengan kantong anda
                        </p>
                    </div>
                </div>
            </div> <!-- / .row -->

            <div class="row justify-content-center">
                <div class="col-lg-4 col-sm-6 col-md-6">
                    <div class="pricing-box shadow shadow-md" style="border-radius: 20px;">
                        <h3>Standar</h3>
                        <div class="price-block">
                            <h2>75K<span>/Orang</span></h2>
                        </div>

                        <ul class="price-features list-unstyled">
                            <li>Penjemputan</li>
                            <li>1 Kursi</li>
                            <li>Pemberhentian sesuai tujuan</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="section" id="mobil">
        <div class="container">
            <div class="section-heading testimonial-heading text-center">
                <h1>Mobil Kami</h1>
                <p>Berikut mobil yang tersedia</p>
            </div>
            <div class="row align-items-center">
                @foreach (App\Models\Taksi::all() as $item)
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="test-inner border border-secondary p-2">
                            <div class="test-author-thumb d-flex align-items-center">
                                <img src="{{ Storage::url($item->foto_depan) }}" alt="Testimonial author" class="img-fluid">
                                <div class="test-author-info">
                                    <h3>{{ $item->plat_nomor }}</h3>
                                    <h4>{{ $item->merek }} - {{ $item->warna }}</h4>
                                    <h6>Supir : {{ $item->supir->name }}</h6>
                                    </h6>
                                </div>
                            </div>
                            <i class="fa fa-car {{ $item->aktif == 1 ? 'text-success' : 'text-muted' }}"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        </div>
    </section>
    <section class="section bg-grey" id="testimoni">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-sm-12 col-md-12">
                    <div class="section-heading testimonial-heading">
                        <h1>Ulasan Pelanggan</h1>
                        <p>Berikut ulasan dan rating oleh para pelanggan kamu yang setia</p>
                    </div>
                </div>
                <div class="col-lg-8 col-sm-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="test-inner ">
                                <div class="test-author-thumb d-flex">
                                    <img src="{{ asset('frontend_theme') }}/images/client/test-1.jpg"
                                        alt="Testimonial author" class="img-fluid">
                                    <div class="test-author-info">
                                        <h4>Will Barrow</h4>
                                        <h6>Sunrise Paradise Hotel</h6>
                                    </div>
                                </div>

                                Quas ut distinctio tenetur animi nihil rem, amet dolorum totam. Ab repudiandae tempore qui
                                fugiat amet ipsa id omnis ipsam, laudantium! Dolorem.

                                <i class="fa fa-quote-right"></i>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="test-inner ">
                                <div class="test-author-thumb d-flex">
                                    <img src="{{ asset('frontend_theme') }}/images/client/test-2.jpg"
                                        alt="Testimonial author" class="img-fluid">
                                    <div class="test-author-info">
                                        <h4>Will Barrow</h4>
                                        <h6>Sunrise Paradise Hotel</h6>
                                    </div>
                                </div>

                                Quas ut distinctio tenetur animi nihil rem, amet dolorum totam. Ab repudiandae tempore qui
                                fugiat amet ipsa id omnis ipsam, laudantium! Dolorem.

                                <i class="fa fa-quote-right"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
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
            $.ajax({
                url: '/rute-list',
                method: 'GET',
                success: function(data) {
                    data.forEach(function(item) {
                        var marker = L.marker([item.latitude, item.longitude], {
                                draggable: false
                            }).addTo(map)
                            .bindPopup(item.name);
                    });

                    // Membuat waypoints dari data yang diterima
                    var waypoints = data.map(function(item) {
                        return L.latLng(item.latitude, item.longitude);
                    });

                    // Menambahkan rute antara waypoint
                    var routingControl = L.Routing.control({
                        waypoints: waypoints,
                        routeWhileDragging: true,
                        show: false
                    }).addTo(map);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }

        // Panggil fungsi untuk mengambil data dari API
        getDataFromAPI();

        map.on('routing_error', function(e) {
            console.error('Routing error:', e.error);
        });
    </script>
@endpush
