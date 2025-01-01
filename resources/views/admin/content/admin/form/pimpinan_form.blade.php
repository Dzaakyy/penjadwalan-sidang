@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Input Data Pimpinan</h4>


                <form class="forms-sample" method="post" action="{{ route('pimpinan.store') }}"
                    enctype="multipart/form-data">
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
                        <input type="hidden" class="form-control" id="id_pimpinan" name="id_pimpinan"
                            value="{{ $nextNumber }}"readonly>
                    </div>

                    <div class="form-group">
                        <label for="dosen_id">Dosen</label>
                        <select class="form-select" id="dosen_id" name="dosen_id">
                            <option>Pilih Dosen</option>
                            @foreach ($data_dosen as $dosen)
                                @php
                                    $isDisabledJurusan = \App\Models\Pimpinan::where(
                                        'dosen_id',
                                        $dosen->id_dosen,
                                    )->exists();
                                @endphp
                                @unless ($isDisabledJurusan)
                                    <option value="{{ $dosen->id_dosen }}">{{ $dosen->nama_dosen }}</option>
                                @endunless
                            @endforeach
                        </select>
                        @error('dosen_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jabatan_pimpinan_id">Jabatan</label>
                        <select class="form-select" id="jabatan_pimpinan_id" name="jabatan_pimpinan_id">
                            <option>Pilih Jabatan</option>
                            @foreach ($data_jabatan_pimpinan as $jabatan)
                                <option value="{{ $jabatan->id_jabatan_pimpinan }}">{{ $jabatan->jabatan_pimpinan }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan_pimpinan_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    {{-- <div class="form-group">
                        <label for="jurusan_id">Jurusan</label>
                        <select class="form-select" id="jurusan_id" name="jurusan_id">
                            <option>Pilih Jurusan</option>
                            @foreach ($data_jurusan as $jurusan)
                                @php
                                    $isDisabledJurusan = \App\Models\Pimpinan::where('jurusan_id',$jurusan->id_jurusan)->exists();
                                @endphp
                                @unless ($isDisabledJurusan)
                                    <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nama_jurusan }}</option>
                                @endunless
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
                                @php
                                    $isDisabledJurusan = \App\Models\Pimpinan::where('prodi_id',$prodi->id_prodi)->exists();
                                @endphp
                                @unless ($isDisabledJurusan)
                                    <option value="{{ $prodi->id_prodi }}">{{ $prodi->prodi }}</option>
                                @endunless
                            @endforeach
                        </select>
                        @error('prodi_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div> --}}



                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <input type="text" class="form-control" id="periode" name="periode" placeholder="Periode" required>
                        @error('periode')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="status_pimpinan">Status Pimpinan</label>
                        <div class="d-flex">
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status_pimpinan"
                                            id="aktif" value="1" checked> Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status_pimpinan"
                                            id="tidak-aktif" value="0"> Tidak Aktif
                                    </label>
                                </div>
                            </div>

                        </div>
                        @error('status_pimpinan')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-inline">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                    <div class="d-inline">
                        <a href="{{ route('pimpinan') }}" class="btn btn-success">Kembali</a>
                    </div>


                </form>
            </div>
        </div>
    </div>
@endsection
