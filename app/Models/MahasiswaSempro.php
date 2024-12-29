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
                $nilaimahasiswa = ($nilaiPembimbingSatu+$nilaiPembimbingDua+$nilaiPenguji) /3 ;

                $sidangSempro->nilai_mahasiswa = $nilaimahasiswa;

                $sidangSempro->save();
            } else {
            }
        });
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
