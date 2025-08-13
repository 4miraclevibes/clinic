@extends('layouts.dashboard.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-lg-4 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-lg me-3">
              <span class="avatar-initial rounded bg-label-primary">{{ substr($patient->name, 0, 1) }}</span>
            </div>
            <div>
              <h5 class="mb-0">{{ $patient->name }}</h5>
              <small class="text-muted">Pasien</small>
            </div>
          </div>

          <div class="mb-3">
            <span class="badge bg-label-primary">{{ $patient->no_rekam_medis }}</span>
          </div>

          <div class="info-list">
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">NIK:</span>
              <span>{{ $patient->nik }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">No HP:</span>
              <span>{{ $patient->no_hp }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Alamat:</span>
              <span class="text-end">{{ $patient->alamat }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Tanggal Daftar:</span>
              <span>{{ $patient->created_at->format('d/m/Y H:i') }}</span>
            </div>
          </div>

          <div class="mt-3">
            <a href="{{ route('patients.index') }}" class="btn btn-secondary btn-sm">
              <i class="bx bx-arrow-back me-1"></i>Kembali
            </a>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPatientModal{{ $patient->id }}">
              <i class="bx bx-edit me-1"></i>Edit
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Riwayat Antrian</h5>
        </div>
        <div class="card-body">
          @if($patient->queues->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>No Antrian</th>
                    <th>Dokter</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($patient->queues as $queue)
                  <tr>
                    <td>
                      <span class="badge bg-label-primary">#{{ $queue->no_antrian }}</span>
                    </td>
                    <td>{{ $queue->doctor->name ?? '-' }}</td>
                    <td>
                      @if($queue->status == 'pending')
                        <span class="badge bg-label-warning">Menunggu</span>
                      @elseif($queue->status == 'in_progress')
                        <span class="badge bg-label-info">Sedang Berlangsung</span>
                      @elseif($queue->status == 'completed')
                        <span class="badge bg-label-success">Selesai</span>
                      @else
                        <span class="badge bg-label-secondary">{{ $queue->status }}</span>
                      @endif
                    </td>
                    <td>{{ $queue->created_at->format('d/m/Y H:i') }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-4">
              <i class="bx bx-list-ul bx-lg text-muted mb-2"></i>
              <p class="text-muted">Belum ada riwayat antrian</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Riwayat Medis</h5>
        </div>
        <div class="card-body">
          @php
            $medicalRecords = $patient->queues->flatMap(function($queue) {
                return $queue->medicalRecords;
            });
          @endphp

          @if($medicalRecords->count() > 0)
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
                  @foreach($medicalRecords as $record)
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

<!-- Modal Edit Pasien -->
<div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1" aria-labelledby="editPatientModalLabel{{ $patient->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPatientModalLabel{{ $patient->id }}">
          <i class="bx bx-edit me-2"></i>Edit Data Pasien
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('patients.update', $patient->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_name{{ $patient->id }}" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_name{{ $patient->id }}" name="name" value="{{ $patient->name }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_no_rekam_medis{{ $patient->id }}" class="form-label">No Rekam Medis <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_no_rekam_medis{{ $patient->id }}" name="no_rekam_medis" value="{{ $patient->no_rekam_medis }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_nik{{ $patient->id }}" class="form-label">NIK <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_nik{{ $patient->id }}" name="nik" value="{{ $patient->nik }}" maxlength="16" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_no_hp{{ $patient->id }}" class="form-label">No HP <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_no_hp{{ $patient->id }}" name="no_hp" value="{{ $patient->no_hp }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_alamat{{ $patient->id }}" class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea class="form-control" id="edit_alamat{{ $patient->id }}" name="alamat" rows="4" required>{{ $patient->alamat }}</textarea>
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
