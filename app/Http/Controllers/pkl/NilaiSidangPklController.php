<?php

namespace App\Http\Controllers\pkl;

use App\Models\MahasiswaPkl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NilaiPkl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NilaiSidangPklController extends Controller
{
    public function index()
    {
        $dosen_penilai = Auth::user()->r_dosen->id_dosen;

        $data_dosen_pembimbing_pkl = MahasiswaPkl::with([
            'r_pkl.r_mahasiswa',
            'r_dosen_pembimbing',
            'r_dosen_penguji',
            'r_nilai_pembimbing' => function ($query) {
                $query->where('status', '0');
            },
            'r_nilai_penguji' => function ($query) {
                $query->where('status', '1');
            },
        ])
            ->where(function ($query) use ($dosen_penilai) {
                $query->where('dosen_pembimbing', $dosen_penilai)
                    ->orWhere('dosen_penguji', $dosen_penilai);
            })
            ->distinct('mahasiswa_id')
            ->get();



        $data_dosen_penguji_pkl = MahasiswaPkl::with([
            'r_pkl.r_mahasiswa',
            'r_dosen_penguji',
            'r_nilai_penguji' => function ($query) {
                $query->where('status', '1');
            }
        ])
            ->where('dosen_penguji', $dosen_penilai)
            ->distinct('mahasiswa_id')
            ->get();

        $data_nilai_sidang_pkl = $data_dosen_penguji_pkl->merge($data_dosen_pembimbing_pkl);



        $nextNumber = $this->getCariNomor();

        $mahasiswaPkl = $data_dosen_pembimbing_pkl->first();
        $isPembimbing = $mahasiswaPkl && $mahasiswaPkl->dosen_pembimbing == $dosen_penilai;
        $isPenguji = $mahasiswaPkl && $mahasiswaPkl->dosen_penguji == $dosen_penilai;


        return view('admin.content.pkl.sidang_pkl.nilai_sidang_pkl', compact('data_nilai_sidang_pkl', 'isPenguji', 'data_dosen_pembimbing_pkl', 'data_dosen_penguji_pkl', 'isPembimbing', 'mahasiswaPkl', 'nextNumber'));
    }


    public function nilai_sidang_pkl(Request $request, string $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id_nilai_pkl' => 'required',
            'mhs_pkl_id' => 'required|exists:mhs_pkl,id_mhs_pkl',
            'bahasa' => 'required',
            'analisis' => 'required',
            'sikap' => 'required',
            'komunikasi' => 'required',
            'penyajian' => 'required',
            'penguasaan' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $nilai_pkl =
            ($request->bahasa * 0.15) +
            ($request->analisis * 0.15) +
            ($request->sikap * 0.15) +
            ($request->komunikasi * 0.15) +
            ($request->penyajian * 0.15) +
            ($request->penguasaan * 0.25);


        $nilai_mahasiswaPkl = NilaiPkl::updateOrCreate(
            ['id_nilai_pkl' => $request->id_nilai_pkl],
            [
                'mhs_pkl_id' => $request->mhs_pkl_id,
                'bahasa' => $request->bahasa,
                'analisis' => $request->analisis,
                'sikap' => $request->sikap,
                'komunikasi' => $request->komunikasi,
                'penyajian' => $request->penyajian,
                'penguasaan' => $request->penguasaan,
                'nilai_pkl' => $nilai_pkl,
                'status' => $request->status,
            ]
        );

        $message = $nilai_mahasiswaPkl->wasRecentlyCreated
            ? 'Nilai berhasil disimpan!'
            : 'Nilai berhasil diperbarui!';

        return redirect()->route('nilai_sidang_pkl')
            ->with('success', $message);
    }





    function getCariNomor()
    {
        $id_nilai_pkl = NilaiPkl::pluck('id_nilai_pkl')->toArray();
        for ($i = 1;; $i++) {

            if (!in_array($i, $id_nilai_pkl)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
