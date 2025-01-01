@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Edit Data mahasiswa</h4>


                <form class="forms-sample" method="post" action="{{ route('mahasiswa.update',['id' => $data_mahasiswa->id_mahasiswa]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                        value="{{ $data_mahasiswa->id_mahasiswa }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama mahasiswa</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $data_mahasiswa->nama}}"
                            placeholder="Nama Mahasiswa">
                        @error('nama_mahasiswa')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $data_mahasiswa->r_user->email ?? '') }}"
                            placeholder="Email">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password">
                            <label for="password">Kosongkan password jika tidak ingin diganti</label>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nim">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" value="{{ $data_mahasiswa->nim }}" placeholder="NIM">
                        @error('nim')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="prodi_id">Prodi</label>
                        <select class="form-select" id="prodi_id" name="prodi_id">
                            <option>Pilih Prodi</option>
                            @foreach ($data_prodi as $prodi)
                                            <option value="{{ $prodi->id_prodi }}"
                                                {{ $prodi->id_prodi == $data_mahasiswa->prodi_id ? 'selected' : '' }}>
                                                {{ $prodi->prodi }}
                                            </option>
                                     @endforeach
                        </select>
                        @error('prodi_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Upload Gambar</label>
                        <input type="file" name="image" class="form-control" id="image" value="{{ $data_mahasiswa->image }}">
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
                                        <input type="radio" class="form-check-input" name="gender" id="laki-laki" value="0" {{ $data_mahasiswa->gender == 0 ? 'checked' : '' }}> Laki - Laki
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="gender" id="perempuan" value="1" {{ $data_mahasiswa->gender == 1 ? 'checked' : '' }}> Perempuan
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
                                        <input type="radio" class="form-check-input" name="status_mahasiswa" id="tidak-aktif" value="0" {{ $data_mahasiswa->status_mahasiswa == 0 ? 'checked' : '' }}> Tidak Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status_mahasiswa" id="aktif" value="1" {{ $data_mahasiswa->status_mahasiswa == 1 ? 'checked' : '' }}> Aktif
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
