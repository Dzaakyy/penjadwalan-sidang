<?php

namespace App\Http\Controllers\pkl;

use App\Models\MahasiswaPkl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MahasiswaPklController extends Controller
{
    public function index()
    {
        $nextNumber = $this->getCariNomor();
        $data_mahasiswa_pkl = MahasiswaPkl::all();
        return view('admin.content.pkl.mahasiswa.daftar_sidang_pkl', compact('data_mahasiswa_pkl', 'nextNumber'));
    }


    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'pembimbing_pkl' => 'required',
            'dokumen_nilai_industri' => 'required|file',
            'laporan_pkl' => 'required|file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $oldData = MahasiswaPkl::where('id_mhs_pkl', $id)->first();

        $data = [
            'judul' => $request->judul,
            'pembimbing_pkl' => $request->pembimbing_pkl,
        ];

        if ($request->hasFile('dokumen_nilai_industri')) {
            if ($oldData->dokumen_nilai_industri) {
                Storage::delete('public/uploads/mahasiswa/dokumen_nilai_industri/' . $oldData->dokumen_nilai_industri);
            }

            $file = $request->file('dokumen_nilai_industri');
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/uploads/mahasiswa/dokumen_nilai_industri', $filename);
            $data['dokumen_nilai_industri'] = $filename;
        }

        if ($request->hasFile('laporan_pkl')) {
            if ($oldData->laporan_pkl) {
                Storage::delete('public/uploads/mahasiswa/laporan_pkl/' . $oldData->laporan_pkl);
            }

            $file = $request->file('laporan_pkl');
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/uploads/mahasiswa/laporan_pkl', $filename);
            $data['laporan_pkl'] = $filename;
        }

        $daftar_sidang = MahasiswaPkl::find($id);
        if ($daftar_sidang) {
            $daftar_sidang->update($data);
        }

        return redirect()->route('daftar_sidang')->with('success', 'Data berhasil diupload.');
    }



    public function delete(string $id)
    {
        $data_mahasiswa_pkl = MahasiswaPkl::where('id_mhs_pkl', $id)->first();
        if ($data_mahasiswa_pkl) {
            $data_mahasiswa_pkl->delete();
        }
        return redirect()->route('usulan_pkl');
    }


    function getCariNomor()
    {
        $id_mahasiswa_pkl = MahasiswaPkl::pluck('id_mhs_pkl')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_mahasiswa_pkl)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
