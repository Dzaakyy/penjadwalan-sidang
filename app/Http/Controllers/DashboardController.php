<?php

namespace App\Http\Controllers;

use App\Models\Pimpinan;
use App\Models\MahasiswaPkl;
use Illuminate\Http\Request;
use App\Models\MahasiswaSempro;
use App\Models\MahasiswaTa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();


        $data = [];

        if ($user->hasRole('pimpinanProdi')) {
            $prodiId = $user->r_dosen->prodi_id;
            $getDosen = $user->r_dosen->id_dosen;

            $Kaprodi = Pimpinan::with('r_dosen')
                ->where('dosen_id', $getDosen)
                ->whereHas('r_jabatan_pimpinan', function ($query) {
                    $query->where('kode_jabatan_pimpinan', 'Kaprodi');
                })
                ->whereHas('r_dosen', function ($query) use ($prodiId) {
                    $query->where('prodi_id', $prodiId);
                })
                ->first();

            if (!$Kaprodi) {
                abort(404, 'Data Kaprodi tidak ditemukan.');
            }

            $pklBelumDiterima = DB::table('mahasiswa')
                ->where('prodi_id', $prodiId)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('mhs_pkl')
                        ->whereRaw('mhs_pkl.mahasiswa_id = mahasiswa.id_mahasiswa');
                })
                ->count();

            $pklDiterima = MahasiswaPkl::whereHas('r_pkl.r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
                ->whereNotNull('dosen_pembimbing')
                ->count();

            $pklSelesai = MahasiswaPkl::whereHas('r_pkl.r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
                ->whereNotNull('nilai_mahasiswa')
                ->count();

            $banyak_pengunggahan_pkl = $pklDiterima;
            $banyak_verifikasi_pkl = $pklSelesai;

            $total_pkl = $banyak_pengunggahan_pkl + $banyak_verifikasi_pkl;
            $percentUploadedPKL = $total_pkl > 0 ? ($banyak_pengunggahan_pkl / $total_pkl) * 100 : 0;
            $percentVerifiedPKL = $total_pkl > 0 ? ($banyak_verifikasi_pkl / $total_pkl) * 100 : 0;

            $semproJudulDiajukan = MahasiswaSempro::where('status_judul', '2')
                ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                    $query->where('prodi_id', $prodiId);
                })
                ->count();

            $semproSelesai = MahasiswaSempro::whereNotNull('nilai_mahasiswa')
                ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                    $query->where('prodi_id', $prodiId);
                })
                ->count();

            $banyak_pengunggahan_sempro = $semproJudulDiajukan;
            $banyak_verifikasi_sempro = $semproSelesai;

            $total_sempro = $banyak_pengunggahan_sempro + $banyak_verifikasi_sempro;
            $percentUploadedSempro = $total_sempro > 0 ? ($banyak_pengunggahan_sempro / $total_sempro) * 100 : 0;
            $percentVerifiedSempro = $total_sempro > 0 ? ($banyak_verifikasi_sempro / $total_sempro) * 100 : 0;


            $taDiajukan = MahasiswaTa::where('status_berkas', '1')
                ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                    $query->where('prodi_id', $prodiId);
                })
                ->count();

            $taSelesai = MahasiswaTa::whereNotNull('nilai_mahasiswa')
                ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                    $query->where('prodi_id', $prodiId);
                })
                ->count();

            $banyak_pengunggahan_ta = $taDiajukan;
            $banyak_verifikasi_ta = $taSelesai;

            $total_ta = $banyak_pengunggahan_ta + $banyak_verifikasi_ta;
            $percentUploadedTa = $total_ta > 0 ? ($banyak_pengunggahan_ta / $total_ta) * 100 : 0;
            $percentVerifiedTa = $total_ta > 0 ? ($banyak_verifikasi_ta / $total_ta) * 100 : 0;

            $data = array_merge($data, compact(
                'Kaprodi',
                'percentUploadedPKL',
                'percentVerifiedPKL',
                'banyak_pengunggahan_pkl',
                'banyak_verifikasi_pkl',
                'percentUploadedSempro',
                'percentVerifiedSempro',
                'banyak_pengunggahan_sempro',
                'banyak_verifikasi_sempro',
                'percentUploadedTa',
                'percentVerifiedTa',
                'banyak_pengunggahan_ta',
                'banyak_verifikasi_ta',
                'pklBelumDiterima',
                'pklDiterima',
                'pklSelesai',
                'semproJudulDiajukan',
                'semproSelesai'
            ));
        }


        if ($user->hasRole('admin')) {

            $MahasiswaPkl = MahasiswaPkl::count();

            $MahasiswaSelesaiPkl = MahasiswaPkl::whereNotNull('nilai_mahasiswa')
                ->count();

            $MahasiswaSempro = MahasiswaSempro::count();

            $MahasiswaSelesaiSempro = MahasiswaSempro::whereNotNull('nilai_mahasiswa')
                ->count();


            $MahasiswaTA = MahasiswaTa::count();

            $MahasiswaSelesaiTA = MahasiswaTa::whereNotNull('nilai_mahasiswa')
                ->count();


            $data = array_merge($data, compact(
                'MahasiswaPkl',
                'MahasiswaSelesaiPkl',
                'MahasiswaSempro',
                'MahasiswaSelesaiSempro',
                'MahasiswaTA',
                'MahasiswaSelesaiTA'
            ));
        }

        if ($user->hasRole('mahasiswa')) {

            $jadwal_sidang_pkl = MahasiswaPkl::where('mahasiswa_id', $user->r_mahasiswa ? $user->r_mahasiswa->id_mahasiswa : null)->get();


            // dd($jadwal_sidang_pkl);



            // dd($events);

            $jadwal_sidang_sempro = MahasiswaSempro::where('mahasiswa_id', $user->r_mahasiswa ? $user->r_mahasiswa->id_mahasiswa : null)->get();

            // dd($jadwal_sidang_sempro);
            $jadwal_sidang_ta = MahasiswaTa::where('mahasiswa_id', $user->r_mahasiswa ? $user->r_mahasiswa->id_mahasiswa : null)->get();



            $events = $jadwal_sidang_pkl->map(function ($sidang) {
                return [
                    'title' => 'Sidang PKL',
                    'start' => $sidang->tgl_sidang ? \Carbon\Carbon::parse($sidang->tgl_sidang)->toDateString() : '', 
                    'end' => $sidang->tgl_sidang ? \Carbon\Carbon::parse($sidang->tgl_sidang)->toDateString() : '',
                    'room' => $sidang->r_ruang ? $sidang->r_ruang->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            })->merge($jadwal_sidang_sempro->map(function ($sidang) {
                return [
                    'title' => 'Sidang Sempro',
                    'start' => $sidang->tanggal_sempro ? \Carbon\Carbon::parse($sidang->tanggal_sempro)->toDateString() : '',
                    'end' => $sidang->tanggal_sempro ? \Carbon\Carbon::parse($sidang->tanggal_sempro)->toDateString() : '',
                    'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            }))->merge($jadwal_sidang_ta->map(function ($sidang) {
                return [
                    'title' => 'Sidang TA',
                    'start' => $sidang->tanggal_ta ? \Carbon\Carbon::parse($sidang->tanggal_ta)->toDateString() : '',
                    'end' => $sidang->tanggal_ta ? \Carbon\Carbon::parse($sidang->tanggal_ta)->toDateString() : '',
                    'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            }))->values();


            $data = array_merge($data, compact(
                'jadwal_sidang_pkl',
                'jadwal_sidang_sempro',
                'jadwal_sidang_ta',
                'events'
            ));
        }



        if ($user->hasRole('pengujiPkl|pengujiSempro')) {

            $pklDiterimaPenguji = MahasiswaPkl::where('dosen_penguji', $user->r_dosen->id_dosen)
                ->count();

            $pklSelesaiPenguji = MahasiswaPkl::where('dosen_penguji', $user->r_dosen->id_dosen)
                ->whereHas('r_nilai_penguji', function ($query) {
                    $query->where('status', '1')->whereNotNull('nilai_pkl');
                })
                ->count();

            $semproDiterimaPenguji = MahasiswaSempro::where('penguji', $user->r_dosen->id_dosen)
                ->count();

            $semproSelesaiPenguji = MahasiswaSempro::where('penguji', $user->r_dosen->id_dosen)
                ->whereHas('r_nilai_penguji', function ($query) {
                    $query->where('status', '2')->whereNotNull('nilai_sempro');
                })
                ->count();


            $taDiterimaPenguji = MahasiswaTa::where(function ($query) use ($user) {
                $query->where('ketua', $user->r_dosen->id_dosen)
                    ->orWhere('sekretaris', $user->r_dosen->id_dosen)
                    ->orWhere('penguji_1', $user->r_dosen->id_dosen)
                    ->orWhere('penguji_2', $user->r_dosen->id_dosen);
            })->count();

            $taSelesaiPenguji = MahasiswaTa::where(function ($query) use ($user) {
                $query->where('ketua', $user->r_dosen->id_dosen)
                    ->orWhere('sekretaris', $user->r_dosen->id_dosen)
                    ->orWhere('penguji_1', $user->r_dosen->id_dosen)
                    ->orWhere('penguji_2', $user->r_dosen->id_dosen);
            })
                ->whereHas('r_nilai_ketua', function ($query) {
                    $query->where('status', '0')->whereNotNull('nilai_sidang');
                })
                ->orWhereHas('r_nilai_sekretaris', function ($query) {
                    $query->where('status', '1')->whereNotNull('nilai_sidang');
                })
                ->whereHas('r_nilai_penguji_1', function ($query) {
                    $query->where('status', '2')->whereNotNull('nilai_sidang');
                })
                ->orWhereHas('r_nilai_penguji_2', function ($query) {
                    $query->where('status', '3')->whereNotNull('nilai_sidang');
                })
                ->count();

            // dd($semproDiterimaPenguji);

            $data = array_merge($data, compact(
                'pklDiterimaPenguji',
                'pklSelesaiPenguji',
                'semproDiterimaPenguji',
                'semproSelesaiPenguji',
                'taDiterimaPenguji',
                'taSelesaiPenguji'
            ));
        }

        if ($user->hasRole('pembimbingPkl|pembimbingSempro')) {

            $pklDiterimaPembimbing = MahasiswaPkl::where('dosen_pembimbing', $user->r_dosen->id_dosen)
                ->count();

            $pklSelesaiPembimbing = MahasiswaPkl::where('dosen_pembimbing', $user->r_dosen->id_dosen)
                ->whereHas('r_nilai_pembimbing', function ($query) {
                    $query->where('status', '0')->whereNotNull('nilai_pkl');
                })
                ->count();

            $semproDiterimaPembimbing = MahasiswaSempro::where(function ($query) use ($user) {
                $query->where('pembimbing_satu', $user->r_dosen->id_dosen)
                    ->orWhere('pembimbing_dua', $user->r_dosen->id_dosen);
            })->count();

            $semproSelesaiPembimbing = MahasiswaSempro::where(function ($query) use ($user) {
                $query->where('pembimbing_satu', $user->r_dosen->id_dosen)
                    ->orWhere('pembimbing_dua', $user->r_dosen->id_dosen);
            })
                ->whereHas('r_nilai_pembimbing_satu', function ($query) {
                    $query->where('status', '0')->whereNotNull('nilai_sempro');
                })
                ->whereHas('r_nilai_pembimbing_dua', function ($query) {
                    $query->where('status', '1')->whereNotNull('nilai_sempro');
                })
                ->count();

            $taDiterimaPembimbing = MahasiswaTa::where(function ($query) use ($user) {
                $query->where('pembimbing_satu_id', $user->r_dosen->id_dosen)
                    ->orWhere('pembimbing_dua_id', $user->r_dosen->id_dosen);
            })->count();


            $taSelesaiPembimbing = MahasiswaTa::where(function ($query) use ($user) {
                $query->where('pembimbing_satu_id', $user->r_dosen->id_dosen)
                    ->orWhere('pembimbing_dua_id', $user->r_dosen->id_dosen);
            })
                ->where(function ($query) {
                    $query->where('acc_pembimbing_satu', '1');
                })
                ->where(function ($query) {
                    $query->where('acc_pembimbing_dua', '1');
                })
                ->count();

            // $taSelesaiPembimbing = MahasiswaTa::where(function ($query) use ($user) {
            //     $query->where('pembimbing_satu_id', $user->r_dosen->id_dosen)
            //         ->orWhere('pembimbing_dua_id', $user->r_dosen->id_dosen);
            // })
            //     ->whereHas('r_nilai_pembimbing_satu', function ($query) {
            //         $query->where('status', '0')->whereNotNull('nilai_bimbingan');
            //     })
            //     ->whereHas('r_nilai_pembimbing_dua', function ($query) {
            //         $query->where('status', '1')->whereNotNull('nilai_bimbingan');
            //     })
            //     ->count();



            // dd($semproDiterimaPenguji);

            $data = array_merge($data, compact(
                'pklDiterimaPembimbing',
                'pklSelesaiPembimbing',
                'semproDiterimaPembimbing',
                'semproSelesaiPembimbing',
                'taDiterimaPembimbing',
                'taSelesaiPembimbing'
            ));
        }

        return view('admin.content.dashboard', $data);
    }
}
