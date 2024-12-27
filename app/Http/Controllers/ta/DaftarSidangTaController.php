<?php

namespace App\Http\Controllers\Ta;

use App\Models\Sesi;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Ruang;
use App\Models\Pimpinan;
use App\Models\MahasiswaTa;
use Illuminate\Http\Request;
use App\Models\MahasiswaSempro;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DaftarSidangTaController extends Controller
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

        $data_mahasiswa_ta = MahasiswaTa::with('r_mahasiswa', 'r_pembimbing_satu', 'r_pembimbing_dua', 'r_ketua', 'r_sekretaris', 'r_penguji_1', 'r_penguji_2', 'r_ruangan', 'r_sesi')
            ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->orderByDesc('id_ta')
            ->get();

        $data_ruangan = Ruang::all();
        $dosen = Dosen::all();
        $jam_sidang = Sesi::all();

        return view('admin.content.ta.kaprodi.daftar_sidang_ta', compact('data_ruangan', 'data_mahasiswa_ta', 'dosen', 'jam_sidang'));
    }


    public function getAvailableDates(Request $request)
    {

        $ketua = $request->ketua;
        $sekretaris = $request->sekretaris;
        $penguji_1 = $request->penguji_1;
        $penguji_2 = $request->penguji_2;


        $usedDatesWithRoomAndSession = MahasiswaTa::where(function ($query) use ($ketua, $sekretaris, $penguji_1, $penguji_2) {
            $query->where('ketua', $ketua)
                ->orWhere('sekretaris', $ketua)
                ->orWhere('penguji_1', $ketua)
                ->orWhere('penguji_2', $ketua)
                ->orWhere('ketua', $sekretaris)
                ->orWhere('sekretaris', $sekretaris)
                ->orWhere('penguji_1', $sekretaris)
                ->orWhere('penguji_2', $sekretaris)
                ->orWhere('ketua', $penguji_1)
                ->orWhere('sekretaris', $penguji_1)
                ->orWhere('penguji_1', $penguji_1)
                ->orWhere('penguji_2', $penguji_1)
                ->orWhere('ketua', $penguji_2)
                ->orWhere('sekretaris', $penguji_2)
                ->orWhere('penguji_1', $penguji_2)
                ->orWhere('penguji_2', $penguji_2);
        })
            ->get(['tanggal_ta', 'ruangan_id', 'sesi_id']);


        $usedDatesWithRoomAndSession = $usedDatesWithRoomAndSession->map(function ($ta) {
            return [
                'tanggal_ta' => $ta->tanggal_ta,
                'ruangan_id' => $ta->ruangan_id,
                'sesi_id' => $ta->sesi_id,
            ];
        });


        $upcomingDates = collect();
        for ($i = 0; $i < 120; $i++) {
            $date = \Carbon\Carbon::now()->addDays($i)->format('Y-m-d');


            $isDateAvailable = $usedDatesWithRoomAndSession->every(function ($used) use ($date) {
                return !($used['tanggal_ta'] === $date);
            });

            if ($isDateAvailable) {
                $upcomingDates->push($date);
            }
        }

        return response()->json($upcomingDates);
    }



    public function getAvailableRooms(Request $request)
    {
        $tanggal = $request->tanggal;

        $request->validate([
            'tanggal' => 'required|date',
        ]);


        $usedRoomsAndSessions = MahasiswaTa::where('tanggal_ta', $tanggal)
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


        $request->validate([
            'tanggal' => 'required|date',
            'id_ruang' => 'required|exists:ruang,id_ruang'
        ]);


        $usedSessions = MahasiswaTa::where('tanggal_ta', $tanggal)
            ->where('ruangan_id', $idRuangan)
            ->pluck('sesi_id')->toArray();


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
            'ketua' => 'required|exists:dosen,id_dosen',
            'sekretaris' => 'required|exists:dosen,id_dosen',
            'penguji_1' => 'required|exists:dosen,id_dosen',
            'penguji_2' => 'required|exists:dosen,id_dosen',
            'tanggal_ta' => 'required',
            'ruangan_id' => 'required|exists:ruang,id_ruang',
            'sesi_id' => 'required|exists:sesi,id_sesi',
        ]);

        $existingConflict = MahasiswaTa::where('tanggal_ta', $request->tanggal_ta)
            ->where('sesi_id', $request->sesi_id)
            ->where('ruangan_id', $request->ruangan_id)
            ->where('id_ta', '!=', $id)
            ->exists();

        if ($existingConflict) {
            return back()->with('error', 'Jadwal bentrok: Ruang, atau sesi ini sudah digunakan pada waktu tersebut.');
        }

        $mahasiswa_ta = MahasiswaTa::findOrFail($id);


        $mahasiswa_ta->ketua = $request->ketua;
        $mahasiswa_ta->sekretaris = $request->sekretaris;
        $mahasiswa_ta->penguji_1 = $request->penguji_1;
        $mahasiswa_ta->penguji_2 = $request->penguji_2;
        $mahasiswa_ta->tanggal_ta = $request->tanggal_ta;
        $mahasiswa_ta->ruangan_id = $request->ruangan_id;
        $mahasiswa_ta->sesi_id = $request->sesi_id;

        $mahasiswa_ta->save();

        $penguji_ids = [$request->ketua, $request->sekretaris, $request->penguji_1, $request->penguji_2];
        foreach ($penguji_ids as $penguji_id) {
            $dosen = Dosen::find($penguji_id);

            if ($dosen) {
                $user = User::where('email', $dosen->r_user->email)->first();

                if ($user) {

                    if (!$user->hasRole('pengujiTa')) {
                        $user->assignRole('pengujiTa');
                    }
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

        $data_sidang_ta = MahasiswaTa::with(['r_mahasiswa.r_prodi', 'r_pembimbing_satu', 'r_pembimbing_dua', 'r_ketua', 'r_sekretaris', 'r_penguji_1', 'r_penguji_2', 'r_sesi', 'r_ruangan'])
            ->where('id_ta', $id)
            ->first();

        if (!$data_sidang_ta) {
            abort(404, 'Data Mahasiswa TA tidak ditemukan.');
        }


        if (!$data_sidang_ta) {
            abort(404, 'Data Mahasiswa TA tidak ditemukan.');
        }

        $judulSempro = MahasiswaSempro::where('mahasiswa_id', $data_sidang_ta->mahasiswa_id)->value('judul');

        if (!$judulSempro) {
            $judulSempro = 'Judul Sempro tidak ditemukan.';
        }


        $pembimbingList = collect([$data_sidang_ta->r_pembimbing_satu, $data_sidang_ta->r_pembimbing_dua]);
        $ketuaList = collect([$data_sidang_ta->r_ketua]);
        $sekretarisList = collect([$data_sidang_ta->r_sekretaris]);
        $pengujiList = collect([$data_sidang_ta->r_penguji_1, $data_sidang_ta->r_penguji_2,]);

        $data_ruangan = Ruang::all();
        $dosen_penyidang = Dosen::all();
        $jam_sidang = Sesi::all();

        $pdf = Pdf::loadView('admin.content.ta.pdf.surat_tugas_ta', [
            'data_sidang_ta' => $data_sidang_ta,
            'Kaprodi' => $Kaprodi,
            'data_ruangan' => $data_ruangan,
            'dosen_penyidang' => $dosen_penyidang,
            'jam_sidang' => $jam_sidang,
            'judulSempro' => $judulSempro,
            'pembimbingList' => $pembimbingList,
            'ketuaList' => $ketuaList,
            'sekretarisList' => $sekretarisList,
            'pengujiList' => $pengujiList,
        ]);

        // return $pdf->stream('Surat_Tugas_ta.pdf');
        return $pdf->stream('Surat_Tugas_ta.pdf');
    }
}
