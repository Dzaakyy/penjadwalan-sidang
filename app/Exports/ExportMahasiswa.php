<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportMahasiswa implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data_mahasiswa = DB::table('mahasiswa')
        ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id_prodi')
        ->join('users', 'mahasiswa.user_id', '=', 'users.id')
        ->select(
            'mahasiswa.nama',
            'users.email',
            'mahasiswa.nim',
            'prodi.prodi',
            DB::raw('CASE WHEN mahasiswa.gender = 0 THEN "Perempuan" ELSE "Laki-Laki" END AS gender'),
            // 'mahasiswa.image',
            DB::raw('CASE WHEN mahasiswa.status_mahasiswa = 0 THEN "Tidak Aktif" ELSE "Aktif" END AS status_mahasiswa')
        )
        ->orderBy('id_mahasiswa')
        ->get();

    return $data_mahasiswa;
}

public function headings(): array
{
    return [
        'Nama',
        'Email',
        'NIM',
        'Prodi',
        'Gender',
        'Status mahasiswa',
        // 'Image',
    ];
}

    }

