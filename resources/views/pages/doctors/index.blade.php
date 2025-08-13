@extends('layouts.dashboard.main')

@section('content')
<!-- Konten -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createDoctorModal">
        Tambah Dokter
      </button>
    </div>
  </div>
  <div class="card mt-2">
    <h5 class="card-header">Daftar Dokter</h5>
    <div class="table-responsive text-nowrap p-3">
      <table class="table table" id="example">
        <thead>
          <tr class="text-nowrap table-dark">
            <th class="text-white">No</th>
            <th class="text-white">Nama</th>
            <th class="text-white">Spesialis</th>
            <th class="text-white">No HP</th>
            <th class="text-white">No STR</th>
            <th class="text-white">No SIP</th>
            <th class="text-white">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($doctors as $doctor)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded bg-label-success">{{ substr($doctor->name, 0, 1) }}</span>
                </div>
                <span class="fw-semibold">Dr. {{ $doctor->name }}</span>
              </div>
            </td>
            <td>
              <span class="badge bg-label-warning">{{ $doctor->spesialis }}</span>
            </td>
            <td>{{ $doctor->no_hp }}</td>
            <td>{{ $doctor->no_str }}</td>
            <td>{{ $doctor->no_sip }}</td>
            <td>
              <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-info btn-sm">Detail</a>
              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDoctorModal{{ $doctor->id }}">
                Edit
              </button>
              <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus dokter ini?')">Hapus</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah Dokter -->
<div class="modal fade" id="createDoctorModal" tabindex="-1" aria-labelledby="createDoctorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createDoctorModalLabel">Tambah Dokter Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('doctors.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="spesialis" class="form-label">Spesialis</label>
                <select class="form-select" id="spesialis" name="spesialis" required>
                  <option value="">Pilih Spesialis</option>
                  <option value="Dermatologi">Dermatologi</option>
                  <option value="Bedah Plastik">Bedah Plastik</option>
                  <option value="Kecantikan">Kecantikan</option>
                  <option value="Estetika">Estetika</option>
                  <option value="Kulit">Kulit</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="no_str" class="form-label">No STR</label>
                <input type="text" class="form-control" id="no_str" name="no_str" required>
              </div>
              <div class="mb-3">
                <label for="no_sip" class="form-label">No SIP</label>
                <input type="text" class="form-control" id="no_sip" name="no_sip" required>
              </div>
              <div class="mb-3">
                <label for="no_spesialis" class="form-label">No Spesialis</label>
                <input type="text" class="form-control" id="no_spesialis" name="no_spesialis" required>
              </div>
            </div>
            <div class="col-12">
              <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
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

<!-- Modal Edit Dokter -->
@foreach($doctors as $doctor)
<div class="modal fade" id="editDoctorModal{{ $doctor->id }}" tabindex="-1" aria-labelledby="editDoctorModalLabel{{ $doctor->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editDoctorModalLabel{{ $doctor->id }}">Edit Dokter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('doctors.update', $doctor->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_name{{ $doctor->id }}" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="edit_name{{ $doctor->id }}" name="name" value="{{ $doctor->name }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_spesialis{{ $doctor->id }}" class="form-label">Spesialis</label>
                <select class="form-select" id="edit_spesialis{{ $doctor->id }}" name="spesialis" required>
                  <option value="">Pilih Spesialis</option>
                  <option value="Dermatologi" {{ $doctor->spesialis == 'Dermatologi' ? 'selected' : '' }}>Dermatologi</option>
                  <option value="Bedah Plastik" {{ $doctor->spesialis == 'Bedah Plastik' ? 'selected' : '' }}>Bedah Plastik</option>
                  <option value="Kecantikan" {{ $doctor->spesialis == 'Kecantikan' ? 'selected' : '' }}>Kecantikan</option>
                  <option value="Estetika" {{ $doctor->spesialis == 'Estetika' ? 'selected' : '' }}>Estetika</option>
                  <option value="Kulit" {{ $doctor->spesialis == 'Kulit' ? 'selected' : '' }}>Kulit</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="edit_no_hp{{ $doctor->id }}" class="form-label">No HP</label>
                <input type="text" class="form-control" id="edit_no_hp{{ $doctor->id }}" name="no_hp" value="{{ $doctor->no_hp }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_no_str{{ $doctor->id }}" class="form-label">No STR</label>
                <input type="text" class="form-control" id="edit_no_str{{ $doctor->id }}" name="no_str" value="{{ $doctor->no_str }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_no_sip{{ $doctor->id }}" class="form-label">No SIP</label>
                <input type="text" class="form-control" id="edit_no_sip{{ $doctor->id }}" name="no_sip" value="{{ $doctor->no_sip }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_no_spesialis{{ $doctor->id }}" class="form-label">No Spesialis</label>
                <input type="text" class="form-control" id="edit_no_spesialis{{ $doctor->id }}" name="no_spesialis" value="{{ $doctor->no_spesialis }}" required>
              </div>
            </div>
            <div class="col-12">
              <div class="mb-3">
                <label for="edit_alamat{{ $doctor->id }}" class="form-label">Alamat</label>
                <textarea class="form-control" id="edit_alamat{{ $doctor->id }}" name="alamat" rows="3" required>{{ $doctor->alamat }}</textarea>
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
