@extends('layouts.dashboard.main')

@section('content')
<!-- Konten -->
<div class="container-xxl flex-grow-1 container-p-y">
  @if($isAdmin)
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createProductModal">
        Tambah Produk
      </button>
    </div>
  </div>
  @endif

  <div class="card mt-2">
    <h5 class="card-header">Daftar Produk Kecantikan</h5>
    <div class="table-responsive text-nowrap p-3">
      <table class="table table" id="example">
        <thead>
          <tr class="text-nowrap table-dark">
            <th class="text-white">No</th>
            <th class="text-white">Gambar</th>
            <th class="text-white">Nama Produk</th>
            <th class="text-white">Harga</th>
            <th class="text-white">Keterangan</th>
            <th class="text-white">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($products as $product)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
              @if($product->gambar)
                <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" width="50" height="50" class="rounded">
              @else
                <div class="avatar avatar-sm">
                  <span class="avatar-initial rounded bg-label-primary">{{ substr($product->nama, 0, 1) }}</span>
                </div>
              @endif
            </td>
            <td>
              <span class="fw-semibold">{{ $product->nama }}</span>
            </td>
            <td>
              <span class="fw-semibold text-primary">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
            </td>
            <td>{{ Str::limit($product->keterangan, 50) }}</td>
            <td>
              @if(!$isAdmin)
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#buyProductModal{{ $product->id }}">
                  Beli
                </button>
              @else
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                  Edit
                </button>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                </form>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah Produk (Admin Only) -->
@if($isAdmin)
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createProductModalLabel">Tambah Produk Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="nama" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
              </div>
              <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" value="{{ old('harga') }}" min="0" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Produk</label>
                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
              </div>
            </div>
            <div class="col-12">
              <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
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

<!-- Modal Edit Produk (Admin Only) -->
@foreach($products as $product)
<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_nama{{ $product->id }}" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="edit_nama{{ $product->id }}" name="nama" value="{{ $product->nama }}" required>
              </div>
              <div class="mb-3">
                <label for="edit_harga{{ $product->id }}" class="form-label">Harga</label>
                <input type="number" class="form-control" id="edit_harga{{ $product->id }}" name="harga" value="{{ $product->harga }}" min="0" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit_gambar{{ $product->id }}" class="form-label">Gambar Produk</label>
                <input type="file" class="form-control" id="edit_gambar{{ $product->id }}" name="gambar" accept="image/*">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
              </div>
              @if($product->gambar)
                <div class="mb-3">
                  <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" width="100" class="rounded">
                </div>
              @endif
            </div>
            <div class="col-12">
              <div class="mb-3">
                <label for="edit_keterangan{{ $product->id }}" class="form-label">Keterangan</label>
                <textarea class="form-control" id="edit_keterangan{{ $product->id }}" name="keterangan" rows="3">{{ $product->keterangan }}</textarea>
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
@endif

<!-- Modal Beli Produk (User) -->
@if(!$isAdmin)
@foreach($products as $product)
<div class="modal fade" id="buyProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="buyProductModalLabel{{ $product->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="buyProductModalLabel{{ $product->id }}">Beli Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('products.buy') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <div class="modal-body">
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="text-center mb-3">
            @if($product->gambar)
              <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="img-fluid rounded" style="max-height: 200px;">
            @else
              <div class="avatar avatar-xl mx-auto">
                <span class="avatar-initial rounded bg-label-primary" style="font-size: 3rem;">{{ substr($product->nama, 0, 1) }}</span>
              </div>
            @endif
          </div>

          <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" class="form-control" value="{{ $product->nama }}" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="text" class="form-control" value="Rp {{ number_format($product->harga, 0, ',', '.') }}" readonly>
          </div>
          <div class="mb-3">
            <label for="quantity{{ $product->id }}" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="quantity{{ $product->id }}" name="quantity" value="1" min="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Total Harga</label>
            <input type="text" class="form-control fw-bold text-primary" id="total{{ $product->id }}" value="Rp {{ number_format($product->harga, 0, ',', '.') }}" readonly>
          </div>
          @if($product->keterangan)
          <div class="mb-3">
            <label class="form-label">Keterangan Produk</label>
            <p class="text-muted">{{ $product->keterangan }}</p>
          </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Beli Sekarang</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endif

<!-- / Konten -->
@endsection

@push('scripts')
<script>
// Auto-show modal if there are errors
@if($errors->any())
  $(document).ready(function() {
    @if($isAdmin)
      $('#createProductModal').modal('show');
    @else
      @foreach($products as $product)
        @if(old('product_id') == $product->id)
          $('#buyProductModal{{ $product->id }}').modal('show');
        @endif
      @endforeach
    @endif
  });
@endif

// Hitung total harga saat quantity berubah (untuk user)
@if(!$isAdmin)
  @foreach($products as $product)
    $('#quantity{{ $product->id }}').on('input', function() {
      var quantity = $(this).val();
      var price = {{ $product->harga }};
      var total = quantity * price;
      $('#total{{ $product->id }}').val('Rp ' + total.toLocaleString('id-ID'));
    });
  @endforeach
@endif
</script>
@endpush
