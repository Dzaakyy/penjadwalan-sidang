<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanPimpinan extends Model
{
    use HasFactory;
    protected $fillable = ['id_jabatan_pimpinan', 'jabatan_pimpinan', 'kode_jabatan_pimpinan', 'status_jabatan_pimpinan'];
    protected $table = 'jabatan_pimpinan';
    protected $primaryKey = 'id_jabatan_pimpinan';
    public $timestamps = false;
}
