<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Queue;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik umum
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalUsers = User::count();

        // Antrian hari ini
        $todayQueues = Queue::whereDate('created_at', Carbon::today())->count();
        $pendingQueues = Queue::where('status', 'pending')->count();
        $inProgressQueues = Queue::where('status', 'in_progress')->count();
        $completedQueues = Queue::where('status', 'completed')->count();

        // Transaksi hari ini
        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())->count();
        $totalRevenue = Transaction::whereDate('created_at', Carbon::today())
            ->with('transactionDetails')
            ->get()
            ->sum(function($transaction) {
                return $transaction->transactionDetails->sum('harga');
            });

        // Antrian terbaru
        $latestQueues = Queue::with(['patient', 'doctor'])
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Dokter dengan jadwal hari ini - PERBAIKAN
        $todaySchedules = Doctor::with(['doctorSchedules' => function($query) {
            $query->whereDate('date', Carbon::today());
        }])
        ->whereHas('doctorSchedules', function($query) {
            $query->whereDate('date', Carbon::today());
        })
        ->get();

        // Statistik per bulan (7 bulan terakhir)
        $monthlyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'patients' => Patient::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'transactions' => Transaction::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'revenue' => Transaction::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->with('transactionDetails')
                    ->get()
                    ->sum(function($transaction) {
                        return $transaction->transactionDetails->sum('harga');
                    })
            ];
        }

        // Top 5 layanan terpopuler (berdasarkan transaction details)
        $popularServices = Transaction::with('transactionDetails')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->get()
            ->flatMap(function($transaction) {
                return $transaction->transactionDetails;
            })
            ->groupBy('layanan')
            ->map(function($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(5);

        return view('pages.dashboard.index', compact(
            'totalPatients',
            'totalDoctors',
            'totalUsers',
            'todayQueues',
            'pendingQueues',
            'inProgressQueues',
            'completedQueues',
            'todayTransactions',
            'totalRevenue',
            'latestQueues',
            'todaySchedules',
            'monthlyStats',
            'popularServices'
        ));
    }
}
