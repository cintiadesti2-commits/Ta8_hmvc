@extends('layouts.app')
@section('title', 'Kelola User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Kelola User</h4>
        <p class="text-muted small mb-0">Kelola akun pengguna SiPustaka.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah User</a>
</div>

<form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3">
    <div class="col-md-5">
        <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..."
               value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
        <select name="role" class="form-select">
            <option value="">Semua role</option>
            @foreach (\App\Enums\UserRole::cases() as $role)
                <option value="{{ $role->value }}" @selected(request('role') === $role->value)>{{ $role->label() }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
        @if (request()->filled('search') || request()->filled('role'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Reset</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Dibuat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            {{ $user->name }}
                            @if ($user->is(auth()->user()))
                                <span class="badge text-bg-light border">Anda</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->isAdmin() ? 'text-bg-primary' : 'text-bg-secondary' }}">
                                {{ $user->role->label() }}
                            </span>
                        </td>
                        <td>{{ $user->created_at?->format('d M Y') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @unless ($user->is(auth()->user()))
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                      data-confirm="Akun &quot;{{ $user->name }}&quot; akan dihapus permanen.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-people fs-1"></i>
                                <p class="mb-0 mt-2">Tidak ada user ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $users->links('pagination::bootstrap-5') }}
</div>
@endsection