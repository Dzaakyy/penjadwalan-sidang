<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   UsulanPkl extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_usulan_pkl',
        'mahasiswa_id',
        'perusahaan_id',
        'konfirmasi'
    ];
    protected $table = 'usulan_pkl';
    protected $primaryKey = 'id_usulan_pkl';
    public $timestamps = false;

    public function r_mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    public function r_perusahaan()
    {
        return $this->belongsTo(TempatPkl::class, 'perusahaan_id', 'id_perusahaan');
    }

    public function mhs_pkl()
    {
        return $this->hasOne(MahasiswaPkl::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
