    @extends('admin.admin_master')
    @section('admin')
        <div class="col-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Edit Data Dosen</h4>


                    <form class="forms-sample" method="post" action="{{ route('dosen.update',['id' => $data_dosen->id_dosen]) }}" enctype="multipart/form-data">
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
                            <input type="hidden" class="form-control" id="id_dosen" name="id_dosen"
                            value="{{ $data_dosen->id_dosen }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="nama_dosen">Nama dosen</label>
                            <input type="text" class="form-control" id="nama_dosen" name="nama_dosen" value="{{ $data_dosen->nama_dosen}}"
                                placeholder="Nama dosen">
                            @error('nama_dosen')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $data_dosen->r_user->email ?? '') }}"
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
                            <label for="nidn">NIDN</label>
                            <input type="text" class="form-control" id="nidn" name="nidn" value="{{ $data_dosen->nidn }}" placeholder="NIDN">
                            @error('nidn')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" value="{{ $data_dosen->nip }}" placeholder="NIP">
                            @error('nip')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <div class="d-flex">
                                <div class="col-sm-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="gender" id="laki-laki" value="0" {{ $data_dosen->gender == 0 ? 'checked' : '' }}> Laki - Laki
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="gender" id="perempuan" value="1" {{ $data_dosen->gender == 1 ? 'checked' : '' }}> Perempuan
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('gender')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jurusan_id">Jurusan</label>
                            <select class="form-select" id="jurusan_id" name="jurusan_id">
                                <option>Pilih Jurusan</option>
                                @foreach ($data_jurusan as $jurusan)
                                                <option value="{{ $jurusan->id_jurusan }}"
                                                    {{ $jurusan->id_jurusan == $data_dosen->jurusan_id ? 'selected' : '' }}>
                                                    {{ $jurusan->nama_jurusan }}
                                                </option>
                                        @endforeach
                            </select>
                            @error('jurusan_id')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="prodi_id">Prodi</label>
                            <select class="form-select" id="prodi_id" name="prodi_id">
                                <option>Pilih Prodi</option>
                                @foreach ($data_prodi as $prodi)
                                                <option value="{{ $prodi->id_prodi }}"
                                                    {{ $prodi->id_prodi == $data_dosen->prodi_id ? 'selected' : '' }}>
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
                            <input type="file" name="image" class="form-control" id="image" value="{{ $data_dosen->image }}">
                            @error('image')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="golongan">Golongan</label>
                            <select class="form-select" id="golongan" name="golongan">
                                <option>Pilih Golongan</option>
                                <option value="0" {{ $data_dosen->golongan == '0' ? 'selected' : '' }}>Guru Besar</option>
                                <option value="1" {{ $data_dosen->golongan == '1' ? 'selected' : '' }}>Lector Kepala</option>
                                <option value="2" {{ $data_dosen->golongan == '2' ? 'selected' : '' }}>Lector</option>
                                <option value="3" {{ $data_dosen->golongan == '3' ? 'selected' : '' }}>Assisten Ahli</option>
                            </select>
                            @error('golongan')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>





                        <div class="form-group">
                            <label for="status_dosen">Status dosen</label>
                            <div class="d-flex">
                                <div class="col-sm-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="status_dosen" id="aktif" value="1" {{ $data_dosen->status_dosen == 1 ? 'checked' : '' }}> Aktif
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="status_dosen" id="tidak-aktif" value="0" {{ $data_dosen->status_dosen == 0 ? 'checked' : '' }}> Tidak Aktif
                                        </label>
                                    </div>
                                </div>

                            </div>
                            @error('status_dosen')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="d-inline">
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                        </div>
                        <div class="d-inline">
                            <a href="{{ route('dosen') }}" class="btn btn-success">Kembali</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endsection
