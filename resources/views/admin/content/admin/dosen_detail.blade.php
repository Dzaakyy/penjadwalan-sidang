@extends('admin.admin_master')
@section('admin')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h4 class="card-title mt-4">Data Dosen</h4>
    </div>

    <div class="col-12 grid-margin d-flex justify-content-center">
        <div class="row" style="width: 100%;">
            <!-- Wrapper Flexbox untuk menampilkan kedua card bersebelahan -->
            <div class="d-flex justify-content-between">

                <!-- Card Pertama -->
                <div class="card me-3" style="width: 60%;">
                    <div class="card-body">
                        <form class="form-sample">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Form Fields -->
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Nama Dosen</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_dosen->nama_dosen }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Email</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_dosen->r_user->email }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">NIDN</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_dosen->nidn }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">NIP</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_dosen->nip }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Gender</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_dosen->gender == 1 ? 'Perempuan' : 'Laki-Laki' }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Jurusan</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_dosen->r_jurusan->nama_jurusan }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Prodi</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_dosen->r_prodi->prodi }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Golongan</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">: &nbsp;&nbsp;
                                                {{ $data_dosen->golongan == 0
                                                    ? 'Guru Besar'
                                                    : ($data_dosen->golongan == 1
                                                        ? 'Lector Kepala'
                                                        : ($data_dosen->golongan == 2
                                                            ? 'Lector'
                                                            : ($data_dosen->golongan == 3
                                                                ? 'Asisten Ahli'
                                                                : 'Unknown'))) }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Status</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_dosen->status_dosen == 1 ? 'Aktif' : 'Tidak Aktif' }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card Kedua untuk Gambar -->
                <div class="card" style="width: 35%; max-height: 300px;">
                    <div class="card-body d-flex justify-content-center align-items-center">
                        @if (isset($data_dosen) && $data_dosen->image && $data_dosen->image !== 'default.png')
                            <img src="{{ asset('storage/uploads/dosen/image/' . $data_dosen->image) }}"
                                style="width: 200px; height: 250px; object-fit: cover; border: 2px solid black; padding: 5px;">
                        @else
                        <img src="{{ asset('admin/assets/images/faces/default.png') }}"
                        style="width: 200px; height: 250px; object-fit: cover; border: 2px solid black; padding: 5px;">
                        @endif


                    </div>

                    <!-- Button Kembali di bawah gambar -->
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('dosen') }}" class="btn btn-success">Kembali</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
