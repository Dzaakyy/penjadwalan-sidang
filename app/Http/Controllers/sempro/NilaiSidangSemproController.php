<?php

namespace App\Http\Controllers\Sempro;

use App\Models\NilaiSempro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MahasiswaSempro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NilaiSidangSemproController extends Controller
{
    public function index()
    {
        $dosen_penilai = Auth::user()->r_dosen->id_dosen;

        $data_dosen_sempro = MahasiswaSempro::with([
            'r_mahasiswa',
            'r_pembimbing_satu',
            'r_pembimbing_dua',
            'r_penguji',
            'r_nilai_pembimbing_satu' => function ($query) {
                $query->where('status', '0');
            },
            'r_nilai_pembimbing_dua' => function ($query) {
                $query->where('status', '1');
            },
            'r_nilai_penguji' => function ($query) {
                $query->where('status', '2');
            },
        ])
            ->where(function ($query) use ($dosen_penilai) {
                $query->where('pembimbing_satu', $dosen_penilai)
                    ->orWhere('pembimbing_dua', $dosen_penilai)
                    ->orWhere('penguji', $dosen_penilai);
            })

            ->distinct('mahasiswa_id')
            ->get();



        $data_dosen_penguji_sempro = MahasiswaSempro::with([
            'r_mahasiswa',
            'r_penguji',
            'r_nilai_penguji' => function ($query) {
                $query->where('status', '2');
            }
        ])
            ->where('penguji', $dosen_penilai)
            ->distinct('mahasiswa_id')
            ->get();

        $data_nilai_sidang_sempro = $data_dosen_penguji_sempro->merge($data_dosen_sempro);

        $nextNumber = $this->getCariNomor();

        $mahasiswaSempro = $data_dosen_sempro->first();
        $isPembimbingSatu = $mahasiswaSempro && $mahasiswaSempro->pembimbing_satu == $dosen_penilai;
        $isPembimbingDua = $mahasiswaSempro && $mahasiswaSempro->pembimbing_dua == $dosen_penilai;
        $isPenguji = $mahasiswaSempro && $mahasiswaSempro->penguji == $dosen_penilai;

        return view('admin.content.sempro.sidang.sidang_sempro', compact('data_nilai_sidang_sempro', 'isPenguji', 'data_dosen_sempro', 'data_dosen_penguji_sempro', 'isPembimbingSatu', 'isPembimbingDua', 'mahasiswaSempro', 'nextNumber'));
    }


    public function nilai_sidang_sempro(Request $request, string $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id_nilai_sempro' => 'required',
            'sempro_id' => 'required|exists:mhs_sempro,id_sempro',
            'pendahuluan' => 'required',
            'tinjauan_pustaka' => 'required',
            'metodologi' => 'required',
            'penggunaan_bahasa' => 'required',
            'presentasi' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $nilai_sempro =
            (($request->pendahuluan) +
            ($request->tinjauan_pustaka) +
            ($request->metodologi) +
            ($request->penggunaan_bahasa) +
            ($request->presentasi)) / 5
            ;


        $nilai_mahasiswaSempro = NilaiSempro::updateOrCreate(
            ['id_nilai_sempro' => $request->id_nilai_sempro],
            [
                'sempro_id' => $request->sempro_id,
                'pendahuluan' => $request->pendahuluan,
                'tinjauan_pustaka' => $request->tinjauan_pustaka,
                'metodologi' => $request->metodologi,
                'penggunaan_bahasa' => $request->penggunaan_bahasa,
                'presentasi' => $request->presentasi,
                'nilai_sempro' => $nilai_sempro,
                'status' => $request->status,
            ]
        );

        $message = $nilai_mahasiswaSempro->wasRecentlyCreated
            ? 'Nilai berhasil disimpan!'
            : 'Nilai berhasil diperbarui!';

        return redirect()->route('nilai_sidang_sempro')
            ->with('success', $message);
    }


    function getCariNomor()
    {
        $id_nilai_sempro = NilaiSempro::pluck('id_nilai_sempro')->toArray();
        for ($i = 1;; $i++) {

            if (!in_array($i, $id_nilai_sempro)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
