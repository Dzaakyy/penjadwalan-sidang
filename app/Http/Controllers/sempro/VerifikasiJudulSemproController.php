<?php

namespace App\Http\Controllers\Sempro;

use App\Models\Dosen;
use App\Models\Pimpinan;
use Illuminate\Http\Request;
use App\Models\MahasiswaSempro;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VerifikasiJudulSemproController extends Controller
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

        $data_mahasiswa_sempro = MahasiswaSempro::with('r_mahasiswa', 'r_pembimbing_satu', 'r_pembimbing_dua', 'r_penguji', 'r_ruangan', 'r_sesi')
            ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->orderByDesc('id_sempro')
            ->get();
        $dosen = Dosen::all();
        // $data_mahasiswa_sempro = MahasiswaSempro::all();

        return view('admin.content.sempro.kaprodi.verifikasi_judul', compact('data_mahasiswa_sempro', 'dosen'));
    }




    public function update(Request $request, $id)
    {
        $request->validate([
            'status_judul' => 'required|in:1,2',
            'pembimbing_satu' => $request->status_judul == 2 ? 'required|exists:dosen,id_dosen' : 'nullable',
            'pembimbing_dua' => $request->status_judul == 2 ? 'required|exists:dosen,id_dosen|different:pembimbing_satu' : 'nullable',
        ]);

        $mahasiswa_sempro = MahasiswaSempro::findOrFail($id);

        $mahasiswa_sempro->status_judul = $request->status_judul;
        $mahasiswa_sempro->pembimbing_satu = $request->status_judul == 2 ? $request->pembimbing_satu : null;
        $mahasiswa_sempro->pembimbing_dua = $request->status_judul == 2 ? $request->pembimbing_dua : null;
        $mahasiswa_sempro->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }
}
