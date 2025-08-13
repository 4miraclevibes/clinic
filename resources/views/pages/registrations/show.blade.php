@extends('layouts.dashboard.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-lg-4 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-lg me-3">
              <span class="avatar-initial rounded bg-label-primary">#{{ $queue->no_antrian }}</span>
            </div>
            <div>
              <h5 class="mb-0">Antrian #{{ $queue->no_antrian }}</h5>
              <small class="text-muted">{{ $queue->created_at->format('d/m/Y H:i') }}</small>
            </div>
          </div>

          <div class="mb-3">
            @if($queue->status == 'pending')
              <span class="badge bg-warning">Menunggu</span>
            @elseif($queue->status == 'in_progress')
              <span class="badge bg-info">Sedang Berlangsung</span>
            @elseif($queue->status == 'completed')
              <span class="badge bg-success">Selesai</span>
            @elseif($queue->status == 'cancelled')
              <span class="badge bg-danger">Dibatalkan</span>
            @endif
          </div>

          <div class="info-list">
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Pasien:</span>
              <span>{{ $queue->patient->name }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">No Rekam Medis:</span>
              <span>{{ $queue->patient->no_rekam_medis }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Dokter:</span>
              <span>Dr. {{ $queue->doctor->name }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Spesialis:</span>
              <span>{{ $queue->doctor->spesialis }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Pendaftar:</span>
              <span>{{ $queue->user->name }}</span>
            </div>
            @if($queue->keterangan)
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Keterangan:</span>
              <span class="text-end">{{ $queue->keterangan }}</span>
            </div>
            @endif
          </div>

          <div class="mt-3">
            <a href="{{ route('registrations.index') }}" class="btn btn-secondary btn-sm">
              <i class="bx bx-arrow-back me-1"></i>Kembali
            </a>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRegistrationModal{{ $queue->id }}">
              <i class="bx bx-edit me-1"></i>Edit
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Riwayat Medis</h5>
        </div>
        <div class="card-body">
          @if($queue->medicalRecords->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Diagnosis</th>
                    <th>Pengobatan</th>
                    <th>Dokter</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($queue->medicalRecords as $record)
                  <tr>
                    <td>{{ $record->created_at->format('d/m/Y') }}</td>
                    <td>{{ $record->diagnosis ?? '-' }}</td>
                    <td>{{ $record->treatment ?? '-' }}</td>
                    <td>{{ $record->doctor->name ?? '-' }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-4">
              <i class="bx bx-file bx-lg text-muted mb-2"></i>
              <p class="text-muted">Belum ada riwayat medis</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Registration -->
<div class="modal fade" id="editRegistrationModal{{ $queue->id }}" tabindex="-1" aria-labelledby="editRegistrationModalLabel{{ $queue->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRegistrationModalLabel{{ $queue->id }}">
          <i class="bx bx-edit me-2"></i>Edit Status Antrian
        </h5>
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
                <label for="edit_status{{ $queue->id }}" class="form-label">Status <span class="text-danger">*</span></label>
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
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-save me-1"></i>Perbarui
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
