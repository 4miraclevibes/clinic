@extends('layouts.dashboard.main')

@section('content')
<!-- Konten -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createRegistrationModal">
        Tambah Pendaftaran
      </button>
    </div>
  </div>
  <div class="card mt-2">
    <h5 class="card-header">Daftar Pendaftaran</h5>
    <div class="table-responsive text-nowrap p-3">
      <table class="table table" id="example">
        <thead>
          <tr class="text-nowrap table-dark">
            <th class="text-white">No</th>
            <th class="text-white">No Antrian</th>
            <th class="text-white">Pasien</th>
            <th class="text-white">Dokter</th>
            <th class="text-white">Status</th>
            <th class="text-white">Tanggal</th>
            <th class="text-white">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($queues as $queue)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
              <span class="badge bg-primary">#{{ $queue->no_antrian }}</span>
            </td>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded bg-label-primary">{{ substr($queue->patient->name, 0, 1) }}</span>
                </div>
                <div>
                  <span class="fw-semibold">{{ $queue->patient->name }}</span>
                  <br>
                  <small class="text-muted">{{ $queue->patient->no_rekam_medis }}</small>
                </div>
              </div>
            </td>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded bg-label-success">{{ substr($queue->doctor->name, 0, 1) }}</span>
                </div>
                <div>
                  <span class="fw-semibold">Dr. {{ $queue->doctor->name }}</span>
                  <br>
                  <small class="text-muted">{{ $queue->doctor->spesialis }}</small>
                </div>
              </div>
            </td>
            <td>
              @if($queue->status == 'pending')
                <span class="badge bg-warning">Menunggu</span>
              @elseif($queue->status == 'in_progress')
                <span class="badge bg-info">Sedang Berlangsung</span>
              @elseif($queue->status == 'completed')
                <span class="badge bg-success">Selesai</span>
              @elseif($queue->status == 'cancelled')
                <span class="badge bg-danger">Dibatalkan</span>
              @endif
            </td>
            <td>{{ $queue->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <a href="{{ route('registrations.show', $queue->id) }}" class="btn btn-info btn-sm">Detail</a>
              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRegistrationModal{{ $queue->id }}">
                Edit
              </button>
              <form action="{{ route('registrations.destroy', $queue->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus antrian ini?')">Hapus</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah Pendaftaran -->
<div class="modal fade" id="createRegistrationModal" tabindex="-1" aria-labelledby="createRegistrationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createRegistrationModalLabel">Tambah Pendaftaran Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('registrations.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="patient_id" class="form-label">Pasien</label>
                <select class="form-select" id="patient_id" name="patient_id" required>
                  <option value="">Pilih Pasien</option>
                  @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->name }} - {{ $patient->no_rekam_medis }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="doctor_id" class="form-label">Dokter</label>
                <select class="form-select" id="doctor_id" name="doctor_id" required>
                  <option value="">Pilih Dokter</option>
                  @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} - {{ $doctor->spesialis }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="4" placeholder="Keluhan atau keterangan tambahan..."></textarea>
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

<!-- Modal Edit Pendaftaran -->
@foreach($queues as $queue)
<div class="modal fade" id="editRegistrationModal{{ $queue->id }}" tabindex="-1" aria-labelledby="editRegistrationModalLabel{{ $queue->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRegistrationModalLabel{{ $queue->id }}">Edit Status Antrian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('registrations.update', $queue->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">No Antrian</label>
                <input type="text" class="form-control" value="#{{ $queue->no_antrian }}" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Pasien</label>
                <input type="text" class="form-control" value="{{ $queue->patient->name }}" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Dokter</label>
                <input type="text" class="form-control" value="Dr. {{ $queue->doctor->name }}" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_status{{ $queue->id }}" class="form-label">Status</label>
                <select class="form-select" id="edit_status{{ $queue->id }}" name="status" required>
                  <option value="pending" {{ $queue->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                  <option value="in_progress" {{ $queue->status == 'in_progress' ? 'selected' : '' }}>Sedang Berlangsung</option>
                  <option value="completed" {{ $queue->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                  <option value="cancelled" {{ $queue->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="edit_keterangan{{ $queue->id }}" class="form-label">Keterangan</label>
                <textarea class="form-control" id="edit_keterangan{{ $queue->id }}" name="keterangan" rows="4">{{ $queue->keterangan }}</textarea>
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
