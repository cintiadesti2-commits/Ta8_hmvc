@extends('layouts.app')
@section('title', 'Buku')

@section('content')
@php $isAdmin = auth()->user()->isAdmin(); @endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">{{ $isAdmin ? 'Data Buku' : 'Katalog Buku' }}</h4>
        <p class="text-muted small mb-0">{{ $isAdmin ? 'Kelola koleksi buku perpustakaan.' : 'Daftar koleksi buku perpustakaan.' }}</p>
    </div>
    @if ($isAdmin)
        <a href="{{ route('buku.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Buku</a>
    @endif
</div>

<form method="GET" action="{{ route('buku.index') }}" class="row g-2 mb-3">
    <div class="col-md-6">
        <input type="text" name="search" class="form-control"
               placeholder="Cari judul, penulis, atau penerbit..." value="{{ request('search') }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
        @if (request()->filled('search'))
            <a href="{{ route('buku.index') }}" class="btn btn-light">Reset</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    @if ($isAdmin)<th class="text-end">Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($bukus as $buku)
                    <tr>
                        <td>{{ $bukus->firstItem() + $loop->index }}</td>
                        <td>{{ $buku->judul }}</td>
                        <td>{{ $buku->penulis }}</td>
                        <td>{{ $buku->penerbit }}</td>
                        <td>{{ $buku->tahun_terbit }}</td>
                        <td>
                            <span class="badge {{ $buku->stok > 0 ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $buku->stok }}</span>
                        </td>
                        @if ($isAdmin)
                            <td class="text-end text-nowrap">
                                <a href="{{ route('buku.edit', $buku) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('buku.destroy', $buku) }}" method="POST" class="d-inline"
                                      data-confirm="Buku &quot;{{ $buku->judul }}&quot; akan dihapus permanen.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isAdmin ? 7 : 6 }}">
                            <div class="empty-state">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mb-0 mt-2">Belum ada data buku.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $bukus->links('pagination::bootstrap-5') }}
</div>
@endsection