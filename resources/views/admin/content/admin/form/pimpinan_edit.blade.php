@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Data Pimpinan</h4>


                <form class="forms-sample" method="post" action="{{ route('pimpinan.update',['id' => $data_pimpinan->id_pimpinan]) }}" enctype="multipart/form-data">
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
                        <input type="hidden" class="form-control" id="id_pimpinan" name="id_pimpinan"
                        value="{{ $data_pimpinan->id_pimpinan }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="dosen_id">Dosen</label>
                        <select class="form-select" id="dosen_id" name="dosen_id">
                            <option>Pilih Dosen</option>
                            @foreach ($data_dosen as $dosen)
                                @php

                                    $isDisabledJurusan = \App\Models\Pimpinan::where('dosen_id', $dosen->id_dosen)->exists();
                                    $isSelected = ($dosen->id_dosen == $data_pimpinan->dosen_id) ? 'selected' : '';
                                @endphp
                                @unless ($isDisabledJurusan && $dosen->id_dosen != $data_pimpinan->dosen_id)
                                    <option value="{{ $dosen->id_dosen }}"
                                        {{ $isSelected }}>{{ $dosen->nama_dosen }}
                                    </option>
                                @endunless
                            @endforeach
                        </select>
                        @error('dosen_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>




                    <div class="form-group">
                        <label for="jabatan_pimpinan_id">Jabatan Pimpinan</label>
                        <select class="form-select" id="jabatan_pimpinan_id" name="jabatan_pimpinan_id">
                            <option>Pilih Jabatan Pimpinan</option>
                            @foreach ($data_jabatan_pimpinan as $jabatan_pimpinan)
                                            <option value="{{ $jabatan_pimpinan->id_jabatan_pimpinan }}"
                                                {{ $jabatan_pimpinan->id_jabatan_pimpinan == $data_pimpinan->jabatan_pimpinan_id ? 'selected' : '' }}>
                                                {{ $jabatan_pimpinan->jabatan_pimpinan }}
                                            </option>
                                     @endforeach
                        </select>
                        @error('jabatan_pimpinan_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
{{--
                    <div class="form-group">
                        <label for="jurusan_id">Jurusan</label>
                        <select class="form-select" id="jurusan_id" name="jurusan_id">
                            <option>Pilih Jurusan</option>
                            @foreach ($data_jurusan as $jurusan)
                                @php
                                    $isDisabledJurusan = \App\Models\Pimpinan::where('jurusan_id', $jurusan->id_jurusan)->exists();
                                    $isSelected = ($jurusan->id_jurusan == $data_pimpinan->jurusan_id) ? 'selected' : '';
                                @endphp
                                @unless ($isDisabledJurusan && $jurusan->id_jurusan != $data_pimpinan->jurusan_id)
                                    <option value="{{ $jurusan->id_jurusan }}"
                                        {{ $isSelected }}>{{ $jurusan->nama_jurusan }}
                                    </option>
                                @endunless
                            @endforeach
                        </select>
                        @error('jurusan_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="Prodi_id">Prodi</label>
                        <select class="form-select" id="prodi_id" name="prodi_id">
                            <option>Pilih Prodi</option>
                            @foreach ($data_prodi as $prodi)
                                @php
                                    $isDisabledProdi = \App\Models\Pimpinan::where('prodi_id', $prodi->id_prodi)->exists();
                                    $isSelected = ($prodi->id_prodi == $data_pimpinan->prodi_id) ? 'selected' : '';
                                @endphp
                                @unless ($isDisabledProdi && $prodi->id_prodi != $data_pimpinan->prodi_id)
                                    <option value="{{ $prodi->id_prodi }}"
                                        {{ $isSelected }}>{{ $prodi->prodi }}
                                    </option>
                                @endunless
                            @endforeach
                        </select>
                        @error('jurusan_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div> --}}


                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <input type="text" class="form-control" id="periode" name="periode" value="{{ $data_pimpinan->periode}}"
                            placeholder="Periode">
                        @error('periode')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="status_pimpinan">Status pimpinan</label>
                        <div class="d-flex">
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status_pimpinan" id="tidak-aktif" value="0" {{ $data_pimpinan->status_pimpinan == 0 ? 'checked' : '' }}> Tidak Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status_pimpinan" id="aktif" value="1" {{ $data_pimpinan->status_pimpinan == 1 ? 'checked' : '' }}> Aktif
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
