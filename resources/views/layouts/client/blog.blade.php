@extends('layouts.client.app')
@section('content')
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="sanshita-swashed font-weight-bold mb-3">{{$blog_post->title}}</h2>
                <small class="text-muted mb-3">{{date('l', strtotime($blog_post->created_at))}}, {{date('d M Y', strtotime($blog_post->created_at))}} ({{date('H:i', strtotime($blog_post->created_at))}})</small>
                <img src="{{asset('uploads/blog-post/photo/'. $blog_post->url)}}" alt="{{$blog_post->title}}" class="img-fluid d-block mx-auto shadow mt-4" style="border-radius: 15px; height: 500px">
                <br>
                <br>
                {!!$blog_post->description!!}
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                 <h4 class="font-weight-bold sanshita-swashed">Related Article</h4>
            </div>
            @foreach ($blog_posts as $item_blog)
                <div class="col-md-4">
                    <div class="card shadow mb-2" style="border-radius: 15px; height: 25rem">
                        <img src="{{asset('uploads/blog-post/photo/'. $item_blog->url)}}" class="card-img-top shadow img-thumbnail" alt="{{$item_blog->title}}" style="border-radius: 15px">
                        <div class="card-body">
                          <h5 class="card-title sanshita-swashed">{{$item_blog->title}}</h5>
                          <a href="{{route('blog.post',['id' => $item_blog->id])}}" class="btn btn-primary">Read More</a>
                        </div>
                      </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

