<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Gula Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Gula Keluar</h1>
        <p>Tanggal: {{ date('d-m-Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pembeli</th>
                <th>Sak</th>
                <th>Bobot (KG)</th>
                <th>Harga Per KG</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outputs as $index => $output)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('d-m-Y', strtotime($output->tanggal)) }}</td>
                <td>{{ $output->nama_pembeli }}</td>
                <td class="text-right">{{ number_format($output->sak) }}</td>
                <td class="text-right">{{ number_format($output->bobot) }}</td>
                <td class="text-right">Rp{{ number_format($output->harga_per_kg) }}</td>
                <td class="text-right">Rp{{ number_format($output->total_harga) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div>
        <p><strong>Total Transaksi:</strong> {{ $outputs->count() }}</p>
        <p><strong>Total Sak:</strong> {{ number_format($outputs->sum('sak')) }}</p>
        <p><strong>Total Bobot:</strong> {{ number_format($outputs->sum('bobot')) }} KG</p>
        <p><strong>Total Harga Keseluruhan:</strong> Rp{{ number_format($outputs->sum('total_harga')) }}</p>
    </div>
</body>
</html>