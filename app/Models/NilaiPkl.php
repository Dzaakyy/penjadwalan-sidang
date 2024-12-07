<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiPkl extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_nilai_pkl',
        'mhs_pkl_id',
        'bahasa',
        'analisis',
        'sikap',
        'komunikasi',
        'penyajian',
        'penguasaan',
        'nilai_pkl',
        'status'
    ];
    protected $table = 'nilai_pkl';
    protected $primaryKey = 'id_nilai_pkl';
    public $timestamps = false;


    public function r_mhs_pkl()
    {
        return $this->belongsTo(MahasiswaPkl::class, 'mhs_pkl_id', 'id_mhs_pkl');
    }
}
