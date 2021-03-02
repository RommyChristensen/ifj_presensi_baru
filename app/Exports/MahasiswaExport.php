<?php

namespace App\Exports;

use App\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MahasiswaExport implements FromQuery, WithHeadings
{
    use Exportable;
    public function __construct($date_from, $date_to)
    {
        $this->date_from = $date_from;
        $this->date_to = $date_to;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        // return DB::table(DB::raw("SELECT mhs.nrp, mhs.nama, count(dk.nrp) as jumlah_kehadiran FROM mahasiswa mhs JOIN detail_kehadiran dk ON dk.nrp = mhs.nrp WHERE dk.created_at BETWEEN '$this->date_from' AND '$this->date_to' AND mhs.konfirmasi = 1 GROUP BY mhs.nrp, mhs.nama"));
        return DB::table('mahasiswa', 'mhs')
        ->select(DB::raw('mhs.nrp, mhs.nama, count(detail_kehadiran.nrp) as jumlah_kehadiran'))
        ->join('detail_kehadiran', 'detail_kehadiran.nrp', '=', 'mhs.nrp')
        ->where('mhs.konfirmasi', 1)
        ->whereBetween('detail_kehadiran.created_at', [$this->date_from, $this->date_to])
        ->groupBy('mhs.nrp', 'mhs.nama')
        ->orderBy('mhs.nrp');
    }

    public function headings(): array
    {
        return [
            'NRP',
            'Nama Mahasiswa',
            'Jumlah Kehadiran',
        ];
    }
}
