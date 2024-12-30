<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganTa extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_bimbingan_ta',
        'ta_id',
        'dosen_id',
        'pembahasan',
        'tgl_bimbingan',
        'file_bimbingan',
        'komentar',
        'status'
    ];
    protected $table = 'bimbingan_ta';
    protected $primaryKey = 'id_bimbingan_ta';
    public $timestamps = false;

    public function r_ta()
    {
        return $this->belongsTo(MahasiswaTa::class, 'ta_id', 'id_ta');
    }
    public function r_dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'id_dosen');
    }

}
