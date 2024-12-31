<?php

namespace App\Http\Controllers\Ta;

use App\Models\MahasiswaTa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KeteranganMahasiswaController extends Controller
{


    public function checkPengujiTaKetua()
    {
        $dosenId = Auth::user()->r_dosen->id_dosen;
        $isKetua = MahasiswaTa::where('ketua', $dosenId)->exists();
        return $isKetua;
    }

    public function index()
    {
        $dosenId = Auth::user()->r_dosen->id_dosen;

        $data_mahasiswa_ta = MahasiswaTa::where('ketua', $dosenId)->get();


        return view('admin.content.ta.sidang.Keterangan_kelulusan', compact('data_mahasiswa_ta'));
    }


    public function update(Request $request, $id)
    {

        // dd($request->all());

        $request->validate([
            'keterangan' => 'required|in:0,1,2',
        ]);

        $mahasiswa_ta = MahasiswaTa::findOrFail($id);
        $mahasiswa_ta->keterangan = $request->keterangan;
        if ($request->keterangan == 1) {
            $mahasiswa_ta->tanggal_ta = null;
            $mahasiswa_ta->ruangan_id = null;
            $mahasiswa_ta->sesi_id = null;
        }
        $mahasiswa_ta->save();

        return redirect()->back()->with('success', 'Keterangan berhasil diberikan');
    }
}
