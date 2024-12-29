@extends('layout.layout')

@section('title', 'Update post')

@section('content')
    <div class="w-full flex justify-center mb-[20px]">
        <form class="update-post-form" action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            <h2 class="mb-[20px] font-semibold text-center text-[24px]">Update post {{ $post->id }}</h2>

            @csrf
            @method('PUT')

            @error('tags')
                <p>{{ $message }}</p>
            @enderror
            <div class="mb-[20px] flex flex-col gap-2">
                <label for="tags" class="font-medium">Post tags</label>
                <input class="w-[600px] px-[20px] py-[15px] outline-none m-0" name="tags" id="tags" type="text" placeholder="Post tag 1, post tag 2, ..." value="@foreach ($post->tags as $tag){{ $tag->name.',' }} @endforeach">
            </div>

            @error('title')
            <p>{{ $message }}</p>
            @enderror
            <div class="mb-[20px] flex flex-col gap-2">
                <label for="title" class="font-medium">Post title</label>
                <input class="w-[600px] px-[20px] py-[15px] outline-none m-0" name="title" id="title" type="text" placeholder="Post title ..." value="{{ $post->title }}">
            </div>

            @error('desc')
            <p>{{ $message }}</p>
            @enderror
            <div class=" flex flex-col gap-2">
                <label for="desc" class="font-medium">Post description</label>
                <textarea class="mb-[20px] h-[250px] w-[600px] px-[20px] py-[15px] outline-none m-0" name="desc" id="desc" placeholder="Post text ...">{{ $post->desc }}</textarea>
            </div>

            @error('image')
                <p>{{ $message }}</p>
            @enderror
            <div class="flex mb-[20px]">
                <span class="mr-[20px] font-medium">Post image</span>
                <input name="image" type="file">
            </div>
            <div>
                <button class="w-full main-link" type="submit">Add</button>
            </div>
        </form>
    </div>
@endsection
