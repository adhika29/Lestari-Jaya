<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengiriman Tebu</title>
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
        <h1>Laporan Pengiriman Tebu</h1>
        <p>Tanggal: {{ date('d-m-Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pengirim</th>
                <th>Jenis Tebu</th>
                <th>Bobot (KG)</th>
                <th>Harga (Per KG)</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $index => $shipment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('d-m-Y', strtotime($shipment->tanggal)) }}</td>
                <td>{{ $shipment->nama_pengirim }}</td>
                <td>{{ $shipment->jenis_tebu }}</td>
                <td>{{ number_format($shipment->bobot_kg) }}</td>
                <td>Rp{{ number_format($shipment->harga_per_kg) }}</td>
                <td>Rp{{ number_format($shipment->total_harga) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div>
        <p><strong>Total Pengiriman:</strong> {{ $shipments->count() }}</p>
        <p><strong>Total Bobot:</strong> {{ number_format($shipments->sum('bobot_kg')) }} KG</p>
        <p><strong>Total Nilai:</strong> Rp{{ number_format($shipments->sum('total_harga')) }}</p>
    </div>
</body>
</html>