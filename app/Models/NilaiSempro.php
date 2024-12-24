<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSempro extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_nilai_sempro',
        'sempro_id',
        'pendahuluan',
        'tinjauan_pustaka',
        'metodologi',
        'penggunaan_bahasa',
        'presentasi',
        'nilai_sempro',
        'status'
    ];
    protected $table = 'nilai_sempro';
    protected $primaryKey = 'id_nilai_sempro';
    public $timestamps = false;

    public function r_sempro()
    {
        return $this->belongsTo(MahasiswaSempro::class, 'sempro_id', 'id_sempro');
    }
}
