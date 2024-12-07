<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    use HasFactory;
    protected $fillable = ['id_sesi', 'sesi', 'jam'];
    protected $table = 'sesi';
    protected $primaryKey = 'id_sesi';
    public $timestamps = false;
}
