@php $isEdit = isset($user); @endphp

<div class="mb-3">
    <label for="name" class="form-label">Nama</label>
    <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
           class="form-control @error('name') is-invalid @enderror">
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}"
           class="form-control @error('email') is-invalid @enderror">
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="role" class="form-label">Role</label>
    <select id="role" name="role" class="form-select @error('role') is-invalid @enderror">
        @foreach (\App\Enums\UserRole::cases() as $role)
            <option value="{{ $role->value }}"
                @selected(old('role', $isEdit ? $user->role->value : 'user') === $role->value)>
                {{ $role->label() }}
            </option>
        @endforeach
    </select>
    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if ($isEdit && $user->is(auth()->user()))
        <div class="form-text">Role akun Anda sendiri tidak dapat diturunkan.</div>
    @endif
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
    @if ($isEdit)
        <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
    @endif
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