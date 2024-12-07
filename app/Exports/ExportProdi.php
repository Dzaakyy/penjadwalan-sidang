<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportProdi implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data_prodi = DB::table('prodi')
        ->join('jurusan', 'prodi.jurusan_id', '=', 'jurusan.id_jurusan')
        ->select(
            'prodi.kode_prodi',
            'prodi.prodi',
            'prodi.jenjang',
            'jurusan.nama_jurusan'
        )
        ->orderBy('id_prodi')
        ->get();

    return $data_prodi;
}

public function headings(): array
{
    return [
        'Kode Prodi',
        'Prodi',
        'Jenjang',
        'Nama Jurusan',
    ];
}

    }

