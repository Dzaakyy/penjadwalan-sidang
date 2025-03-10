<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaPkl extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_mhs_pkl',
        'mahasiswa_id',
        'judul',
        'pembimbing_pkl',
        'tahun_pkl',
        'dokumen_nilai_industri',
        'nilai_pembimbing_industri',
        'laporan_pkl',
        'status_admin',
        'ruang_sidang',
        'dosen_pembimbing',
        'dosen_penguji',
        'jam_sidang',
        'tgl_sidang',
        'nilai_mahasiswa',
    ]; 
    protected $table = 'mhs_pkl';
    protected $primaryKey = 'id_mhs_pkl';
    public $timestamps = false;



    public static function boot()
    {
        parent::boot();
        MahasiswaPkl::all()->each(function ($sidangPkl) {
            $nilaiBimbingan = $sidangPkl->r_nilai_bimbingan->nilai_bimbingan ?? null;
            $nilaiPembimbing = $sidangPkl->r_nilai_pembimbing->nilai_pkl ?? null;
            $nilaiPenguji = $sidangPkl->r_nilai_penguji->nilai_pkl ?? null;
            $nilaiIndustri = $sidangPkl->nilai_pembimbing_industri ?? null;

            if ($nilaiBimbingan !== null && $nilaiPembimbing !== null && $nilaiPenguji !== null && $nilaiIndustri !== null) {
                $nilaimahasiswa = ($nilaiBimbingan * 0.35) + ($nilaiIndustri * 0.3) + ((($nilaiPembimbing + $nilaiPenguji) /2) * 0.35) ;

                $sidangPkl->nilai_mahasiswa = $nilaimahasiswa;


                $sidangPkl->save();
            } else {
            }
        });
    }


    public function r_pkl()
    {
        return $this->belongsTo(UsulanPkl::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function r_dosen_pembimbing()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing', 'id_dosen');
    }

    public function r_dosen_penguji()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji', 'id_dosen');
    }

    public function r_ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_sidang', 'id_ruang');
    }

    public function r_sesi()
    {
        return $this->belongsTo(Sesi::class, 'jam_sidang', 'id_sesi');
    }

    public function r_bimbingan()
    {
        return $this->hasMany(BimbinganPkl::class, 'pkl_id', 'id_mhs_pkl');
    }

    public function r_nilai_bimbingan()
    {
        return $this->hasOne(NilaiBimbinganPkl::class, 'mhs_pkl_id', 'id_mhs_pkl');
    }

    public function r_nilai_pembimbing()
    {
        return $this->hasOne(NilaiPkl::class, 'mhs_pkl_id', 'id_mhs_pkl')->where('status', '0');
    }
    public function r_nilai_penguji()
    {
        return $this->hasOne(NilaiPkl::class, 'mhs_pkl_id', 'id_mhs_pkl')->where('status', '1');
    }


    public function r_usulan_pkl()
    {
        return $this->belongsTo(UsulanPkl::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
