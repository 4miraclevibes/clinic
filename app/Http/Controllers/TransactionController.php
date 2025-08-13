<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['queue.patient', 'queue.doctor', 'user', 'transactionDetails'])
            ->orderBy('created_at', 'desc')
            ->get();

        $queues = Queue::where('status', 'completed')
            ->whereDoesntHave('transaction')
            ->with(['patient', 'doctor'])
            ->get();

        return view('pages.transactions.index', compact('transactions', 'queues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'queue_id' => 'required|exists:queues,id',
            'layanan' => 'required|array',
            'layanan.*' => 'required|string',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        // Cek apakah queue sudah ada transaksi
        $existingTransaction = Transaction::where('queue_id', $request->queue_id)->first();
        if ($existingTransaction) {
            return redirect()->back()->withErrors(['Transaksi untuk antrian ini sudah ada']);
        }

        // Hitung total
        $totalAmount = 0;
        foreach ($request->harga as $harga) {
            $totalAmount += $harga;
        }

        // Buat transaksi
        $transaction = Transaction::create([
            'queue_id' => $request->queue_id,
            'user_id' => Auth::user()->id,
            'status' => $request->status,
            'keterangan' => $request->keterangan_transaction ?? null,
            'total_amount' => $totalAmount,
        ]);

        // Buat detail transaksi
        foreach ($request->layanan as $index => $layanan) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'layanan' => $layanan,
                'harga' => $request->harga[$index],
                'keterangan' => $request->keterangan[$index] ?? null,
            ]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
            'keterangan' => 'nullable|string',
            'edit_layanan' => 'required|array',
            'edit_layanan.*' => 'required|string',
            'edit_harga' => 'required|array',
            'edit_harga.*' => 'required|numeric|min:0',
            'edit_keterangan' => 'nullable|array',
            'edit_keterangan.*' => 'nullable|string',
        ]);

        // Update transaksi
        $transaction->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        // Hapus detail lama
        $transaction->transactionDetails()->delete();

        // Hitung total baru
        $totalAmount = 0;
        foreach ($request->edit_harga as $harga) {
            $totalAmount += $harga;
        }

        // Update total amount
        $transaction->update(['total_amount' => $totalAmount]);

        // Buat detail baru
        foreach ($request->edit_layanan as $index => $layanan) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'layanan' => $layanan,
                'harga' => $request->edit_harga[$index],
                'keterangan' => $request->edit_keterangan[$index] ?? null,
            ]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        // Hapus detail transaksi terlebih dahulu
        $transaction->transactionDetails()->delete();
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus');
    }

    public function show($id)
    {
        $transaction = Transaction::with(['queue.patient', 'queue.doctor', 'user', 'transactionDetails'])->findOrFail($id);
        return view('pages.transactions.show', compact('transaction'));
    }
}
