<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        $isAdmin = Auth::user()->userDetails->role === 'admin';

        return view('pages.products.index', compact('products', 'isAdmin'));
    }

    public function store(Request $request)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->userDetails->role !== 'admin') {
            return redirect()->back()->withErrors(['general' => 'Hanya admin yang dapat menambah produk.']);
        }

        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0',
                'keterangan' => 'nullable|string|max:1000',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'nama.required' => 'Nama produk harus diisi.',
                'harga.required' => 'Harga produk harus diisi.',
                'harga.numeric' => 'Harga harus berupa angka.',
                'harga.min' => 'Harga tidak boleh negatif.',
                'gambar.image' => 'File harus berupa gambar.',
                'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            ]);

            $data = $request->except('gambar');

            if ($request->hasFile('gambar')) {
                $data['gambar'] = $request->file('gambar')->store('products', 'public');
            }

            Product::create($data);

            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Terjadi kesalahan saat menambah produk: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->userDetails->role !== 'admin') {
            return redirect()->back()->withErrors(['general' => 'Hanya admin yang dapat mengedit produk.']);
        }

        try {
            $product = Product::findOrFail($id);

            $request->validate([
                'nama' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0',
                'keterangan' => 'nullable|string|max:1000',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->except('gambar');

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($product->gambar) {
                    Storage::disk('public')->delete($product->gambar);
                }
                $data['gambar'] = $request->file('gambar')->store('products', 'public');
            }

            $product->update($data);

            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Terjadi kesalahan saat memperbarui produk: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->userDetails->role !== 'admin') {
            return redirect()->back()->withErrors(['general' => 'Hanya admin yang dapat menghapus produk.']);
        }

        try {
            $product = Product::findOrFail($id);

            // Hapus gambar jika ada
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }

            $product->delete();

            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => 'Terjadi kesalahan saat menghapus produk: ' . $e->getMessage()]);
        }
    }

    public function buy(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ], [
                'product_id.required' => 'Produk harus dipilih.',
                'product_id.exists' => 'Produk tidak ditemukan.',
                'quantity.required' => 'Jumlah harus diisi.',
                'quantity.integer' => 'Jumlah harus berupa angka.',
                'quantity.min' => 'Jumlah minimal 1.',
            ]);

            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;
            $total = $product->harga * $quantity;

            // Buat transaksi baru (tanpa queue karena ini pembelian produk langsung)
            $transaction = Transaction::create([
                'queue_id' => null, // Null karena bukan dari antrian
                'user_id' => Auth::user()->id,
                'status' => 'paid', // Langsung lunas
                'keterangan' => 'Pembelian Produk',
                'total_amount' => $total,
            ]);

            // Buat detail transaksi
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'layanan' => $product->nama,
                'harga' => $total,
                'keterangan' => 'Jumlah: ' . $quantity . ' item',
                'produk' => $product->nama,
            ]);

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Pembelian berhasil! ' . $quantity . ' ' . $product->nama . ' - Total: Rp ' . number_format($total, 0, ',', '.') . '. Transaksi #' . $transaction->id);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Terjadi kesalahan saat membeli produk: ' . $e->getMessage()]);
        }
    }
}
