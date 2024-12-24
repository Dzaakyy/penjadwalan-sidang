<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\DosenController;
use App\Http\Controllers\Admin\JabatanPimpinanController;
use App\Http\Controllers\admin\JurusanController;
use App\Http\Controllers\admin\MahasiswaController;
use App\Http\Controllers\Admin\PimpinanController;
use App\Http\Controllers\admin\ProdiController;
use App\Http\Controllers\admin\RuangController;
use App\Http\Controllers\Admin\SesiController;
use App\Http\Controllers\admin\ThnAjaranController;
use App\Http\Controllers\pkl\BimbinganPklController;
use App\Http\Controllers\pkl\DaftarSidangPklController;
use App\Http\Controllers\pkl\DosenPembimbingPklController;
use App\Http\Controllers\pkl\KonfirmasiUsulanPklController;
use App\Http\Controllers\pkl\MahasiswaPklController;
use App\Http\Controllers\pkl\NilaiSidangPklController;
use App\Http\Controllers\pkl\TempatPklController;
use App\Http\Controllers\pkl\UsulanPklController;
use App\Http\Controllers\pkl\VerifPklController;
use App\Http\Controllers\Sempro\DaftarSemproController;
use App\Http\Controllers\Sempro\DaftarSidangSemproController;
use App\Http\Controllers\Sempro\NilaiSidangSemproController;
use App\Http\Controllers\Sempro\VerifikasiJudulSemproController;
use App\Http\Controllers\Sempro\VerifikasiSemproController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



// Route::get('/dashboard', function () {
//     return view('admin/admin_master');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/admin/logout', [AdminController::class, 'destroy'])->name('admin.logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->middleware(['auth', 'verified'])->name('profile.update');
    Route::put('/profile/image', [ProfileController::class, 'updateImage'])->middleware(['auth', 'verified'])->name('profile.image.update');
    Route::delete('/profile/image/delete', [ProfileController::class, 'destroyImage'])->middleware(['auth', 'verified'])->name('profile.image.delete');
});




// dasboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
});


// Jurusan
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jurusan', [JurusanController::class, 'index'])->middleware(['auth', 'verified'])->name('jurusan');
    Route::post('/jurusan/store', [JurusanController::class, 'store'])->middleware(['auth', 'verified'])->name('jurusan.store');
    Route::get('/jurusan/create', [JurusanController::class, 'create'])->middleware(['auth', 'verified'])->name('jurusan.create');
    Route::get('/jurusan/edit/{id}', [JurusanController::class, 'edit'])->middleware(['auth', 'verified'])->name('jurusan.edit');
    Route::put('/jurusan/update/{id}', [JurusanController::class, 'update'])->middleware(['auth', 'verified'])->name('jurusan.update');
    Route::delete('/jurusan/delete/{id}', [JurusanController::class, 'delete'])->middleware(['auth', 'verified'])->name('jurusan.delete');
    Route::get('/jurusan/export/excel', [JurusanController::class, 'export_excel'])->name('jurusan.export');
    Route::post('/jurusan/import/excel', [JurusanController::class, 'import'])->name('jurusan.import');
});


// Prodi
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/prodi', [ProdiController::class, 'index'])->middleware(['auth', 'verified'])->name('prodi');
    Route::post('/prodi/store', [ProdiController::class, 'store'])->middleware(['auth', 'verified'])->name('prodi.store');
    Route::get('/prodi/create', [ProdiController::class, 'create'])->middleware(['auth', 'verified'])->name('prodi.create');
    Route::get('/prodi/edit/{id}', [ProdiController::class, 'edit'])->middleware(['auth', 'verified'])->name('prodi.edit');
    Route::put('/prodi/update/{id}', [ProdiController::class, 'update'])->middleware(['auth', 'verified'])->name('prodi.update');
    Route::delete('/prodi/delete/{id}', [ProdiController::class, 'delete'])->middleware(['auth', 'verified'])->name('prodi.delete');
    Route::get('/prodi/export/excel', [ProdiController::class, 'export_excel'])->name('prodi.export');
});

// Ruang
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/ruang', [RuangController::class, 'index'])->middleware(['auth', 'verified'])->name('ruang');
    Route::post('/ruang/store', [RuangController::class, 'store'])->middleware(['auth', 'verified'])->name('ruang.store');
    Route::get('/ruang/create', [RuangController::class, 'create'])->middleware(['auth', 'verified'])->name('ruang.create');
    Route::get('/ruang/edit/{id}', [RuangController::class, 'edit'])->middleware(['auth', 'verified'])->name('ruang.edit');
    Route::put('/ruang/update/{id}', [RuangController::class, 'update'])->middleware(['auth', 'verified'])->name('ruang.update');
    Route::delete('/ruang/delete/{id}', [RuangController::class, 'delete'])->middleware(['auth', 'verified'])->name('ruang.delete');
});

