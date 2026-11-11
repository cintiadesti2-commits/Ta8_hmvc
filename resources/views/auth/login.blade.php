@extends('layouts.guest')
@section('title', 'Masuk')
@section('subtitle', 'Silakan masuk untuk melanjutkan')

@section('content')
<form action="{{ route('login.store') }}" method="POST" novalidate>
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror" autofocus>
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
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Ingat saya</label>
    </div>
    <button type="submit" class="btn btn-primary w-100">Masuk</button>
</form>
<p class="text-center text-muted small mt-3 mb-0">
    Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
</p>
@endsection