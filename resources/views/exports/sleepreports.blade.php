<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
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
    <h3 class="text-center">Laporan Sleepreport Supir (AMT)</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama AMT</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Kecukupan Tidur</th>
                <th>Durasi (jam)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td class="text-center">{{ $report->id }}</td>
                    <td>{{ $report->driver->name ?? '-' }}</td>
                    <td>{{ ucfirst($report->status) }}</td>
                    <td class="text-center">{{ $report->date?->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $report->sleep_category }}</td>
                    <td class="text-right">{{ number_format($report->sleep_duration_hours, 1) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


