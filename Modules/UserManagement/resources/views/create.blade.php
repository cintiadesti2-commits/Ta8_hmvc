@extends('layouts.app')
@section('title', 'Tambah Buku')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Tambah Buku</h5>
                <form action="{{ route('buku.store') }}" method="POST" novalidate>
                    @csrf
                    @include('buku::partials.form')
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection