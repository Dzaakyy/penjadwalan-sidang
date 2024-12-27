<?php

namespace App\Http\Controllers\Ta;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaTa;
use Illuminate\Http\Request;

class VerifikasiBerkasTAController extends Controller
{
    public function index()
    {
        $data_mahasiswa_ta = MahasiswaTa::all();

        return view('admin.content.ta.admin.verifikasi_ta', compact('data_mahasiswa_ta'));
    }




    public function update(Request $request, $id)
    {

        // dd($request->all());

        $request->validate([
            'status_berkas' => 'required|in:0,1',
        ]);

        $mahasiswa_ta = MahasiswaTa::findOrFail($id);
        $mahasiswa_ta->status_berkas = $request->status_berkas;
        $mahasiswa_ta->save();

        return redirect()->back()->with('success', 'Data berhasil diverifikasi');
    }

}
