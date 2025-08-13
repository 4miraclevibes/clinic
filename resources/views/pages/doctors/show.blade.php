@extends('layouts.dashboard.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-lg-4 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-lg me-3">
              <span class="avatar-initial rounded bg-label-success">{{ substr($doctor->name, 0, 1) }}</span>
            </div>
            <div>
              <h5 class="mb-0">Dr. {{ $doctor->name }}</h5>
              <small class="text-muted">{{ $doctor->spesialis }}</small>
            </div>
          </div>

          <div class="mb-3">
            <span class="badge bg-label-warning">{{ $doctor->spesialis }}</span>
          </div>

          <div class="info-list">
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">No HP:</span>
              <span>{{ $doctor->no_hp }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">No STR:</span>
              <span>{{ $doctor->no_str }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">No SIP:</span>
              <span>{{ $doctor->no_sip }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">No Spesialis:</span>
              <span>{{ $doctor->no_spesialis }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Alamat:</span>
              <span class="text-end">{{ $doctor->alamat }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Tanggal Bergabung:</span>
              <span>{{ $doctor->created_at->format('d/m/Y H:i') }}</span>
            </div>
          </div>

          <div class="mt-3">
            <a href="{{ route('doctors.index') }}" class="btn btn-secondary btn-sm">
              <i class="bx bx-arrow-back me-1"></i>Kembali
            </a>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDoctorModal{{ $doctor->id }}">
              <i class="bx bx-edit me-1"></i>Edit
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Jadwal Praktik</h5>
        </div>
        <div class="card-body">
          @if($doctor->doctorSchedules->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($doctor->doctorSchedules as $schedule)
                  <tr>
                    <td>{{ $schedule->hari }}</td>
                    <td>{{ $schedule->jam_mulai }}</td>
                    <td>{{ $schedule->jam_selesai }}</td>
                    <td>
                      @if($schedule->status == 'active')
                        <span class="badge bg-label-success">Aktif</span>
                      @else
                        <span class="badge bg-label-secondary">Tidak Aktif</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-4">
              <i class="bx bx-calendar bx-lg text-muted mb-2"></i>
              <p class="text-muted">Belum ada jadwal praktik</p>
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
          <h5 class="card-title mb-0">Riwayat Antrian</h5>
        </div>
        <div class="card-body">
          @if($doctor->queues->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>No Antrian</th>
                    <th>Pasien</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($doctor->queues->take(10) as $queue)
                  <tr>
                    <td>
                      <span class="badge bg-label-primary">#{{ $queue->no_antrian }}</span>
                    </td>
                    <td>{{ $queue->patient->name ?? '-' }}</td>
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
</div>

<!-- Modal Edit Dokter -->
<div class="modal fade" id="editDoctorModal{{ $doctor->id }}" tabindex="-1" aria-labelledby="editDoctorModalLabel{{ $doctor->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editDoctorModalLabel{{ $doctor->id }}">
          <i class="bx bx-edit me-2"></i>Edit Data Dokter
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('doctors.update', $doctor->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_name{{ $doctor->id }}" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_name{{ $doctor->id }}" name="name" value="{{ $doctor->name }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_spesialis{{ $doctor->id }}" class="form-label">Spesialis <span class="text-danger">*</span></label>
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
                <label for="edit_no_hp{{ $doctor->id }}" class="form-label">No HP <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_no_hp{{ $doctor->id }}" name="no_hp" value="{{ $doctor->no_hp }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_no_str{{ $doctor->id }}" class="form-label">No STR <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_no_str{{ $doctor->id }}" name="no_str" value="{{ $doctor->no_str }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_no_sip{{ $doctor->id }}" class="form-label">No SIP <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_no_sip{{ $doctor->id }}" name="no_sip" value="{{ $doctor->no_sip }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_no_spesialis{{ $doctor->id }}" class="form-label">No Spesialis <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_no_spesialis{{ $doctor->id }}" name="no_spesialis" value="{{ $doctor->no_spesialis }}" required>
              </div>
            </div>
            <div class="col-12">
              <div class="mb-3">
                <label for="edit_alamat{{ $doctor->id }}" class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea class="form-control" id="edit_alamat{{ $doctor->id }}" name="alamat" rows="3" required>{{ $doctor->alamat }}</textarea>
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
