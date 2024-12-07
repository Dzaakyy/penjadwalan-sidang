<?php

namespace App\Http\Controllers\pkl;

use App\Models\Mahasiswa;
use App\Models\TempatPkl;
use App\Models\UsulanPkl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UsulanPklController extends Controller
{
    public function index()
    {
        $data_perusahaan = TempatPkl::with('r_usulan_pkl')->get();
        $nextNumber = $this->getCariNomor();
        $data_usulan_pkl = UsulanPkl::with('r_mahasiswa', 'r_perusahaan')
            ->orderByDesc('id_usulan_pkl')
            ->get();
        return view('admin.content.pkl.mahasiswa.usulan_pkl', compact('data_usulan_pkl', 'data_perusahaan', 'nextNumber'));
    }


    // public function create()
    // {
    //     $data_mahasiswa = Mahasiswa::all();
    //     $data_perusahaan = TempatPkl::all();
    //     $nextNumber = $this->getCariNomor();

    //     return view('admin.content.pkl.mahasiswa.usulan_pkl', compact('data_mahasiswa', 'data_perusahaan', 'nextNumber'));
    // }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_usulan_pkl' => 'required|unique:usulan_pkl,id_usulan_pkl',
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mahasiswa',
            'perusahaan_id' => 'required|exists:tempat_pkl,id_perusahaan',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        UsulanPkl::create([
            'id_usulan_pkl' => $request->id_usulan_pkl,
            'mahasiswa_id' => $request->mahasiswa_id,
            'perusahaan_id' => $request->perusahaan_id,
        ]);


        return redirect()->route('usulan_pkl')->with('success', 'Pendaftaran PKL berhasil!');
    }


    // public function edit(string $id)
    // {
    //     $data_usulan_pkl = UsulanPkl::where('id_usulan_pkl', $id)->first();
    //     $data_mahasiswa = Mahasiswa::all();
    //     $data_perusahaan = TempatPkl::all();
    //     return view('admin.content.pkl.mahasiswa.form.usulan_pkl_edit', compact('data_usulan_pkl' ,'data_mahasiswa', 'data_perusahaan'));
    // }


    // public function update(Request $request, string $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id_usulan_pkl' => 'required',
    //         'mahasiswa_id' => 'required',
    //         'perusahaan_id' => 'required',
    //     ],

    // );

    //     if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

    //     $data = [
    //         'id_usulan_pkl' => $request->id_usulan_pkl,
    //         'mahasiswa_id' => $request->mahasiswa_id,
    //         'perusahaan_id' => $request->perusahaan_id,
    //     ];
    //     $usulan_pkl = UsulanPkl::find($id);

    //     if ($usulan_pkl) {
    //         $usulan_pkl->update($data);
    //     }
    //     return redirect()->route('usulan_pkl');
    // }


    public function delete(string $id)
    {
        $data_usulan_pkl = UsulanPkl::where('id_usulan_pkl', $id)->first();
        if ($data_usulan_pkl) {
            $data_usulan_pkl->delete();
        }
        return redirect()->route('usulan_pkl');
    }


    function getCariNomor()
    {
        $id_usulan_pkl = UsulanPkl::pluck('id_usulan_pkl')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_usulan_pkl)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
 