// Mahasiswa
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->middleware(['auth', 'verified'])->name('mahasiswa');
    Route::post('mahasiswa/store', [MahasiswaController::class, 'store'])->middleware(['auth', 'verified'])->name('mahasiswa.store');
    Route::get('mahasiswa/create', [MahasiswaController::class, 'create'])->middleware(['auth', 'verified'])->name('mahasiswa.create');
    Route::get('mahasiswa/detail/{id}', [MahasiswaController::class, 'show'])->middleware(['auth', 'verified'])->name('mahasiswa.detail');
    Route::get('mahasiswa/edit/{id}', [MahasiswaController::class, 'edit'])->middleware(['auth', 'verified'])->name('mahasiswa.edit');
    Route::put('mahasiswa/update/{id}', [MahasiswaController::class, 'update'])->middleware(['auth', 'verified'])->name('mahasiswa.update');
    Route::delete('mahasiswa/delete/{id}', [MahasiswaController::class, 'delete'])->middleware(['auth', 'verified'])->name('mahasiswa.delete');
    Route::get('/mahasiswa/export/excel', [MahasiswaController::class, 'export_excel'])->name('mahasiswa.export');
    Route::post('/mahasiswa/import/excel', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
});


// Dosen
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dosen', [DosenController::class, 'index'])->middleware(['auth', 'verified'])->name('dosen');
    Route::post('dosen/store', [DosenController::class, 'store'])->middleware(['auth', 'verified'])->name('dosen.store');
    Route::get('dosen/create', [DosenController::class, 'create'])->middleware(['auth', 'verified'])->name('dosen.create');
    Route::get('dosen/detail/{id}', [DosenController::class, 'show'])->middleware(['auth', 'verified'])->name('dosen.detail');
    Route::get('dosen/edit/{id}', [DosenController::class, 'edit'])->middleware(['auth', 'verified'])->name('dosen.edit');
    Route::put('dosen/update/{id}', [DosenController::class, 'update'])->middleware(['auth', 'verified'])->name('dosen.update');
    Route::delete('dosen/delete/{id}', [DosenController::class, 'delete'])->middleware(['auth', 'verified'])->name('dosen.delete');
    Route::get('/dosen/export/excel', [DosenController::class, 'export_excel'])->name('dosen.export');
    Route::post('/dosen/import/excel', [DosenController::class, 'import'])->name('dosen.import');
});



// Jabatan JabatanPimpinan
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jabatanpimpinan', [JabatanPimpinanController::class, 'index'])->middleware(['auth', 'verified'])->name('jabatan_pimpinan');
    Route::post('jabatanpimpinan/store', [JabatanPimpinanController::class, 'store'])->middleware(['auth', 'verified'])->name('jabatan_pimpinan.store');
    Route::get('jabatanpimpinan/create', [JabatanPimpinanController::class, 'create'])->middleware(['auth', 'verified'])->name('jabatan_pimpinan.create');
    Route::get('jabatanpimpinan/edit/{id}', [JabatanPimpinanController::class, 'edit'])->middleware(['auth', 'verified'])->name('jabatan_pimpinan.edit');
    Route::put('jabatanpimpinan/update/{id}', [JabatanPimpinanController::class, 'update'])->middleware(['auth', 'verified'])->name('jabatan_pimpinan.update');
    Route::delete('jabatanpimpinan/delete/{id}', [JabatanPimpinanController::class, 'delete'])->middleware(['auth', 'verified'])->name('jabatan_pimpinan.delete');
});



// Jabatan Pimpinan
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/pimpinan', [PimpinanController::class, 'index'])->middleware(['auth', 'verified'])->name('pimpinan');
    Route::post('pimpinan/store', [PimpinanController::class, 'store'])->middleware(['auth', 'verified'])->name('pimpinan.store');
    Route::get('pimpinan/create', [PimpinanController::class, 'create'])->middleware(['auth', 'verified'])->name('pimpinan.create');
    Route::get('pimpinan/edit/{id}', [PimpinanController::class, 'edit'])->middleware(['auth', 'verified'])->name('pimpinan.edit');
    Route::put('pimpinan/update/{id}', [PimpinanController::class, 'update'])->middleware(['auth', 'verified'])->name('pimpinan.update');
    Route::delete('pimpinan/delete/{id}', [PimpinanController::class, 'delete'])->middleware(['auth', 'verified'])->name('pimpinan.delete');
});


