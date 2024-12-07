@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Data Dosen</h4>


                <form class="forms-sample" method="post" action="{{ route('dosen.store') }}" enctype="multipart/form-data">
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
                        <input type="hidden" class="form-control" id="id_dosen" name="id_dosen"
                            value="{{ $nextNumber }}"readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama_dosen">Nama Dosen</label>
                        <input type="text" class="form-control" id="nama_dosen" name="nama_dosen"
                            placeholder="Nama dosen" value="{{ old('nama_dosen') }}" required>
                        @error('nama_dosen')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Passowrd</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                            value="{{ old('password') }}" required>
                        @error('password')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nidn">NIDN</label>
                        <input type="text" class="form-control" id="nidn" name="nidn" placeholder="NIDN"
                            value="{{ old('nidn') }}" required>
                        @error('nidn')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" placeholder="NIP"
                            value="{{ old('nip') }}" required>
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
                                        <input type="radio" class="form-check-input" name="gender" id="laki-laki"
                                            value="0" {{ old('gender') == '0' ? 'checked' : '' }}> Laki - Laki
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="gender" id="perempuan"
                                            value="1" {{ old('gender') == '1' ? 'checked' : '' }}> Perempuan
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
                                    {{ old('jurusan_id') == $jurusan->id_jurusan ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }}</option>

                            @endforeach
                        </select>
                        @error('jurusan_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="prodi_id">Prodi</label>
                        <select class="form-select" id="prodi_id" name="prodi_id">
                            <option value="">Pilih Prodi</option>
                            {{-- Populate prodi options dynamically based on selected jurusan --}}
                            @if (old('jurusan_id'))
                                @php
                                    $selectedJurusan = $data_jurusan->firstWhere('id_jurusan', old('jurusan_id'));
                                @endphp
                                @if ($selectedJurusan && !empty($selectedJurusan->r_prodi))
                                    @foreach ($selectedJurusan->r_prodi as $prodi)
                                        <option value="{{ $prodi->id_prodi }}" {{ old('prodi_id') == $prodi->id_prodi ? 'selected' : '' }}>
                                            {{ $prodi->prodi }}
                                        </option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                        @error('prodi_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label>Upload Gambar</label>
                        <input type="file" name="image" class="form-control" id="image">
                        @error('image')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="golongan">Golongan</label>
                        <select class="form-select" id="golongan" name="golongan">
                            <option>Pilih Golongan</option>
                            <option value="0" {{ old('golongan') == '0' ? 'selected' : '' }}>Guru Besar</option>
                            <option value="1" {{ old('golongan') == '1' ? 'selected' : '' }}>Lector Kepala</option>
                            <option value="2" {{ old('golongan') == '2' ? 'selected' : '' }}>Lector</option>
                            <option value="3" {{ old('golongan') == '3' ? 'selected' : '' }}>Assisten Ahli</option>
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
                                        <input type="radio" class="form-check-input" name="status_dosen"
                                            id="aktif"
                                            value="1"{{ old('status_dosen') == '1' ? 'checked' : '' }} checked> Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status_dosen"
                                            id="tidak-aktif" value="0"
                                            {{ old('status_dosen') == '0' ? 'checked' : '' }}> Tidak Aktif
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
@section('scripts')
    <script>
        var dataJurusan = @json($data_jurusan);


        function filterProdi() {
            var jurusanId = document.getElementById('jurusan_id').value;
            var prodiSelect = document.getElementById('prodi_id');


            prodiSelect.innerHTML = '<option value="">Pilih Prodi</option>';


            var selectedJurusan = dataJurusan.find(jurusan => jurusan.id_jurusan == jurusanId);


            if (selectedJurusan && selectedJurusan.r_prodi.length > 0) {
                selectedJurusan.r_prodi.forEach(function(prodi) {
                    var option = document.createElement('option');
                    option.value = prodi.id_prodi;
                    option.text = prodi.prodi;
                    prodiSelect.appendChild(option);
                });
            }
        }

        document.getElementById('jurusan_id').addEventListener('change', filterProdi);
    </script>
@endsection
