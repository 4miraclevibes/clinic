@extends('layouts.dashboard.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-lg-4 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-lg me-3">
              <span class="avatar-initial rounded bg-label-primary">#{{ $transaction->id }}</span>
            </div>
            <div>
              <h5 class="mb-0">Transaksi #{{ $transaction->id }}</h5>
              <small class="text-muted">{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
            </div>
          </div>

          <div class="mb-3">
            @if($transaction->status == 'pending')
              <span class="badge bg-warning">Menunggu</span>
            @elseif($transaction->status == 'paid')
              <span class="badge bg-success">Lunas</span>
            @elseif($transaction->status == 'cancelled')
              <span class="badge bg-danger">Dibatalkan</span>
            @endif
          </div>

          <div class="info-list">
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Pasien:</span>
              <span>{{ $transaction->queue->patient->name }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">No Antrian:</span>
              <span>#{{ $transaction->queue->no_antrian }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Dokter:</span>
              <span>Dr. {{ $transaction->queue->doctor->name }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Total:</span>
              <span class="fw-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Kasir:</span>
              <span>{{ $transaction->user->name }}</span>
            </div>
            @if($transaction->keterangan)
            <div class="d-flex justify-content-between mb-2">
              <span class="fw-semibold">Keterangan:</span>
              <span class="text-end">{{ $transaction->keterangan }}</span>
            </div>
            @endif
          </div>

          <div class="mt-3">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm">
              <i class="bx bx-arrow-back me-1"></i>Kembali
            </a>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTransactionModal{{ $transaction->id }}">
              <i class="bx bx-edit me-1"></i>Edit
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Detail Layanan</h5>
        </div>
        <div class="card-body">
          @if($transaction->transactionDetails->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Layanan</th>
                    <th>Harga</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($transaction->transactionDetails as $detail)
                  <tr>
                    <td>{{ $detail->layanan }}</td>
                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td>{{ $detail->keterangan ?? '-' }}</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr class="table-active">
                    <th>Total</th>
                    <th colspan="2">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          @else
            <div class="text-center py-4">
              <i class="bx bx-receipt bx-lg text-muted mb-2"></i>
              <p class="text-muted">Belum ada detail layanan</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Transaction -->
<div class="modal fade" id="editTransactionModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="editTransactionModalLabel{{ $transaction->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTransactionModalLabel{{ $transaction->id }}">
          <i class="bx bx-edit me-2"></i>Edit Status Transaksi
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">No Transaksi</label>
                <input type="text" class="form-control" value="#{{ $transaction->id }}" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Pasien</label>
                <input type="text" class="form-control" value="{{ $transaction->queue->patient->name }}" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Total</label>
                <input type="text" class="form-control" value="Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_status{{ $transaction->id }}" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="edit_status{{ $transaction->id }}" name="status" required>
                  <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                  <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }}>Lunas</option>
                  <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="edit_keterangan{{ $transaction->id }}" class="form-label">Keterangan</label>
                <textarea class="form-control" id="edit_keterangan{{ $transaction->id }}" name="keterangan" rows="3">{{ $transaction->keterangan }}</textarea>
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
