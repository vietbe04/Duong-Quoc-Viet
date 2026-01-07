@extends('layouts.master')

@section('title','Edit Post')

@section('content')
    <h1>Edit Post</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',$post->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Thumbnail</label>
            @if($post->thumbnail)
                <div class="mb-2"><img src="{{ $post->thumbnail_url }}" width="160"></div>
            @endif
            <input type="file" name="thumbnail" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required>{{ old('description',$post->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="6">{{ old('content',$post->content) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Categories</label>
            <select name="categories[]" class="form-control" multiple>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @if(in_array($cat->id,$selected)) selected @endif>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
@endsection