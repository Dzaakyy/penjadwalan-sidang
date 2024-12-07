<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganPkl extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_bimbingan_pkl',
        'pkl_id',
        'kegiatan', 
        'tgl_kegiatan_awal',
        'tgl_kegiatan_akhir',
        'file_dokumentasi',
        'komentar',
        'status'
    ];
    protected $table = 'bimbingan_pkl';
    protected $primaryKey = 'id_bimbingan_pkl';
    public $timestamps = false;



    public function r_pkl()
    {
        return $this->belongsTo(MahasiswaPkl::class, 'pkl_id', 'id_mhs_pkl');
    }

}
