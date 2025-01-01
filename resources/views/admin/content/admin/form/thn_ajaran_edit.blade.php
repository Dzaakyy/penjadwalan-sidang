@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Edit Data Tahun Ajaran</h4>


                <form class="forms-sample" method="post" action="{{ route('thn_ajaran.update', ['id' => $data_thn_ajaran->id_thn_ajaran]) }}">
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
                        <input type="hidden" class="form-control" id="id_thn_ajaran" name="id_thn_ajaran"
                            value="{{ $data_thn_ajaran->id_thn_ajaran }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="thn_ajaran">Tahun Ajaran</label>
                        <input type="text" class="form-control" id="thn_ajaran" name="thn_ajaran"
                            value="{{ $data_thn_ajaran->thn_ajaran }}" placeholder="thn_ajaran">
                        @error('thn_ajaran')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <div class="d-flex">

                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" id="aktif" value="1" {{ $data_thn_ajaran->status == 1 ? 'checked' : '' }}> Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" id="tidak-aktif" value="0" {{ $data_thn_ajaran->status == 0 ? 'checked' : '' }}> Tidak Aktif
                                    </label>
                                </div>
                            </div>

                        </div>
                        @error('status')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-inline">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                    <div class="d-inline">
                        <a href="{{ route('thn_ajaran') }}" class="btn btn-success">Kembali</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
