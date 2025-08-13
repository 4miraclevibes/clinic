@extends('layouts.dashboard.main')

@section('content')
<!-- Konten -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createPatientModal">
        Tambah Pasien
      </button>
    </div>
  </div>
  <div class="card mt-2">
    <h5 class="card-header">Daftar Pasien</h5>
    <div class="table-responsive text-nowrap p-3">
      <table class="table table" id="example">
        <thead>
          <tr class="text-nowrap table-dark">
            <th class="text-white">No</th>
            <th class="text-white">No Rekam Medis</th>
            <th class="text-white">Nama</th>
            <th class="text-white">NIK</th>
            <th class="text-white">No HP</th>
            <th class="text-white">Alamat</th>
            <th class="text-white">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($patients as $patient)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
              <span class="badge bg-primary">{{ $patient->no_rekam_medis }}</span>
            </td>
            <td>{{ $patient->name }}</td>
            <td>{{ $patient->nik }}</td>
            <td>{{ $patient->no_hp }}</td>
            <td>{{ $patient->alamat }}</td>
            <td>
              <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-info btn-sm">Detail</a>
              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPatientModal{{ $patient->id }}">
                Edit
              </button>
              <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pasien ini?')">Hapus</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah Pasien -->
<div class="modal fade" id="createPatientModal" tabindex="-1" aria-labelledby="createPatientModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createPatientModalLabel">Tambah Pasien Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('patients.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="no_rekam_medis" class="form-label">No Rekam Medis</label>
                <input type="text" class="form-control" id="no_rekam_medis" name="no_rekam_medis" required>
              </div>
              <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" class="form-control" id="nik" name="nik" maxlength="16" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
              </div>
              <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="4" required></textarea>
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

<!-- Modal Edit Pasien -->
@foreach($patients as $patient)
<div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1" aria-labelledby="editPatientModalLabel{{ $patient->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPatientModalLabel{{ $patient->id }}">Edit Pasien</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('patients.update', $patient->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_name{{ $patient->id }}" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="edit_name{{ $patient->id }}" name="name" value="{{ $patient->name }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_no_rekam_medis{{ $patient->id }}" class="form-label">No Rekam Medis</label>
                <input type="text" class="form-control" id="edit_no_rekam_medis{{ $patient->id }}" name="no_rekam_medis" value="{{ $patient->no_rekam_medis }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_nik{{ $patient->id }}" class="form-label">NIK</label>
                <input type="text" class="form-control" id="edit_nik{{ $patient->id }}" name="nik" value="{{ $patient->nik }}" maxlength="16" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_no_hp{{ $patient->id }}" class="form-label">No HP</label>
                <input type="text" class="form-control" id="edit_no_hp{{ $patient->id }}" name="no_hp" value="{{ $patient->no_hp }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_alamat{{ $patient->id }}" class="form-label">Alamat</label>
                <textarea class="form-control" id="edit_alamat{{ $patient->id }}" name="alamat" rows="4" required>{{ $patient->alamat }}</textarea>
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
