<?php

namespace App\Http\Controllers;

use App\Models\Pimpinan;
use App\Models\MahasiswaPkl;
use Illuminate\Http\Request;
use App\Models\MahasiswaSempro;
use App\Models\MahasiswaTa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();


        $data = [];

        if ($user->hasRole('pimpinanProdi')) {
            $prodiId = $user->r_dosen->prodi_id;
            $getDosen = $user->r_dosen->id_dosen;

            $Kaprodi = Pimpinan::with('r_dosen')
                ->where('dosen_id', $getDosen)
                ->whereHas('r_jabatan_pimpinan', function ($query) {
                    $query->where('kode_jabatan_pimpinan', 'Kaprodi');
                })
                ->whereHas('r_dosen', function ($query) use ($prodiId) {
                    $query->where('prodi_id', $prodiId);
                })
                ->first();

            if (!$Kaprodi) {
                abort(404, 'Data Kaprodi tidak ditemukan.');
            }

            $pklBelumDiterima = DB::table('mahasiswa')
                ->where('prodi_id', $prodiId)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('mhs_pkl')
                        ->whereRaw('mhs_pkl.mahasiswa_id = mahasiswa.id_mahasiswa');
                })
                ->count();

            $pklDiterima = MahasiswaPkl::whereHas('r_pkl.r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
                ->whereNotNull('dosen_pembimbing')
                ->count();

            $pklSelesai = MahasiswaPkl::whereHas('r_pkl.r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
                ->whereNotNull('nilai_mahasiswa')
                ->count();

            $banyak_pengunggahan_pkl = $pklDiterima;
            $banyak_verifikasi_pkl = $pklSelesai;

            $total_pkl = $banyak_pengunggahan_pkl + $banyak_verifikasi_pkl;
            $percentUploadedPKL = $total_pkl > 0 ? ($banyak_pengunggahan_pkl / $total_pkl) * 100 : 0;
            $percentVerifiedPKL = $total_pkl > 0 ? ($banyak_verifikasi_pkl / $total_pkl) * 100 : 0;

            $semproJudulDiajukan = MahasiswaSempro::where('status_judul', '2')
                ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                    $query->where('prodi_id', $prodiId);
                })
                ->count();

            $semproSelesai = MahasiswaSempro::whereNotNull('nilai_mahasiswa')
                ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                    $query->where('prodi_id', $prodiId);
                })
                ->count();

            $banyak_pengunggahan_sempro = $semproJudulDiajukan;
            $banyak_verifikasi_sempro = $semproSelesai;

            $total_sempro = $banyak_pengunggahan_sempro + $banyak_verifikasi_sempro;
            $percentUploadedSempro = $total_sempro > 0 ? ($banyak_pengunggahan_sempro / $total_sempro) * 100 : 0;
            $percentVerifiedSempro = $total_sempro > 0 ? ($banyak_verifikasi_sempro / $total_sempro) * 100 : 0;


            $taDiajukan = MahasiswaTa::where('status_berkas', '1')
                ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                    $query->where('prodi_id', $prodiId);
                })
                ->count();

            $taSelesai = MahasiswaTa::whereNotNull('nilai_mahasiswa')
                ->whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                    $query->where('prodi_id', $prodiId);
                })
                ->count();

            $banyak_pengunggahan_ta = $taDiajukan;
            $banyak_verifikasi_ta = $taSelesai;

            $total_ta = $banyak_pengunggahan_ta + $banyak_verifikasi_ta;
            $percentUploadedTa = $total_ta > 0 ? ($banyak_pengunggahan_ta / $total_ta) * 100 : 0;
            $percentVerifiedTa = $total_ta > 0 ? ($banyak_verifikasi_ta / $total_ta) * 100 : 0;



            $jadwal_sidang_pkl_kaprodi = MahasiswaPkl::whereHas('r_pkl.r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })->get();

            $jadwal_sidang_sempro_kaprodi = MahasiswaSempro::whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })->get();

            $jadwal_sidang_ta_kaprodi = MahasiswaTa::whereHas('r_mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })->get();

            $eventsKaprodi = $jadwal_sidang_pkl_kaprodi->map(function ($sidang) {
                $start = null;
                $end = null;
                if ($sidang->tgl_sidang && $sidang->r_sesi) {
                    $times = explode(' - ', $sidang->r_sesi->jam);
                    if (count($times) === 2) {
                        $start = \Carbon\Carbon::parse($sidang->tgl_sidang . ' ' . trim($times[0]))->toDateTimeString();
                        $end = \Carbon\Carbon::parse($sidang->tgl_sidang . ' ' . trim($times[1]))->toDateTimeString();
                    }
                }
                return [
                    'title' => 'Sidang PKL - ' . ($sidang->r_pkl->r_mahasiswa->nama ?? ''),
                    'start' => $start,
                    'end' => $end,
                    'room' => $sidang->r_ruang ? $sidang->r_ruang->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            })->merge($jadwal_sidang_sempro_kaprodi->map(function ($sidang) {
                $start = null;
                $end = null;
                if ($sidang->tanggal_sempro && $sidang->r_sesi) {
                    $times = explode(' - ', $sidang->r_sesi->jam);
                    if (count($times) === 2) {
                        $start = \Carbon\Carbon::parse($sidang->tanggal_sempro . ' ' . trim($times[0]))->toDateTimeString();
                        $end = \Carbon\Carbon::parse($sidang->tanggal_sempro . ' ' . trim($times[1]))->toDateTimeString();
                    }
                }
                return [
                    'title' => 'Sidang Sempro - ' . ($sidang->r_mahasiswa->nama ?? ''),
                    'start' => $start,
                    'end' => $end,
                    'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            }))->merge($jadwal_sidang_ta_kaprodi->map(function ($sidang) {
                $start = null;
                $end = null;
                if ($sidang->tanggal_ta && $sidang->r_sesi) {
                    $times = explode(' - ', $sidang->r_sesi->jam);
                    if (count($times) === 2) {
                        $start = \Carbon\Carbon::parse($sidang->tanggal_ta . ' ' . trim($times[0]))->toDateTimeString();
                        $end = \Carbon\Carbon::parse($sidang->tanggal_ta . ' ' . trim($times[1]))->toDateTimeString();
                    }
                }
                return [
                    'title' => 'Sidang TA - ' . ($sidang->r_mahasiswa->nama ?? ''),
                    'start' => $start,
                    'end' => $end,
                    'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            }))->values();
            $data = array_merge($data, compact(
                'Kaprodi',
                'percentUploadedPKL',
                'percentVerifiedPKL',
                'banyak_pengunggahan_pkl',
                'banyak_verifikasi_pkl',
                'percentUploadedSempro',
                'percentVerifiedSempro',
                'banyak_pengunggahan_sempro',
                'banyak_verifikasi_sempro',
                'percentUploadedTa',
                'percentVerifiedTa',
                'banyak_pengunggahan_ta',
                'banyak_verifikasi_ta',
                'pklBelumDiterima',
                'pklDiterima',
                'pklSelesai',
                'semproJudulDiajukan',
                'semproSelesai',
                'jadwal_sidang_pkl_kaprodi',
                'jadwal_sidang_sempro_kaprodi',
                'jadwal_sidang_ta_kaprodi',
                'eventsKaprodi'
            ));
        }


        if ($user->hasRole('admin')) {

            $MahasiswaPkl = MahasiswaPkl::count();

            $MahasiswaSelesaiPkl = MahasiswaPkl::whereNotNull('nilai_mahasiswa')
                ->count();

            $MahasiswaSempro = MahasiswaSempro::count();

            $MahasiswaSelesaiSempro = MahasiswaSempro::whereNotNull('nilai_mahasiswa')
                ->count();


            $MahasiswaTA = MahasiswaTa::count();

            $MahasiswaSelesaiTA = MahasiswaTa::whereNotNull('nilai_mahasiswa')
                ->count();
            $jadwal_sidang_pkl_admin = MahasiswaPkl::with(['r_sesi', 'r_ruang'])->get();
            $jadwal_sidang_sempro_admin = MahasiswaSempro::with(['r_sesi', 'r_ruangan'])->get();
            $jadwal_sidang_ta_admin = MahasiswaTa::with(['r_sesi', 'r_ruangan'])->get();

            $eventsAdmin = $jadwal_sidang_pkl_admin->map(function ($sidang) {
                $start = null;
                $end = null;
                if ($sidang->tgl_sidang && $sidang->r_sesi) {
                    $times = explode(' - ', $sidang->r_sesi->jam);
                    if (count($times) === 2) {
                        $start = \Carbon\Carbon::parse($sidang->tgl_sidang . ' ' . trim($times[0]))->toDateTimeString();
                        $end = \Carbon\Carbon::parse($sidang->tgl_sidang . ' ' . trim($times[1]))->toDateTimeString();
                    }
                }
                return [
                    'title' => 'Sidang PKL - ' . ($sidang->r_pkl->r_mahasiswa->nama ?? ''),
                    'start' => $start,
                    'end' => $end,
                    'room' => $sidang->r_ruang ? $sidang->r_ruang->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            })->merge($jadwal_sidang_sempro_admin->map(function ($sidang) {
                $start = null;
                $end = null;
                if ($sidang->tanggal_sempro && $sidang->r_sesi) {
                    $times = explode(' - ', $sidang->r_sesi->jam);
                    if (count($times) === 2) {
                        $start = \Carbon\Carbon::parse($sidang->tanggal_sempro . ' ' . trim($times[0]))->toDateTimeString();
                        $end = \Carbon\Carbon::parse($sidang->tanggal_sempro . ' ' . trim($times[1]))->toDateTimeString();
                    }
                }
                return [
                    'title' => 'Sidang Sempro - ' . ($sidang->r_mahasiswa->nama ?? ''),
                    'start' => $start,
                    'end' => $end,
                    'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            }))->merge($jadwal_sidang_ta_admin->map(function ($sidang) {
                $start = null;
                $end = null;
                if ($sidang->tanggal_ta && $sidang->r_sesi) {
                    $times = explode(' - ', $sidang->r_sesi->jam);
                    if (count($times) === 2) {
                        $start = \Carbon\Carbon::parse($sidang->tanggal_ta . ' ' . trim($times[0]))->toDateTimeString();
                        $end = \Carbon\Carbon::parse($sidang->tanggal_ta . ' ' . trim($times[1]))->toDateTimeString();
                    }
                }
                return [
                    'title' => 'Sidang TA - ' . ($sidang->r_mahasiswa->nama ?? ''),
                    'start' => $start,
                    'end' => $end,
                    'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            }))->values();


            $data = array_merge($data, compact(
                'MahasiswaPkl',
                'MahasiswaSelesaiPkl',
                'MahasiswaSempro',
                'MahasiswaSelesaiSempro',
                'MahasiswaTA',
                'MahasiswaSelesaiTA',
                'eventsAdmin'
            ));
        }

        if ($user->hasRole('mahasiswa')) {

            $jadwal_sidang_pkl = MahasiswaPkl::where('mahasiswa_id', $user->r_mahasiswa ? $user->r_mahasiswa->id_mahasiswa : null)->get();

            // dd($jadwal_sidang_pkl);

            // dd($events);

            $jadwal_sidang_sempro = MahasiswaSempro::where('mahasiswa_id', $user->r_mahasiswa ? $user->r_mahasiswa->id_mahasiswa : null)->get();

            // dd($jadwal_sidang_sempro);
            $jadwal_sidang_ta = MahasiswaTa::where('mahasiswa_id', $user->r_mahasiswa ? $user->r_mahasiswa->id_mahasiswa : null)->get();

            $eventsMahasiswa = $jadwal_sidang_pkl->map(function ($sidang) {
                $start = null;
                $end = null;
                if ($sidang->tgl_sidang && $sidang->r_sesi) {
                    $times = explode(' - ', $sidang->r_sesi->jam);
                    if (count($times) === 2) {
                        $start = \Carbon\Carbon::parse($sidang->tgl_sidang . ' ' . trim($times[0]))->toDateTimeString();
                        $end = \Carbon\Carbon::parse($sidang->tgl_sidang . ' ' . trim($times[1]))->toDateTimeString();
                    }
                }
                return [
                    'title' => 'Sidang PKL',
                    'start' => $start,
                    'end' => $end,
                    'room' => $sidang->r_ruang ? $sidang->r_ruang->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            })->merge($jadwal_sidang_sempro->map(function ($sidang) {
                $start = null;
                $end = null;
                if ($sidang->tanggal_sempro && $sidang->r_sesi) {
                    $times = explode(' - ', $sidang->r_sesi->jam);
                    if (count($times) === 2) {
                        $start = \Carbon\Carbon::parse($sidang->tanggal_sempro . ' ' . trim($times[0]))->toDateTimeString();
                        $end = \Carbon\Carbon::parse($sidang->tanggal_sempro . ' ' . trim($times[1]))->toDateTimeString();
                    }
                }
                return [
                    'title' => 'Sidang Sempro',
                    'start' => $start,
                    'end' => $end,
                    'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            }))->merge($jadwal_sidang_ta->map(function ($sidang) {
                $start = null;
                $end = null;
                if ($sidang->tanggal_ta && $sidang->r_sesi) {
                    $times = explode(' - ', $sidang->r_sesi->jam);
                    if (count($times) === 2) {
                        $start = \Carbon\Carbon::parse($sidang->tanggal_ta . ' ' . trim($times[0]))->toDateTimeString();
                        $end = \Carbon\Carbon::parse($sidang->tanggal_ta . ' ' . trim($times[1]))->toDateTimeString();
                    }
                }
                return [
                    'title' => 'Sidang TA',
                    'start' => $start,
                    'end' => $end,
                    'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                    'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                ];
            }))->values();


            $data = array_merge($data, compact(
                'jadwal_sidang_pkl',
                'jadwal_sidang_sempro',
                'jadwal_sidang_ta',
                'eventsMahasiswa'
            ));
        }



        if ($user->hasRole('pengujiPkl|pengujiSempro|pengujiTa')) {

            $pklDiterimaPenguji = MahasiswaPkl::where('dosen_penguji', $user->r_dosen->id_dosen)
                ->count();

            $pklSelesaiPenguji = MahasiswaPkl::where('dosen_penguji', $user->r_dosen->id_dosen)
                ->whereHas('r_nilai_penguji', function ($query) {
                    $query->where('status', '1')->whereNotNull('nilai_pkl');
                })
                ->count();

            $semproDiterimaPenguji = MahasiswaSempro::where('penguji', $user->r_dosen->id_dosen)
                ->count();

            $semproSelesaiPenguji = MahasiswaSempro::where('penguji', $user->r_dosen->id_dosen)
                ->whereHas('r_nilai_penguji', function ($query) {
                    $query->where('status', '2')->whereNotNull('nilai_sempro');
                })
                ->count();


            $taDiterimaPenguji = MahasiswaTa::where(function ($query) use ($user) {
                $query->where('ketua', $user->r_dosen->id_dosen)
                    ->orWhere('sekretaris', $user->r_dosen->id_dosen)
                    ->orWhere('penguji_1', $user->r_dosen->id_dosen)
                    ->orWhere('penguji_2', $user->r_dosen->id_dosen);
            })->count();

            $taSelesaiPenguji = MahasiswaTa::where(function ($query) use ($user) {
                $query->where('ketua', $user->r_dosen->id_dosen)
                    ->orWhere('sekretaris', $user->r_dosen->id_dosen)
                    ->orWhere('penguji_1', $user->r_dosen->id_dosen)
                    ->orWhere('penguji_2', $user->r_dosen->id_dosen);
            })
                ->whereHas('r_nilai_ketua', function ($query) {
                    $query->where('status', '0')->whereNotNull('nilai_sidang');
                })
                ->orWhereHas('r_nilai_sekretaris', function ($query) {
                    $query->where('status', '1')->whereNotNull('nilai_sidang');
                })
                ->whereHas('r_nilai_penguji_1', function ($query) {
                    $query->where('status', '2')->whereNotNull('nilai_sidang');
                })
                ->orWhereHas('r_nilai_penguji_2', function ($query) {
                    $query->where('status', '3')->whereNotNull('nilai_sidang');
                })
                ->count();
            $dosenId = $user->r_dosen->id_dosen;


            $jadwal_sidang_pkl_penguji = MahasiswaPkl::where('dosen_penguji', $dosenId)->get();
            $jadwal_sidang_sempro_penguji = MahasiswaSempro::where('penguji', $dosenId)->get();
            $jadwal_sidang_ta_penguji = MahasiswaTa::where(function ($query) use ($dosenId) {
                $query->where('ketua', $dosenId)
                      ->orWhere('sekretaris', $dosenId)
                      ->orWhere('penguji_1', $dosenId)
                      ->orWhere('penguji_2', $dosenId);
            })->get();

            $eventsPenguji = collect();

            if ($jadwal_sidang_pkl_penguji->isNotEmpty()) {
                $eventsPenguji = $eventsPenguji->merge($jadwal_sidang_pkl_penguji->map(function ($sidang) use ($dosenId) {
                    $start = null;
                    $end = null;
                    if ($sidang->tgl_sidang && $sidang->r_sesi) {
                        $times = explode(' - ', $sidang->r_sesi->jam);
                        if (count($times) === 2) {
                            $start = \Carbon\Carbon::parse($sidang->tgl_sidang . ' ' . trim($times[0]))->toDateTimeString();
                            $end = \Carbon\Carbon::parse($sidang->tgl_sidang . ' ' . trim($times[1]))->toDateTimeString();
                        }
                    }
                    $role = 'Penguji'; // Role untuk PKL
                    return [
                        'title' => 'Sidang PKL - ' . ($sidang->r_pkl->r_mahasiswa->nama ?? ''),
                        'start' => $start,
                        'end' => $end,
                        'room' => $sidang->r_ruang ? $sidang->r_ruang->kode_ruang : '',
                        'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                        'role' => $role, 
                    ];
                }));
            }

            if ($jadwal_sidang_sempro_penguji->isNotEmpty()) {
                $eventsPenguji = $eventsPenguji->merge($jadwal_sidang_sempro_penguji->map(function ($sidang) use ($dosenId) {
                    $start = null;
                    $end = null;
                    if ($sidang->tanggal_sempro && $sidang->r_sesi) {
                        $times = explode(' - ', $sidang->r_sesi->jam);
                        if (count($times) === 2) {
                            $start = \Carbon\Carbon::parse($sidang->tanggal_sempro . ' ' . trim($times[0]))->toDateTimeString();
                            $end = \Carbon\Carbon::parse($sidang->tanggal_sempro . ' ' . trim($times[1]))->toDateTimeString();
                        }
                    }
                    $role = 'Penguji';
                    return [
                        'title' => 'Sidang Sempro - ' . ($sidang->r_mahasiswa->nama ?? ''),
                        'start' => $start,
                        'end' => $end,
                        'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                        'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                        'role' => $role,
                    ];
                }));
            }

            if ($jadwal_sidang_ta_penguji->isNotEmpty()) {
                $eventsPenguji = $eventsPenguji->merge($jadwal_sidang_ta_penguji->map(function ($sidang) use ($dosenId) {
                    $start = null;
                    $end = null;
                    if ($sidang->tanggal_ta && $sidang->r_sesi) {
                        $times = explode(' - ', $sidang->r_sesi->jam);
                        if (count($times) === 2) {
                            $start = \Carbon\Carbon::parse($sidang->tanggal_ta . ' ' . trim($times[0]))->toDateTimeString();
                            $end = \Carbon\Carbon::parse($sidang->tanggal_ta . ' ' . trim($times[1]))->toDateTimeString();
                        }
                    }
                    $role = '';
                    if ($sidang->ketua == $dosenId) {
                        $role = 'Ketua';
                    } elseif ($sidang->sekretaris == $dosenId) {
                        $role = 'Sekretaris';
                    } elseif ($sidang->penguji_1 == $dosenId) {
                        $role = 'Penguji 1';
                    } elseif ($sidang->penguji_2 == $dosenId) {
                        $role = 'Penguji 2';
                    }
                    return [
                        'title' => 'Sidang TA - ' . ($sidang->r_mahasiswa->nama ?? ''),
                        'start' => $start,
                        'end' => $end,
                        'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                        'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                        'role' => $role,
                    ];
                }));
            }

            $eventsPenguji = $eventsPenguji->values()->toArray();
            // dd($eventsPenguji);

            $data = array_merge($data, compact(
                'pklDiterimaPenguji',
                'pklSelesaiPenguji',
                'semproDiterimaPenguji',
                'semproSelesaiPenguji',
                'taDiterimaPenguji',
                'taSelesaiPenguji',
                'jadwal_sidang_pkl_penguji',
                'jadwal_sidang_sempro_penguji',
                'jadwal_sidang_ta_penguji',
                'eventsPenguji'
            ));
        }

        if ($user->hasRole('pembimbingPkl|pembimbingSempro|pembimbingTa')) {

            $pklDiterimaPembimbing = MahasiswaPkl::where('dosen_pembimbing', $user->r_dosen->id_dosen)
                ->count();

            $pklSelesaiPembimbing = MahasiswaPkl::where('dosen_pembimbing', $user->r_dosen->id_dosen)
                ->whereHas('r_nilai_pembimbing', function ($query) {
                    $query->where('status', '0')->whereNotNull('nilai_pkl');
                })
                ->count();

            $semproDiterimaPembimbing = MahasiswaSempro::where(function ($query) use ($user) {
                $query->where('pembimbing_satu', $user->r_dosen->id_dosen)
                    ->orWhere('pembimbing_dua', $user->r_dosen->id_dosen);
            })->count();

            $semproSelesaiPembimbing = MahasiswaSempro::where(function ($query) use ($user) {
                $query->where('pembimbing_satu', $user->r_dosen->id_dosen)
                    ->orWhere('pembimbing_dua', $user->r_dosen->id_dosen);
            })
                ->whereHas('r_nilai_pembimbing_satu', function ($query) {
                    $query->where('status', '0')->whereNotNull('nilai_sempro');
                })
                ->whereHas('r_nilai_pembimbing_dua', function ($query) {
                    $query->where('status', '1')->whereNotNull('nilai_sempro');
                })
                ->count();

            $taDiterimaPembimbing = MahasiswaTa::where(function ($query) use ($user) {
                $query->where('pembimbing_satu_id', $user->r_dosen->id_dosen)
                    ->orWhere('pembimbing_dua_id', $user->r_dosen->id_dosen);
            })->count();


            $taSelesaiPembimbing = MahasiswaTa::where(function ($query) use ($user) {
                $query->where('pembimbing_satu_id', $user->r_dosen->id_dosen)
                    ->orWhere('pembimbing_dua_id', $user->r_dosen->id_dosen);
            })
                ->where(function ($query) {
                    $query->where('acc_pembimbing_satu', '1');
                })
                ->where(function ($query) {
                    $query->where('acc_pembimbing_dua', '1');
                })
                ->count();

            // $taSelesaiPembimbing = MahasiswaTa::where(function ($query) use ($user) {
            //     $query->where('pembimbing_satu_id', $user->r_dosen->id_dosen)
            //         ->orWhere('pembimbing_dua_id', $user->r_dosen->id_dosen);
            // })
            //     ->whereHas('r_nilai_pembimbing_satu', function ($query) {
            //         $query->where('status', '0')->whereNotNull('nilai_bimbingan');
            //     })
            //     ->whereHas('r_nilai_pembimbing_dua', function ($query) {
            //         $query->where('status', '1')->whereNotNull('nilai_bimbingan');
            //     })
            //     ->count();
            $dosenId = $user->r_dosen->id_dosen;

            $jadwal_sidang_pkl_pembimbing = MahasiswaPkl::where('dosen_pembimbing', $dosenId)->get();
            $jadwal_sidang_sempro_pembimbing = MahasiswaSempro::where(function ($query) use ($dosenId) {
                $query->where('pembimbing_satu', $dosenId)
                      ->orWhere('pembimbing_dua', $dosenId);
            })->get();
            $jadwal_sidang_ta_pembimbing = MahasiswaTa::where(function ($query) use ($dosenId) {
                $query->where('pembimbing_satu_id', $dosenId)
                      ->orWhere('pembimbing_dua_id', $dosenId);
            })->get();

            $eventsPembimbing = collect();

            if ($jadwal_sidang_pkl_pembimbing->isNotEmpty()) {
                $eventsPembimbing = $eventsPembimbing->merge($jadwal_sidang_pkl_pembimbing->map(function ($sidang) use ($dosenId) {
                    $start = null;
                    $end = null;
                    if ($sidang->tgl_sidang && $sidang->r_sesi) {
                        $times = explode(' - ', $sidang->r_sesi->jam);
                        if (count($times) === 2) {
                            $start = \Carbon\Carbon::parse($sidang->tgl_sidang . ' ' . trim($times[0]))->toDateTimeString();
                            $end = \Carbon\Carbon::parse($sidang->tgl_sidang . ' ' . trim($times[1]))->toDateTimeString();
                        }
                    }
                    $role = 'Pembimbing';
                    return [
                        'title' => 'Sidang PKL - ' . ($sidang->r_pkl->r_mahasiswa->nama ?? ''),
                        'start' => $start,
                        'end' => $end,
                        'room' => $sidang->r_ruang ? $sidang->r_ruang->kode_ruang : '',
                        'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                        'role' => $role,
                    ];
                }));
            }

            if ($jadwal_sidang_sempro_pembimbing->isNotEmpty()) {
                $eventsPembimbing = $eventsPembimbing->merge($jadwal_sidang_sempro_pembimbing->map(function ($sidang) use ($dosenId) {
                    $start = null;
                    $end = null;
                    if ($sidang->tanggal_sempro && $sidang->r_sesi) {
                        $times = explode(' - ', $sidang->r_sesi->jam);
                        if (count($times) === 2) {
                            $start = \Carbon\Carbon::parse($sidang->tanggal_sempro . ' ' . trim($times[0]))->toDateTimeString();
                            $end = \Carbon\Carbon::parse($sidang->tanggal_sempro . ' ' . trim($times[1]))->toDateTimeString();
                        }
                    }
                    $role = ($sidang->pembimbing_satu == $dosenId) ? 'Pembimbing 1' : 'Pembimbing 2';
                    return [
                        'title' => 'Sidang Sempro - ' . ($sidang->r_mahasiswa->nama ?? ''),
                        'start' => $start,
                        'end' => $end,
                        'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                        'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                        'role' => $role,
                    ];
                }));
            }

            if ($jadwal_sidang_ta_pembimbing->isNotEmpty()) {
                $eventsPembimbing = $eventsPembimbing->merge($jadwal_sidang_ta_pembimbing->map(function ($sidang) use ($dosenId) {
                    $start = null;
                    $end = null;
                    if ($sidang->tanggal_ta && $sidang->r_sesi) {
                        $times = explode(' - ', $sidang->r_sesi->jam);
                        if (count($times) === 2) {
                            $start = \Carbon\Carbon::parse($sidang->tanggal_ta . ' ' . trim($times[0]))->toDateTimeString();
                            $end = \Carbon\Carbon::parse($sidang->tanggal_ta . ' ' . trim($times[1]))->toDateTimeString();
                        }
                    }
                    $role = ($sidang->pembimbing_satu_id == $dosenId) ? 'Pembimbing 1' : 'Pembimbing 2';
                    return [
                        'title' => 'Sidang TA - ' . ($sidang->r_mahasiswa->nama ?? ''),
                        'start' => $start,
                        'end' => $end,
                        'room' => $sidang->r_ruangan ? $sidang->r_ruangan->kode_ruang : '',
                        'session' => $sidang->r_sesi ? $sidang->r_sesi->jam : '',
                        'role' => $role,
                    ];
                }));
            }

            $eventsPembimbing = $eventsPembimbing->values()->toArray();



            // dd($semproDiterimaPenguji);

            $data = array_merge($data, compact(
                'pklDiterimaPembimbing',
                'pklSelesaiPembimbing',
                'semproDiterimaPembimbing',
                'semproSelesaiPembimbing',
                'taDiterimaPembimbing',
                'taSelesaiPembimbing',
                'eventsPembimbing'
            ));
        }

        return view('admin.content.dashboard', $data);
    }
}
