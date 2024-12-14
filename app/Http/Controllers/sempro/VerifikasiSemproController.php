<?php

namespace App\Http\Controllers\Sempro;

use App\Models\Sesi;
use App\Models\Dosen;
use App\Models\Ruang;
use App\Models\Pimpinan;
use Illuminate\Http\Request;
use App\Models\MahasiswaSempro;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VerifikasiSemproController extends Controller
{
    public function index()
    {

        $getDosen = Auth::user()->r_dosen->id_dosen;

        $Kaprodi = Pimpinan::where('dosen_id', $getDosen)
            ->whereHas('r_jabatan_pimpinan', function ($query) {
                $query->where('kode_jabatan_pimpinan', 'Kaprodi');
            })
            ->whereHas('r_dosen', function ($query) {
                $query->where('prodi_id', Auth::user()->r_dosen->prodi_id);
            })
            ->exists();
        // $nextNumber = $this->getCariNomor();

        $prodiId = Auth::user()->r_dosen->prodi_id;

        $data_mahasiswa_sempro = MahasiswaSempro::with('r_mahasiswa', 'pembimbing_satu', 'pembimbing_dua', 'penguji', 'r_ruangan', 'r_sesi')
            ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->orderByDesc('id_sempro')
            ->get();

        $data_ruangan = Ruang::all();
        $dosen_penguji = Dosen::all();
        $jam_sidang = Sesi::all();
        $data_sempro = MahasiswaSempro::all();

        return view('admin.content.sempro.kaprodi.verifikasi_sempro', compact('data_ruangan', 'data_mahasiswa_sempro', 'dosen_penguji', 'jam_sidang'));
    }

    

}
