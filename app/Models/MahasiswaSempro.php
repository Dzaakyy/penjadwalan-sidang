<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaSempro extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_sempro',
        'mahasiswa_id',
        'judul',
        'file_sempro',
        'pembimbing_satu',
        'pembimbing_dua',
        'penguji',
        'tanggal_sempro',
        'ruangan_id',
        'sesi_id',
        'nilai_mahasiswa',
        'status_judul',
        'status_berkas',
        'keterangan',
    ];
    protected $table = 'mhs_sempro';
    protected $primaryKey = 'id_sempro';
    public $timestamps = false;


    public static function boot()
{
    parent::boot();

    MahasiswaSempro::all()->each(function ($sidangSempro) {
        $nilaiPembimbingSatu = $sidangSempro->r_nilai_pembimbing_satu->nilai_sempro ?? null;
        $nilaiPembimbingDua = $sidangSempro->r_nilai_pembimbing_dua->nilai_sempro ?? null;
        $nilaiPenguji = $sidangSempro->r_nilai_penguji->nilai_sempro ?? null;

        if ($nilaiPembimbingSatu !== null && $nilaiPembimbingDua !== null && $nilaiPenguji !== null) {
            $nilaimahasiswa = ($nilaiPembimbingSatu + $nilaiPembimbingDua + $nilaiPenguji) / 3;

            $sidangSempro->nilai_mahasiswa = $nilaimahasiswa;

            if ($nilaimahasiswa >= 74) {
                $sidangSempro->keterangan = '1';

                // Cek apakah mahasiswa_id sudah ada di tabel mhs_ta
                if (!MahasiswaTa::where('mahasiswa_id', $sidangSempro->mahasiswa_id)->exists()) {
                    MahasiswaTa::create([
                        'id_ta' => self::getCariNomor(), // Call the static method
                        'mahasiswa_id' => $sidangSempro->mahasiswa_id,
                        'pembimbing_satu_id' => $sidangSempro->pembimbing_satu,
                        'pembimbing_dua_id' => $sidangSempro->pembimbing_dua,
                        'keterangan' => '0',
                    ]);

                    $pembimbing_ids = [$sidangSempro->pembimbing_satu, $sidangSempro->pembimbing_dua];
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
                }
            } else {
                $sidangSempro->keterangan = '2';
            }
        } else {
            $sidangSempro->keterangan = '0';
        }

        $sidangSempro->save();
    });
}


    public static function getCariNomor()
    {
        $id_ta = MahasiswaTa::pluck('id_ta')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_ta)) {
                return $i;
            }
        }
    }

    public function r_mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }
    public function r_pembimbing_satu()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_satu', 'id_dosen');
    }
    public function r_pembimbing_dua()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_dua', 'id_dosen');
    }
    public function r_penguji()
    {
        return $this->belongsTo(Dosen::class, 'penguji', 'id_dosen');
    }
    public function r_ruangan()
    {
        return $this->belongsTo(Ruang::class, 'ruangan_id', 'id_ruang');
    }
    public function r_sesi()
    {
        return $this->belongsTo(Sesi::class, 'sesi_id', 'id_sesi');
    }
    public function r_nilai_pembimbing_satu()
    {
        return $this->hasOne(NilaiSempro::class, 'sempro_id', 'id_sempro')->where('status', '0');
    }
    public function r_nilai_pembimbing_dua()
    {
        return $this->hasOne(NilaiSempro::class, 'sempro_id', 'id_sempro')->where('status', '1');
    }
    public function r_nilai_penguji()
    {
        return $this->hasOne(NilaiSempro::class, 'sempro_id', 'id_sempro')->where('status', '2');
    }
}
