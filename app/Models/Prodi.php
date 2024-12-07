<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;
    protected $fillable = ['id_prodi','kode_prodi', 'prodi', 'jenjang', 'jurusan_id'];
    protected $table = 'prodi';
    protected $primaryKey = 'id_prodi';
    public $timestamps = false;

    public function r_jurusan(){
        return $this->belongsTo(Jurusan::class, 'jurusan_id','id_jurusan');
    }
}
