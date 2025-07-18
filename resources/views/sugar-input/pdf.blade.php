<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Gula Masuk</title>
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
        <h1>Laporan Gula Masuk</h1>
        <p>Tanggal: {{ date('d-m-Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Sak</th>
                <th>Bobot (KG)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inputs as $index => $input)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('d-m-Y', strtotime($input->tanggal)) }}</td>
                <td>{{ number_format($input->sak) }}</td>
                <td>{{ number_format($input->bobot) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div>
        <p><strong>Total Pemasukan:</strong> {{ $inputs->count() }}</p>
        <p><strong>Total Sak:</strong> {{ number_format($inputs->sum('sak')) }}</p>
        <p><strong>Total Bobot:</strong> {{ number_format($inputs->sum('bobot')) }} KG</p>
    </div>
</body>
</html>