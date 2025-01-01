@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Input Data mahasiswa</h4>


                <form class="forms-sample" method="post" action="{{ route('mahasiswa.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="form-group">
                        <input type="hidden" class="form-control" id="id_mahasiswa" name="id_mahasiswa"
                            value="{{ $nextNumber }}"readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama mahasiswa</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                            placeholder="Nama Mahasiswa" required>
                        @error('nama')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Email" required>
                        @error('email')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Passowrd</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password" required>
                        @error('password')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nim">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM" required>
                        @error('nim')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="prodi_id">Prodi</label>
                        <select class="form-select" id="prodi_id" name="prodi_id">
                            <option>Pilih Prodi</option>
                            @foreach ($data_prodi as $prodi)
                                <option value="{{ $prodi->id_prodi }}">{{ $prodi->prodi }}</option>
                            @endforeach
                        </select>
                        @error('prodi_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Upload Gambar</label>
                        <input type="file" name="image" class="form-control" id="image" >
                        @error('image')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <div class="d-flex">
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="gender" id="laki-laki" value="0"> Laki - Laki
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="gender" id="perempuan" value="1"> Perempuan
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('gender')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status_mahasiswa">Status Mahasiswa</label>
                        <div class="d-flex">

                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status_mahasiswa" id="aktif" value="1" checked> Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status_mahasiswa" id="tidak-aktif" value="0"> Tidak Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('status_mahasiswa')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="d-inline">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                    <div class="d-inline">
                        <a href="{{ route('mahasiswa') }}" class="btn btn-success">Kembali</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
