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
    <section class="section" id="harga">
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
                    <div class="pricing-box">
                        <h3>Standar</h3>
                        <div class="price-block">
                            <h2>75K<span>/Orang</span></h2>
                        </div>

                        <ul class="price-features list-unstyled">
                            <li>Penjemputan</li>
                            <li>1 Kursi</li>
                            <li>Pengantaran sesuai tujuan</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="section" id="testimoni">
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
