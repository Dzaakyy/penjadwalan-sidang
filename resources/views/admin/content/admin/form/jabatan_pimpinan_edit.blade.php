@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Edit Data Jabatan Pimpinan</h4>


                <form class="forms-sample" method="post" action="{{ route('jabatan_pimpinan.update', ['id' => $data_jabatan_pimpinan->id_jabatan_pimpinan]) }}">
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
                        <input type="hidden" class="form-control" id="id_jabatan_pimpinan" name="id_jabatan_pimpinan"
                            value="{{ $data_jabatan_pimpinan->id_jabatan_pimpinan }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="jabatan_pimpinan">Jabatan Pimpinan</label>
                        <input type="text" class="form-control" id="jabatan_pimpinan" name="jabatan_pimpinan"
                            value="{{ $data_jabatan_pimpinan->jabatan_pimpinan }}" placeholder="jabatan_pimpinan">
                        @error('jabatan_pimpinan')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kode_jabatan_pimpinan">Kode Jabatan Pimpinan</label>
                        <input type="text" class="form-control" id="kode_jabatan_pimpinan" name="kode_jabatan_pimpinan"
                            value="{{ $data_jabatan_pimpinan->kode_jabatan_pimpinan }}" placeholder="Kode Jabatan Pimpinan">
                        @error('kode_jabatan_pimpinan')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status_jabatan_pimpinan">Status Jabata Pimpinan</label>
                        <div class="d-flex">
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status_jabatan_pimpinan" id="tidak-aktif" value="0" {{ $data_jabatan_pimpinan->status_jabatan_pimpinan == 0 ? 'checked' : '' }}> Tidak Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status_jabatan_pimpinan" id="aktif" value="1" {{ $data_jabatan_pimpinan->status_jabatan_pimpinan == 1 ? 'checked' : '' }}> Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('status_jabatan_pimpinan')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="d-inline">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                    <div class="d-inline">
                        <a href="{{ route('jabatan_pimpinan') }}" class="btn btn-success">Kembali</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
