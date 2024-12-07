<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportDosen implements FromCollection, WithHeadings
// class ExportDosen implements FromCollection, WithHeadings, WithDrawings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        $data_dosen = DB::table('dosen')
            ->join('jurusan', 'dosen.jurusan_id', '=', 'jurusan.id_jurusan')
            ->join('prodi', 'dosen.prodi_id', '=', 'prodi.id_prodi')
            ->join('users', 'dosen.user_id', '=', 'users.id')
            ->select(
                'dosen.nama_dosen',
                'users.email',
                'dosen.nidn',
                'dosen.nip',
                DB::raw('CASE WHEN dosen.gender = 0 THEN "Laki-Laki" ELSE "Perempuan" END AS gender'),
                'jurusan.nama_jurusan',
                'prodi.prodi',
                // 'dosen.image',
                DB::raw('CASE WHEN dosen.status_dosen = 0 THEN "Tidak Aktif" ELSE "Aktif" END AS status_dosen')
            )
            ->orderBy('id_dosen')
            ->get();

        return $data_dosen;
    }

    public function headings(): array
    {
        return [
            'Nama Dosen',
            'Email',
            'NIDN',
            'NIP',
            'Gender',
            'Jurusan',
            'Prodi',
            'Golongan',
            // 'Image',
            'Status Dosen',
        ];
    }

    // public function drawings()
    // {
    //     $drawings = [];
    //     foreach ($this->data_dosen as $index => $dosen) {
    //         if ($dosen->image) {
    //             $drawing = new Drawing();
    //             $drawing->setName($dosen->nama_dosen);
    //             $drawing->setPath(public_path('storage/uploads/dosen/image/' . $dosen->image));
    //             $drawing->setDescription('Image of ' . $dosen->nama_dosen);
    //             $drawing->setHeight(250);
    //             $drawing->setWidth(200);
    //             $drawing->setCoordinates('I' . ($index + 2));
    //             $drawings[] = $drawing;
    //         }
    //     }
    //     return $drawings;
    // }
}
