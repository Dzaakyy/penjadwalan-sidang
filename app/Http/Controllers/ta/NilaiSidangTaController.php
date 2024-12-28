<?php

namespace App\Http\Controllers\Ta;

use App\Models\NilaiTa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MahasiswaTa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class NilaiSidangTaController extends Controller
{
    public function index()
    {
        $dosen_penilai = Auth::user()->r_dosen->id_dosen;

        $data_dosen_ta = MahasiswaTa::with([
            'r_mahasiswa',
            'r_pembimbing_satu',
            'r_pembimbing_dua',
            'r_ketua',
            'r_sekretaris',
            'r_penguji_1',
            'r_penguji_2',
            'r_nilai_ketua' => function ($query) {
                $query->where('status', '0');
            },
            'r_nilai_sekretaris' => function ($query) {
                $query->where('status', '1');
            },
            'r_nilai_penguji_1' => function ($query) {
                $query->where('status', '2');
            },
            'r_nilai_penguji_2' => function ($query) {
                $query->where('status', '3');
            },
        ])
            ->where(function ($query) use ($dosen_penilai) {
                $query->where('ketua', $dosen_penilai)
                    ->orWhere('sekretaris', $dosen_penilai)
                    ->orWhere('penguji_1', $dosen_penilai)
                    ->orWhere('penguji_2', $dosen_penilai);
            })
            ->distinct('mahasiswa_id')
            ->get();

        $data_dosen_penguji_ta = MahasiswaTa::with([
            'r_mahasiswa',
            'r_penguji_1',
            'r_penguji_2',
            'r_nilai_penguji_1' => function ($query) {
                $query->where('status', '1');
            },
            'r_nilai_penguji_2' => function ($query) {
                $query->where('status', '2');
            }
        ])
            ->where('penguji_1', $dosen_penilai)
            ->where('penguji_2', $dosen_penilai)
            ->distinct('mahasiswa_id')
            ->get();

        $data_nilai_sidang_ta = $data_dosen_ta->merge($data_dosen_penguji_ta);

        $nextNumber = $this->getCariNomor();

        $rolesPerMahasiswa = $data_dosen_ta->mapWithKeys(function ($item) use ($dosen_penilai) {
            return [
                $item->mahasiswa_id => [
                    'isKetua' => $item->ketua == $dosen_penilai,
                    'isSekretaris' => $item->sekretaris == $dosen_penilai,
                    'isPenguji1' => $item->penguji_1 == $dosen_penilai,
                    'isPenguji2' => $item->penguji_2 == $dosen_penilai,
                ],
            ];
        });

        // Debugging: Periksa daftar peran dosen untuk setiap mahasiswa
        // dd($rolesPerMahasiswa);

        return view('admin.content.ta.sidang.sidang_ta', compact(
            'data_nilai_sidang_ta',
            'rolesPerMahasiswa',
            'data_dosen_ta',
            'data_dosen_penguji_ta',
            'nextNumber'
        ));
    }



    public function nilai_sidang_ta(Request $request, string $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id_nilai_sidang_ta' => 'required',
            'ta_id' => 'required|exists:mhs_ta,id_ta',
            'sikap_penampilan' => 'required',
            'komunikasi_sistematika' => 'required',
            'penguasaan_materi' => 'required',
            'identifikasi_masalah' => 'required',
            'relevansi_teori' => 'required',
            'metode_algoritma' => 'required',
            'hasil_pembahasan' => 'required',
            'kesimpulan_saran' => 'required',
            'bahasa_tata_tulis' => 'required',
            'kesesuaian_fungsional' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $nilai_sidang =
                ($request->sikap_penampilan * 0.05) +
                ($request->komunikasi_sistematika * 0.05) +
                ($request->penguasaan_materi * 0.20) +
                ($request->identifikasi_masalah * 0.05) +
                ($request->relevansi_teori * 0.05) +
                ($request->metode_algoritma * 0.10) +
                ($request->hasil_pembahasan * 0.15) +
                ($request->kesimpulan_saran * 0.05) +
                ($request->bahasa_tata_tulis * 0.05) +
                ($request->kesesuaian_fungsional * 0.25);


        $nilai_mahasiswaTa = NilaiTa::updateOrCreate(
            ['id_nilai_sidang_ta' => $request->id_nilai_sidang_ta],
            [
                'ta_id' => $request->ta_id,
                'sikap_penampilan' => $request->sikap_penampilan,
                'komunikasi_sistematika' => $request->komunikasi_sistematika,
                'penguasaan_materi' => $request->penguasaan_materi,
                'identifikasi_masalah' => $request->identifikasi_masalah,
                'relevansi_teori' => $request->relevansi_teori,
                'metode_algoritma' => $request->metode_algoritma,
                'hasil_pembahasan' => $request->hasil_pembahasan,
                'kesimpulan_saran' => $request->kesimpulan_saran,
                'bahasa_tata_tulis' => $request->bahasa_tata_tulis,
                'kesesuaian_fungsional' => $request->kesesuaian_fungsional,
                'nilai_sidang' => $nilai_sidang,
                'status' => $request->status,
            ]
        );

        // dd($request->all());

        $message = $nilai_mahasiswaTa->wasRecentlyCreated
            ? 'Nilai berhasil disimpan!'
            : 'Nilai berhasil diperbarui!';

        return redirect()->route('nilai_sidang_ta')
            ->with('success', $message);
    }


    function getCariNomor()
    {
        $id_nilai_sidang_ta = NilaiTa::pluck('id_nilai_sidang_ta')->toArray();
        for ($i = 1;; $i++) {

            if (!in_array($i, $id_nilai_sidang_ta)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
