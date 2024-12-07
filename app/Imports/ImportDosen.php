<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportDosen implements ToCollection, WithHeadingRow
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

            $nama_dosen = $row['nama_dosen'] ?? null;
            $email = $row['email'] ?? null;
            $password = $row['password'] ?? null;

            if ($nama_dosen === null || $email === null || $password === null) {
                Log::warning('Missing nama, email, or password for row: ', $row->toArray());
                continue;
            }


            $existingnidn = Dosen::where('nidn', $row['nidn'])->first();
            if ($existingnidn) {
                $errors[] = "NIDN " . $row['nidn'] . " sudah ada untuk dosen " . $existingnidn->nama_dosen;
                Log::warning('Duplicate found for nidn: ' . $row['nidn']);
            }

            $existingnip = Dosen::where('nip', $row['nip'])->first();
            if ($existingnip) {
                $errors[] = "NIP " . $row['nip'] . " sudah ada untuk dosen " . $existingnip->nama_dosen;
                Log::warning('Duplicate found for nip: ' . $row['nip']);
            }


            $data_user = User::where('email', $email)->first();
            if (!$data_user) {

                $data_user = User::create([
                    'name' => $nama_dosen,
                    'email' => $email,
                    'password' => Hash::make($password),
                ]);
                Log::info('Created new user with email: ' . $email);
            }

            if ($existingnidn || $existingnip) {
                continue;
            }

            $data_prodi = Prodi::where('prodi', $row['prodi'])->first();
            $data_jurusan = Jurusan::where('nama_jurusan', $row['jurusan'])->first();
            $nextNumber = $this->getCariNomor();

            Log::info('Fetched prodi: ', $data_prodi ? $data_prodi->toArray() : ['id_prodi' => null]);
            Log::info('Fetched jurusan: ', $data_jurusan ? $data_jurusan->toArray() : ['id_jurusan' => null]);

            $genderValue = ($row['gender'] === 'Perempuan') ? '1' : '0';
            $statusValue = null;

            if (trim($row['status_dosen']) === 'Aktif') {
                $statusValue = '1';
            } elseif (trim($row['status_dosen']) === 'Tidak Aktif') {
                $statusValue = '0';
            } else {
                Log::warning('Invalid status dosen for row: ', [$row->toArray()]);
                continue;
            }

            Log::info('Inserting dosen with status: ', [$statusValue]);


            Dosen::create([
                'id_dosen' => $nextNumber,
                'nama_dosen' => $nama_dosen,
                'user_id' =>$data_user->id,
                'nidn' => $row['nidn'],
                'nip' => $row['nip'],
                'gender' => $genderValue,
                'jurusan_id' => $data_jurusan ? $data_jurusan->id_jurusan : null,
                'prodi_id' => $data_prodi ? $data_prodi->id_prodi : null,
                'status_dosen' => $statusValue,
            ]);

            Log::info('Inserted dosen with nama: ' . $nama_dosen);
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
        $id_dosen = dosen::pluck('id_dosen')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_dosen)) {
                return $i;
            }
        }
    }
}
