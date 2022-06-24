@extends('layouts.admin')

@section('content')

<div class="container-fluid px-5">

    @include('partials.message')
    @include('partials.errors')

    <div class="row">
        <div class="col-4">
            <h2 class="py-3">Add New Tags</h2>
            <form class="d-flex align-items-center" action="{{route('admin.tags.store')}}" method="post">
                @csrf
                <div class="mr-3">
                    <label for="name" class="form-label mb-0">Name</label>
                    <input type="text" class="form-control" name="name" id="name" aria-describedby="helpIdName" placeholder="Add tag">
                    <small id="helpIdName" class="form-text text-muted">add tag</small>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary mx-1 text-white">Add</button>
                </div>
            </form>
        </div>

        <div class="col-8">
            <h2 class="py-3">All Tags</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Post Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                    <tr>
                        <td scope="row">{{$tag->id}}</td>
                        <td>
                            <form id="form-categories-{{$tag->id}}" action="{{route('admin.tags.update',$tag->slug)}}" method="post">
                                @csrf
                                @method('PATCH')
                                <input class="border-0 bg-transparent" type="text" name="name" id="name" value="{{$tag->name}}">
                            </form>
                        </td>
                        <td>{{$tag->slug}}</td>
                        <td><span class="badge badge-info bg-dark">{{count($tag->posts)}}</span></td>
                        <td class="d-flex">
                        
                        <button form="form-tags-{{$tag->id}}" type="submit" class="btn btn-success text-white mx-1">Update</button>
                        <!-- delete -->
                        <form action="{{route('admin.tags.destroy',$tag->slug)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger text-white">Delete</button>
                        </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td scope="row">No tags</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection