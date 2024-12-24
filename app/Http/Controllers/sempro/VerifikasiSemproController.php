<?php

namespace App\Http\Controllers\Sempro;

use App\Models\Sesi;
use App\Models\User;
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
        $data_mahasiswa_sempro = MahasiswaSempro::all();

        return view('admin.content.sempro.admin.verifikasi_sempro', compact('data_mahasiswa_sempro'));
    }




    public function update(Request $request, $id)
    {

        // dd($request->all());

        $request->validate([
            'status_berkas' => 'required|in:0,1',
        ]);

        $mahasiswa_sempro = MahasiswaSempro::findOrFail($id);
        $mahasiswa_sempro->status_berkas = $request->status_berkas;
        $mahasiswa_sempro->save();

        return redirect()->back()->with('success', 'Data berhasil diverifikasi');
    }



}
