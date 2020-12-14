<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bali Lids Villa & Adventures</title>
    {{-- bootstrap 4.5 css --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    {{-- style client css --}}
    <link rel="stylesheet" href="{{asset('css/style-client.css')}}">
    {{-- <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Sansita+Swashed:wght@500&display=swap" rel="stylesheet"> --}}
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@500&display=swap" rel="stylesheet">
    {{-- swiper js css --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    {{-- slick min css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    {{-- slick theme css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light p-3" id="navbar-resort">
        <div class="container">
            <a class="navbar-brand sanshita-swashed" href="{{route('landing-page')}}" style="font-size: 30px !important">Bali Lids Villa</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto futura">
                @if(Route::current()->getName() != 'landing-page')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('landing-page')}}">Home</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="#villas">Villas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#activities">Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#packages">Packages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#gallery">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#blog">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about-us">About us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#testimonial">Testimonial</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact-us">Contact us</a>
                </li>
                @endif

            </ul>
            </div>
        </div>

    </nav>
    @yield('content')
    <br>
    <br>
     <!-- Footer -->
     <footer class="page-footer font-small blue pt-4">

        <!-- Footer Links -->
        <div class="container text-center text-md-left">

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column -->
            <div class="col-md-6 mt-md-0 mt-3">

            <!-- Content -->
            <h4 class="text-uppercase orange sanshita-swashed font-weight-bold">Bali Lids Villa</h4>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none pb-3">

            <!-- Grid column -->
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-6 mb-md-0 mb-3">

            <!-- Links -->
            <div class="list-social-media">
                <ul class="list-inline">
                    @foreach ($social_medias as $social_media)
                        @if($social_media->item_social_media == 'facebook')
                        <li class="list-inline-item">
                            <img src="{{asset('images/asset/facebook.png')}}" alt="facebook-bali-lids-villa" width="20">
                            <a href="https://www.facebook.com/{{$social_media->name_social_media}}" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                        </li>
                        @elseif($social_media->item_social_media == 'instagram')
                        <li class="list-inline-item">
                            <img src="{{asset('images/asset/instagram.png')}}" alt="facebook-bali-lids-villa" width="20">
                            <a href="https://www.instagram.com/{{$social_media->name_social_media}}" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                        </li>
                        @elseif($social_media->item_social_media == 'whatsapp')
                        <li class="list-inline-item">
                            @php
                                $conv_number = preg_replace('/^0/','62', $social_media->name_social_media);
                            @endphp
                            <img src="{{asset('images/asset/whatsapp.png')}}" alt="whatsapp-bali-lids-villa" width="20">
                            <a href="http://api.whatsapp.com/send?phone={{$conv_number}}&text=Halo%20Customer%20Service%20Satu" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                        </li>
                        @elseif($social_media->item_social_media == 'tiktok')
                        <li class="list-inline-item mt-2">
                            <i class="fab fa-tiktok"></i>
                            @php
                                $urlTiktok = 'https://www.tiktok.com/@'.$social_media->name_social_media.'';
                            @endphp
                            <a href="{{$urlTiktok}}" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                        </li>
                        @elseif($social_media->item_social_media == 'youtube')
                        <li class="list-inline-item mt-2">
                            <i class="fab fa-youtube"></i>
                            <a href="https://www.youtube.com/channel/{{$social_media->name_social_media}}" class="text-dark" target="_blank">Bali Lids Villa & Adventures</a>
                        </li>
                        @endif

                    @endforeach
                </ul>
            </div>


            </div>
            <!-- Grid column -->

        </div>
        <!-- Grid row -->

        </div>
        <!-- Footer Links -->

        <!-- Copyright -->
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="https://mdbootstrap.com/" class="text-dark sanshita-swashed"> Bali Lids Villa</a>
        </div>
        <!-- Copyright -->

    </footer>
    <!-- Footer -->
    {{-- jQuery js --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    {{-- JQuery migrate --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.3.2/jquery-migrate.min.js"></script>
    {{-- popper js --}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    {{-- Bootstrap 4.5 js --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    {{-- Vue js --}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    {{-- smooth scroll --}}
    <script src="https://cdn.jsdelivr.net/gh/cferdinandi/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
    {{-- swiper js --}}
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    {{-- slick carousel js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    @stack('script')

</body>
</html>
