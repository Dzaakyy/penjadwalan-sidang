@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">input Daftar PKL</h4>


                <form class="forms-sample" method="post" action="{{ route('usulan_pkl.store') }}"
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
                        <input type="hidden" class="form-control" id="id_usulan_pkl" name="id_usulan_pkl"
                            value="{{ $nextNumber }}"readonly>
                    </div>

                    <div class="form-group">
                        @if(Auth::user()->r_mahasiswa)
                        <input type="hidden" name="mahasiswa_id" value="{{ Auth::user()->r_mahasiswa->id_mahasiswa }}">
                    @else
                        <div class="alert alert-danger">ID Mahasiswa tidak ditemukan. Pastikan akun Anda terhubung dengan data Mahasiswa.</div>
                    @endif
                    @error('mahasiswa_id')
                        <small>{{ $message }}</small>
                    @enderror
                    </div>

                    <div class="form-group">
                        <label for="perusahaan_id">perusahaan</label>
                        <select class="form-select" id="perusahaan_id" name="perusahaan_id">
                            <option>Pilih Perusahaan</option>
                            @foreach ($data_perusahaan as $perusahaan)
                                <option value="{{ $perusahaan->id_perusahaan }}">{{ $perusahaan->nama_perusahaan }}
                                </option>
                            @endforeach
                        </select>
                        @error('perusahaan_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="d-inline">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                    <div class="d-inline">
                        <a href="{{ route('usulan_pkl') }}" class="btn btn-success">Kembali</a>
                    </div>


                </form>
            </div>
        </div>
    </div>
@endsection
