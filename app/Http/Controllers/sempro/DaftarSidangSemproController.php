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
use App\Models\MahasiswaPkl;
use App\Models\MahasiswaTa;
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

        $availableRuangan = $data_ruangan->filter(function ($ruang) use ($existingSchedules) {
            foreach ($existingSchedules as $schedule) {
                if ($schedule->ruangan_id == $ruang->id_ruang) {
                    return false;
                }
            }
            return true;
        });

        $availableSesi = $jam_sidang->filter(function ($jam) use ($existingSchedules) {
            foreach ($existingSchedules as $schedule) {
                if ($schedule->sesi_id == $jam->id_sesi) {
                    return false;
                }
            }
            return true;
        });



        return view('admin.content.sempro.kaprodi.daftar_sidang_sempro', compact('existingSchedules', 'data_ruangan', 'data_mahasiswa_sempro', 'dosen', 'jam_sidang', 'availableRuangan', 'availableSesi'));
    }


    // public function getAvailableDates(Request $request)
    // {
    //     $penguji = $request->penguji;
    //     $pembimbing_satu = $request->pembimbing_satu;
    //     $pembimbing_dua = $request->pembimbing_dua;

    //     $usedDatesWithRoomAndSession = MahasiswaSempro::where(function ($query) use ($penguji, $pembimbing_satu, $pembimbing_dua) {
    //         $query->where('penguji', $penguji)
    //             ->orWhere('pembimbing_satu', $penguji)
    //             ->orWhere('pembimbing_dua', $penguji)
    //             ->orWhere('pembimbing_satu', $pembimbing_satu)
    //             ->orWhere('pembimbing_dua', $pembimbing_satu)
    //             ->orWhere('pembimbing_satu', $pembimbing_dua)
    //             ->orWhere('pembimbing_dua', $pembimbing_dua);
    //     })
    //         ->get(['tanggal_sempro', 'ruangan_id', 'sesi_id']);

    //     $usedDatesWithRoomAndSession = $usedDatesWithRoomAndSession->map(function ($sempro) {
    //         return [
    //             'tanggal_sempro' => $sempro->tanggal_sempro,
    //             'ruangan_id' => $sempro->ruangan_id,
    //             'sesi_id' => $sempro->sesi_id,
    //         ];
    //     });

    //     $upcomingDates = collect();
    //     for ($i = 0; $i < 120; $i++) {
    //         $date = \Carbon\Carbon::now()->addDays($i)->format('Y-m-d');

    //         $isDateAvailable = $usedDatesWithRoomAndSession->every(function ($used) use ($date) {
    //             return !($used['tanggal_sempro'] === $date);
    //         });

    //         if ($isDateAvailable) {
    //             $upcomingDates->push($date);
    //         }
    //     }

    //     return response()->json($upcomingDates);
    // }


    // public function getAvailableRooms(Request $request)
    // {
    //     $tanggal = $request->tanggal;
    //     $penguji = $request->penguji;


    //     $request->validate([
    //         'tanggal' => 'required|date',
    //         'penguji' => 'required'
    //     ]);


    //     $usedRoomsAndSessions = MahasiswaSempro::where('tanggal_sempro', $tanggal)
    //         ->get(['ruangan_id', 'sesi_id']);

    //     $availableRooms = Ruang::all();


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
    //         return response()->json(['message' => 'Tidak ada ruangan yang tersedia pada tanggal ini.'], 404);
    //     }

    //     return response()->json($availableRoomsWithSessions);
    // }



    // public function getAvailableSessions(Request $request)
    // {
    //     $tanggal = $request->tanggal;
    //     $idRuangan = $request->id_ruang;

    //     $request->validate([
    //         'tanggal' => 'required|date',
    //         'id_ruang' => 'required|exists:ruang,id_ruang'
    //     ]);

    //     $usedSessions = MahasiswaSempro::where('tanggal_sempro', $tanggal)
    //         ->where('ruangan_id', $idRuangan)
    //         ->pluck('sesi_id')->toArray();

    //     $availableSessions = Sesi::whereNotIn('id_sesi', $usedSessions)
    //         ->get(['id_sesi', 'sesi', 'jam']);

    //     if ($availableSessions->isEmpty()) {
    //         return response()->json(['message' => 'Tidak ada sesi yang tersedia pada tanggal ini untuk ruangan ini.'], 404);
    //     }

    //     return response()->json($availableSessions);
    // }


    public function getAvailableRooms(Request $request)
    {
        $tanggal = $request->tanggal;
        $penguji = $request->penguji;

        $request->validate([
            'tanggal' => 'required|date',
            'penguji' => 'required',
        ]);

        $usedRoomsAndSessionsPkl = MahasiswaPkl::where('tgl_sidang', $tanggal)
            ->get(['ruang_sidang', 'jam_sidang']);
        $usedRoomsAndSessionsSempro = MahasiswaSempro::where('tanggal_sempro', $tanggal)
            ->get(['ruangan_id as ruang_sidang', 'sesi_id as jam_sidang']);
        $usedRoomsAndSessionsTa = MahasiswaTa::where('tanggal_ta', $tanggal)
            ->get(['ruangan_id as ruang_sidang', 'sesi_id as jam_sidang']);

        $usedRoomsAndSessions = collect()
            ->concat($usedRoomsAndSessionsPkl)
            ->concat($usedRoomsAndSessionsSempro)
            ->concat($usedRoomsAndSessionsTa);

        $availableRooms = Ruang::all();

        $roomsWithAvailableSessions = $availableRooms->map(function ($room) use ($usedRoomsAndSessions) {
            $usedSessions = $usedRoomsAndSessions
                ->where('ruang_sidang', $room->id_ruang)
                ->pluck('jam_sidang')
                ->toArray();

            $availableSessions = Sesi::whereNotIn('id_sesi', $usedSessions)->get();

            return [
                'id_ruang' => $room->id_ruang,
                'nama_ruangan' => $room->kode_ruang,
                'sessions' => $availableSessions->map(function ($session) {
                    return [
                        'id_sesi' => $session->id_sesi,
                        'sesi' => $session->sesi,
                        'jam' => $session->jam,
                    ];
                })->values(),
            ];
        });

        $roomsAvailable = $roomsWithAvailableSessions->filter(function ($room) {
            return $room['sessions']->isNotEmpty();
        });

        if ($roomsAvailable->isEmpty()) {
            $roomsAvailable = $availableRooms->map(function ($room) {
                return [
                    'id_ruang' => $room->id_ruang,
                    'nama_ruangan' => $room->kode_ruang,
                    'sessions' => [],
                    'message' => 'Tidak ada sesi tersedia di ruangan ini',
                ];
            });
        }

        return response()->json($roomsAvailable->values());
    }




    public function getAvailableSessions(Request $request)
    {
        $tanggal = $request->tanggal;
        $idRuangan = $request->id_ruang;

        $request->validate([
            'tanggal' => 'required|date',
            'id_ruang' => 'required|exists:ruang,id_ruang'
        ]);

        $usedSessionsPkl = MahasiswaPkl::where('tgl_sidang', $tanggal)
            ->where('ruang_sidang', $idRuangan)
            ->pluck('jam_sidang')
            ->toArray();

        $usedSessionsSempro = MahasiswaSempro::where('tanggal_sempro', $tanggal)
            ->where('ruangan_id', $idRuangan)
            ->pluck('sesi_id')
            ->toArray();

        $usedSessionsTa = MahasiswaTa::where('tanggal_ta', $tanggal)
            ->where('ruangan_id', $idRuangan)
            ->pluck('sesi_id')
            ->toArray();

        $usedSessions = array_merge($usedSessionsPkl, $usedSessionsSempro, $usedSessionsTa);

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
