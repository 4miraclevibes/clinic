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
            grid-template-columns: 1fr 1fr 1fr;
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
            font-size: 10px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-pending {
            color: #856404;
            font-weight: bold;
        }
        .status-paid {
            color: #155724;
            font-weight: bold;
        }
        .status-cancelled {
            color: #721c24;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .transaction-details {
            margin-top: 20px;
        }
        .transaction-details h3 {
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .detail-item {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border-left: 3px solid #28a745;
        }
        .detail-item h4 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 14px;
        }
        .detail-item p {
            margin: 2px 0;
            font-size: 11px;
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
                <h3>Total Transaksi</h3>
                <p>{{ number_format($totalTransactions) }}</p>
            </div>
            <div class="summary-item">
                <h3>Total Pendapatan</h3>
                <p>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="summary-item">
                <h3>Transaksi Lunas</h3>
                <p>{{ number_format($paidTransactions) }}</p>
            </div>
            <div class="summary-item">
                <h3>Transaksi Pending</h3>
                <p>{{ number_format($pendingTransactions) }}</p>
            </div>
            <div class="summary-item">
                <h3>Transaksi Dibatalkan</h3>
                <p>{{ number_format($cancelledTransactions) }}</p>
            </div>
        </div>
    </div>

    <div class="table-container">
        <h2>Daftar Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Transaksi</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>#{{ $transaction->id }}</td>
                    <td>{{ $transaction->queue->patient->name ?? '-' }}</td>
                    <td>Dr. {{ $transaction->queue->doctor->name ?? '-' }}</td>
                    <td>
                        <span class="status-{{ $transaction->status }}">
                            @if($transaction->status == 'pending')
                                Menunggu
                            @elseif($transaction->status == 'paid')
                                Lunas
                            @elseif($transaction->status == 'cancelled')
                                Dibatalkan
                            @endif
                        </span>
                    </td>
                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="transaction-details">
        <h3>Detail Layanan Transaksi</h3>
        @foreach($transactions as $transaction)
        <div class="detail-item">
            <h4>Transaksi #{{ $transaction->id }} - {{ $transaction->queue->patient->name ?? '-' }}</h4>
            <p><strong>Dokter:</strong> Dr. {{ $transaction->queue->doctor->name ?? '-' }}</p>
            <p><strong>Status:</strong>
                @if($transaction->status == 'pending')
                    Menunggu
                @elseif($transaction->status == 'paid')
                    Lunas
                @elseif($transaction->status == 'cancelled')
                    Dibatalkan
                @endif
            </p>
            <p><strong>Total:</strong> Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
            <p><strong>Layanan:</strong></p>
            <ul>
                @foreach($transaction->transactionDetails as $detail)
                <li>{{ $detail->layanan }} - Rp {{ number_format($detail->harga, 0, ',', '.') }}
                    @if($detail->keterangan)
                        ({{ $detail->keterangan }})
                    @endif
                </li>
                @endforeach
            </ul>
            @if($transaction->keterangan)
            <p><strong>Keterangan:</strong> {{ $transaction->keterangan }}</p>
            @endif
        </div>
        @endforeach
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Klinik Kecantikan</p>
    </div>
</body>
</html>
