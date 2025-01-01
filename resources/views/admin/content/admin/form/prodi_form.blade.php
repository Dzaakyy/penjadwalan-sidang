@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Input Data Prodi</h4>


                <form class="forms-sample" method="post" action="{{ route('prodi.store') }}">
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
                        <input type="hidden" class="form-control" id="id_prodi" name="id_prodi"
                            value="{{ $nextNumber }}"readonly>
                    </div>

                    <div class="form-group">
                        <label for="kode_prodi">Kode Prodi</label>
                        <input type="text" class="form-control" id="kode_prodi" name="kode_prodi"
                            placeholder="Kode Prodi" required>
                        @error('kode_prodi')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="prodi">Prodi</label>
                        <input type="text" class="form-control" id="prodi" name="prodi" placeholder="Prodi" required>
                        @error('prodi')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="jurusan_id">Jurusan</label>
                        <select class="form-select" id="jurusan_id" name="jurusan_id">
                            <option>Pilih Jurusan</option>
                            @foreach ($data_jurusan as $jurusan)
                                <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nama_jurusan }}</option>
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
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="D4">D4</option>
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
