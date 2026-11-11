<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body class="d-flex align-items-center min-vh-100">
<div class="container py-4" style="max-width: 440px;">
    <div class="text-center mb-4">
        <span class="auth-logo"><i class="bi bi-book"></i></span>
        <h4 class="mt-3 mb-0">SiPustaka</h4>
        <p class="text-muted small mb-0">@yield('subtitle')</p>
    </div>
    <div class="card shadow-sm">
        <div class="card-body p-4">
            @yield('content')
        </div>
    </div>
</div>

@include('layouts.partials.scripts')
</body>
</html>