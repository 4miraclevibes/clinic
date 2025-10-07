<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];

        // Header informasi
        $rows[] = ['Laporan Transaksi Klinik'];
        $rows[] = ['Tanggal Generate', $this->data['date']];
        $rows[] = ['Total Transaksi', $this->data['totalTransactions']];
        $rows[] = ['Total Pendapatan', 'Rp ' . number_format($this->data['totalRevenue'], 0, ',', '.')];
        $rows[] = ['Transaksi Pending', $this->data['pendingTransactions']];
        $rows[] = ['Transaksi Lunas', $this->data['paidTransactions']];
        $rows[] = ['Transaksi Dibatalkan', $this->data['cancelledTransactions']];
        $rows[] = []; // Baris kosong

        // Header tabel
        $rows[] = ['Detail Transaksi'];
        $rows[] = ['No', 'ID Transaksi', 'Pasien', 'Dokter', 'Status', 'Total', 'Tanggal', 'Layanan'];

        // Data transaksi
        foreach ($this->data['transactions'] as $index => $transaction) {
            $services = [];
            foreach ($transaction->transactionDetails as $detail) {
                $services[] = $detail->layanan . ' (Rp ' . number_format($detail->harga, 0, ',', '.') . ')';
            }
            $servicesText = implode(', ', $services);

            $rows[] = [
                $index + 1,
                '#' . $transaction->id,
                $transaction->queue->patient->name ?? '-',
                'Dr. ' . ($transaction->queue->doctor->name ?? '-'),
                ucfirst($transaction->status),
                'Rp ' . number_format($transaction->total_amount, 0, ',', '.'),
                $transaction->created_at->format('d/m/Y H:i'),
                $servicesText
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            6 => ['font' => ['bold' => true]],
            7 => ['font' => ['bold' => true]],
            9 => ['font' => ['bold' => true, 'size' => 14]],
            10 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Transaksi Klinik';
    }
}
