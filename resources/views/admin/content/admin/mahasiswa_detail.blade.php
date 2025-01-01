@extends('admin.admin_master')
@section('admin')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h4 class="card-title mt-4">Data Mahasiswa</h4>
    </div>

    <div class="col-12 grid-margin d-flex justify-content-center">
        <div class="row" style="width: 100%;">

            <div class="d-flex justify-content-between">


                <div class="card me-3 shadow-sm" style="width: 60%;">
                    <div class="card-body">
                        <form class="form-sample">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Nama
                                            Mahasiswa</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_mahasiswa->nama }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Email</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_mahasiswa->r_user->email }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">NIM</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_mahasiswa->nim }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Jurusan</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_mahasiswa->r_prodi->r_jurusan->nama_jurusan }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Prodi</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_mahasiswa->r_prodi->prodi }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Gender</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_mahasiswa->gender == 1 ? 'Perempuan' : 'Laki-Laki' }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-1">
                                        <label class="col-sm-4 col-form-label" style="font-size: 15px;">Status</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $data_mahasiswa->status_mahasiswa == 1 ? 'Aktif' : 'Tidak Aktif' }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="card shadow-sm" style="width: 35%; max-height: 300px;">
                    <div class="card-body d-flex justify-content-center align-items-center">
                        @if (isset($data_mahasiswa) && $data_mahasiswa->image && $data_mahasiswa->image !== 'default.png')
                            <img src="{{ asset('storage/uploads/mahasiswa/image/' . $data_mahasiswa->image) }}"
                                style="width: 200px; height: 250px; object-fit: cover; border: 2px solid black; padding: 5px;">
                        @else
                            <img src="{{ asset('admin/assets/images/faces/default.png') }}"
                                style="width: 200px; height: 250px; object-fit: cover; border: 2px solid black; padding: 5px;">
                        @endif
                    </div>


                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('mahasiswa') }}" class="btn btn-success">Kembali</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
