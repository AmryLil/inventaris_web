<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h2 class="text-center">Laporan Penjualan</h2>
    <p>Periode: {{ $startDate ? $startDate : 'Semua' }} - {{ $endDate ? $endDate : 'Semua' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->putri_tanggal_pesan)->format('d/m/Y') }}</td>
                    <td>{{ $item->barang ? $item->barang->putri_nama_barang : 'Data barang tidak tersedia' }}</td>
                    <td>{{ $item->putri_jumlah_barang }}</td>
                    <td class="text-right">Rp{{ number_format($item->putri_total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th>{{ $laporan->sum('putri_jumlah_barang') }}</th>
                <th class="text-right">Rp{{ number_format($laporan->sum('putri_total_harga'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <p>Total Transaksi: {{ $statistik['total_transaksi'] }}</p>
    <p>Rata-rata Penjualan: Rp{{ number_format($statistik['rata_rata_penjualan'], 0, ',', '.') }}</p>
</body>

</html>
