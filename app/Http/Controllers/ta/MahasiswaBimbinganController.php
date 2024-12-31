<?php

namespace App\Http\Controllers\Ta;

use App\Models\BimbinganTa;
use App\Models\MahasiswaTa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MahasiswaBimbinganController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->r_mahasiswa;
        $nextNumber = $this->getCariNomor();
        $data_bimbingan_ta = BimbinganTa::with('r_ta.r_mahasiswa')->get();
        $mahasiswa_ta = MahasiswaTa::with('r_mahasiswa')->get();
        $ta_id = optional($mahasiswa->r_ta)->id_ta;
        $isMahasiswaInTa = MahasiswaTa::where('mahasiswa_id', $mahasiswa->id_mahasiswa)->exists();
        // dd($ta_id);
        $dosen_pembimbing = [];
        if ($mahasiswa->r_ta) {
            $dosen_pembimbing[] = $mahasiswa->r_ta->r_pembimbing_satu;
            $dosen_pembimbing[] = $mahasiswa->r_ta->r_pembimbing_dua;
        }
        return view('admin.content.ta.mahasiswa.bimbingan_ta', compact('isMahasiswaInTa','mahasiswa_ta', 'dosen_pembimbing', 'data_bimbingan_ta', 'nextNumber', 'mahasiswa', 'ta_id'));
    }


    public function store(Request $request)
    {

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id_bimbingan_ta' => 'required',
            'ta_id' => 'required|exists:mhs_ta,id_ta',
            'dosen_id' => 'required|exists:dosen,id_dosen',
            'tgl_bimbingan' => 'required',
            'pembahasan' => 'required',
            'file_bimbingan' => 'required',

        ]);


        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);



        $data = [
            'id_bimbingan_ta' => $request->id_bimbingan_ta,
            'ta_id' => $request->ta_id,
            'dosen_id' => $request->dosen_id,
            'tgl_bimbingan' => $request->tgl_bimbingan,
            'pembahasan' => $request->pembahasan,
            'file_bimbingan' => $request->file_bimbingan,
        ];


        if ($request->hasFile('file_bimbingan')) {
            $file = $request->file('file_bimbingan');
            $filename = $file->getClientOriginalName();
            $path = 'public/uploads/mahasiswa/bimbingan/ta/';
            $file->storeAs($path, $filename);

            $data['file_bimbingan'] = $filename;
        }


        BimbinganTa::create($data);
        return redirect()->route('bimbingan_ta')->with('success', 'Data berhasil ditambahkan.');
    }


    function getCariNomor()
    {
        $id_bimbingan_ta = BimbinganTa::pluck('id_bimbingan_ta')->toArray();
        for ($i = 1;; $i++) {

            if (!in_array($i, $id_bimbingan_ta)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
