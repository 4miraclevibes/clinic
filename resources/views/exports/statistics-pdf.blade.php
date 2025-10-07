<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .summary-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .summary-item h3 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 14px;
        }
        .summary-item p {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
        }
        .table-container {
            margin-top: 30px;
        }
        .table-container h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dibuat pada: {{ $date }}</p>
    </div>

    <div class="summary">
        <h2>Ringkasan Data</h2>
        <div class="summary-grid">
            <div class="summary-item">
                <h3>Total Pasien</h3>
                <p>{{ number_format($totalPatients) }}</p>
            </div>
            <div class="summary-item">
                <h3>Total Dokter</h3>
                <p>{{ number_format($totalDoctors) }}</p>
            </div>
            <div class="summary-item">
                <h3>Total Transaksi</h3>
                <p>{{ number_format($totalTransactions) }}</p>
            </div>
            <div class="summary-item">
                <h3>Total Pendapatan</h3>
                <p>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="table-container">
        <h2>Statistik Bulanan</h2>
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Pasien Baru</th>
                    <th>Transaksi</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyStats as $stat)
                <tr>
                    <td>{{ $stat['month'] }}</td>
                    <td>{{ number_format($stat['patients']) }}</td>
                    <td>{{ number_format($stat['transactions']) }}</td>
                    <td>Rp {{ number_format($stat['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Klinik Kecantikan</p>
    </div>
</body>
</html>
