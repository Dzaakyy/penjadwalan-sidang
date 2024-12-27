<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiTa extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_nilai_sidang_ta',
        'ta_id',
        'sikap_penampilan',
        'komunikasi_sistematika',
        'penguasaan_materi',
        'identifikasi_masalah',
        'relevansi_teori',
        'metode_algoritma',
        'hasil_pembahasan',
        'kesimpulan_saran',
        'bahasa_tata_tulis',
        'kesesuaian_fungsional',
        'nilai_sidang',
        'status'
    ];
    protected $table = 'nilai_sidang_ta';
    protected $primaryKey = 'id_nilai_sidang_ta';
    public $timestamps = false;

    public function r_ta()
    {
        return $this->belongsTo(MahasiswaTa::class, 'ta_id', 'id_ta');
    }
}
