<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pimpinan extends Model
{
    use HasFactory;
    protected $fillable = ['id_pimpinan', 'dosen_id', 'jabatan_pimpinan_id','periode', 'status_pimpinan'];
    protected $table = 'pimpinan';
    public $timestamps = false;
    protected $primaryKey = 'id_pimpinan';


    public function r_dosen(){
        return $this->belongsTo(Dosen::class, 'dosen_id','id_dosen');
    }

    public function r_prodi(){
        return $this->belongsTo(Prodi::class,'id_prodi');
    }

    public function r_jabatan_pimpinan(){
        return $this->belongsTo(JabatanPimpinan::class, 'jabatan_pimpinan_id','id_jabatan_pimpinan');
    }

    public function scopeKajur($query)
    {
        return $query->whereHas('r_jabatan_pimpinan', function ($query) {
            $query->where('kode_jabatan_pimpinan', 'Kajur');
        });
    }
    public function scopeKaprodi($query)
    {
        return $query->whereHas('r_jabatan_pimpinan', function ($query) {
            $query->where('kode_jabatan_pimpinan', 'Kaprodi');
        });
    }

}
