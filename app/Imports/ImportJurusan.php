<?php

namespace App\Imports;

use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportJurusan implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            $errors = [];
            $successCount = 0;

            foreach ($rows as $row) {
                Log::info('Processing row: ', $row->toArray());

                if ($row->filter()->isEmpty()) {
                    Log::warning('Skipping empty row');
                    continue;
                }

                $nama_jurusan = $row['nama_jurusan'] ?? null;
                if ($nama_jurusan === null) {
                    Log::warning('Missing Nama Jurusan for row: ', $row->toArray());
                    continue;
                }

                $existingJurusan = Jurusan::where('nama_jurusan', $row['nama_jurusan'])->first();
                if ($existingJurusan) {
                    $errors[] = "Nama Jurusan " . $row['nama_jurusan'] . " sudah ada untuk jurusan " . $existingJurusan->nama_jurusan;
                    Log::warning('Duplicate found for Nama Jurusan: ' . $row['nama_jurusan']);
                    continue;
                }

                $nextNumber = $this->getCariNomor();



                Jurusan::create([
                    'id_jurusan' => $nextNumber,
                    'kode_jurusan' => $row['kode_jurusan'],
                    'nama_jurusan' => $nama_jurusan,
                ]);

                Log::info('Inserted Jurusan with Nama : ' . $nama_jurusan);
                $successCount++;
            }

            if (!empty($errors)) {
                throw ValidationException::withMessages(['duplicate_data' => $errors]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Exception in collection method: ' . $e->getMessage() . ' at line ' . $e->getLine() . ' in file ' . $e->getFile());
            throw $e;
        }
    }




    public function headingRow(): int
    {
        return 1;
    }

    public function getCariNomor()
    {
        $id_jurusan = Jurusan::pluck('id_jurusan')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_jurusan)) {
                return $i;
            }
        }
    }
}
