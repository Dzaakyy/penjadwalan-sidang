<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportMahasiswa implements ToCollection, WithHeadingRow
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

                $nama = $row['nama'] ?? null;
                $email = $row['email'] ?? null;

                $password = $row['password'] ?? '12345678';

                if ($nama === null || $email === null || $password === null) {
                    Log::warning('Missing nama, email, or password for row: ', $row->toArray());
                    continue;
                }

                $existingMahasiswa = Mahasiswa::where('nim', $row['nim'])->first();
                if ($existingMahasiswa) {
                    $errors[] = "NIM " . $row['nim'] . " sudah ada untuk mahasiswa " . $existingMahasiswa->nama;
                    Log::warning('Duplicate found for NIM: ' . $row['nim']);
                    continue;
                }

                $data_user = User::where('email', $email)->first();
                if (!$data_user) {

                    $data_user = User::create([
                        'name' => $nama,
                        'email' => $email,
                        'password' => Hash::make($password),
                    ]);
                    Log::info('Created new user with email: ' . $email);

                    $data_user->assignRole('mahasiswa');
                    Log::info('Assigned "mahasiswa" role to user: ' . $email);
                }

                $data_prodi = Prodi::where('prodi', $row['prodi'])->first();
                $nextNumber = $this->getCariNomor();
                Log::info('Fetched prodi: ', $data_prodi ? $data_prodi->toArray() : ['id_prodi' => null]);

                $genderValue = ($row['gender'] === 'Perempuan') ? '1' : '0';
                $statusValue = null;

                if (trim($row['status_mahasiswa']) === 'Aktif') {
                    $statusValue = '1';
                } elseif (trim($row['status_mahasiswa']) === 'Tidak Aktif') {
                    $statusValue = '0';
                } else {
                    Log::warning('Invalid status mahasiswa for row: ', [$row->toArray()]);
                    continue;
                }

                Log::info('Inserting mahasiswa with status: ', [$statusValue]);

                Mahasiswa::create([
                    'id_mahasiswa' => $nextNumber,
                    'nama' => $nama,
                    'user_id' => $data_user->id,
                    'nim' => $row['nim'],
                    'prodi_id' => $data_prodi ? $data_prodi->id_prodi : null,
                    'gender' => $genderValue,
                    'status_mahasiswa' => $statusValue,
                ]);

                Log::info('Inserted mahasiswa with nama: ' . $nama);
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
        $id_mahasiswa = Mahasiswa::pluck('id_mahasiswa')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_mahasiswa)) {
                return $i;
            }
        }
    }
}
