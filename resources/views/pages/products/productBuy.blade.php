@extends('layouts.dashboard.main')

@section('content')
<!-- Konten -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <h5 class="card-header">Riwayat Pembelian Produk</h5>
    <div class="table-responsive text-nowrap p-3">
      <table class="table table" id="example">
        <thead>
          <tr class="text-nowrap table-dark">
            <th class="text-white">No</th>
            <th class="text-white">No Transaksi</th>
            <th class="text-white">Pembeli</th>
            <th class="text-white">Produk</th>
            <th class="text-white">Jumlah</th>
            <th class="text-white">Total</th>
            <th class="text-white">Status</th>
            <th class="text-white">Tanggal</th>
            <th class="text-white">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($transactions as $transaction)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
              <span class="badge bg-primary">#{{ $transaction->id }}</span>
            </td>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded bg-label-primary">{{ substr($transaction->user->name, 0, 1) }}</span>
                </div>
                <span class="fw-semibold">{{ $transaction->user->name }}</span>
              </div>
            </td>
            <td>
              @foreach($transaction->transactionDetails as $detail)
                <div class="mb-1">
                  <span class="fw-semibold">{{ $detail->produk }}</span>
                </div>
              @endforeach
            </td>
            <td>
              @foreach($transaction->transactionDetails as $detail)
                <div class="mb-1">
                  <span>{{ $detail->keterangan }}</span>
                </div>
              @endforeach
            </td>
            <td>
              <span class="fw-semibold text-primary">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </td>
            <td>
              @if($transaction->status == 'pending')
                <span class="badge bg-warning">Menunggu</span>
              @elseif($transaction->status == 'paid')
                <span class="badge bg-success">Lunas</span>
              @elseif($transaction->status == 'cancelled')
                <span class="badge bg-danger">Dibatalkan</span>
              @endif
            </td>
            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailTransactionModal{{ $transaction->id }}">
                Detail
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Detail Transaksi Produk -->
@foreach($transactions as $transaction)
<div class="modal fade" id="detailTransactionModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="detailTransactionModalLabel{{ $transaction->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailTransactionModalLabel{{ $transaction->id }}">Detail Transaksi #{{ $transaction->id }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label fw-bold">No Transaksi</label>
              <p>#{{ $transaction->id }}</p>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Pembeli</label>
              <p>{{ $transaction->user->name }}</p>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Tanggal</label>
              <p>{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label fw-bold">Status</label>
              <p>
                @if($transaction->status == 'pending')
                  <span class="badge bg-warning">Menunggu</span>
                @elseif($transaction->status == 'paid')
                  <span class="badge bg-success">Lunas</span>
                @elseif($transaction->status == 'cancelled')
                  <span class="badge bg-danger">Dibatalkan</span>
                @endif
              </p>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Keterangan</label>
              <p>{{ $transaction->keterangan ?? '-' }}</p>
            </div>
          </div>
        </div>

        <hr>

        <div class="row">
          <div class="col-12">
            <h6 class="mb-3">Detail Produk</h6>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($transaction->transactionDetails as $detail)
                  <tr>
                    <td>{{ $detail->produk }}</td>
                    <td>{{ $detail->keterangan }}</td>
                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr class="table-active">
                    <th colspan="2">Total</th>
                    <th>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endforeach

<!-- / Konten -->
@endsection
