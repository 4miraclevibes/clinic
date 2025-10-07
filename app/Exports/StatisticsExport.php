<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatisticsExport implements FromArray, WithHeadings, WithStyles, WithTitle
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
        $rows[] = ['Laporan Statistik Klinik'];
        $rows[] = ['Tanggal Generate', $this->data['date']];
        $rows[] = ['Total Pasien', $this->data['totalPatients']];
        $rows[] = ['Total Dokter', $this->data['totalDoctors']];
        $rows[] = ['Total Transaksi', $this->data['totalTransactions']];
        $rows[] = ['Total Pendapatan', 'Rp ' . number_format($this->data['totalRevenue'], 0, ',', '.')];
        $rows[] = []; // Baris kosong

        // Data statistik bulanan
        $rows[] = ['Statistik Bulanan'];
        $rows[] = ['Bulan', 'Pasien Baru', 'Transaksi', 'Pendapatan'];

        foreach ($this->data['monthlyStats'] as $stat) {
            $rows[] = [
                $stat['month'],
                $stat['patients'],
                $stat['transactions'],
                'Rp ' . number_format($stat['revenue'], 0, ',', '.')
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
            8 => ['font' => ['bold' => true, 'size' => 14]],
            9 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Statistik Klinik';
    }
}
