<?php

namespace App\Http\Controllers\Sempro;

use App\Models\Sesi;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Ruang;
use App\Models\Pimpinan;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Models\MahasiswaSempro;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        return view('admin.content.sempro.kaprodi.daftar_sidang_sempro', compact('existingSchedules', 'data_ruangan', 'data_mahasiswa_sempro', 'dosen', 'jam_sidang', 'availableRuangan', 'availableSesi'));
    }


    public function getAvailableDates(Request $request)
    {
        $penguji = $request->penguji;
        $pembimbing_satu = $request->pembimbing_satu;
        $pembimbing_dua = $request->pembimbing_dua;

        // Ambil semua jadwal yang sudah digunakan oleh penguji atau pembimbing
        $usedDatesWithRoomAndSession = MahasiswaSempro::where(function ($query) use ($penguji, $pembimbing_satu, $pembimbing_dua) {
            $query->where('penguji', $penguji)
                ->orWhere('pembimbing_satu', $penguji)
                ->orWhere('pembimbing_dua', $penguji)
                ->orWhere('pembimbing_satu', $pembimbing_satu)
                ->orWhere('pembimbing_dua', $pembimbing_satu)
                ->orWhere('pembimbing_satu', $pembimbing_dua)
                ->orWhere('pembimbing_dua', $pembimbing_dua);
        })
            ->get(['tanggal_sempro', 'ruangan_id', 'sesi_id']);

        // Format data untuk pengecekan lebih mudah
        $usedDatesWithRoomAndSession = $usedDatesWithRoomAndSession->map(function ($sempro) {
            return [
                'tanggal_sempro' => $sempro->tanggal_sempro,
                'ruangan_id' => $sempro->ruangan_id,
                'sesi_id' => $sempro->sesi_id,
            ];
        });

        // Generate 120 hari ke depan
        $upcomingDates = collect();
        for ($i = 0; $i < 120; $i++) {
            $date = \Carbon\Carbon::now()->addDays($i)->format('Y-m-d');

            // Check if this date has a conflicting room-session pair
            $isDateAvailable = $usedDatesWithRoomAndSession->every(function ($used) use ($date) {
                return !($used['tanggal_sempro'] === $date);
            });

            if ($isDateAvailable) {
                $upcomingDates->push($date);
            }
        }

        return response()->json($upcomingDates);
    }



    // public function getAvailableRoomsAndSessions(Request $request)
    // {
    //     $tanggal = $request->tanggal;
    //     $penguji = $request->penguji;
    //     $pembimbing_satu = $request->pembimbing_satu;
    //     $pembimbing_dua = $request->pembimbing_dua;

    //     // Ambil jadwal yang sudah digunakan berdasarkan tanggal dan ruangan
    //     $usedRoomsAndSessions = MahasiswaSempro::where('tanggal_sempro', $tanggal)
    //         ->get(['ruangan_id', 'sesi_id']);

    //     $availableRooms = Ruang::all();

    //     // Filter rooms berdasarkan ketersediaan (ruangan yang tidak memiliki sesi pada tanggal yang dipilih)
    //     $availableRoomsWithSessions = $availableRooms->map(function ($room) use ($usedRoomsAndSessions) {
    //         $availableSessions = Sesi::whereNotIn(
    //             'id_sesi',
    //             $usedRoomsAndSessions->where('ruangan_id', $room->id_ruang)->pluck('sesi_id')->toArray()
    //         )->get();

    //         return [
    //             'id_ruang' => $room->id_ruang,
    //             'nama_ruangan' => $room->kode_ruang,
    //             'sessions' => $availableSessions->map(function ($session) {
    //                 return [
    //                     'id_sesi' => $session->id_sesi,
    //                     'sesi' => $session->sesi,
    //                     'jam' => $session->jam,
    //                 ];
    //             })->values()
    //         ];
    //     })->filter(function ($room) {
    //         return $room['sessions']->isNotEmpty();
    //     });

    //     if ($availableRoomsWithSessions->isEmpty()) {
    //         return response()->json(['message' => 'Tidak ada ruangan yang tersedia pada tanggal ini.']);
    //     }

    //     return response()->json($availableRoomsWithSessions);
    // }


    public function getAvailableRooms(Request $request)
    {
        $tanggal = $request->tanggal;
        $penguji = $request->penguji;


        $request->validate([
            'tanggal' => 'required|date',
            'penguji' => 'required'
        ]);


        $usedRoomsAndSessions = MahasiswaSempro::where('tanggal_sempro', $tanggal)
            ->get(['ruangan_id', 'sesi_id']);

        $availableRooms = Ruang::all();


        $availableRoomsWithSessions = $availableRooms->map(function ($room) use ($usedRoomsAndSessions) {
            $availableSessions = Sesi::whereNotIn(
                'id_sesi',
                $usedRoomsAndSessions->where('ruangan_id', $room->id_ruang)->pluck('sesi_id')->toArray()
            )->get();

            return [
                'id_ruang' => $room->id_ruang,
                'nama_ruangan' => $room->kode_ruang,
                'sessions' => $availableSessions->map(function ($session) {
                    return [
                        'id_sesi' => $session->id_sesi,
                        'sesi' => $session->sesi,
                        'jam' => $session->jam,
                    ];
                })->values()
            ];
        })->filter(function ($room) {
            return $room['sessions']->isNotEmpty();
        });

        if ($availableRoomsWithSessions->isEmpty()) {
            return response()->json(['message' => 'Tidak ada ruangan yang tersedia pada tanggal ini.'], 404);
        }

        return response()->json($availableRoomsWithSessions);
    }



    public function getAvailableSessions(Request $request)
    {
        $tanggal = $request->tanggal;
        $idRuangan = $request->id_ruang;

        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'id_ruang' => 'required|exists:ruang,id_ruang'
        ]);

        // Ambil sesi yang sudah digunakan pada tanggal dan ruangan tertentu
        $usedSessions = MahasiswaSempro::where('tanggal_sempro', $tanggal)
            ->where('ruangan_id', $idRuangan)
            ->pluck('sesi_id')->toArray();

        // Ambil sesi yang tersedia
        $availableSessions = Sesi::whereNotIn('id_sesi', $usedSessions)
            ->get(['id_sesi', 'sesi', 'jam']);

        if ($availableSessions->isEmpty()) {
            return response()->json(['message' => 'Tidak ada sesi yang tersedia pada tanggal ini untuk ruangan ini.'], 404);
        }

        return response()->json($availableSessions);
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

        $existingConflict = MahasiswaSempro::where('tanggal_sempro', $request->tanggal_sempro)
            ->where('sesi_id', $request->sesi_id)
            ->where('ruangan_id', $request->ruangan_id)
            ->where(function ($query) use ($request) {

                $query->where('penguji', $request->penguji)
                    ->orWhere('pembimbing_satu', $request->penguji)
                    ->orWhere('pembimbing_dua', $request->penguji);
            })
            ->where(function ($query) use ($request) {
                $query->where('pembimbing_satu', $request->pembimbing_satu)
                    ->orWhere('pembimbing_satu', $request->pembimbing_dua)
                    ->orWhere('pembimbing_dua', $request->pembimbing_satu)
                    ->orWhere('pembimbing_dua', $request->pembimbing_dua);
            })
            ->where('id_sempro', '!=', $id) // Ensure it does not conflict with the current record being updated
            ->exists();

        if ($existingConflict) {
            return back()->with('error', 'Jadwal bentrok: Penguji, pembimbing, ruang, atau sesi ini sudah digunakan pada waktu tersebut.');
        }

        $mahasiswa_sempro = MahasiswaSempro::findOrFail($id);


        $mahasiswa_sempro->penguji = $request->penguji;
        $mahasiswa_sempro->tanggal_sempro = $request->tanggal_sempro;
        $mahasiswa_sempro->ruangan_id = $request->ruangan_id;
        $mahasiswa_sempro->sesi_id = $request->sesi_id;

        $mahasiswa_sempro->save();

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
            abort(404, 'Data Mahasiswa Sempro tidak ditemukan.');
        }


        if (!$data_sidang_sempro) {
            abort(404, 'Data Mahasiswa Sempro tidak ditemukan.');
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
