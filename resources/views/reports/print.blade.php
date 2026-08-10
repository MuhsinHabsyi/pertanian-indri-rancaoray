<!DOCTYPE html>
<html>
<head>
    <title>Laporan Operasional</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body onload="window.print()"> <h2 style="text-align: center;">LAPORAN OPERASIONAL TANI RANCAORAY</h2>
    <p style="text-align: center;">Periode: {{ $start }} s/d {{ $end }}</p>
    <hr>

    <h3>1. Ringkasan Eksekutif</h3>
    <table>
        <tr>
            <th>Total Pengeluaran</th>
            <th>Total Pemasukan</th>
            <th>Total Panen</th>
        </tr>
        <tr>
            <td>Rp{{ number_format($summary['total_expense'], 0, ',', '.') }}</td>
            <td>Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</td>
            <td>{{ $summary['total_harvest'] }} Kg</td>
        </tr>
    </table>

    <h3>2. Detail Pengeluaran (Pembiayaan Disetujui)</h3>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Nominal</th>
        </tr>
        @forelse($purchases as $p)
        <tr>
            <td>{{ $p->submission_date }}</td>
            <td>{{ $p->validation_status }}</td>
            <td>Rp{{ number_format($p->total_cost, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr><td colspan="3" style="text-align:center;">Tidak ada data pembiayaan.</td></tr>
        @endforelse
    </table>

    <h3>3. Detail Pemasukan (Pesanan Selesai)</h3>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Metode Bayar</th>
            <th>Nominal</th>
        </tr>
        @forelse($orders as $o)
        <tr>
            <td>{{ $o->transaction_date }}</td>
            <td>{{ $o->payment_method }}</td>
            <td>Rp{{ number_format($o->total_payment, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr><td colspan="3" style="text-align:center;">Tidak ada data penjualan.</td></tr>
        @endforelse
    </table>

</body>
</html>