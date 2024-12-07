<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use function Laravel\Prompts\table;

class ExportJurusan implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data_jurusan = DB::table('jurusan')
        ->select(
            'jurusan.kode_jurusan',
            'jurusan.nama_jurusan',
        )
        ->orderBy('id_jurusan')
        ->get();

    return $data_jurusan;
}

public function headings(): array
{
    return [
        'Kode Jurusan',
        'Nama Jurusan',
    ];
}

    }

