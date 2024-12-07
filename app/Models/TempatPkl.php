<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempatPkl extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_perusahaan', 'nama_perusahaan', 'deskripsi', 'kuota' ,'status'
    ];
    protected $table = 'tempat_pkl';
    protected $primaryKey = 'id_perusahaan';
    public $timestamps = false;

    public function r_usulan_pkl()
{
    return $this->hasMany(UsulanPkl::class, 'perusahaan_id', 'id_perusahaan');
}
}
