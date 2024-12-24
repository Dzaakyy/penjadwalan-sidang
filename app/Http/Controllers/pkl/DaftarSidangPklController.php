<?php

namespace App\Http\Controllers\pkl;

use App\Models\Sesi;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Ruang;
use App\Models\Pimpinan;
use App\Models\MahasiswaPkl;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DaftarSidangPklController extends Controller
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


        $data_mahasiswa_pkl = MahasiswaPkl::with('r_pkl', 'r_dosen_pembimbing')
            ->whereHas('r_pkl', function ($query) use ($prodiId) {
                $query->whereHas('r_mahasiswa', function ($subQuery) use ($prodiId) {
                    $subQuery->where('prodi_id', $prodiId);
                });
            })
            ->orderByDesc('id_mhs_pkl')
            ->get();
        $data_ruangan = Ruang::all();
        $dosen_penguji = Dosen::all();
        $jam_sidang = Sesi::all();

        return view('admin.content.pkl.kaprodi.daftar_sidang_pkl', compact('data_ruangan', 'data_mahasiswa_pkl', 'dosen_penguji', 'jam_sidang'));
    }


    public function update(Request $request, $id)
    {

        // dd($request->all());

        $request->validate([
            'dosen_penguji' => 'required|exists:dosen,id_dosen',
            'ruang_sidang' => 'required|exists:ruang,id_ruang',
            'jam_sidang' => 'required|exists:sesi,id_sesi',
            'tgl_sidang' => 'required',
        ]);

        $mahasiswaPkl = MahasiswaPkl::findOrFail($id);

        $mahasiswaPkl->dosen_penguji = $request->dosen_penguji;
        $mahasiswaPkl->ruang_sidang = $request->ruang_sidang;
        $mahasiswaPkl->tgl_sidang = $request->tgl_sidang;
        $mahasiswaPkl->jam_sidang = $request->jam_sidang;
        $mahasiswaPkl->save();

        $dosen = Dosen::find($request->dosen_penguji);

        if ($dosen) {
            $user = User::where('email', $dosen->r_user->email)->first();

            if ($user) {
                if (!$user->hasRole('pengujiPkl')) {
                    $user->assignRole('pengujiPkl');
                }
            }
        }


        return redirect()->back()->with('success', 'Pendaftaran sidang berhasil ditambahkan');
    }

    public function download_pdf(Request $request, $id)
    {
        $getDosen = Auth::user()->r_dosen->id_dosen;
        $Kaprodi = Pimpinan::with('r_dosen')
            ->where('dosen_id', $getDosen)
            ->whereHas('r_jabatan_pimpinan', function ($query) {
                $query->where('kode_jabatan_pimpinan', 'Kaprodi');
            })
            ->whereHas('r_dosen', function ($query) {
                $query->where('prodi_id', Auth::user()->r_dosen->prodi_id);
            })
            ->first();

        if (!$Kaprodi) {
            abort(404, 'Data Kaprodi tidak ditemukan.');
        }

        $data_sidang_pkl = MahasiswaPkl::with(['r_pkl.r_mahasiswa.r_prodi', 'r_dosen_pembimbing', 'r_dosen_penguji', 'r_sesi', 'r_ruang'])
        ->where('id_mhs_pkl', $id)
        ->first();

    if (!$data_sidang_pkl) {
        abort(404, 'Data Mahasiswa PKL tidak ditemukan.');
    }


        if (!$data_sidang_pkl) {
            abort(404, 'Data Mahasiswa PKL tidak ditemukan.');
        }

        $pembimbingList = collect([$data_sidang_pkl->r_dosen_pembimbing]);
        $pengujiList = collect([$data_sidang_pkl->r_dosen_penguji]);

        $data_ruangan = Ruang::all();
        $dosen_penyidang = Dosen::all();
        $jam_sidang = Sesi::all();

        $pdf = Pdf::loadView('admin.content.pkl.pdf.surat_tugas_pkl', [
            'data_sidang_pkl' => $data_sidang_pkl,
            'Kaprodi' => $Kaprodi,
            'data_ruangan' => $data_ruangan,
            'dosen_penyidang' => $dosen_penyidang,
            'jam_sidang' => $jam_sidang,
            'pembimbingList' => $pembimbingList,
            'pengujiList' => $pengujiList,
        ]);

        // return $pdf->stream('Surat_Tugas_PKL.pdf');
        return $pdf->download('Surat_Tugas_PKL.pdf');
    }
}
