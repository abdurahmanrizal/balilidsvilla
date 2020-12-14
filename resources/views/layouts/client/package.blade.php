@extends('layouts.client.app')
@section('content')
    <style>
        .carousel-nav{
            width:100%;
            margin:0px auto;
        }
        .carousel-single img {
            width:100%;

            /* margin:0px auto; */
        }
        .slick-slide{
            margin:10px;
        }
        .slick-slide img{
            width:100%;
            border: 2px solid #fff;
        }
        .slick-next:before, .slick-prev:before {
            color: #00b14e;
        }
    </style>
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="carousel-single">
                    @foreach ($package->package_galleries as $gallery)
                        @if($gallery->type == 'photo')
                        <div><img src="{{asset('uploads/package/photo/'.$gallery->url)}}" class="shadow" style="border-radius: 15px"></div>
                        @else
                        <div>
                            <iframe class="embed-responsive-item" src="{{$gallery->url}}" style="height: 20rem;
                                width: 27rem;
                                border-radius: 15px"></iframe>
                        </div>
                        @endif
                    @endforeach
                </div>
                <div class="carousel-nav">
                    @foreach ($package->package_galleries as $gallery)
                        @if($gallery->type == 'photo')
                        <div><img src="{{asset('uploads/package/photo/'.$gallery->url)}}" class="shadow" style="border-radius: 15px"></div>
                        @else
                        <div>
                            <iframe class="embed-responsive-item" src="{{$gallery->url}}" style="height: 13.8rem; border-radius: 15px"></iframe>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-md-7">
                <h3 class="sanshita-swashed mt-3 font-weight-bold">{{$package->name}}</h3>
                <p class="text-muted" style="font-size: 12px">Package</p>
                <small>
                    <i class="fas fa-map-marker-alt" style="color: #e53251"></i>
                    @foreach ($package->resort_items as $item)
                        @if(!$loop->last)
                            <span>{{$item->name}} +</span>
                        @else
                            <span>{{$item->name}}</span>
                        @endif
                    @endforeach
                </small>
                <br>
                <div class="text-muted mt-2" style="font-size: 14px">{!!$package->description!!}</div>
                <h3 style="color: #e53251" class="font-weight-bold mt-3"> {{'IDR ' . number_format($package->price, 0, ',', '.') . ' / Night'}}</h3>
                <br>
                <button type="button" class="btn btn-success sanshita-swashed" id="btn-message-whatsapp">
                    <i class="fab fa-whatsapp" style="font-size:13px"></i>
                     Message to whatsapp
                </button>
            </div>
        </div>
        <br>
        <h3 class="font-weight-bold">Another Package</h3>
        <div class="row mt-4">
            @foreach ($packages as $package)
                <div class="col-md-3">
                    <a href="{{route('package.post',['id' => $package->id])}}" class="villa-hover">
                        <div class="card shadow" style="border-radius: 15px; height: 22rem">
                            @if($package->package_gallery->type == 'photo')
                                <img src="{{asset('uploads/package/photo/'.$package->package_gallery->url)}}" class="card-img-top shadow" alt="{{$package->name}}" style="border-radius: 15px; height: 10.8rem">
                            @else
                                <div>
                                    <iframe class="embed-responsive-item" src="{{$package->package_gallery->url}}" style="width: 16rem;
                                        border-radius: 15px; height: 9rem"></iframe>
                                </div>
                            @endif

                            <div class="card-body">
                            <h5 class="card-title sanshita-swashed font-weight-bold">{{$package->name}}</h5>
                            <p class="text-muted" style="font-size: 12px">Package</p>
                            <small>
                                <i class="fas fa-map-marker-alt" style="color: #e53251"></i>
                                @foreach ($package->resort_items as $item)
                                    @if(!$loop->last)
                                        <span>{{$item->name}} +</span>
                                    @else
                                        <span>{{$item->name}}</span>
                                    @endif
                                @endforeach
                            </small>
                            <p style="color: #e53251" class="font-weight-bold mt-3"> {{'IDR ' . number_format($package->price, 0, ',', '.')}}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('script')
    <script>
        let carouselNav = "{{$count_gallery}}"
        $('.carousel-single').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.carousel-nav'
            });
        if(carouselNav == 1) {
            $('.carousel-nav').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                asNavFor: '.carousel-single',
                // dots: true,

                centerMode: true,
                focusOnSelect: true
            });
        }else if(carouselNav > 1 && carouselNav <= 2) {
            $('.carousel-nav').slick({
                slidesToShow: 2,
                slidesToScroll: 1,
                asNavFor: '.carousel-single',
                // dots: true,

                centerMode: true,
                focusOnSelect: true
            });
        }else {
            $('.carousel-nav').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                asNavFor: '.carousel-single',
                // dots: true,

                centerMode: true,
                focusOnSelect: true
            });
        }


    </script>
@endpush
