<?php

namespace App\Exports;

use App\Models\SleepReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SleepReportExport implements FromCollection, WithHeadings
{
    protected Collection $reports;

    public function __construct(Collection $reports)
    {
        $this->reports = $reports;
    }

    public function collection()
    {
        return $this->reports->map(function ($report) {

            $sleep = $report->sleepTracks->firstWhere('input_type', 'sleep');
            $wake  = $report->sleepTracks->firstWhere('input_type', 'wake');

            return [
                'nama_amt'    => $report->driver->name,
                'tanggal'     => $report->date->format('d/m/Y'),
                'kecukupan'   => $report->getSleepCategoryAttribute(),
                'durasi'      => $report->getSleepDurationHoursAttribute(),
                'lokasi_tidur'      => $sleep?->lat
                    ? "https://www.google.com/maps?q={$sleep->lat},{$sleep->long}"
                    : '-',
                'lokasi_bangun'      => $wake?->lat
                    ? "https://www.google.com/maps?q={$wake->lat},{$wake->long}"
                    : '-',
                'foto_tidur'  => $sleep?->photo ? url(Storage::url($sleep?->photo)) : '-',
                'foto_bangun' => $wake?->photo ? url(Storage::url($wake?->photo)) : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama AMT',
            'Tanggal',
            'Kecukupan Tidur',
            'Durasi (jam)',
            'Lokasi',
            'Foto Tidur',
            'Foto Bangun',
        ];
    }
}
