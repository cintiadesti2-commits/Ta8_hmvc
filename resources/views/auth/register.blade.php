@extends('layouts.guest')
@section('title', 'Daftar')
@section('subtitle', 'Buat akun baru')

@section('content')
<form action="{{ route('register.store') }}" method="POST" novalidate>
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}"
               class="form-control @error('name') is-invalid @enderror" autofocus>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
            <input type="password" id="password" name="password"
                   class="form-control @error('password') is-invalid @enderror">
            <button class="btn btn-outline-secondary" type="button" data-toggle-password="password">
                <i class="bi bi-eye"></i>
            </button>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <div class="input-group">
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            <button class="btn btn-outline-secondary" type="button" data-toggle-password="password_confirmation">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100">Daftar</button>
</form>
<p class="text-center text-muted small mt-3 mb-0">
    Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
</p>
@endsection