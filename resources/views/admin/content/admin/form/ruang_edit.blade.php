@extends('admin.admin_master')
@section('admin')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit Data Ruangan</h4>


        <form class="forms-sample" method="post" action="{{ route('ruang.update',['id' => $data_ruang->id_ruang]) }}">
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
            <input type="hidden" class="form-control" id="id_ruang" name="id_ruang" value="{{ $data_ruang->id_ruang }}" readonly>
          </div>

          <div class="form-group">
            <label for="kode_ruang">Kode/Nama Ruang</label>
            <input type="text" class="form-control" id="kode_ruang" name="kode_ruang" value="{{ $data_ruang->kode_ruang }}" placeholder="Kode/Nama ruang">
            @error('kode_ruang')
            <small>{{ $message}}</small>
        @enderror
          </div>
          <div class="d-inline">
            <button type="submit" class="btn btn-primary me-2">Submit</button>
        </div>
        <div class="d-inline">
            <a href="{{ route('ruang') }}" class="btn btn-success">Kembali</a>
        </div>




        </form>
      </div>
    </div>
  </div>
@endsection
