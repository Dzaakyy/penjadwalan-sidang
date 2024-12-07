<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_jurusan', 'kode_jurusan', 'nama_jurusan'
    ];
    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';
    public $timestamps = false;

    public function r_prodi()
    {
        return $this->hasMany(Prodi::class, 'jurusan_id', 'id_jurusan');
    }
}
