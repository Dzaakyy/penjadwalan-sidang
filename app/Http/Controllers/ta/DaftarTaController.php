<?php

namespace App\Http\Controllers\Ta;

use App\Models\User;
use App\Models\Dosen;
use App\Models\MahasiswaTa;
use Illuminate\Http\Request;
use App\Models\MahasiswaSempro;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DaftarTaController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->r_mahasiswa;
        $nextNumber = $this->getCariNomor();
        $data_mahasiswa_ta = MahasiswaTa::all();
        return view('admin.content.ta.mahasiswa.daftar_sidang', compact('data_mahasiswa_ta', 'nextNumber', 'mahasiswa'));
    }


    public function getPembimbing($mahasiswaId)
    {

        $sempro = MahasiswaSempro::where('mahasiswa_id', $mahasiswaId)->first();

        if (!$sempro) {
            return response()->json([
                'error' => 'Data Sempro tidak ditemukan untuk mahasiswa ini'
            ], 404);
        }


        $pembimbingSatu = $sempro->r_pembimbing_satu;
        $pembimbingDua = $sempro->r_pembimbing_dua;


        // if (!$pembimbingSatu || !$pembimbingDua) {
        //     \Log::info('Relasi Pembimbing Null', [
        //         'pembimbing_satu' => $pembimbingSatu,
        //         'pembimbing_dua' => $pembimbingDua,
        //     ]);
        // }

        return response()->json([
            'pembimbing_satu' => $pembimbingSatu->nama_dosen ?? 'Tidak ditemukan',
            'pembimbing_dua' => $pembimbingDua->nama_dosen ?? 'Tidak ditemukan',
        ]);
    }





    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_ta' => 'required',
            'mahasiswa_id' => 'required|exists:mahasiswa,id_mahasiswa',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Ambil data pembimbing dari tabel sempro berdasarkan mahasiswa_id
        $sempro = MahasiswaSempro::where('mahasiswa_id', $request->mahasiswa_id)->first();

        if (!$sempro) {
            return back()->with('error', 'Data Sempro untuk mahasiswa ini tidak ditemukan.');
        }

        // Siapkan data untuk disimpan ke tabel TA
        $data = [
            'id_ta' => $request->id_ta,
            'mahasiswa_id' => $request->mahasiswa_id,
            'proposal_final' => $request->proposal_final,
            'laporan_ta' => $request->laporan_ta,
            'tugas_akhir' => $request->tugas_akhir,
            'pembimbing_satu_id' => $sempro->pembimbing_satu,
            'pembimbing_dua_id' => $sempro->pembimbing_dua,
        ];

        // Handle upload file jika ada
        if ($request->hasFile('proposal_final')) {
            $file = $request->file('proposal_final');
            $filename = $file->getClientOriginalName();
            $path = 'public/uploads/mahasiswa/ta/proposal/';
            $file->storeAs($path, $filename);

            if (file_exists(storage_path('app/' . $path . $filename))) {
                $data['proposal_final'] = $filename;
            } else {
                return back()->with('error', 'Gagal mengunggah file proposal_final');
            }
        }

        if ($request->hasFile('laporan_ta')) {
            $file = $request->file('laporan_ta');
            $filename = $file->getClientOriginalName();
            $path = 'public/uploads/mahasiswa/ta/laporan/';
            $file->storeAs($path, $filename);

            if (file_exists(storage_path('app/' . $path . $filename))) {
                $data['laporan_ta'] = $filename;
            } else {
                return back()->with('error', 'Gagal mengunggah file laporan_ta');
            }
        }

        if ($request->hasFile('tugas_akhir')) {
            $file = $request->file('tugas_akhir');
            $filename = $file->getClientOriginalName();
            $path = 'public/uploads/mahasiswa/ta/tugasAkhir/';
            $file->storeAs($path, $filename);

            if (file_exists(storage_path('app/' . $path . $filename))) {
                $data['tugas_akhir'] = $filename;
            } else {
                return back()->with('error', 'Gagal mengunggah file tugas_akhir');
            }
        }

        $mahasiswaTa = MahasiswaTa::where('mahasiswa_id', $request->mahasiswa_id)->first();

        if ($mahasiswaTa) {
            $mahasiswaTa->update($data);
            $message = 'Data berhasil diupdate.';
        } else {
            MahasiswaTa::create($data);
            $message = 'Data berhasil ditambahkan.';
        }


        $pembimbing_ids = [$sempro->pembimbing_satu, $sempro->pembimbing_dua];
        foreach ($pembimbing_ids as $pembimbing_id) {
            $dosen = Dosen::find($pembimbing_id);

            if ($dosen) {
                $user = User::where('email', $dosen->r_user->email)->first();

                if ($user) {
                    if (!$user->hasRole('pembimbingTa')) {
                        $user->assignRole('pembimbingTa');
                    }
                }
            }
        }

        return redirect()->route('daftar_ta')->with('success', $message);
    }


    function getCariNomor()
    {
        $id_ta = MahasiswaTa::pluck('id_ta')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_ta)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
