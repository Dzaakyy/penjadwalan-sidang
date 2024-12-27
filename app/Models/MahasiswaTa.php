<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaTa extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_ta',
        'mahasiswa_id',
        'proposal_final',
        'laporan_ta',
        'tugas_akhir',
        'status_berkas',
        'pembimbing_satu_id',
        'pembimbing_dua_id',
        'ketua',
        'sekretaris',
        'penguji_1',
        'penguji_2',
        'tanggal_ta',
        'ruangan_id',
        'sesi_id',
        'nilai_mahasiswa',
        'keterangan',
        'acc_pembimbing_satu',
        'acc_pembimbing_dua',
    ];
    protected $table = 'mhs_ta';
    protected $primaryKey = 'id_ta';
    public $timestamps = false;

    public function r_mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }
    public function r_pembimbing_satu()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_satu_id', 'id_dosen');
    }
    public function r_pembimbing_dua()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_dua_id', 'id_dosen');
    }
    public function r_ketua()
    {
        return $this->belongsTo(Dosen::class, 'ketua', 'id_dosen');
    }
    public function r_sekretaris()
    {
        return $this->belongsTo(Dosen::class, 'sekretaris', 'id_dosen');
    }
    public function r_penguji_1()
    {
        return $this->belongsTo(Dosen::class, 'penguji_1', 'id_dosen');
    }
    public function r_penguji_2()
    {
        return $this->belongsTo(Dosen::class, 'penguji_2', 'id_dosen');
    }
    public function r_ruangan()
    {
        return $this->belongsTo(Ruang::class, 'ruangan_id', 'id_ruang');
    }
    public function r_sesi()
    {
        return $this->belongsTo(Sesi::class, 'sesi_id', 'id_sesi');
    }
    public function r_nilai_ketua()
    {
        return $this->hasOne(NilaiTa::class, 'ta_id', 'id_sempro')->where('status', '0');
    }
    public function r_nilai_sekretaris()
    {
        return $this->hasOne(NilaiTa::class, 'ta_id', 'id_sempro')->where('status', '1');
    }
    public function r_nilai_penguji_1()
    {
        return $this->hasOne(NilaiTa::class, 'ta_id', 'id_sempro')->where('status', '2');
    }
    public function r_nilai_penguji_2()
    {
        return $this->hasOne(NilaiTa::class, 'ta_id', 'id_sempro')->where('status', '3');
    }
}
