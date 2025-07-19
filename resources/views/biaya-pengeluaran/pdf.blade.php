<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Biaya Konsumsi</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Biaya Konsumsi</h1>
        <p>Tanggal: {{ date('d-m-Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Volume</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($biayaKonsumsi as $index => $biaya)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('d-m-Y', strtotime($biaya->tanggal)) }}</td>
                <td>{{ $biaya->keterangan }}</td>
                <td>{{ $biaya->volume }}</td>
                <td>{{ $biaya->satuan }}</td>
                <td>Rp{{ number_format($biaya->harga) }}</td>
                <td>Rp{{ number_format($biaya->total_harga) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div>
        <p><strong>Total Data:</strong> {{ $biayaKonsumsi->count() }}</p>
        <p><strong>Total Biaya:</strong> Rp{{ number_format($biayaKonsumsi->sum('total_harga')) }}</p>
    </div>
</body>
</html>