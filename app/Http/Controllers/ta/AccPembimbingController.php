<?php

namespace App\Http\Controllers\Ta;

use App\Models\Dosen;
use App\Models\MahasiswaTa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccPembimbingController extends Controller
{
    public function index()
    {
        $data_mahasiswa_ta = MahasiswaTa::all();

        return view('admin.content.ta.pembimbing.acc_mahasiswa', compact('data_mahasiswa_ta'));
    }


    public function update_pembimbing_satu(Request $request, $id)
    {

        // dd($request->all());

        $request->validate([
            'acc_pembimbing_satu' => 'required|in:0,1',
        ]);

        $mahasiswa_ta = MahasiswaTa::findOrFail($id);
        $mahasiswa_ta->acc_pembimbing_satu = $request->acc_pembimbing_satu;
        $mahasiswa_ta->save();

        return redirect()->back()->with('success', 'Data berhasil diAcc');
    }

    public function update_pembimbing_dua(Request $request, $id)
    {

        // dd($request->all());

        $request->validate([
            'acc_pembimbing_dua' => 'required|in:0,1',
        ]);

        $mahasiswa_ta = MahasiswaTa::findOrFail($id);
        $mahasiswa_ta->acc_pembimbing_dua = $request->acc_pembimbing_dua;
        $mahasiswa_ta->save();

        return redirect()->back()->with('success', 'Data berhasil DiAcc');
    }

}
