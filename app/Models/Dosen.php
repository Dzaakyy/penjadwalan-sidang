<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_dosen', 'nama_dosen', 'nidn', 'nip', 'gender', 'user_id', 'jurusan_id', 'prodi_id', 'image', 'golongan', 'status_dosen'
    ];
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    public $timestamps = false;


    public function r_user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function r_jurusan(){
        return $this->belongsTo(Jurusan::class, 'jurusan_id','id_jurusan');
    }

    public function r_prodi(){
        return $this->belongsTo(Prodi::class, 'prodi_id','id_prodi');
    }
}