// Sesi
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sesi', [SesiController::class, 'index'])->middleware(['auth', 'verified'])->name('sesi');
    Route::post('/sesi/store', [SesiController::class, 'store'])->middleware(['auth', 'verified'])->name('sesi.store');
    Route::get('/sesi/create', [SesiController::class, 'create'])->middleware(['auth', 'verified'])->name('sesi.create');
    Route::get('/sesi/edit/{id}', [SesiController::class, 'edit'])->middleware(['auth', 'verified'])->name('sesi.edit');
    Route::put('/sesi/update/{id}', [SesiController::class, 'update'])->middleware(['auth', 'verified'])->name('sesi.update');
    Route::delete('/sesi/delete/{id}', [SesiController::class, 'delete'])->middleware(['auth', 'verified'])->name('sesi.delete');
});


// Tahun Ajaran
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/thn_ajaran', [ThnAjaranController::class, 'index'])->middleware(['auth', 'verified'])->name('thn_ajaran');
    Route::post('/thn_ajaran/store', [ThnAjaranController::class, 'store'])->middleware(['auth', 'verified'])->name('thn_ajaran.store');
    Route::get('/thn_ajaran/create', [ThnAjaranController::class, 'create'])->middleware(['auth', 'verified'])->name('thn_ajaran.create');
    Route::get('/thn_ajaran/edit/{id}', [ThnAjaranController::class, 'edit'])->middleware(['auth', 'verified'])->name('thn_ajaran.edit');
    Route::put('/thn_ajaran/update/{id}', [ThnAjaranController::class, 'update'])->middleware(['auth', 'verified'])->name('thn_ajaran.update');
    Route::delete('/thn_ajaran/delete/{id}', [ThnAjaranController::class, 'delete'])->middleware(['auth', 'verified'])->name('thn_ajaran.delete');
});


// -- PKL --  //

// Tempat PKL
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tempat_pkl', [TempatPklController::class, 'index'])->middleware(['auth', 'verified'])->name('tempat_pkl');
    Route::post('/tempat_pkl/store', [TempatPklController::class, 'store'])->middleware(['auth', 'verified'])->name('tempat_pkl.store');
    Route::get('/tempat_pkl/create', [TempatPklController::class, 'create'])->middleware(['auth', 'verified'])->name('tempat_pkl.create');
    Route::get('/tempat_pkl/edit/{id}', [TempatPklController::class, 'edit'])->middleware(['auth', 'verified'])->name('tempat_pkl.edit');
    Route::put('/tempat_pkl/update/{id}', [TempatPklController::class, 'update'])->middleware(['auth', 'verified'])->name('tempat_pkl.update');
    Route::delete('/tempat_pkl/delete/{id}', [TempatPklController::class, 'delete'])->middleware(['auth', 'verified'])->name('tempat_pkl.delete');
});


// Usulan PKL
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/usulan_pkl', [UsulanPklController::class, 'index'])->middleware(['auth', 'verified'])->name('usulan_pkl');
    Route::post('/usulan_pkl/store', [UsulanPklController::class, 'store'])->middleware(['auth', 'verified'])->name('usulan_pkl.store');
    Route::get('/usulan_pkl/create', [UsulanPklController::class, 'create'])->middleware(['auth', 'verified'])->name('usulan_pkl.create');
    Route::get('/usulan_pkl/edit/{id}', [UsulanPklController::class, 'edit'])->middleware(['auth', 'verified'])->name('usulan_pkl.edit');
    Route::put('/usulan_pkl/update/{id}', [UsulanPklController::class, 'update'])->middleware(['auth', 'verified'])->name('usulan_pkl.update');
    Route::delete('/usulan_pkl/delete/{id}', [UsulanPklController::class, 'delete'])->middleware(['auth', 'verified'])->name('usulan_pkl.delete');
});


// Konfirmasi Usulan PKL
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/konfirmasi_usulan_pkl', [KonfirmasiUsulanPklController::class, 'index'])->middleware(['auth', 'verified'])->name('konfirmasi_usulan_pkl');
    Route::put('/konfirmasi_usulan_pkl/confirm/{id}', [KonfirmasiUsulanPklController::class, 'confirm'])->middleware(['auth', 'verified'])->name('konfirmasi_usulan_pkl.confirm');
    // Route::get('/konfirmasi_usulan_pkl/edit/{id}', [KonfirmasiUsulanPklController::class, 'edit'])->middleware(['auth', 'verified'])->name('konfirmasi_usulan_pkl.edit');
    // Route::put('/konfirmasi_usulan_pkl/update/{id}', [KonfirmasiUsulanPklController::class, 'update'])->middleware(['auth', 'verified'])->name('konfirmasi_usulan_pkl.update');
    Route::delete('/konfirmasi_usulan_pkl/delete/{id}', [KonfirmasiUsulanPklController::class, 'delete'])->middleware(['auth', 'verified'])->name('konfirmasi_usulan_pkl.delete');
    Route::get('/konfirmasi_edit_pembimbing/edit/{id}', [KonfirmasiUsulanPklController::class, 'edit'])->middleware(['auth', 'verified'])->name('konfirmasi_edit_pembimbing.edit');
    Route::put('/konfirmasi_edit_pembimbing/update/{id}', [KonfirmasiUsulanPklController::class, 'update'])->middleware(['auth', 'verified'])->name('konfirmasi_edit_pembimbing.update');
});






// Verifikasi PKL (Admin)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/verif_pkl', [VerifPklController::class, 'index'])->middleware(['auth', 'verified'])->name('verif_pkl');
    Route::put('/verif_pkl/update/{id}', [VerifPklController::class, 'verif'])->middleware(['auth', 'verified'])->name('ver_berkas_pkl.update');
});


// // MHS PKL (Kaprodi)
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/mhs_pkl', [MahasiswaPklController::class, 'index'])->middleware(['auth', 'verified'])->name('mhs_pkl');
//     Route::put('/mhs_pkl/update/{id}', [MahasiswaPklController::class, 'verif'])->middleware(['auth', 'verified'])->name('mhs_pkl.update');
// });

// Daftar Bimbingan PKL (Mahasiswa)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bimbingan/pkl', [BimbinganPklController::class, 'index'])->middleware(['auth', 'verified'])->name('bimbingan_pkl');
    Route::get('/bimbingan/pkl/create', [BimbinganPklController::class, 'create'])->middleware(['auth', 'verified'])->name('bimbingan_pkl.create');
    Route::post('/bimbingan/pkl/store', [BimbinganPklController::class, 'store'])->middleware(['auth', 'verified'])->name('bimbingan_pkl.store');
    Route::get('/bimbingan/pkl/edit/{id}', [BimbinganPklController::class, 'edit'])->middleware(['auth', 'verified'])->name('bimbingan_pkl.edit');
    Route::put('/bimbingan/pkl/update/{id}', [BimbinganPklController::class, 'update'])->middleware(['auth', 'verified'])->name('bimbingan_pkl.update');
    Route::delete('/bimbingan/pkl/delete/{id}', [BimbinganPklController::class, 'delete'])->middleware(['auth', 'verified'])->name('bimbingan_pkl.delete');
    // Route::put('/mhs_pkl/update/{id}', [MahasiswaPklController::class, 'verif'])->middleware(['auth', 'verified'])->name('mhs_pkl.update');
});



// Bimbingan PKL (Pembimbing)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dosen_bimbingan_pkl', [DosenPembimbingPklController::class, 'index'])->middleware(['auth', 'verified'])->name('dosen_bimbingan_pkl');
    Route::get('/dosen_bimbingan_pkl/detail/{id}', [DosenPembimbingPklController::class, 'detail'])->middleware(['auth', 'verified'])->name('dosen_bimbingan_pkl.detail');
    Route::put('/dosen_bimbingan_pkl/update/{id}', [DosenPembimbingPklController::class, 'update'])->middleware(['auth', 'verified'])->name('dosen_bimbingan_pkl.update');
    Route::post('/dosen_bimbingan_pkl/nilai/{id}', [DosenPembimbingPklController::class, 'nilai_bimbingan_pkl'])->middleware(['auth', 'verified'])->name('nilai_dosen_bimbingan_pkl.post');
    Route::put('/dosen_bimbingan_pkl/nilai/{id}', [DosenPembimbingPklController::class, 'nilai_bimbingan_pkl'])->middleware(['auth', 'verified'])->name('nilai_dosen_bimbingan_pkl.update');
});


// Daftar Sidang PKL (Kaprodi)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/daftar_sidang_kaprodi', [DaftarSidangPklController::class, 'index'])->middleware(['auth', 'verified'])->name('daftar_sidang_kaprodi');
    Route::put('/daftar_sidang_kaprodi/update/{id}', [DaftarSidangPklController::class, 'update'])->middleware(['auth', 'verified'])->name('daftar_sidang_kaprodi.update');
    Route::get('/cetak-surat-tugas-pkl/download/{id}', [DaftarSidangPklController::class, 'download_pdf'])->name('cetak_surat_tugas_pkl.download');
});


// Daftar Sidang PKL (Mahasiswa)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/daftar_sidang_mahasiswa', [MahasiswaPklController::class, 'index'])->middleware(['auth', 'verified'])->name('daftar_sidang');
    Route::put('/daftar_sidang_mahasiswa/update/{id}', [MahasiswaPklController::class, 'update'])->middleware(['auth', 'verified'])->name('daftar_sidang.update');
    // Route::put('/mhs_pkl/update/{id}', [MahasiswaPklController::class, 'verif'])->middleware(['auth', 'verified'])->name('mhs_pkl.update');
});



// Nilai Sidang PKL
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/nilai_sidang_pkl', [NilaiSidangPklController::class, 'index'])->middleware(['auth', 'verified'])->name('nilai_sidang_pkl');
    Route::post('/nilai_sidang_pkl/nilai/{id}', [NilaiSidangPklController::class, 'nilai_sidang_pkl'])->middleware(['auth', 'verified'])->name('nilai_sidang_pkl.post');
    Route::put('/nilai_sidang_pkl/edit/nilai/{id}', [NilaiSidangPklController::class, 'nilai_sidang_pkl'])->middleware(['auth', 'verified'])->name('nilai_sidang_pkl.update');
});


// ------------------------------------------------------ Sempro ------------------------------------------------------

// Mahasiswa
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/daftar_sempro', [DaftarSemproController::class, 'index'])->middleware(['auth', 'verified'])->name('daftar_sempro');
    Route::put('/daftar_sempro/mahasiswa/', [DaftarSemproController::class, 'store'])->middleware(['auth', 'verified'])->name('daftar_sempro.post');
    Route::put('/daftar_sempro/mahasiswa/update/{id}', [DaftarSemproController::class, 'update'])->middleware(['auth', 'verified'])->name('daftar_sempro.update');
    Route::delete('/daftar_sempro/mahasiswa/delete/{id}', [DaftarSemproController::class, 'delete'])->middleware(['auth', 'verified'])->name('daftar_sempro.delete');
});


// Kaprodi
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/verifikasi-judul-sempro', [VerifikasiJudulSemproController::class, 'index'])->middleware(['auth', 'verified'])->name('verifikasi_judul_sempro_kaprodi');
    Route::put('/verifikasi-judul-sempro/kaprodi/update/{id}', [VerifikasiJudulSemproController::class, 'update'])->middleware(['auth', 'verified'])->name('verifikasi_judul_sempro_kaprodi.update');
    Route::get('/get-existing-schedules', [DaftarSidangSemproController::class, 'getExistingSchedules']);
    Route::get('/daftar-sidang-sempro', [DaftarSidangSemproController::class, 'index'])->middleware(['auth', 'verified'])->name('daftar_sidang_sempro_kaprodi');
    Route::put('/daftar-sidang-sempro/kaprodi/update/{id}', [DaftarSidangSemproController::class, 'update'])->middleware(['auth', 'verified'])->name('daftar_sidang_sempro_kaprodi.update');
    Route::get('/cetak-surat-tugas-sempro/download/{id}', [DaftarSidangSemproController::class, 'download_pdf'])->name('cetak_surat_tugas_sempro.download');
});

// Admin
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/verifikasi-berkas-sempro', [VerifikasiSemproController::class, 'index'])->middleware(['auth', 'verified'])->name('verifikasi_berkas_sempro_admin');
    Route::put('/verifikasi-berkas-sempro/kaprodi/update/{id}', [VerifikasiSemproController::class, 'update'])->middleware(['auth', 'verified'])->name('verifikasi_berkas_sempro_admin.update');

});

// Nilai Sidang Sempro
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/nilai_sidang_sempro', [NilaiSidangSemproController::class, 'index'])->middleware(['auth', 'verified'])->name('nilai_sidang_sempro');
    Route::post('/nilai_sidang_sempro/nilai/{id}', [NilaiSidangSemproController::class, 'nilai_sidang_sempro'])->middleware(['auth', 'verified'])->name('nilai_sidang_sempro.post');
    Route::put('/nilai_sidang_sempro/edit/nilai/{id}', [NilaiSidangSemproController::class, 'nilai_sidang_sempro'])->middleware(['auth', 'verified'])->name('nilai_sidang_sempro.update');
});

require __DIR__ . '/auth.php';
