<?php

namespace App\Http\Controllers\Ta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BimbinganTa;
use App\Models\MahasiswaTa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DosenBimbinganController extends Controller
{
    public function index()
    {
        $dosenPembimbingId = Auth::user()->r_dosen->id_dosen;

        $data_dosen_bimbingan_ta = MahasiswaTa::with(['r_mahasiswa'])
        ->whereHas('r_pembimbing_satu', function ($query) use ($dosenPembimbingId) {
            $query->where('id_dosen', $dosenPembimbingId);
        })
        ->orWhereHas('r_pembimbing_dua', function ($query) use ($dosenPembimbingId) {
            $query->where('id_dosen', $dosenPembimbingId);
        })
        ->distinct('mahasiswa_id')
        ->get();



        return view('admin.content.ta.pembimbing.bimbingan_ta', compact('data_dosen_bimbingan_ta'));
    }

    public function detail($id)
    {
        $dosenPembimbingId = Auth::user()->r_dosen->id_dosen;

        $data_bimbingan = BimbinganTa::where('ta_id', $id)
            ->where('dosen_id', $dosenPembimbingId)
            ->with(['r_ta.r_mahasiswa'])
            ->get();

        $data_mahasiswa = MahasiswaTa::where('pembimbing_satu_id', $dosenPembimbingId)
            ->orWhere('pembimbing_dua_id', $dosenPembimbingId)
            ->get();

        return view('admin.content.ta.pembimbing.bimbingan_ta_detail', compact('data_bimbingan', 'data_mahasiswa'));
    }




    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'komentar' => 'required',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $mhs_ta = BimbinganTa::find($id);

        if ($mhs_ta) {

            $mhs_ta->komentar = $request->komentar;
            $mhs_ta->status = $request->status;
            $mhs_ta->save();


            return redirect()->route('dosen_bimbingan_ta.detail', ['id' => $mhs_ta->ta_id])
                         ->with('success', 'Bimbingan ta berhasil diverifikasi!');
        } else {
            return redirect()->back()->withErrors(['msg' => 'Bimbingan ta tidak ditemukan.']);
        }
    }



    public function update_pembimbing_satu(Request $request, $id)
    {

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

        $request->validate([
            'acc_pembimbing_dua' => 'required|in:0,1',
        ]);

        $mahasiswa_ta = MahasiswaTa::findOrFail($id);
        $mahasiswa_ta->acc_pembimbing_dua = $request->acc_pembimbing_dua;
        $mahasiswa_ta->save();

        return redirect()->back()->with('success', 'Data berhasil DiAcc');
    }
}
