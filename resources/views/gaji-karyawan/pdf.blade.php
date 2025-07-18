<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Gaji Karyawan</title>
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
        <h1>Laporan Gaji Karyawan</h1>
        <p>Tanggal: {{ date('d-m-Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Sak</th>
                <th>Bobot (kg)</th>
                <th>Jumlah Gula (ton)</th>
                <th>Gaji (per ton)</th>
                <th>Total Gaji</th>
                <th>Jumlah Karyawan</th>
                <th>Gaji per Karyawan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gajiKaryawan as $index => $gaji)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('d-m-Y', strtotime($gaji->tanggal)) }}</td>
                <td>{{ $gaji->sak }}</td>
                <td>{{ number_format($gaji->bobot_kg, 0, ',', '.') }}</td>
                <td>{{ number_format($gaji->jumlah_gula_ton, 2, ',', '.') }}</td>
                <td>Rp{{ number_format($gaji->gaji_per_ton, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($gaji->total_gaji, 0, ',', '.') }}</td>
                <td>{{ $gaji->jumlah_karyawan }}</td>
                <td>Rp{{ number_format($gaji->gaji_per_karyawan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div>
        <p><strong>Total Data:</strong> {{ $gajiKaryawan->count() }}</p>
        <p><strong>Total Gaji:</strong> Rp{{ number_format($gajiKaryawan->sum('total_gaji'), 0, ',', '.') }}</p>
    </div>
</body>
</html>