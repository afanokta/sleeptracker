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
    <h3 class="text-center">Laporan Jam Tidur Awak Mobil Tangki (AMT)</h3>

    <table>
        <thead>
            <tr>
                <th>Nama AMT</th>
                <th>Tanggal</th>
                <th>Kecukupan Tidur</th>
                <th>Durasi (jam)</th>
                <th>Lokasi</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->driver->name ?? '-' }}</td>
                    <td class="text-center">{{ $report->date?->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $report->sleep_category }}</td>
                    <td class="text-right">{{ number_format($report->sleep_duration_hours, 1) }}</td>
                    <td class="text-center">
                        @php
                            $sleep = $report->sleeptracks[0];
                            $fotoTidur =  url(Storage::url($sleep->photo));
                            $mapsUrl = "https://www.google.com/maps?q={$sleep->lat},{$sleep->long}";
                            if(isset($report->sleeptracks[1])) {
                                $wake = $report->sleeptracks[1];
                                $fotoBangun = url(Storage::url($wake->photo));
                                $mapsUrl2 = "https://www.google.com/maps?q={$wake->lat},{$wake->long}";
                            }
                        @endphp
                        <a href="{{ $mapsUrl }}">Tidur</a>
                        @if (isset($report->sleeptracks[1]))
                            <a href="{{ $mapsUrl2 }}">Bangun</a>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ $fotoTidur }}">Foto Tidur</a>
                        @if (isset($report->sleeptracks[1]))
                            <a href="{{ $fotoBangun }}">Foto Bangun</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


