@extends('layouts.dashboard.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-lg-8 mb-4 order-0">
      <div class="card">
        <div class="d-flex align-items-end row">
          <div class="col-sm-7">
            <div class="card-body">
              <h5 class="card-title text-primary">Selamat Datang! ��</h5>
              <p class="mb-4">Dashboard Klinik Kecantikan</p>
              <a href="javascript:;" class="btn btn-sm btn-outline-primary">Lihat Laporan</a>
            </div>
          </div>
          <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <i class="bx bx-heart bx-lg text-primary"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 order-1">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-dollar-circle bx-sm text-success"></i>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Pendapatan Hari Ini</span>
              <h3 class="card-title mb-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
              <small class="text-success fw-semibold">
                <i class='bx bx-up-arrow-alt'></i> +72.80%
              </small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-wallet bx-sm text-info"></i>
                </div>
              </div>
              <span>Transaksi Hari Ini</span>
              <h3 class="card-title text-nowrap mb-1">{{ $todayTransactions }}</h3>
              <small class="text-success fw-semibold">
                <i class='bx bx-up-arrow-alt'></i> +28.14%
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-4 col-md-4 order-1">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-user bx-sm text-primary"></i>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Total Pasien</span>
              <h3 class="card-title mb-2">{{ $totalPatients }}</h3>
              <small class="text-success fw-semibold">
                <i class='bx bx-up-arrow-alt'></i> +28.14%
              </small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-plus-medical bx-sm text-warning"></i>
                </div>
              </div>
              <span>Total Dokter</span>
              <h3 class="card-title text-nowrap mb-1">{{ $totalDoctors }}</h3>
              <small class="text-success fw-semibold">
                <i class='bx bx-up-arrow-alt'></i> +18.2%
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 order-1">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-list-ul bx-sm text-success"></i>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Antrian Hari Ini</span>
              <h3 class="card-title mb-2">{{ $todayQueues }}</h3>
              <small class="text-success fw-semibold">
                <i class='bx bx-up-arrow-alt'></i> +12.5%
              </small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-group bx-sm text-info"></i>
                </div>
              </div>
              <span>Total User</span>
              <h3 class="card-title text-nowrap mb-1">{{ $totalUsers }}</h3>
              <small class="text-success fw-semibold">
                <i class='bx bx-up-arrow-alt'></i> +8.1%
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 order-1">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-time bx-sm text-danger"></i>
                </div>
              </div>
              <span class="fw-semibold d-block mb-1">Antrian Pending</span>
              <h3 class="card-title mb-2">{{ $pendingQueues }}</h3>
              <small class="text-warning fw-semibold">
                <i class='bx bx-time'></i> Menunggu
              </small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-check-circle bx-sm text-primary"></i>
                </div>
              </div>
              <span>Antrian Selesai</span>
              <h3 class="card-title text-nowrap mb-1">{{ $completedQueues }}</h3>
              <small class="text-success fw-semibold">
                <i class='bx bx-check'></i> Selesai
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 col-lg-4 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Antrian Terbaru</h5>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="antrianTerbaru" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="antrianTerbaru">
              <a class="dropdown-item" href="javascript:void(0);">Lihat Semua</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            @forelse($latestQueues as $queue)
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-primary">{{ substr($queue->patient->name, 0, 1) }}</span>
              </div>
              <div class="d-flex w-100 flex-wrap justify-content-between">
                <div class="me-2">
                  <h6 class="mb-0">{{ $queue->patient->name }}</h6>
                  <small class="text-muted">Dr. {{ $queue->doctor->name }}</small>
                </div>
                <div class="user-progress">
                  <small class="fw-semibold">Antrian #{{ $queue->no_antrian }}</small>
                </div>
              </div>
            </li>
            @empty
            <li class="d-flex mb-4 pb-1">
              <div class="d-flex w-100 flex-wrap justify-content-between">
                <div class="me-2">
                  <h6 class="mb-0">Tidak ada antrian hari ini</h6>
                </div>
              </div>
            </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Jadwal Dokter Hari Ini</h5>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="jadwalDokter" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="jadwalDokter">
              <a class="dropdown-item" href="javascript:void(0);">Lihat Semua</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            @forelse($todaySchedules as $doctor)
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-success">{{ substr($doctor->name, 0, 1) }}</span>
              </div>
              <div class="d-flex w-100 flex-wrap justify-content-between">
                <div class="me-2">
                  <h6 class="mb-0">Dr. {{ $doctor->name }}</h6>
                  <small class="text-muted">{{ $doctor->spesialis }}</small>
                </div>
                <div class="user-progress">
                  <small class="fw-semibold">{{ $doctor->doctorSchedules->first()->start_time ?? '-' }}</small>
                </div>
              </div>
            </li>
            @empty
            <li class="d-flex mb-4 pb-1">
              <div class="d-flex w-100 flex-wrap justify-content-between">
                <div class="me-2">
                  <h6 class="mb-0">Tidak ada jadwal hari ini</h6>
                </div>
              </div>
            </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Layanan Terpopuler</h5>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="layananPopuler" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="layananPopuler">
              <a class="dropdown-item" href="javascript:void(0);">Lihat Semua</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            @forelse($popularServices as $service => $count)
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-warning">{{ substr($service, 0, 1) }}</span>
              </div>
              <div class="d-flex w-100 flex-wrap justify-content-between">
                <div class="me-2">
                  <h6 class="mb-0">{{ $service }}</h6>
                  <small class="text-muted">{{ $count }} transaksi</small>
                </div>
                <div class="user-progress">
                  <small class="fw-semibold">{{ round(($count / max($popularServices->max(), 1)) * 100) }}%</small>
                </div>
              </div>
            </li>
            @empty
            <li class="d-flex mb-4 pb-1">
              <div class="d-flex w-100 flex-wrap justify-content-between">
                <div class="me-2">
                  <h6 class="mb-0">Belum ada data layanan</h6>
                </div>
              </div>
            </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 mb-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title m-0 me-2">Statistik Bulanan</h5>
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="statistikBulanan" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bx bx-export me-1"></i>Export
            </button>
            <div class="dropdown-menu" aria-labelledby="statistikBulanan">
              <a class="dropdown-item" href="javascript:void(0);">PDF</a>
              <a class="dropdown-item" href="javascript:void(0);">Excel</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Bulan</th>
                  <th>Pasien Baru</th>
                  <th>Transaksi</th>
                  <th>Pendapatan</th>
                </tr>
              </thead>
              <tbody>
                @foreach($monthlyStats as $stat)
                <tr>
                  <td>{{ $stat['month'] }}</td>
                  <td>{{ $stat['patients'] }}</td>
                  <td>{{ $stat['transactions'] }}</td>
                  <td>Rp {{ number_format($stat['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
