<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThnAjaran extends Model
{
    use HasFactory;
    protected $fillable = ['id_thn_ajaran', 'thn_ajaran', 'status'];
    protected $table = 'thn_ajaran';
    protected $primaryKey = 'id_thn_ajaran';
    public $timestamps = false;
}
