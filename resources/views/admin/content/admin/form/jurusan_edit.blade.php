@extends('admin.admin_master')
@section('admin')
<div class="col-12 grid-margin stretch-card">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="card-title">Edit Data Jurusan</h4>


        <form class="forms-sample" method="post" action="{{ route('jurusan.update',['id' => $data_jurusan->id_jurusan]) }}">
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
            <input type="hidden" class="form-control" id="id_jurusan" name="id_jurusan" value="{{ $data_jurusan->id_jurusan }}" readonly>
          </div>

          <div class="form-group">
            <label for="kode_jurusan">Kode jurusan</label>
            <input type="text" class="form-control" id="kode_jurusan" name="kode_jurusan" value="{{ $data_jurusan->kode_jurusan }}" placeholder="Kode Jurusan">
            @error('kode_jurusan')
            <small>{{ $message}}</small>
        @enderror
          </div>
          <div class="form-group">
            <label for="nama_jurusan">jurusan</label>
            <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" value="{{ $data_jurusan->nama_jurusan }}" placeholder="Nama Jurusan">
            @error('nama_jurusan')
            <small>{{ $message}}</small>
        @enderror
          </div>
          <div class="d-inline">
            <button type="submit" class="btn btn-primary me-2">Submit</button>
        </div>
        <div class="d-inline">
            <a href="{{ route('jurusan') }}" class="btn btn-success">Kembali</a>
        </div>

        </form>
      </div>
    </div>
  </div>
@endsection
