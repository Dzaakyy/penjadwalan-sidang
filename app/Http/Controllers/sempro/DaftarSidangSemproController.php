<?php

namespace App\Http\Controllers\Sempro;

use App\Models\Sesi;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Ruang;
use App\Models\Pimpinan;
use Illuminate\Http\Request;
use App\Models\MahasiswaSempro;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DaftarSidangSemproController extends Controller
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
        // $nextNumber = $this->getCariNomor();

        $prodiId = Auth::user()->r_dosen->prodi_id;

        $data_mahasiswa_sempro = MahasiswaSempro::with('r_mahasiswa', 'r_pembimbing_satu', 'r_pembimbing_dua', 'r_penguji', 'r_ruangan', 'r_sesi')
            ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->orderByDesc('id_sempro')
            ->get();

        $data_ruangan = Ruang::all();
        $dosen = Dosen::all();
        $jam_sidang = Sesi::all();

        $existingSchedules = MahasiswaSempro::whereNotNull('tanggal_sempro')
            ->get(['penguji', 'tanggal_sempro', 'ruangan_id', 'sesi_id']);

        // Filter ruangan berdasarkan tanggal dan sesi
        $availableRuangan = $data_ruangan->filter(function ($ruang) use ($existingSchedules) {
            foreach ($existingSchedules as $schedule) {
                if ($schedule->ruangan_id == $ruang->id_ruang) {
                    return false; // Ruangan sudah dipakai pada tanggal tersebut
                }
            }
            return true;
        });

        // Filter sesi berdasarkan ruangan dan tanggal
        $availableSesi = $jam_sidang->filter(function ($jam) use ($existingSchedules) {
            foreach ($existingSchedules as $schedule) {
                if ($schedule->sesi_id == $jam->id_sesi) {
                    return false; // Sesi sudah dipakai pada ruangan dan tanggal tersebut
                }
            }
            return true;
        });
        // $dosen_pembimbing_satu = Dosen::all();
        // $dosen_pembimbing_dua = Dosen::all();
        // $dosen_penguji = Dosen::all();


        // return view('admin.content.sempro.kaprodi.verifikasi_sempro', compact('data_ruangan', 'data_mahasiswa_sempro', 'dosen','dosen_pembimbing_satu','dosen_pembimbing_dua','dosen_penguji', 'jam_sidang'));
        return view('admin.content.sempro.kaprodi.daftar_sidang_sempro', compact('existingSchedules','data_ruangan', 'data_mahasiswa_sempro', 'dosen', 'jam_sidang', 'availableRuangan', 'availableSesi'));
    }


    public function getExistingSchedules()
    {
        $schedules = MahasiswaSempro::whereNotNull('tanggal_sempro')
            ->get(['penguji', 'tanggal_sempro', 'ruangan_id', 'sesi_id'])
            ->map(function ($item) {
                return [
                    'dosenId' => $item->penguji,
                    'tanggal' => $item->tanggal_sempro,
                    'ruanganId' => $item->ruangan_id,
                    'sesiId' => $item->sesi_id,
                ];
            });

        return response()->json($schedules);
    }

    public function update(Request $request, $id)
    {

        // dd($request->all());

        $request->validate([

            'penguji' => 'required|exists:dosen,id_dosen',
            'tanggal_sempro' => 'required',
            'ruangan_id' => 'required|exists:ruang,id_ruang',
            'sesi_id' => 'required|exists:sesi,id_sesi',
        ]);

        // Cek bentrok jadwal di server
        $existingConflict = MahasiswaSempro::where('tanggal_sempro', $request->tanggal_sempro)
            ->where('sesi_id', $request->sesi_id)
            ->where(function ($query) use ($request) {
                $query->where('penguji', $request->penguji)
                    ->orWhere('pembimbing_satu', $request->penguji)
                    ->orWhere('pembimbing_dua', $request->penguji);
            })
            ->where('id_sempro', '!=', $id) // Hindari konflik dengan data yang sedang diperbarui
            ->exists();

        if ($existingConflict) {
            return back()->with('error', 'Jadwal bentrok: Dosen ini sudah memiliki jadwal sidang di waktu tersebut.');
        }

        $mahasiswa_sempro = MahasiswaSempro::findOrFail($id);


        $mahasiswa_sempro->penguji = $request->penguji;
        $mahasiswa_sempro->tanggal_sempro = $request->tanggal_sempro;
        $mahasiswa_sempro->ruangan_id = $request->ruangan_id;
        $mahasiswa_sempro->sesi_id = $request->sesi_id;
        $mahasiswa_sempro->save();

        $pembimbing_ids = [$request->pembimbing_satu, $request->pembimbing_dua];
        foreach ($pembimbing_ids as $pembimbing_id) {
            $dosen = Dosen::find($pembimbing_id);

            if ($dosen) {
                $user = User::where('email', $dosen->r_user->email)->first();

                if ($user) {

                    if (!$user->hasRole('pembimbingSempro')) {
                        $user->assignRole('pembimbingSempro');
                    }
                }
            }
        }


        $dosen = Dosen::find($request->penguji);

        if ($dosen) {
            $user = User::where('email', $dosen->r_user->email)->first();

            if ($user) {

                if (!$user->hasRole('pengujiSempro')) {
                    $user->assignRole('pengujiSempro');
                }
            }
        }
        return redirect()->back()->with('success', 'Pendaftaran sidang berhasil ditambahkan');
    }


    public function download_pdf(Request $request, $id)
    {
        $getDosen = Auth::user()->r_dosen->id_dosen;
        $Kaprodi = Pimpinan::with('r_dosen')
            ->where('dosen_id', $getDosen)
            ->whereHas('r_jabatan_pimpinan', function ($query) {
                $query->where('kode_jabatan_pimpinan', 'Kaprodi');
            })
            ->whereHas('r_dosen', function ($query) {
                $query->where('prodi_id', Auth::user()->r_dosen->prodi_id);
            })
            ->first();

        if (!$Kaprodi) {
            abort(404, 'Data Kaprodi tidak ditemukan.');
        }

        $data_sidang_sempro = MahasiswaSempro::with(['r_mahasiswa.r_prodi', 'r_pembimbing_satu', 'r_pembimbing_dua', 'r_penguji', 'r_sesi', 'r_ruangan'])
            ->where('id_sempro', $id)
            ->first();

        if (!$data_sidang_sempro) {
            abort(404, 'Data Mahasiswa PKL tidak ditemukan.');
        }


        if (!$data_sidang_sempro) {
            abort(404, 'Data Mahasiswa PKL tidak ditemukan.');
        }

        $pembimbingList = collect([$data_sidang_sempro->r_pembimbing_satu, $data_sidang_sempro->r_pembimbing_dua]);
        $pengujiList = collect([$data_sidang_sempro->r_penguji]);

        $data_ruangan = Ruang::all();
        $dosen_penyidang = Dosen::all();
        $jam_sidang = Sesi::all();

        $pdf = Pdf::loadView('admin.content.sempro.pdf.surat_tugas_sempro', [
            'data_sidang_sempro' => $data_sidang_sempro,
            'Kaprodi' => $Kaprodi,
            'data_ruangan' => $data_ruangan,
            'dosen_penyidang' => $dosen_penyidang,
            'jam_sidang' => $jam_sidang,
            'pembimbingList' => $pembimbingList,
            'pengujiList' => $pengujiList,
        ]);

        // return $pdf->stream('Surat_Tugas_Sempro.pdf');
        return $pdf->download('Surat_Tugas_Sempro.pdf');
    }
}
