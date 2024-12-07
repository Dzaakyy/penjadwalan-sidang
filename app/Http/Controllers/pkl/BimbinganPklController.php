<?php

namespace App\Http\Controllers\pkl;

use App\Models\BimbinganPkl;
use App\Models\MahasiswaPkl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BimbinganPklController extends Controller
{
    public function index()
    {

        $mahasiswa = Auth::user()->r_mahasiswa;
        $nextNumber = $this->getCariNomor();
        $data_bimbingan_pkl = BimbinganPkl::with('r_pkl.r_pkl.r_mahasiswa')->get();
        // dd($data_bimbingan_pkl->toArray());

        return view('admin.content.pkl.mahasiswa.bimbingan_pkl', compact('data_bimbingan_pkl', 'nextNumber','mahasiswa'));
    }


    public function create()
    {
        $mahasiswa = Auth::user()->r_mahasiswa;
        $nextNumber = $this->getCariNomor();


        $pkl_id = optional($mahasiswa->usulan_pkl->mhs_pkl)->id_mhs_pkl;

        if (!$pkl_id) {
            return redirect()->back()->with('error', 'Data PKL untuk mahasiswa ini tidak ditemukan.');
        }

        $data_pkl = BimbinganPkl::all();

        return view('admin.content.pkl.mahasiswa.form.bimbingan_pkl_form', compact('data_pkl', 'nextNumber', 'pkl_id'));
    }



    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id_bimbingan_pkl' => 'required',
            'pkl_id' => 'required|exists:mhs_pkl,id_mhs_pkl',
            'tgl_kegiatan_awal' => 'required',
            'tgl_kegiatan_akhir' => 'required',
            'kegiatan' => 'required',
            'file_dokumentasi' => 'required',

        ]);


        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);


        $data = [
            'id_bimbingan_pkl' => $request->id_bimbingan_pkl,
            'pkl_id' => $request->pkl_id,
            'tgl_kegiatan_awal' => $request->tgl_kegiatan_awal,
            'tgl_kegiatan_akhir' => $request->tgl_kegiatan_akhir,
            'kegiatan' => $request->kegiatan,
            'file_dokumentasi' => $request->file_dokumentasi,
        ];

        if ($request->hasFile('file_dokumentasi')) {
            $file = $request->file('file_dokumentasi');
            $filename = $file->getClientOriginalName();
            $path = 'public/uploads/mahasiswa/bimbingan/';
            $file->storeAs($path, $filename);

            $data['file_dokumentasi'] = $filename;
        }


        BimbinganPkl::create($data);
        return redirect()->route('bimbingan_pkl')->with('success', 'Data berhasil ditambahkan.');
    }



    public function edit(string $id)
    {

        $data_pkl = BimbinganPkl::where('id_bimbingan_pkl', $id)->firstOrFail();
        // dd($data_pkl);
        return view('admin.content.pkl.mahasiswa.form.bimbingan_pkl_edit', compact('data_pkl'));
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_bimbingan_pkl' => 'required',
            'pkl_id' => 'required|exists:mhs_pkl,id_mhs_pkl',
            'tgl_kegiatan_awal' => 'required',
            'tgl_kegiatan_akhir' => 'required',
            'kegiatan' => 'required',

        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_bimbingan_pkl' => $request->id_bimbingan_pkl,
            'pkl_id' => $request->pkl_id,
            'tgl_kegiatan_awal' => $request->tgl_kegiatan_awal,
            'tgl_kegiatan_akhir' => $request->tgl_kegiatan_akhir,
            'kegiatan' => $request->kegiatan,
        ];


        $oldData = BimbinganPkl::where('id_bimbingan_pkl', $id)->first();
        if ($oldData->file_dokumentasi !== null && $request->hasFile('file_dokumentasi')) {
            Storage::delete('public/uploads/mahasiswa/bimbingan/' . $oldData->file_dokumentasi);
        }


        if ($request->hasFile('file_dokumentasi')) {
            $file = $request->file('file_dokumentasi');
            $filename = $file->getClientOriginalName();
            $path = 'public/uploads/mahasiswa/bimbingan/';
            $file->storeAs($path, $filename);

            $data['file_dokumentasi'] = $filename;
        }

        $bimbingan_pkl = BimbinganPkl::find($id);
        if ($bimbingan_pkl) {
            $bimbingan_pkl->update($data);
        }

        return redirect()->route('bimbingan_pkl')->with('success', 'Data berhasil diganti.');
    }



    public function verif(Request $request, string $id)
    {

        $validator = Validator::make($request->all(), [
            'nilai_pembimbing_industri' => 'required',
            'status_admin' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $mhs_pkl = MahasiswaPkl::find($id);

        if ($mhs_pkl) {
            $mhs_pkl->nilai_pembimbing_industri = $request->nilai_pembimbing_industri;
            $mhs_pkl->status_admin = $request->status_admin;
            $mhs_pkl->save();
        } else {
            return redirect()->back()->withErrors(['msg' => 'Verifikasi PKL tidak ditemukan.']);
        }

        return redirect()->route('verif_pkl')->with('success', 'Berkas PKL berhasil diverifikasi!');
    }


    public function delete(string $id)
    {
        $data_pkl = BimbinganPkl::where('id_bimbingan_pkl', $id)->first();
        if ($data_pkl && $data_pkl->laporan_pkl) {
            Storage::delete('public/uploads/mahasiswa/bimbingan/' . $data_pkl->file_dokumentasi);
        }

        if ($data_pkl) {
            $data_pkl->delete();
        }
        return redirect()->route('bimbingan_pkl')->with('success', 'Data berhasil dihapus.');
    }


    function getCariNomor()
    {
        $id_bimbingan_pkl = BimbinganPkl::pluck('id_bimbingan_pkl')->toArray();
        for ($i = 1;; $i++) {

            if (!in_array($i, $id_bimbingan_pkl)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
