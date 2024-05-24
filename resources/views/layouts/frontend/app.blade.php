<!DOCTYPE html>

<html lang="en">

<head>

    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title>{{ env('APP_NAME') }}</title>

    <!-- Mobile Specific Metas
  ================================================== -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Corporate Html5 Template">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="author" content="Themefisher">
    <meta name="generator" content="Themefisher Rappo HTML Template v1.0">

    <!-- theme meta -->
    <meta name="theme-name" content="rappo" />

    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('frontend_theme') }}/plugins/bootstrap/bootstrap.min.css">
    <!-- Animate -->
    <link rel="stylesheet" href="{{ asset('frontend_theme') }}/plugins/animate-css/animate.css">
    <!-- Icon Font css -->
    <link rel="stylesheet" href="{{ asset('frontend_theme') }}/plugins/fontawesome/css/all.css">
    <link rel="stylesheet" href="{{ asset('frontend_theme') }}/plugins/fonts/Pe-icon-7-stroke.css">
    <!-- Themify icon Css -->
    <link rel="stylesheet" href="{{ asset('frontend_theme') }}/plugins/themify/css/themify-icons.css">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('frontend_theme') }}/plugins/slick-carousel/slick/slick.css">
    <link rel="stylesheet" href="{{ asset('frontend_theme') }}/plugins/slick-carousel/slick/slick-theme.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('frontend_theme') }}/css/style.css">

    <!--Favicon-->
    <link rel="icon" href="{{ asset('frontend_theme') }}/images/favicon.png" type="image/x-icon">
    @stack('css')
</head>

<body id="top-header">

    <!-- LOADER TEMPLATE -->
    <div id="page-loader">
        <div class="loader-icon fa fa-spin colored-border"></div>
    </div>
    <!-- /LOADER TEMPLATE -->



    <div class="logo-bar d-none d-md-block d-lg-block bg-light">
        <div class="container">
            <div class="row">
                <div class="  d-flex col-12 col-md-12 justify-content-md-center">
                    <h2 class="mb-0">{{ env('APP_NAME') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- NAVBAR
        ================================================= -->
    <div class="main-navigation" id="mainmenu-area">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary main-nav navbar-togglable rounded-radius">

                <a class="navbar-brand d-lg-none d-block" href="index.html">
                    <h4 class="h3 mb-0">{{ env('APP_NAME') }}</h4>
                </a>
                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa fa-bars"></span>
                </button>

                <!-- Collapse -->
                <div class="collapse navbar-collapse has-dropdown" id="navbarCollapse">
                    <!-- Links -->
                    <ul class="navbar-nav ">
                        <li class="nav-item dropdown">
                            <a class="nav-link " href="#!">
                                Home
                            </a>

                        </li>
                        <li class="nav-item ">
                            <a href="#rute" class="nav-link js-scroll-trigger">
                                Rute Kami
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="#layanan" class="nav-link js-scroll-trigger">
                                Layanan Kami
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="#harga" class="nav-link js-scroll-trigger">
                                Harga
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="#testimoni" class="nav-link js-scroll-trigger">
                                Ulasan Pengguna
                            </a>
                        </li>

                    </ul>

                    <ul class="ml-lg-auto list-unstyled m-0">
                        @guest
                            <li><a href="{{ route('login') }}" class="btn btn-white btn-circled">Login</a></li>
                        @else
                            <li><a href="{{ route('home') }}" class="btn btn-white btn-circled">Dashboard</a></li>
                        @endguest
                    </ul>
                </div> <!-- / .navbar-collapse -->
            </nav>
        </div> <!-- / .container -->
    </div>
    @yield('content')
    <footer class="section " id="footer">
        <div class="overlay footer-overlay"></div>
        <!--Content -->
        <div class="container">


            <div class="row text-right pt-5">
                <div class="col-lg-12">
                    <div class="overflow-hidden">
                        <!-- Copyright -->
                        <p class="footer-copy">
                            Copyright &copy;
                            <script>
                                var CurrentYear = new Date().getFullYear()
                                document.write(CurrentYear)
                            </script>. Designed &amp; Developed by <a class="current-year"
                                href="{{ url('/') }}">{{ env('APP_NAME') }}</a>
                        </p>
                    </div>
                </div>
            </div> <!-- / .row -->
        </div> <!-- / .container -->
    </footer>

    <!--  Page Scroll to Top  -->

    <a id="scroll-to-top" class="scroll-to-top js-scroll-trigger" href="#top-header">
        <i class="fa fa-angle-up"></i>
    </a>

    <!--
   Essential Scripts
   =====================================-->
    <!-- jQuery -->
    <script src="{{ asset('frontend_theme') }}/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('frontend_theme') }}/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- Slick Slider -->
    <script src="{{ asset('frontend_theme') }}/plugins/slick-carousel/slick/slick.min.js"></script>
    <!-- Google Map -->
    <script
        src="{{ asset('frontend_theme') }}/https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU">
    </script>
    <script src="{{ asset('frontend_theme') }}/plugins/google-map/gmap.js"></script>
    @stack('js')
    <script src="{{ asset('frontend_theme') }}/js/script.js"></script>

</body>

</html>
