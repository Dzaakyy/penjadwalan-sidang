<?php

namespace App\Http\Controllers\Sempro;

use Illuminate\Http\Request;
use App\Models\MahasiswaSempro;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DaftarSemproController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->r_mahasiswa;
        $nextNumber = $this->getCariNomor();

        $data_mahasiswa_sempro = MahasiswaSempro::all();
        return view('admin.content.sempro.mahasiswa.daftar_sempro', compact('data_mahasiswa_sempro', 'nextNumber', 'mahasiswa'));
    }



    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id_sempro' => 'required',
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mahasiswa',
            'judul' => 'required',
            'file_sempro' => 'required|file',
        ]);


        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);


        $data = [
            'id_sempro' => $request->id_sempro,
            'mahasiswa_id' => $request->mahasiswa_id,
            'judul' => $request->judul,
            'file_sempro' => $request->file_sempro,
        ];

        if ($request->hasFile('file_sempro')) {
            $file = $request->file('file_sempro');
            $filename = $file->getClientOriginalName();
            $path = 'public/uploads/mahasiswa/sempro/';
            $file->storeAs($path, $filename);

            $data['file_sempro'] = $filename;
        }
        MahasiswaSempro::create($data);
        return redirect()->route('daftar_sempro')->with('success', 'Data berhasil ditambahkan.');
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_sempro' => 'required',
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mahasiswa',
            'judul' => 'required'

        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_sempro' => $request->id_sempro,
            'mahasiswa_id' => $request->mahasiswa_id,
            'judul' => $request->judul,
        ];


        $oldData = MahasiswaSempro::where('id_sempro', $id)->first();
        if ($oldData->file_sempro !== null && $request->hasFile('file_sempro')) {
            Storage::delete('public/uploads/mahasiswa/sempro/' . $oldData->file_sempro);
        }


        if ($request->hasFile('file_sempro')) {
            $file = $request->file('file_sempro');
            $filename = $file->getClientOriginalName();
            $path = 'public/uploads/mahasiswa/sempro/';
            $file->storeAs($path, $filename);

            $data['file_sempro'] = $filename;
        }

        $mhs_sempro = MahasiswaSempro::find($id);
        if ($mhs_sempro) {
            $mhs_sempro->update($data);
        }

        return redirect()->route('daftar_sempro')->with('success', 'Data berhasil diganti.');
    }




    public function delete(string $id)
    {
        $data_sempro = MahasiswaSempro::where('id_sempro', $id)->first();
        if ($data_sempro && $data_sempro->file_sempro) {
            Storage::delete('public/uploads/mahasiswa/sempro/' . $data_sempro->file_sempro);
        }

        if ($data_sempro) {
            $data_sempro->delete();
        }
        return redirect()->route('daftar_sempro')->with('success', 'Data berhasil dihapus.');
    }

    function getCariNomor()
    {
        $id_sempro = MahasiswaSempro::pluck('id_sempro')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_sempro)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
