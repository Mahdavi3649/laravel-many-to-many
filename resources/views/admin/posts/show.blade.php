s (12 sloc)  337 Bytes

@extends('layouts.admin')


@section('content')
<div class="container">
    <div class="posts row d-flex py-4">
        <div class="col-6">
            <img class="img-fluid" src="{{asset('storage/'. $post->cover_image)}}" alt="{{$post->title}}">
        </div>
    
        <div class="col-6 post-data px-4">
            <h1>{{$post->title}}</h1>
            <div class="metadata">
                <div class="category">
                    <strong>Category</strong>: {{$post->category ? $post->category->name: 'uncategorized'}}
                </div>
    
                <div class="tags">
                    <strong>Tags:</strong>
                    @if (count($post->tags) > 0 )
                    
                        @foreach($post->tags as $tag)
                        <span>#{{$tag->name}}</span>
     
                        @endforeach
    
                    @else
                      <span>N/A</span>
                    @endif
                </div>
            </div>
            <div class="content">
                {{$post->content}}
            </div>
        </div>
    </div>
</div>


@endsection