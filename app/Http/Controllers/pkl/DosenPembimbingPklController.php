<?php

namespace App\Http\Controllers\pkl;

use App\Models\BimbinganPkl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MahasiswaPkl;
use App\Models\NilaiBimbinganPkl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DosenPembimbingPklController extends Controller
{

    public function index()
    {
        $dosenPembimbingId = Auth::user()->r_dosen->id_dosen;

        $data_dosen_bimbingan_pkl = MahasiswaPkl::with(['r_pkl.r_mahasiswa', 'r_pkl.r_perusahaan','r_nilai_bimbingan'])
            ->whereHas('r_pkl', function ($query) use ($dosenPembimbingId) {
                $query->where('dosen_pembimbing', $dosenPembimbingId);
            })
            ->distinct('mahasiswa_id')
            ->get();
            $nextNumber = $this->getCariNomor();


        return view('admin.content.pkl.pembimbing.bimbingan_pkl', compact('data_dosen_bimbingan_pkl','nextNumber'));
    }






    // public function detail()
    // {
    //     $dosenPembimbingId = Auth::user()->r_dosen->id_dosen;
    //     $data_dosen_bimbingan_pkl = BimbinganPkl::whereHas('r_pkl', function ($query) use ($dosenPembimbingId) {
    //         $query->where('dosen_pembimbing', $dosenPembimbingId);
    //     })->get();

    //     return view('admin.content.pkl.pembimbing.dosen_bimbingan_pkl', compact('data_dosen_bimbingan_pkl'));
    // }

    public function detail($id)
    {
        $data_bimbingan = BimbinganPkl::where('pkl_id', $id)
            ->with(['r_pkl.r_usulan_pkl.r_mahasiswa'])
            ->get();

            $data_mahasiswa = MahasiswaPkl::all();

        return view('admin.content.pkl.pembimbing.dosen_bimbingan_pkl', compact('data_bimbingan', 'data_mahasiswa'));
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


        $mhs_pkl = BimbinganPkl::find($id);

        if ($mhs_pkl) {

            $mhs_pkl->komentar = $request->komentar;
            $mhs_pkl->status = $request->status;
            $mhs_pkl->save();


            return redirect()->route('dosen_bimbingan_pkl.detail', ['id' => $mhs_pkl->pkl_id])
                         ->with('success', 'Bimbingan PKL berhasil diverifikasi!');
        } else {
            return redirect()->back()->withErrors(['msg' => 'Bimbingan PKL tidak ditemukan.']);
        }
    }


    public function nilai_bimbingan_pkl(Request $request, string $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id_nilai_bimbingan_pkl' => 'required',
            'keaktifan' => 'required',
            'komunikatif' => 'required',
            'problem_solving' => 'required',
            'mhs_pkl_id' => 'required|exists:mhs_pkl,id_mhs_pkl',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $nilai_bimbingan =
        ($request->keaktifan * 0.3) +
        ($request->komunikatif * 0.3) +
        ($request->problem_solving * 0.4);


        $mhs_pkl = NilaiBimbinganPkl::updateOrCreate(
            ['id_nilai_bimbingan_pkl' => $request->id_nilai_bimbingan_pkl],
            [
                'mhs_pkl_id' => $request->mhs_pkl_id,
                'keaktifan' => $request->keaktifan,
                'komunikatif' => $request->komunikatif,
                'problem_solving' => $request->problem_solving,
                'nilai_bimbingan' => $nilai_bimbingan,
            ]
        );

        $message = $mhs_pkl->wasRecentlyCreated
        ? 'Nilai berhasil disimpan!'
        : 'Nilai berhasil diperbarui!';

        return redirect()->route('dosen_bimbingan_pkl')
                         ->with('success', $message);
    }


    function getCariNomor()
    {
        $id_nilai_bimbingan_pkl = NilaiBimbinganPkl::pluck('id_nilai_bimbingan_pkl')->toArray();
        for ($i = 1;; $i++) {

            if (!in_array($i, $id_nilai_bimbingan_pkl)) {
                return $i;
                break;
            }
        }
        return $i;
    }


}
