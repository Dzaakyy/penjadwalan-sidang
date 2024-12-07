<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiBimbinganPkl extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_nilai_bimbingan_pkl',
        'mhs_pkl_id',
        'keaktifan',
        'komunikatif',
        'problem_solving',
        'nilai_bimbingan'
    ];
    protected $table = 'nilai_bimbingan_pkl';
    protected $primaryKey = 'id_nilai_bimbingan_pkl';
    public $timestamps = false;


    public function r_mhs_pkl()
    {
        return $this->belongsTo(MahasiswaPkl::class, 'mhs_pkl_id', 'id_mhs_pkl');
    }
}
