<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;
    protected $fillable = ['id_ruang', 'kode_ruang'];
    protected $table = 'ruang';
    protected $primaryKey = 'id_ruang';
    public $timestamps = false;
}
