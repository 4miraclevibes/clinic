@extends('layouts.dashboard.main')

@section('content')
<!-- Konten -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createScheduleModal">
        Tambah Jadwal
      </button>
    </div>
  </div>
  <div class="card mt-2">
    <h5 class="card-header">Daftar Jadwal Dokter</h5>
    <div class="table-responsive text-nowrap p-3">
      <table class="table table" id="example">
        <thead>
          <tr class="text-nowrap table-dark">
            <th class="text-white">No</th>
            <th class="text-white">Dokter</th>
            <th class="text-white">Tanggal</th>
            <th class="text-white">Jam Mulai</th>
            <th class="text-white">Jam Selesai</th>
            <th class="text-white">Status</th>
            <th class="text-white">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($schedules as $schedule)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded bg-label-success">{{ substr($schedule->doctor->name, 0, 1) }}</span>
                </div>
                <div>
                  <span class="fw-semibold">Dr. {{ $schedule->doctor->name }}</span>
                  <br>
                  <small class="text-muted">{{ $schedule->doctor->spesialis }}</small>
                </div>
              </div>
            </td>
            <td>{{ $schedule->date->format('d/m/Y') }}</td>
            <td>{{ $schedule->start_time->format('H:i') }}</td>
            <td>{{ $schedule->end_time->format('H:i') }}</td>
            <td>
              @if($schedule->status == 'available')
                <span class="badge bg-success">Tersedia</span>
              @else
                <span class="badge bg-danger">Tidak Tersedia</span>
              @endif
            </td>
            <td>
              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editScheduleModal{{ $schedule->id }}">
                Edit
              </button>
              <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">Hapus</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="createScheduleModal" tabindex="-1" aria-labelledby="createScheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createScheduleModalLabel">Tambah Jadwal Dokter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('schedules.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="doctor_id" class="form-label">Dokter</label>
                <select class="form-select" id="doctor_id" name="doctor_id" required>
                  <option value="">Pilih Dokter</option>
                  @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} - {{ $doctor->spesialis }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="date" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="date" name="date" min="{{ date('Y-m-d') }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="start_time" class="form-label">Jam Mulai</label>
                <input type="time" class="form-control" id="start_time" name="start_time" required>
              </div>
              <div class="mb-3">
                <label for="end_time" class="form-label">Jam Selesai</label>
                <input type="time" class="form-control" id="end_time" name="end_time" required>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                  <option value="available">Tersedia</option>
                  <option value="unavailable">Tidak Tersedia</option>
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

<!-- Modal Edit Jadwal -->
@foreach($schedules as $schedule)
<div class="modal fade" id="editScheduleModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="editScheduleModalLabel{{ $schedule->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editScheduleModalLabel{{ $schedule->id }}">Edit Jadwal Dokter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('schedules.update', $schedule->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_doctor_id{{ $schedule->id }}" class="form-label">Dokter</label>
                <select class="form-select" id="edit_doctor_id{{ $schedule->id }}" name="doctor_id" required>
                  <option value="">Pilih Dokter</option>
                  @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ $schedule->doctor_id == $doctor->id ? 'selected' : '' }}>
                      Dr. {{ $doctor->name }} - {{ $doctor->spesialis }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="edit_date{{ $schedule->id }}" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="edit_date{{ $schedule->id }}" name="date" value="{{ $schedule->date->format('Y-m-d') }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_start_time{{ $schedule->id }}" class="form-label">Jam Mulai</label>
                <input type="time" class="form-control" id="edit_start_time{{ $schedule->id }}" name="start_time" value="{{ $schedule->start_time->format('H:i') }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_end_time{{ $schedule->id }}" class="form-label">Jam Selesai</label>
                <input type="time" class="form-control" id="edit_end_time{{ $schedule->id }}" name="end_time" value="{{ $schedule->end_time->format('H:i') }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_status{{ $schedule->id }}" class="form-label">Status</label>
                <select class="form-select" id="edit_status{{ $schedule->id }}" name="status" required>
                  <option value="available" {{ $schedule->status == 'available' ? 'selected' : '' }}>Tersedia</option>
                  <option value="unavailable" {{ $schedule->status == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
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
