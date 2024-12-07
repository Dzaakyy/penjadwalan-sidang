@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Data Prodi</h4>


                <form class="forms-sample" method="post" action="{{ route('prodi.update', ['id' => $data_prodi->id_prodi]) }}">
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
                        <input type="hidden" class="form-control" id="id_prodi" name="id_prodi"
                            value="{{ $data_prodi->id_prodi }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="kode_prodi">Kode Prodi</label>
                        <input type="text" class="form-control" id="kode_prodi" name="kode_prodi"
                            value="{{ $data_prodi->kode_prodi }}" placeholder="Kode Prodi">
                        @error('kode_prodi')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="prodi">Prodi</label>
                        <input type="text" class="form-control" id="prodi" name="prodi"
                            value="{{ $data_prodi->prodi }}" placeholder="Prodi">
                        @error('prodi')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="jurusan_id">Jurusan</label>
                        <select class="form-select" id="jurusan_id" name="jurusan_id">
                            <option>Pilih Jurusan</option>
                            @foreach ($data_jurusan as $jurusan)
                                <option value="{{ $jurusan->id_jurusan }}"
                                    {{ $jurusan->id_jurusan == $data_prodi->jurusan_id ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('jurusan_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jenjang">Jenjang</label>
                        <select class="form-select" id="jenjang" name="jenjang">
                            <option value="">Pilih Jenjang</option>
                            <option value="D2" {{ $data_prodi->jenjang == 'D2' ? 'selected' : '' }}>D2</option>
                            <option value="D3" {{ $data_prodi->jenjang == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="D4" {{ $data_prodi->jenjang == 'D4' ? 'selected' : '' }}>D4</option>
                        </select>
                        @error('jenjang')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="d-inline">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                    <div class="d-inline">
                        <a href="{{ route('prodi') }}" class="btn btn-success">Kembali</a>
                    </div>




                </form>
            </div>
        </div>
    </div>
@endsection
