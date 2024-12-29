@extends('layout.layout')

@section('title', 'Registration')

@section('content')
    <div class="w-full flex justify-center">
        <form action="{{ route('reg.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        <h2 class="mb-[20px] font-semibold text-center text-[24px]">Registration</h2>
            @csrf

            @error('email')
                <p>{{ $message }}</p>
            @enderror
            <div class="mb-[10px] border rounded-md">
                <input class="px-[20px] py-[10px] outline-none w-full" name="email" type="email" placeholder="Email" value="{{ old('email') }}">
            </div>

            @error('login')
                <p>{{ $message }}</p>
            @enderror
            <div class="mb-[10px] border rounded-md">
                <input class="px-[20px] py-[10px] outline-none w-full" name="login" type="login" placeholder="Login" value="{{ old('login') }}">
            </div>

            @error('password')
                <p>{{ $message }}</p>
            @enderror
            <div class="mb-[10px] border rounded-md">
                <input class="px-[20px] py-[10px] outline-none w-full" name="password" type="password" placeholder="Password">
            </div>
            <div class="mb-[20px] border rounded-md">
                <input class="px-[20px] py-[10px] outline-none w-full" name="password_confirmation" type="password" placeholder="Confirm password">
            </div>

            @error('image')
                <p>{{ $message }}</p>
            @enderror
            <div class="flex">
                <span class="mr-[20px]">Avatar:</span>
                <input class="mb-[20px]" name="user_image" type="file">
            </div>
            <div><button class="w-full main-link" type="submit">Registration</button></div>
        </form>
    </div>
@endsection
