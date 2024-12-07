@extends('admin.admin_master')
@section('admin')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Input Data Jurusan</h4>


        <form class="forms-sample" method="post" action="{{ route('jurusan.store')}}">
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
            <input type="hidden" class="form-control" id="id_jurusan" name="id_jurusan" value="{{ $nextNumber }}"readonly>
          </div>

          <div class="form-group">
            <label for="kode_jurusan">Kode jurusan</label>
            <input type="text" class="form-control" id="kode_jurusan" name="kode_jurusan" placeholder="Kode jurusan" required>
            @error('kode_jurusan')
            <small>{{ $message}}</small>
        @enderror
          </div>
          <div class="form-group">
            <label for="nama_jurusan">jurusan</label>
            <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" placeholder="Nama Jurusan" required>
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
