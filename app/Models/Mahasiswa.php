<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_mahasiswa',
        'nim',
        'nama',
        'user_id',
        'prodi_id',
        'image',
        'gender',
        'status_mahasiswa'
    ];
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    public $timestamps = false;


    // public function r_jurusan(){
    //     return $this->belongsTo(Jurusan::class, 'jurusan_id','id_jurusan');
    // }
    public function r_user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function r_prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', 'id_prodi');
    }

    public function usulan_pkl()
    {
        return $this->hasOne(UsulanPkl::class, 'mahasiswa_id', 'id_mahasiswa');
    }
}
