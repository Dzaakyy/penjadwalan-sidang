<?php

namespace App\Http\Controllers;

use App\Models\Pimpinan;
use App\Models\MahasiswaPkl;
use Illuminate\Http\Request;
use App\Models\MahasiswaSempro;
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

            $data = array_merge($data, compact(
                'Kaprodi',
                'percentUploadedPKL',
                'percentVerifiedPKL',
                'percentUploadedSempro',
                'percentVerifiedSempro',
                'banyak_pengunggahan_pkl',
                'banyak_verifikasi_pkl',
                'banyak_pengunggahan_sempro',
                'banyak_verifikasi_sempro',
                'pklBelumDiterima',
                'pklDiterima',
                'pklSelesai',
                'semproJudulDiajukan',
                'semproSelesai'
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

            // dd($semproDiterimaPenguji);

            $data = array_merge($data, compact(
                'pklDiterimaPenguji',
                'pklSelesaiPenguji',
                'semproDiterimaPenguji',
                'semproSelesaiPenguji'
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


            // dd($semproDiterimaPenguji);

            $data = array_merge($data, compact(
                'pklDiterimaPembimbing',
                'pklSelesaiPembimbing',
                'semproDiterimaPembimbing',
                'semproSelesaiPembimbing'
            ));
        }

        return view('admin.content.dashboard', $data);
    }
}
