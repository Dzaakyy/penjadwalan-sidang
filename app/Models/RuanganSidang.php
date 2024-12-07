<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuanganSidang extends Model
{
    use HasFactory;
    protected $fillable = ['id_ruangan_sidang','nama_ruangan', 'sesi_ruangan'];
    protected $table = 'ruangan_sidang';
    protected $primaryKey = 'id_ruangan_sidang';
    public $timestamps = false;

    public function r_ruangan(){
        return $this->belongsTo(Ruang::class, 'ruangan_sidang','id_ruang');
    }

    public function r_sesi(){
        return $this->belongsTo(Sesi::class, 'sesi_ruangan','id_sesi');
    }
}
