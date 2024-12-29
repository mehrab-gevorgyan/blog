@extends('layout.layout')

@section('title', 'Registration')

@section('content')
    <div class="w-full flex justify-center">
        <form class="new-post-form" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            <h2 class="mb-[20px] font-semibold text-center text-[24px]">Create new post</h2>

            @csrf

            @error('tags')
                <p>{{ $message }}</p>
            @enderror
            <div class="mb-[10px] border rounded-md">
                <input class="w-[600px] px-[20px] py-[15px] outline-none m-0" name="tags" type="text" placeholder="Post tag 1, post tag 2, ...">
            </div>

            @error('title')
                <p>{{ $message }}</p>
            @enderror
            <div class="mb-[10px] border rounded-md">
                <input class="w-[600px] px-[20px] py-[15px] outline-none m-0" name="title" type="text" placeholder="Post title ...">
            </div>

            @error('desc')
                <p>{{ $message }}</p>
            @enderror
            <textarea class="h-[250px] p-[20px] mb-[10px] border rounded-md outline-none w-full" name="desc" placeholder="Post text ..."></textarea>

            @error('image')
                <p>{{ $message }}</p>
            @enderror
            <div class="flex items-center mb-[20px]">
                <span class="mr-[20px]">Post image:</span>
                <input name="image" type="file">
            </div>

            <div>
                <button class="main-link w-full" type="submit">Add</button>
            </div>
        </form>
    </div>
@endsection
