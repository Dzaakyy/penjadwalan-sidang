<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MahasiswaPkl;
use App\Models\Pimpinan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::create(['name' => 'superAdmin']);
        $adminRole = Role::create(['name' => 'admin']);
        $dosenRole = Role::firstOrCreate(['name' => 'dosen']);
        $pimpinanJurusanRole = Role::firstOrCreate(['name' => 'pimpinanJurusan']);
        $pimpinanProdiRole = Role::firstOrCreate(['name' => 'pimpinanProdi']);
        $pembimbingPklRole = Role::firstOrCreate(['name' => 'pembimbingPkl']);
        $pengujiPklRole = Role::firstOrCreate(['name' => 'pengujiPkl']);
        $pembimbingSemproRole = Role::firstOrCreate(['name' => 'pembimbingSempro']);
        $pengujiSemproRole = Role::firstOrCreate(['name' => 'pengujiSempro']);
        $pembimbingTaRole = Role::firstOrCreate(['name' => 'pembimbingTa']);
        $pengujiTaRole = Role::firstOrCreate(['name' => 'pengujiTa']);
        $mahasiswaRole = Role::firstOrCreate(['name' => 'mahasiswa']);

        // Mengaitkan peran dengan pengguna
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $adminUser->assignRole($adminRole);

        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $superAdminUser->assignRole($superAdminRole);

        $dosens = Dosen::all();
        $kajurDosenIds = Pimpinan::kajur()->pluck('dosen_id');
        $kaprodiDosenIds = Pimpinan::kaprodi()->pluck('dosen_id');
        $mahasiswas = Mahasiswa::all();
        $pembimbingDosenIds = MahasiswaPkl::pluck('dosen_pembimbing');
        $pengujiDosenIds = MahasiswaPkl::pluck('dosen_penguji');

        $kajurdosenIdsArray = [];
        $kaprodidosenIdsArray = [];
        $mahasiswaIdsArray = [];
        $pembimbingdosenIdsArray = [];
        $pengujidosenIdsArray = [];
        foreach ($kajurDosenIds as $kajur) {
            $kajurdosenIdsArray[] = $kajur;
        }
        foreach ($kaprodiDosenIds as $kaprodi) {
            $kaprodidosenIdsArray[] = $kaprodi;
        }
        foreach ($mahasiswas as $mahasiswa) {
            $mahasiswaIdsArray[] = $mahasiswa;
        }
        foreach ($pembimbingDosenIds as $dosenpembimbing) {
            $pembimbingdosenIdsArray[] = $dosenpembimbing;
        }
        foreach ($pengujiDosenIds as $dosenpenguji) {
            $pengujidosenIdsArray[] = $dosenpenguji;
        }


        foreach ($dosens as $dosen) {
            // Cek apakah pengguna sudah ada berdasarkan email
            $existingUser = User::where('email', $dosen->r_user->email)->first();

            // Jika pengguna sudah ada, tambahkan peran sesuai dengan tabelnya
            if ($existingUser) {
                $existingUser->assignRole($dosenRole);

                if (in_array($dosen->id_dosen, $kajurDosenIds->toArray())) {
                    $existingUser->assignRole($pimpinanJurusanRole);
                }

                if (in_array($dosen->id_dosen, $kaprodiDosenIds->toArray())) {
                    $existingUser->assignRole($pimpinanProdiRole);
                }

                if (in_array($dosen->id_dosen, $pembimbingDosenIds->toArray())) {
                    $existingUser->assignRole($pembimbingPklRole);
                }

                if (in_array($dosen->id_dosen, $pengujiDosenIds->toArray())) {
                    $existingUser->assignRole($pengujiPklRole);
                }

            } else {
                // Jika pengguna belum ada, buat pengguna baru dan tambahkan peran
                $user = User::create([
                    'name' => $dosen->nama_dosen,
                    'email' => $dosen->email,
                    'password' => $dosen->password,
                ]);

                if (in_array($dosen->id_dosen, $kajurDosenIds->toArray())) {
                    $user->assignRole($pimpinanJurusanRole);
                }

                if (in_array($dosen->id_dosen, $kaprodiDosenIds->toArray())) {
                    $user->assignRole($pimpinanProdiRole);
                }

                if (in_array($dosen->id_dosen, $pembimbingDosenIds->toArray())) {
                    $existingUser->assignRole($pembimbingPklRole);
                }

                if (in_array($dosen->id_dosen, $pengujiDosenIds->toArray())) {
                    $existingUser->assignRole($pengujiPklRole);
                }

            }
        }

        foreach ($mahasiswas as $mahasiswa) {
            // Cek apakah pengguna sudah ada berdasarkan email
            $existingUser = User::where('email', $mahasiswa->r_user->email)->first();

            // Jika pengguna sudah ada, tambahkan peran sesuai dengan tabelnya
            if ($existingUser) {
                if (in_array($mahasiswa->id_mahasiswa, $mahasiswa->toArray())) {
                    $existingUser->assignRole($mahasiswaRole);
                }

            } else {
                // Jika pengguna belum ada, buat pengguna baru dan tambahkan peran
                $user = User::create([
                    'name' => $mahasiswa->nama,
                    'email' => $mahasiswa->email,
                    'password' => $mahasiswa->password,
                ]);

                if (in_array($mahasiswa->id_mahasiswa, $mahasiswa->toArray())) {
                    $user->assignRole($mahasiswaRole);
                }

            }
        }
    }
}
