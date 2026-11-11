@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Edit User</h5>
                <form action="{{ route('admin.users.update', $user) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    @include('usermanagement::partials.form')
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection