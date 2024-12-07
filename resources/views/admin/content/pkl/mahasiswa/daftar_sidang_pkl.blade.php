@extends('admin.admin_master')
@section('admin')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h4 class="card-title mt-4">Data PKL</h4>
    </div>

    <div class="col-12 grid-margin d-flex justify-content-center">
        <div class="row" style="width: 100%;">

            @foreach ($data_mahasiswa_pkl as $data)
                @if (auth()->user()->id == $data->r_pkl->r_mahasiswa->user_id)
                    <div class="col-md-7 mb-3">
                        <div class="card" style="width: 100%; max-width: 800px;">
                            <div class="card-body">
                                <form action="{{ route('daftar_sidang.update', $data->id_mhs_pkl) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Nama
                                                    Mahasiswa</label>
                                                <div class="col-sm-8">
                                                    <label class="col-form-label" style="font-size: 15px;">:
                                                        &nbsp;&nbsp;{{ $data->r_pkl->r_mahasiswa->nama }}</label>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Tempat
                                                    PKL</label>
                                                <div class="col-sm-8">
                                                    <label class="col-form-label" style="font-size: 15px;">:
                                                        &nbsp;&nbsp;{{ $data->r_pkl->r_perusahaan->nama_perusahaan }}</label>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Tahun
                                                    PKL</label>
                                                <div class="col-sm-8">
                                                    <label class="col-form-label" style="font-size: 15px;">:
                                                        &nbsp;&nbsp;{{ $data->tahun_pkl }}</label>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label"
                                                    style="font-size: 15px;">Judul</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="judul" name="judul"
                                                        placeholder="Judul" value="{{ old('judul', $data->judul ?? '') }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Pembimbing
                                                    Industri</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="pembimbing_pkl"
                                                        name="pembimbing_pkl" placeholder="Pembimbing Industri"
                                                        value="{{ old('pembimbing_pkl', $data->pembimbing_pkl ?? '') }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Dokumen
                                                    Nilai Industri</label>
                                                <div class="col-sm-8">
                                                    <input type="file" name="dokumen_nilai_industri" class="form-control"
                                                        id="dokumen_nilai_industri">
                                                    @if (isset($data->dokumen_nilai_industri))
                                                        <p class="mt-2">Current file: <a
                                                                href="{{ asset('storage/uploads/mahasiswa/dokumen_nilai_industri/' . $data->dokumen_nilai_industri) }}"
                                                                target="_blank">{{ $data->dokumen_nilai_industri }}</a></p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Laporan
                                                    PKL</label>
                                                <div class="col-sm-8">
                                                    <input type="file" name="laporan_pkl" class="form-control"
                                                        id="laporan_pkl">
                                                    @if (isset($data->laporan_pkl))
                                                        <p class="mt-2">Current file: <a
                                                                href="{{ asset('storage/uploads/mahasiswa/laporan_pkl/' . $data->laporan_pkl) }}"
                                                                target="_blank">{{ $data->laporan_pkl }}</a></p>
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label"
                                                    style="font-size: 15px;">Status</label>
                                                <div class="col-sm-8">
                                                    <label
                                                        class="badge {{ $data->status_admin == 1 ? 'badge-success' : 'badge-danger' }} mt-2"
                                                        style="font-size: 15px;">
                                                        {{ $data->status_admin == 1 ? 'Diverifikasi' : 'Belum Diverifikasi' }}
                                                    </label>
                                                </div>
                                            </div>

                                            @if (!empty($data->r_nilai_bimbingan) && !empty($data->r_nilai_bimbingan->nilai_bimbingan))
                                                <button type="submit" class="btn btn-primary">Upload</button>
                                            @else
                                                <span class="badge badge-dark" style="font-weight: bold;">Belum Ada Nilai Bimbingan</span>
                                            @endif


                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 mb-3">
                        <div class="card" style="width: 100%; max-width: 800px;">
                            <div class="card-body">
                                <h5 class="card-title">Status <i class="menu-icon mdi mdi-school"></i></h5>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Pembimbing</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;{{ $data->r_dosen_pembimbing->nama_dosen }}</label>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Dosen Penguji</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">
                                            &nbsp;&nbsp;
                                            {{ $data->r_dosen_penguji ? $data->r_dosen_penguji->nama_dosen : '-' }}
                                        </label>

                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Ruang Sidang</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;{{ $data->r_ruang ? $data->r_ruang->kode_ruang : '-' }}</label>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Jam Sidang</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;{{ $data->r_sesi ? $data->r_sesi->jam : '-' }}</label>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Tanggal Sidang</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">
                                            &nbsp;&nbsp; {{ $data->tgl_sidang ? \Carbon\Carbon::parse($data->tgl_sidang)->locale('id')->format('d-m-Y') : '-' }}
                                        </label>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
