@extends('layouts.client.app')
@section('content')
    <section id="banner">
        <div class="swiper-container" id="swiper-banner">
            <div class="swiper-wrapper">
                @foreach ($banners as $banner)
                <div class="swiper-slide">
                    <img src="{{asset('uploads/banner/photo/'.$banner->url)}}" alt="{{$banner->id}}" class="img-fluid">
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <br>
    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center font-weight-bold sanshita-swashed orange">About</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mt-2 col-about-banner">
                    <img src="{{asset('uploads/gallery/about-us.jpeg')}}" alt="about-banner" class="img-fluid about-banner">
                </div>
                <div class="col-md-6 block-margin">
                    <div class="card bg-orange" style="border-radius: 10px">
                        <div class="card-body">
                            <h3 class="text-center font-weight-bold sanshita-swashed text-light">Bali Lids Villa</h3>
                            <img src="{{asset('images/asset/hotel.png')}}" alt="hotel-icon" class="img-fluid d-block mx-auto mt-4" width="30">
                            @if($description == 'empty')
                                <p class="text-center text-light mt-2">
                                    Coming Soon
                                </p>
                            @else
                                <p class="text-center text-light mt-2">
                                    {!! $description !!}
                                </p>
                            @endif

                        </div>
                      </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    <br>
    <section id="villa">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="font-weight-bold sanshita-swashed orange">Villa</h2>
                </div>
            </div>
            <div class="row">
                @if(count($villas) == 0)
                    <button type="button" class="btn btn-outline-success d-block mx-auto">Coming Soon</button>
                @else
                    @foreach ($villas as $villa)
                        <div class="col-md-4 click-villa" data-id="{{$villa->id}}">
                            <a href="{{route('villa.post',['id' => $villa->id])}}" target="_blank">
                                @if($villa->resort_gallery->type == 'photo')
                                <img src="{{asset('uploads/villa/photo/'.$villa->resort_gallery->url)}}" alt="{{$villa->name}}" class="img-fluid img-thumbnail shadow" style="border-top-right-radius: 20px;border-bottom-left-radius: 15px;border-bottom-right-radius: 15px;height: 25rem">
                                @else
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="{{$villa->resort_gallery->url}}" allowfullscreen></iframe>
                                </div>
                                @endif
                                <div class="card shadow mb-2 card-villa card-villa" style="margin-top:-8.5rem !important; background-color: rgba(0,0,0,0.4); height:8.5rem; border-radius:15px">
                                    <div class="card-body">
                                        <h5 class="font-weight-bold text-light">{{$villa->name}}</h5>
                                        <h6 class="font-weight-bold text-light">{{'IDR ' . number_format($villa->price, 0, ',', '.') . ' / Night'}}</h6>
                                        <p class="text-light mt-2 sanshita-swashed"><i class="fas fa-map-marker-alt" style="color:red; font-size:13px"></i> {{$villa->location}}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <br>
    <br>
    <section id="activity">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="font-weight-bold sanshita-swashed orange text-center">Activity</h2>
                </div>
            </div>
            @if(count($activities) == 0)
            <button type="button" class="btn btn-outline-success d-block mx-auto">Coming Soon</button>
            @else
                <div class="swiper-container mt-2" id="swiper-activity">
                    <div class="swiper-wrapper">
                        @foreach ($activities as $activity)
                            <div class="swiper-slide click-activity">
                                <a href="{{route('activity.post',['id' => $activity->id])}}">
                                @if($activity->resort_gallery->type == 'photo')
                                    <img src="{{asset('uploads/activity/photo/'.$activity->resort_gallery->url)}}" alt="{{$activity->name}}" class="img-fluid img-thumbnail shadow" style="border-top-right-radius: 20px;border-bottom-left-radius: 15px;border-bottom-right-radius: 15px;height: 25rem">
                                @else
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="{{$activity->resort_gallery->url}}" allowfullscreen></iframe>
                                    </div>
                                @endif
                                <div class="card shadow mb-2 card-activity" style="margin-top:-7.5rem !important; background-color: rgba(0,0,0,0.9); height:7.5rem; border-radius:15px">
                                    <div class="card-body">
                                        <h5 class="font-weight-bold text-light">{{$activity->name}}</h5>
                                        <h6 class="font-weight-bold text-light">{{'IDR ' . number_format($activity->price, 0, ',', '.')}}</h6>
                                        <p class="text-light mt-2 sanshita-swashed"><i class="fas fa-map-marker-alt" style="color:red; font-size:13px"></i> {{$activity->location}}</p>
                                    </div>
                                </div>
                            </a>
                            </div>
                        @endforeach
                    </div>
                    <!-- Add Arrows -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            @endif
        </div>
    </section>
    <br>
    <br>
    <section id="package">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="font-weight-bold sanshita-swashed orange">Package</h2>
                </div>
            </div>
            <div class="row mt-3">
                @if(count($packages) > 0)
                    @foreach ($packages as $package)
                    <div class="col-md-4">
                        <a href="{{route('package.post',['id' => $package->id])}}">
                            <img src="{{asset('uploads/package/photo/'.$package->package_gallery->url)}}" alt="{{$package->name}}" class="img-fluid img-thumbnail shadow" style="border-top-right-radius: 20px;border-bottom-left-radius: 15px;border-bottom-right-radius: 15px;height: 25rem">
                            <div class="card shadow mb-2 card-package" style="margin-top:-7.5rem !important; background-color: rgba(0,0,0,0.4); height:7.5rem; border-radius:15px">
                                <div class="card-body">
                                    <h5 class="font-weight-bold text-light">{{$package->name}}</h5>
                                    <h6 class="font-weight-bold text-light">{{'IDR ' . number_format($package->price, 0, ',', '.')}}</h6>
                                    <i class="fas fa-map-marker-alt" style="color: red"></i>
                                    @foreach ($package->resort_items as $item)
                                        @if (!$loop->last)
                                            <span class="text-light">{{$item->name}} + </span>
                                        @else
                                            <span class="text-light">{{$item->name}}</span>
                                        @endif

                                    @endforeach
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                @else
                    <button type="button" class="btn btn-outline-success d-block mx-auto">Coming Soon</button>
                @endif
            </div>
        </div>
    </section>
    <br>
    <br>
    <section id="blog">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="font-weight-bold sanshita-swashed orange text-center">Blog</h2>
                </div>
            </div>
            <div class="row mt-3">
                @if(count($blogs) > 0)
                    @foreach ($blogs as $blog)
                        @if($loop->first)
                            <div class="col-md-6">
                                <img src="{{asset('uploads/blog-post/photo/'.$blog->url)}}" alt="{{$blog->title}}" class="img-fluid img-thumbnail" style="border-radius: 15px">
                                <br>
                                <br>
                                <h4 class="font-weight-bold">{{$blog->title}}</h4>
                                <br>
                                <p class="text-justify">{{strip_tags($blog->description)}}</p>
                                <a href="{{route('blog.post',['id' => $blog->id])}}">Read More</a>
                            </div>
                        @endif
                    @endforeach
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                        @foreach ($blogs as $blog)
                                @if(!$loop->first)
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="{{asset('uploads/blog-post/photo/'.$blog->url)}}" alt="{{$blog->title}}" class="img-fluid img-thumbnail" style="border-radius: 15px; height: 150px">
                                            </div>
                                            <div class="col-md-8">
                                                <br>
                                                <h5 class="font-weight-bold">{{$blog->title}}</h5>
                                                <a href="{{route('blog.post',['id' => $blog->id])}}">Read More</a>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                        @endforeach
                        </ul>
                    </div>
                @else
                    <button type="button" class="btn btn-outline-success d-block mx-auto">Coming Soon</button>
                @endif
            </div>
    </section>
    <br>
    <br>
    <section id="gallery">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="font-weight-bold sanshita-swashed orange text-center">Gallery</h2>
                </div>
            </div>
            <br>
            <div class="section-gallery">
                <div class="row">
                   @if(count($galleries) > 0)
                        @foreach ($galleries as $gallery)
                            @if($loop->iteration == 1 || $loop->iteration == 2)
                                    <div class="col-md-4">
                                        <img src="{{asset('uploads/gallery/photo/'.$gallery->url)}}" alt="{{$gallery->id}}" class="img-fluid shadow" style="border-radius: 15px;height: 27.5rem">
                                    </div>

                            @endif
                        @endforeach
                        <div class="col-md-4">
                        @foreach ($galleries as $gallery)
                            @if($loop->iteration != 1 && $loop->iteration != 2)
                                    <img src="{{asset('uploads/gallery/photo/'.$gallery->url)}}" alt="{{$gallery->id}}" class="img-fluid shadow mb-4" style="border-radius: 15px">
                            @endif
                        @endforeach
                        </div>
                    @else
                    <button type="button" class="btn btn-outline-success d-block mx-auto">Coming Soon</button>
                    @endif
                </div>
                @if(count($galleries) > 0)
                <div class="text-center">
                    <a href="{{route('gallery.more')}}" class="btn btn-success">See More</a>
                </div>

                @endif
                <div class="row"></div>
                </div>
            </div>

        </div>
    </section>
    <br>
    <br>

    <br>
    <br>
    <section id="testimonial">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="font-weight-bold sanshita-swashed orange">Our Guest Experience</h2>
                </div>
            </div>
            <div class="row" id="row-testimonial">
                @if (count($testimonials) > 0)
                    @foreach ($testimonials as $testimonial)
                        <div class="col-md-4">
                            <div class="card shadow" style="border-radius: 15px">
                                <img src="{{asset('uploads/testimonial/photo/'.$testimonial->url)}}" class="rounded-circle d-block mx-auto mt-4" alt="{{$testimonial->id}}" width="80" height="80">
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{$testimonial->name}}</h5>
                                    <p class="text-muted text-center" style="font-size: 12px">{{$testimonial->position}}</p>
                                    <p class="card-text text-center">
                                        <span class="font-weight-bold pacifico" style="font-size: 25px; color: grey">"</span>
                                        {{$testimonial->testimonial}}
                                        <span class="font-weight-bold pacifico" style="font-size: 25px; color: grey">"</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h5 class="btn btn-outline-success sanshita-swashed mx-auto">Coming Soon</h5>
                @endif
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        $(function() {
            const listSocialMediaEL = document.querySelector('.list-social-media')
            const gallery = document.querySelector('.section-gallery')
            const testimonial = document.querySelector('#row-testimonial')
            if(/Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
                listSocialMediaEL.innerHTML = `
                    <ul style="list-style:none">
                        @foreach ($social_medias as $social_media)
                            @if($social_media->item_social_media == 'facebook')
                                <li class="mb-3">
                                    <img src="{{asset('images/asset/facebook.png')}}" alt="facebook-bali-lids-villa" width="20">
                                    <a href="https://www.facebook.com/{{$social_media->name_social_media}}" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                                </li>
                            @elseif($social_media->item_social_media == 'instagram')
                                <li class="mb-3">
                                    <img src="{{asset('images/asset/instagram.png')}}" alt="facebook-bali-lids-villa" width="20">
                                    <a href="https://www.instagram.com/{{$social_media->name_social_media}}" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                                </li>
                            @elseif($social_media->item_social_media == 'whatsapp')
                                <li class="mb-3">
                                    @php
                                        $conv_number = preg_replace('/^0/','62', $social_media->name_social_media);
                                    @endphp
                                    <img src="{{asset('images/asset/whatsapp.png')}}" alt="whatsapp-bali-lids-villa" width="20">
                                    <a href="http://api.whatsapp.com/send?phone={{$conv_number}}&text=Halo%20Customer%20Service%20Satu" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                                </li>
                            @elseif($social_media->item_social_media == 'tiktok')
                                <li class="mb-3">
                                    <i class="fab fa-tiktok"></i>
                                    @php
                                        $urlTiktok = 'https://www.tiktok.com/@'.$social_media->name_social_media.'';
                                    @endphp
                                    <a href="{{$urlTiktok}}" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                                </li>
                            @elseif($social_media->item_social_media == 'youtube')
                                <li class="mb-3">
                                    <i class="fab fa-youtube"></i>
                                    <a href="https://www.youtube.com/channel/{{$social_media->name_social_media}}" class="text-dark" target="_blank">Bali Lids Villa & Adventures</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                `
                gallery.innerHTML = `
                    <div class="swiper-container mt-2" id="swiper-gallery">
                        <div class="swiper-wrapper">
                            @foreach ($galleries as $gallery)
                                <div class="swiper-slide">
                                    <img src="{{asset('uploads/gallery/photo/'.$gallery->url)}}" alt="{{$gallery->id}}" class="img-fluid img-thumbnail">
                                </div>
                            @endforeach

                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                `
                testimonial.innerHTML = `
                    @if (count($testimonials) > 0)
                        <div class="swiper-container" id="swiper-testimonial">
                            <div class="swiper-wrapper">
                                @foreach ($testimonials as $testimonial)
                                    <div class="swiper-slide">
                                        <div class="card shadow mb-2 ml-2" style="border-radius: 15px; width: 20rem">
                                            <img src="{{asset('uploads/testimonial/photo/'.$testimonial->url)}}" class="rounded-circle d-block mx-auto mt-4" alt="{{$testimonial->id}}" width="80" height="80">
                                            <div class="card-body">
                                                <h5 class="card-title text-center">{{$testimonial->name}}</h5>
                                                <p class="text-muted text-center" style="font-size: 12px">{{$testimonial->position}}</p>
                                                <p class="card-text text-center">
                                                    <span class="font-weight-bold pacifico" style="font-size: 25px; color: grey">"</span>
                                                    {{$testimonial->testimonial}}
                                                    <span class="font-weight-bold pacifico" style="font-size: 25px; color: grey">"</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </div>
                        </div>

                    @else
                        <h5 class="btn btn-outline-success sanshita-swashed mx-auto">Coming Soon</h5>
                    @endif
                `
            }else if(/Ipad/i.test(navigator.userAgent)) {
                listSocialMediaEL.innerHTML = `
                <ul style="list-style:none">
                        @foreach ($social_medias as $social_media)
                            @if($social_media->item_social_media == 'facebook')
                                <li class="mb-3">
                                    <img src="{{asset('images/asset/facebook.png')}}" alt="facebook-bali-lids-villa" width="20">
                                    <a href="https://www.facebook.com/{{$social_media->name_social_media}}" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                                </li>
                            @elseif($social_media->item_social_media == 'instagram')
                                <li class="mb-3">
                                    <img src="{{asset('images/asset/instagram.png')}}" alt="facebook-bali-lids-villa" width="20">
                                    <a href="https://www.instagram.com/{{$social_media->name_social_media}}" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                                </li>
                            @elseif($social_media->item_social_media == 'whatsapp')
                                <li class="mb-3">
                                    @php
                                        $conv_number = preg_replace('/^0/','62', $social_media->name_social_media);
                                    @endphp
                                    <img src="{{asset('images/asset/whatsapp.png')}}" alt="whatsapp-bali-lids-villa" width="20">
                                    <a href="http://api.whatsapp.com/send?phone={{$conv_number}}&text=Halo%20Customer%20Service%20Satu" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                                </li>
                            @elseif($social_media->item_social_media == 'tiktok')
                                <li class="mb-3">
                                    <i class="fab fa-tiktok"></i>
                                    @php
                                        $urlTiktok = 'https://www.tiktok.com/@'.$social_media->name_social_media.'';
                                    @endphp
                                    <a href="{{$urlTiktok}}" class="text-dark" target="_blank">{{$social_media->name_social_media}}</a>
                                </li>
                            @elseif($social_media->item_social_media == 'youtube')
                                <li class="mb-3">
                                    <i class="fab fa-youtube"></i>
                                    <a href="https://www.youtube.com/channel/{{$social_media->name_social_media}}" class="text-dark" target="_blank">Bali Lids Villa & Adventures</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                `
            }
            var swiper = new Swiper('#swiper-banner', {
                slidesPerView: 1,
                autoplay: {
                    delay: 4000,
                },

                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
            var swiperService = new Swiper('#swiper-service', {
                slidesPerView: 3,
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints : {
                    320: {
                        slidesPerView: 1,
                        spaceBetweenSlides: 30
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetweenSlides: 30
                    }
                }
            });
            var swiperActivity = new Swiper('#swiper-activity', {
                slidesPerView: 3,
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints : {
                    320: {
                        slidesPerView: 1,
                        spaceBetweenSlides: 30
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetweenSlides: 30
                    }
                }
            });
            var swiperActivity = new Swiper('#swiper-gallery', {
                slidesPerView: 1,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                }
            });
            var swiperTestimonial = new Swiper('#swiper-testimonial', {
                slidesPerView: 1,
                spaceBetween: 30,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints : {
                    320: {
                        slidesPerView: 1,
                        spaceBetweenSlides: 50
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetweenSlides: 30
                    }
                }
            });
            $(document).on('click', 'ul.navbar-nav li', function(e) {
                e.preventDefault()
                $(this).parent().find('li.active').removeClass('active');
                $(this).addClass('active');
                var target = $('a',this).attr('href');
                var scroll = new SmoothScroll()
                var anchor = document.querySelector(target)
                scroll.animateScroll(anchor);

            })

            // // event click villa card
            // $(document).on('click','.click-villa', function() {
            //     let id = $(this).data('id')
            //     alert(id)
            // })

            // // event click swiper activity
            // $(document).on('click','.click-activity', function() {
            //     let id = $(this).data('id')
            //     alert(id)
            // })
        });
    </script>
@endpush
