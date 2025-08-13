@extends('layouts.dashboard.main')

@section('content')
<!-- Konten -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTransactionModal">
        Tambah Transaksi
      </button>
    </div>
  </div>
  <div class="card mt-2">
    <h5 class="card-header">Daftar Transaksi</h5>
    <div class="table-responsive text-nowrap p-3">
      <table class="table table" id="example">
        <thead>
          <tr class="text-nowrap table-dark">
            <th class="text-white">No</th>
            <th class="text-white">No Transaksi</th>
            <th class="text-white">Pasien</th>
            <th class="text-white">Dokter</th>
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
                  <span class="avatar-initial rounded bg-label-primary">{{ substr($transaction->queue->patient->name, 0, 1) }}</span>
                </div>
                <div>
                  <span class="fw-semibold">{{ $transaction->queue->patient->name }}</span>
                  <br>
                  <small class="text-muted">Antrian #{{ $transaction->queue->no_antrian }}</small>
                </div>
              </div>
            </td>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded bg-label-success">{{ substr($transaction->queue->doctor->name, 0, 1) }}</span>
                </div>
                <div>
                  <span class="fw-semibold">Dr. {{ $transaction->queue->doctor->name }}</span>
                  <br>
                  <small class="text-muted">{{ $transaction->queue->doctor->spesialis }}</small>
                </div>
              </div>
            </td>
            <td>
              <span class="fw-semibold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
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
              <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-info btn-sm">Detail</a>
              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTransactionModal{{ $transaction->id }}">
                Edit
              </button>
              <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">Hapus</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah Transaksi -->
<div class="modal fade" id="createTransactionModal" tabindex="-1" aria-labelledby="createTransactionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createTransactionModalLabel">Tambah Transaksi Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="queue_id" class="form-label">Pilih Antrian</label>
                <select class="form-select" id="queue_id" name="queue_id" required>
                  <option value="">Pilih Antrian</option>
                  @foreach($queues as $queue)
                    <option value="{{ $queue->id }}">#{{ $queue->no_antrian }} - {{ $queue->patient->name }} (Dr. {{ $queue->doctor->name }})</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                  <option value="pending">Menunggu</option>
                  <option value="paid">Lunas</option>
                  <option value="cancelled">Dibatalkan</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="keterangan_transaction" class="form-label">Keterangan Transaksi</label>
                <textarea class="form-control" id="keterangan_transaction" name="keterangan_transaction" rows="3"></textarea>
              </div>
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-12">
              <h6>Detail Layanan</h6>
              <div id="layanan-container">
                <div class="row layanan-item mb-3">
                  <div class="col-md-4">
                    <label class="form-label">Layanan</label>
                    <input type="text" class="form-control" name="layanan[]" required>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Harga</label>
                    <input type="number" class="form-control" name="harga[]" min="0" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control" name="keterangan[]">
                  </div>
                  <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-layanan" style="display:none; margin-bottom: 8px;">
                      <i class="bx bx-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
              <button type="button" class="btn btn-secondary btn-sm" id="add-layanan-btn">
                <i class="bx bx-plus"></i> Tambah Layanan
              </button>
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

<!-- Modal Edit Transaksi -->
@foreach($transactions as $transaction)
<div class="modal fade" id="editTransactionModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="editTransactionModalLabel{{ $transaction->id }}" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTransactionModalLabel{{ $transaction->id }}">Edit Transaksi</h5>
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
                <label for="edit_status{{ $transaction->id }}" class="form-label">Status</label>
                <select class="form-select" id="edit_status{{ $transaction->id }}" name="status" required>
                  <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                  <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }}>Lunas</option>
                  <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Total</label>
                <input type="text" class="form-control" value="Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}" readonly>
              </div>
              <div class="mb-3">
                <label for="edit_keterangan{{ $transaction->id }}" class="form-label">Keterangan</label>
                <textarea class="form-control" id="edit_keterangan{{ $transaction->id }}" name="keterangan" rows="3">{{ $transaction->keterangan }}</textarea>
              </div>
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-12">
              <h6>Detail Layanan</h6>
              <div id="edit-layanan-container{{ $transaction->id }}">
                @foreach($transaction->transactionDetails as $detail)
                <div class="row edit-layanan-item mb-3">
                  <div class="col-md-4">
                    <label class="form-label">Layanan</label>
                    <input type="text" class="form-control" name="edit_layanan[]" value="{{ $detail->layanan }}" required>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Harga</label>
                    <input type="number" class="form-control" name="edit_harga[]" value="{{ $detail->harga }}" min="0" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control" name="edit_keterangan[]" value="{{ $detail->keterangan }}">
                  </div>
                  <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-edit-layanan" style="margin-bottom: 8px;">
                      <i class="bx bx-trash"></i>
                    </button>
                  </div>
                </div>
                @endforeach
              </div>
              <button type="button" class="btn btn-secondary btn-sm add-edit-layanan-btn" data-transaction-id="{{ $transaction->id }}">
                <i class="bx bx-plus"></i> Tambah Layanan
              </button>
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

@push('scripts')
<script>
$(document).ready(function() {
    // Tambah layanan di modal tambah
    $('#add-layanan-btn').on('click', function() {
        var container = $('#layanan-container');
        var firstItem = container.find('.layanan-item:first');
        var newItem = firstItem.clone();

        // Clear input values
        newItem.find('input').val('');

        // Show remove button
        newItem.find('.remove-layanan').show();

        container.append(newItem);
    });

    // Hapus layanan di modal tambah
    $(document).on('click', '.remove-layanan', function() {
        $(this).closest('.layanan-item').remove();
    });

    // Tambah layanan di modal edit
    $(document).on('click', '.add-edit-layanan-btn', function() {
        var transactionId = $(this).data('transaction-id');
        var container = $('#edit-layanan-container' + transactionId);
        var firstItem = container.find('.edit-layanan-item:first');
        var newItem = firstItem.clone();

        // Clear input values
        newItem.find('input').val('');

        container.append(newItem);
    });

    // Hapus layanan di modal edit - PERBAIKAN
    $(document).on('click', '.remove-edit-layanan', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var container = $(this).closest('#edit-layanan-container' + $(this).closest('.modal').find('.add-edit-layanan-btn').data('transaction-id'));
        var items = container.find('.edit-layanan-item');

        // Jangan hapus jika hanya ada 1 item
        if (items.length > 1) {
            $(this).closest('.edit-layanan-item').remove();
        } else {
            alert('Minimal harus ada 1 layanan');
        }
    });
});
</script>
@endpush
