<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Transaction;
use App\Models\Queue;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function exportPDF()
    {
        // Ambil data statistik bulanan
        $monthlyStats = $this->getMonthlyStats();

        // Ambil data tambahan untuk laporan
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('total_amount');

        // Data untuk PDF
        $data = [
            'title' => 'Laporan Statistik Klinik',
            'date' => Carbon::now()->format('d/m/Y'),
            'monthlyStats' => $monthlyStats,
            'totalPatients' => $totalPatients,
            'totalDoctors' => $totalDoctors,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
        ];

        // Generate PDF menggunakan view
        $pdf = \PDF::loadView('exports.statistics-pdf', $data);

        return $pdf->download('statistik-klinik-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        // Ambil data statistik bulanan
        $monthlyStats = $this->getMonthlyStats();

        // Ambil data tambahan
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('total_amount');

        // Data untuk Excel
        $data = [
            'title' => 'Laporan Statistik Klinik',
            'date' => Carbon::now()->format('d/m/Y'),
            'monthlyStats' => $monthlyStats,
            'totalPatients' => $totalPatients,
            'totalDoctors' => $totalDoctors,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
        ];

        // Generate Excel menggunakan view
        return \Excel::download(new \App\Exports\StatisticsExport($data), 'statistik-klinik-' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }

    private function getMonthlyStats()
    {
        $stats = [];

        // Ambil data 12 bulan terakhir
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $patients = Patient::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $transactions = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $revenue = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_amount');

            $stats[] = [
                'month' => $date->format('M Y'),
                'patients' => $patients,
                'transactions' => $transactions,
                'revenue' => $revenue,
            ];
        }

        return $stats;
    }

    public function exportTransactionsPDF()
    {
        // Ambil data transaksi dengan relasi
        $transactions = Transaction::with(['queue.patient', 'queue.doctor', 'transactionDetails'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Data untuk PDF
        $data = [
            'title' => 'Laporan Transaksi Klinik',
            'date' => Carbon::now()->format('d/m/Y'),
            'transactions' => $transactions,
            'totalTransactions' => $transactions->count(),
            'totalRevenue' => $transactions->sum('total_amount'),
            'pendingTransactions' => $transactions->where('status', 'pending')->count(),
            'paidTransactions' => $transactions->where('status', 'paid')->count(),
            'cancelledTransactions' => $transactions->where('status', 'cancelled')->count(),
        ];

        // Generate PDF menggunakan view
        $pdf = \PDF::loadView('exports.transactions-pdf', $data);

        return $pdf->download('laporan-transaksi-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function exportTransactionsExcel()
    {
        // Ambil data transaksi dengan relasi
        $transactions = Transaction::with(['queue.patient', 'queue.doctor', 'transactionDetails'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Data untuk Excel
        $data = [
            'title' => 'Laporan Transaksi Klinik',
            'date' => Carbon::now()->format('d/m/Y'),
            'transactions' => $transactions,
            'totalTransactions' => $transactions->count(),
            'totalRevenue' => $transactions->sum('total_amount'),
            'pendingTransactions' => $transactions->where('status', 'pending')->count(),
            'paidTransactions' => $transactions->where('status', 'paid')->count(),
            'cancelledTransactions' => $transactions->where('status', 'cancelled')->count(),
        ];

        // Generate Excel menggunakan view
        return \Excel::download(new \App\Exports\TransactionsExport($data), 'laporan-transaksi-' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }
}
