<?php

namespace App\Http\Controllers\pkl;

use App\Models\VerPkl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MahasiswaPkl;
use Illuminate\Support\Facades\Validator;

class VerifPklController extends Controller
{
    public function index()
    {
        $nextNumber = $this->getCariNomor();
        $data_mhs_pkl = MahasiswaPkl::all();
        return view('admin.content.pkl.admin.ver_berkas_pkl', compact('data_mhs_pkl','nextNumber'));
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


    function getCariNomor()
    {
        $id_mhs_pkl = MahasiswaPkl::pluck('id_mhs_pkl')->toArray();
        for ($i = 1;; $i++) {

            if (!in_array($i, $id_mhs_pkl)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
