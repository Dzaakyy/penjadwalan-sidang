<?php

namespace App\Http\Controllers\pkl;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Pimpinan;
use App\Models\Mahasiswa;
use App\Models\TempatPkl;
use App\Models\UsulanPkl;
use App\Models\MahasiswaPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KonfirmasiUsulanPklController extends Controller
{
    public function index()
    {

        $getDosen = Auth::user()->r_dosen->id_dosen;

        $Kaprodi = Pimpinan::where('dosen_id', $getDosen)
            ->whereHas('r_jabatan_pimpinan', function ($query) {
                $query->where('kode_jabatan_pimpinan', 'Kaprodi');
            })
            ->whereHas('r_dosen', function ($query) {
                $query->where('prodi_id', Auth::user()->r_dosen->prodi_id);
            })
            ->exists();

        $prodiId = Auth::user()->r_dosen->prodi_id;

        $data_usulan_pkl = UsulanPkl::with('r_mahasiswa', 'r_perusahaan')
            ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->orderByDesc('id_usulan_pkl')
            ->get();

        $data_mahasiswa_pkl = MahasiswaPkl::with('r_pkl', 'r_dosen_pembimbing')
        ->whereHas('r_pkl', function ($query) use ($prodiId) {
            $query->whereHas('r_mahasiswa', function ($subQuery) use ($prodiId) {
                $subQuery->where('prodi_id', $prodiId);
            });
        })
        ->orderByDesc('id_mhs_pkl')
        ->get();

        $dosen = Dosen::all();
        $dosen_pembimbing = Dosen::all();
        $nextNumber = $this->getCariNomor();

        return view('admin.content.pkl.kaprodi.konfirmasi_usulan_pkl', compact('data_usulan_pkl', 'data_mahasiswa_pkl', 'nextNumber', 'dosen', 'dosen_pembimbing'));
    }


    // public function confirm(Request $request, string $id)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'konfirmasi' => 'required|in:0,1',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validator);
    //     }


    //     $usulan_pkl = UsulanPkl::find($id);

    //     if ($usulan_pkl) {

    //         $usulan_pkl->konfirmasi = $request->konfirmasi;
    //         $usulan_pkl->save();
    //     } else {
    //         return redirect()->back()->withErrors(['msg' => 'Usulan PKL tidak ditemukan.']);
    //     }

    //     return redirect()->route('konfirmasi_usulan_pkl')->with('success', 'Usulan PKL berhasil dikonfirmasi!');
    // }



    // public function confirm(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'konfirmasi' => 'required|in:0,1',
    //         'dosen_pembimbing' => 'nullable|exists:dosen,id_dosen',
    //         'tahun_pkl' => 'required|date_format:Y',
    //         'mahasiswa_id' => 'required|exists:usulan_pkl,mahasiswa_id',  // Pastikan validasi terhadap mahasiswa_id
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validator);
    //     }

    //     $usulanPkl = UsulanPkl::find($id);

    //     if ($usulanPkl) {
    //         $usulanPkl->konfirmasi = $usulanPkl->konfirmasi === '1' ? '0' : '1';
    //         $usulanPkl->save();

    //         if ($usulanPkl->konfirmasi === '1') {

    //             $mahasiswaPkl = MahasiswaPkl::where('mahasiswa_id', $usulanPkl->mahasiswa_id)->first();

    //             if (!$mahasiswaPkl) {
    //                 MahasiswaPkl::create([
    //                     'id_mhs_pkl' => $request->id_mhs_pkl,
    //                     'mahasiswa_id' => $usulanPkl->mahasiswa_id,
    //                     'tahun_pkl' => $request->tahun_pkl,
    //                     'dosen_pembimbing' => $request->dosen_pembimbing ?? null,
    //                 ]);
    //             } else {


    //                 $mahasiswaPkl->tahun_pkl = $request->tahun_pkl;
    //                 $mahasiswaPkl->dosen_pembimbing = $request->dosen_pembimbing ?? $mahasiswaPkl->dosen_pembimbing;
    //                 $mahasiswaPkl->save();
    //             }
    //         }
    //     } else {
    //         return redirect()->back()->withErrors(['msg' => 'Usulan PKL tidak ditemukan.']);
    //     }

    //     return redirect()->route('konfirmasi_usulan_pkl')->with('success', 'Usulan PKL berhasil dikonfirmasi dan Pembimbing Sudah Dipilih!');
    // }


    public function confirm(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'id_mhs_pkl' => 'required|unique:mhs_pkl,id_mhs_pkl',
            'mahasiswa_id' => 'required|exists:usulan_pkl,mahasiswa_id',
            'dosen_pembimbing' => 'required|exists:dosen,id_dosen',
            'tahun_pkl' => 'required|date_format:Y',
            'konfirmasi' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $usulanPklRecord = UsulanPkl::find($id);

        if (!$usulanPklRecord) {
            return redirect()->back()->withErrors(['mahasiswa_id' => 'Mahasiswa belum terdaftar di usulan PKL.']);
        }


        $usulanPklRecord->konfirmasi = $request->konfirmasi;
        $usulanPklRecord->save();


        MahasiswaPkl::create([
            'id_mhs_pkl' => $request->id_mhs_pkl,
            'mahasiswa_id' => $request->mahasiswa_id,
            'tahun_pkl' => $request->tahun_pkl,
            'dosen_pembimbing' => $request->dosen_pembimbing,
        ]);

        $dosen = Dosen::find($request->dosen_pembimbing);

        if ($dosen) {
            $user = User::where('email', $dosen->r_user->email)->first();

            if ($user) {
                if (!$user->hasRole('pembimbingPkl  ')) {
                    $user->assignRole('pembimbingPkl');
                }
            }
        }



        return redirect()->route('konfirmasi_usulan_pkl')->with('success', 'Usulan PKL berhasil dikonfirmasi dan Pembimbing Sudah Dipilih!');
    }






    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_mhs_pkl' => 'required|unique:mhs_pkl,id_mhs_pkl',
            'mahasiswa_id' => 'required|exists:usulan_pkl,id_usulan_pkl',
            'dosen_pembimbing' => 'required|exists:dosen,id_dosen',
            'tahun_pkl' => 'required',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        MahasiswaPkl::create([
            'id_mhs_pkl' => $request->id_mhs_pkl,
            'mahasiswa_id' => $request->mahasiswa_id,
            'dosen_pembimbing' => $request->dosen_pembimbing,
            'tahun_pkl' => $request->tahun_pkl,
        ]);


        return redirect()->route('konfirmasi_usulan_pkl')->with('success', 'Pembimbing PKL Berhasil Ditambahkan!');
    }


    public function update(Request $request, $id)
    {

        $request->validate([
            'dosen_pembimbing' => 'required|exists:dosen,id_dosen',
        ]);

        $mahasiswaPkl = MahasiswaPkl::findOrFail($id);

        $mahasiswaPkl->dosen_pembimbing = $request->dosen_pembimbing;
        $mahasiswaPkl->save();


        return redirect()->back()->with('success', 'Pembimbing PKL berhasil diganti.');
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
