@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h4 class="mb-1">Halo, {{ auth()->user()->name }}!</h4>
<p class="text-muted mb-4">Ringkasan data SiPustaka.</p>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card"><div class="card-body d-flex align-items-center gap-3">
            <span class="icon-box blue"><i class="bi bi-journal-bookmark"></i></span>
            <div>
                <div class="text-muted small">Total Buku</div>
                <div class="fs-4 fw-semibold">{{ $totalBuku }}</div>
            </div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-body d-flex align-items-center gap-3">
            <span class="icon-box green"><i class="bi bi-arrow-left-right"></i></span>
            <div>
                <div class="text-muted small">Total Peminjaman</div>
                <div class="fs-4 fw-semibold">{{ $totalPeminjaman }}</div>
            </div>
        </div></div>
    </div>
    @if ($totalUsers !== null)
        <div class="col-md-4">
            <div class="card"><div class="card-body d-flex align-items-center gap-3">
                <span class="icon-box blue"><i class="bi bi-people"></i></span>
                <div>
                    <div class="text-muted small">Total User</div>
                    <div class="fs-4 fw-semibold">{{ $totalUsers }}</div>
                </div>
            </div></div>
        </div>
    @endif
</div>
@endsection