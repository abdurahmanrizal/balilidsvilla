@extends('layouts.client.app')
@section('content')
    <br>
    <br>
    <div class="container">
        <div class="row">
            @foreach ($galleries as $gallery)
                <div class="col-md-4 mb-2">
                    @if($loop->even &&  $loop->index < 2 )
                        <img src="{{asset('uploads/gallery/photo/'. $gallery->url)}}" alt="{{$gallery->id}}" class="img-fluid img-thumbnail shadow" style="border-radius: 15px; height: 400px">
                    @elseif($loop->even && $loop->iteration != 8 && ($loop->iteration % 6 != 2))
                    <img src="{{asset('uploads/gallery/photo/'. $gallery->url)}}" alt="{{$gallery->id}}" class="img-fluid img-thumbnail shadow" style="border-radius: 15px; height: 400px; margin-top: -11rem">
                    @elseif($loop->even && $loop->iteration >= 8 && ($loop->iteration % 6 == 2))
                    <img src="{{asset('uploads/gallery/photo/'. $gallery->url)}}" alt="{{$gallery->id}}" class="img-fluid img-thumbnail shadow" style="border-radius: 15px; height: 400px;">
                    @else
                    <img src="{{asset('uploads/gallery/photo/'. $gallery->url)}}" alt="{{$gallery->id}}" class="img-fluid img-thumbnail shadow" style="border-radius: 15px; height: 200px">
                    @endif
                </div>
            @endforeach
        </div>

    </div>
@endsection

