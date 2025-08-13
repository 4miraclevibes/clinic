@extends('layouts.dashboard.main')

@section('content')
<!-- Konten -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
        Tambah User
      </button>
    </div>
  </div>
  <div class="card mt-2">
    <h5 class="card-header">Daftar User</h5>
    <div class="table-responsive text-nowrap p-3">
      <table class="table table" id="example">
        <thead>
          <tr class="text-nowrap table-dark">
            <th class="text-white">No</th>
            <th class="text-white">Nama</th>
            <th class="text-white">Email</th>
            <th class="text-white">No HP</th>
            <th class="text-white">Alamat</th>
            <th class="text-white">Role</th>
            <th class="text-white">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->userDetails->no_hp ?? '-' }}</td>
            <td>{{ $user->userDetails->alamat ?? '-' }}</td>
            <td>
              <span class="badge bg-{{ $user->userDetails->role == 'admin' ? 'danger' : ($user->userDetails->role == 'doctor' ? 'warning' : 'info') }}">
                {{ ucfirst($user->userDetails->role ?? 'user') }}
              </span>
            </td>
            <td>
              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                Edit
              </button>
              <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createUserModalLabel">Tambah User Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
              </div>
              <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
              </div>
              <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                  <option value="">Pilih Role</option>
                  <option value="admin">Admin</option>
                  <option value="user">User</option>
                  <option value="doctor">Doctor</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit User -->
@foreach ($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_name{{ $user->id }}" class="form-label">Nama</label>
                <input type="text" class="form-control" id="edit_name{{ $user->id }}" name="name" value="{{ $user->name }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_email{{ $user->id }}" class="form-label">Email</label>
                <input type="email" class="form-control" id="edit_email{{ $user->id }}" name="email" value="{{ $user->email }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_password{{ $user->id }}" class="form-label">Password</label>
                <input type="password" class="form-control" id="edit_password{{ $user->id }}" name="password">
                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password.</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_no_hp{{ $user->id }}" class="form-label">No HP</label>
                <input type="text" class="form-control" id="edit_no_hp{{ $user->id }}" name="no_hp" value="{{ $user->userDetails->no_hp ?? '' }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_alamat{{ $user->id }}" class="form-label">Alamat</label>
                <textarea class="form-control" id="edit_alamat{{ $user->id }}" name="alamat" rows="3" required>{{ $user->userDetails->alamat ?? '' }}</textarea>
              </div>
              <div class="mb-3">
                <label for="edit_role{{ $user->id }}" class="form-label">Role</label>
                <select class="form-select" id="edit_role{{ $user->id }}" name="role" required>
                  <option value="">Pilih Role</option>
                  <option value="admin" {{ ($user->userDetails->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                  <option value="user" {{ ($user->userDetails->role ?? '') == 'user' ? 'selected' : '' }}>User</option>
                  <option value="doctor" {{ ($user->userDetails->role ?? '') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<!-- / Konten -->
@endsection
