<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaTa extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_ta',
        'mahasiswa_id',
        'proposal_final',
        'laporan_ta',
        'tugas_akhir',
        'status_berkas',
        'pembimbing_satu_id',
        'pembimbing_dua_id',
        'ketua',
        'sekretaris',
        'penguji_1',
        'penguji_2',
        'tanggal_ta',
        'ruangan_id',
        'sesi_id',
        'nilai_mahasiswa',
        'keterangan',
        'acc_pembimbing_satu',
        'acc_pembimbing_dua',
    ];
    protected $table = 'mhs_ta';
    protected $primaryKey = 'id_ta';
    public $timestamps = false;


    // public static function boot()
    // {
    //     parent::boot();
    //     MahasiswaTa::all()->each(function ($sidangTA) {
    //         $nilaiPembimbing1 = $sidangTA->r_nilai_pembimbing_1->nilai_sidang ?? null;
    //         $nilaiPembimbing2 = $sidangTA->r_nilai_pembimbing_2->nilai_sidang ?? null;
    //         $nilaiKetua = $sidangTA->r_nilai_ketua->nilai_sidang ?? null;
    //         $nilaiSekretaris = $sidangTA->r_nilai_sekretaris->nilai_sidang ?? null;
    //         $nilaiPenguji_1 = $sidangTA->r_nilai_penguji_1->nilai_sidang ?? null;
    //         $nilaiPenguji_2 = $sidangTA->r_nilai_penguji_2->nilai_sidang ?? null;

    //         if ($nilaiPembimbing1 !== null && $nilaiPembimbing2 !== null && $nilaiKetua !== null && $nilaiSekretaris !== null && $nilaiPenguji_1 !== null && $nilaiPenguji_2 !== null) {
    //             $rataRataPembimbing = ($nilaiPembimbing1 + $nilaiPembimbing2) / 2;
    //             $rataRataPenguji = ($nilaiKetua + $nilaiSekretaris + $nilaiPenguji_1 + $nilaiPenguji_2) / 4;
    //             $nilaimahasiswa = ($rataRataPembimbing + $rataRataPenguji) / 2;

    //             $sidangTA->nilai_mahasiswa = $nilaimahasiswa;

    //             if ($nilaimahasiswa >= 74) {
    //                 $sidangTA->keterangan = '2';
    //             } else {
    //                 $sidangTA->keterangan = '1';
    //             }
    //         } else {

    //             $sidangTA->keterangan = '0';
    //         }


    //         $sidangTA->save();

    //     });
    // }



    public static function boot()
    {
        parent::boot();
        MahasiswaTa::all()->each(function ($sidangTA) {
            $nilaiPembimbing1 = optional($sidangTA->r_nilai_pembimbing_1)->nilai_sidang;
            $nilaiPembimbing2 = optional($sidangTA->r_nilai_pembimbing_2)->nilai_sidang;
            $nilaiKetua = optional($sidangTA->r_nilai_ketua)->nilai_sidang;
            $nilaiSekretaris = optional($sidangTA->r_nilai_sekretaris)->nilai_sidang;
            $nilaiPenguji_1 = optional($sidangTA->r_nilai_penguji_1)->nilai_sidang;
            $nilaiPenguji_2 = optional($sidangTA->r_nilai_penguji_2)->nilai_sidang;

            if ($nilaiPembimbing1 !== null && $nilaiPembimbing2 !== null && $nilaiKetua !== null && $nilaiSekretaris !== null && $nilaiPenguji_1 !== null && $nilaiPenguji_2 !== null) {
                $rataRataPembimbing = ($nilaiPembimbing1 + $nilaiPembimbing2) / 2;
                $rataRataPenguji = ($nilaiKetua + $nilaiSekretaris + $nilaiPenguji_1 + $nilaiPenguji_2) / 4;
                $nilaimahasiswa = ($rataRataPembimbing + $rataRataPenguji) / 2;

                $sidangTA->nilai_mahasiswa = $nilaimahasiswa;

            }

            $sidangTA->save();

        });
    }

    // public static function boot()
    // {
    //     parent::boot();
    //     MahasiswaTa::all()->each(function ($sidangTA) {

    //         $nilaiPembimbing1 = $sidangTA->r_nilai_pembimbing_1->nilai_sidang ?? null;
    //         $nilaiPembimbing2 = $sidangTA->r_nilai_pembimbing_2->nilai_sidang ?? null;
    //         $nilaiKetua = $sidangTA->r_nilai_ketua->nilai_sidang ?? null;
    //         $nilaiSekretaris = $sidangTA->r_nilai_sekretaris->nilai_sidang ?? null;
    //         $nilaiPenguji_1 = $sidangTA->r_nilai_penguji_1->nilai_sidang ?? null;
    //         $nilaiPenguji_2 = $sidangTA->r_nilai_penguji_2->nilai_sidang ?? null;

    //         if (
    //             $nilaiPembimbing1 !== null && $nilaiPembimbing1 > 0 &&
    //             $nilaiPembimbing2 !== null && $nilaiPembimbing2 > 0 &&
    //             $nilaiKetua !== null && $nilaiKetua > 0 &&
    //             $nilaiSekretaris !== null && $nilaiSekretaris > 0 &&
    //             $nilaiPenguji_1 !== null && $nilaiPenguji_1 > 0 &&
    //             $nilaiPenguji_2 !== null && $nilaiPenguji_2 > 0
    //         ) {

    //             $rataRataPembimbing = ($nilaiPembimbing1 + $nilaiPembimbing2) / 2;
    //             $rataRataPenguji = ($nilaiKetua + $nilaiSekretaris + $nilaiPenguji_1 + $nilaiPenguji_2) / 4;
    //             $nilaimahasiswa = ($rataRataPembimbing + $rataRataPenguji) / 2;

    //             if ($sidangTA->nilai_mahasiswa !== $nilaimahasiswa) {
    //                 $sidangTA->nilai_mahasiswa = $nilaimahasiswa;
    //             }

    //             if ($nilaimahasiswa >= 74) {
    //                 $sidangTA->keterangan = '2';
    //             } else {
    //                 $sidangTA->keterangan = '1';

    //                 $sidangTA->nilai_mahasiswa = null;
    //                 $sidangTA->tanggal_ta = null;
    //                 $sidangTA->ruangan_id = null;
    //                 $sidangTA->sesi_id = null;

    //                 $relations = [
    //                     $sidangTA->r_nilai_ketua,
    //                     $sidangTA->r_nilai_sekretaris,
    //                     $sidangTA->r_nilai_penguji_1,
    //                     $sidangTA->r_nilai_penguji_2,
    //                 ];

    //                 foreach ($relations as $relation) {
    //                     if ($relation) {
    //                         $relation->nilai_sidang = 0;
    //                         $relation->save();
    //                     }
    //                 }
    //             }
    //         } else {
    //             $sidangTA->keterangan = '0';
    //         }

    //         $sidangTA->save();
    //     });
    // }



    // public static function boot()
    // {
    //     parent::boot();
    //     MahasiswaTa::all()->each(function ($sidangTA) {
    //         $nilaiPembimbing1 = $sidangTA->r_nilai_pembimbing_1->nilai_sidang ?? null;
    //         $nilaiPembimbing2 = $sidangTA->r_nilai_pembimbing_2->nilai_sidang ?? null;
    //         $nilaiKetua = $sidangTA->r_nilai_ketua->nilai_sidang ?? null;
    //         $nilaiSekretaris = $sidangTA->r_nilai_sekretaris->nilai_sidang ?? null;
    //         $nilaiPenguji_1 = $sidangTA->r_nilai_penguji_1->nilai_sidang ?? null;
    //         $nilaiPenguji_2 = $sidangTA->r_nilai_penguji_2->nilai_sidang ?? null;

    //         if ($nilaiPembimbing1 !== null && $nilaiPembimbing2 !== null && $nilaiKetua !== null && $nilaiSekretaris !== null && $nilaiPenguji_1 !== null && $nilaiPenguji_2 !== null) {
    //             $rataRataPembimbing = ($nilaiPembimbing1 + $nilaiPembimbing2) / 2;
    //             $rataRataPenguji = ($nilaiKetua + $nilaiSekretaris + $nilaiPenguji_1 + $nilaiPenguji_2) / 4;
    //             $nilaimahasiswa = ($rataRataPembimbing + $rataRataPenguji) / 2;

    //             $sidangTA->nilai_mahasiswa = $nilaimahasiswa;

    //             if ($nilaimahasiswa >= 74) {
    //                 $sidangTA->keterangan = '2';
    //             } else {
    //                 $sidangTA->keterangan = '1';

    //                 if (!is_null($sidangTA->tanggal_ta) || !is_null($sidangTA->ruangan_id) || !is_null($sidangTA->sesi_id)) {
    //                     $sidangTA->tanggal_ta = null;
    //                     $sidangTA->ruangan_id = null;
    //                     $sidangTA->sesi_id = null;
    //                 }
    //             }
    //         } else {
    //             $sidangTA->keterangan = '0'; // Belum sidang
    //         }

    //         $sidangTA->save();
    //     });
    // }
    public function r_mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }
    public function r_bimbingan()
    {
        return $this->hasMany(BimbinganTa::class, 'ta_id', 'id_ta');
    }

    public function r_pembimbing_satu()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_satu_id', 'id_dosen');
    }
    public function r_pembimbing_dua()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_dua_id', 'id_dosen');
    }
    public function r_ketua()
    {
        return $this->belongsTo(Dosen::class, 'ketua', 'id_dosen');
    }
    public function r_sekretaris()
    {
        return $this->belongsTo(Dosen::class, 'sekretaris', 'id_dosen');
    }
    public function r_penguji_1()
    {
        return $this->belongsTo(Dosen::class, 'penguji_1', 'id_dosen');
    }
    public function r_penguji_2()
    {
        return $this->belongsTo(Dosen::class, 'penguji_2', 'id_dosen');
    }
    public function r_ruangan()
    {
        return $this->belongsTo(Ruang::class, 'ruangan_id', 'id_ruang');
    }
    public function r_sesi()
    {
        return $this->belongsTo(Sesi::class, 'sesi_id', 'id_sesi');
    }
    public function r_nilai_ketua()
    {
        return $this->hasOne(NilaiTa::class, 'ta_id', 'id_ta')->where('status', '0');
    }
    public function r_nilai_sekretaris()
    {
        return $this->hasOne(NilaiTa::class, 'ta_id', 'id_ta')->where('status', '1');
    }
    public function r_nilai_penguji_1()
    {
        return $this->hasOne(NilaiTa::class, 'ta_id', 'id_ta')->where('status', '2');
    }
    public function r_nilai_penguji_2()
    {
        return $this->hasOne(NilaiTa::class, 'ta_id', 'id_ta')->where('status', '3');
    }
    public function r_nilai_pembimbing_1()
    {
        return $this->hasOne(NilaiTa::class, 'ta_id', 'id_ta')->where('status', '4');
    }
    public function r_nilai_pembimbing_2()
    {
        return $this->hasOne(NilaiTa::class, 'ta_id', 'id_ta')->where('status', '5');
    }
}
