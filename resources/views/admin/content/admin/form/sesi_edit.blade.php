@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Edit Data Sesi</h4>


                <form class="forms-sample" method="post" action="{{ route('sesi.update', ['id' => $data_sesi->id_sesi]) }}">
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
                        <input type="hidden" class="form-control" id="id_sesi" name="id_sesi"
                            value="{{ $data_sesi->id_sesi }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="sesi">Sesi</label>
                        <input type="text" class="form-control" id="sesi" name="sesi"
                            value="{{ $data_sesi->sesi }}" placeholder="Sesi">
                        @error('sesi')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jam">Jam</label>
                        <input type="text" class="form-control" id="jam" name="jam"
                            value="{{ $data_sesi->jam }}" placeholder="Jam">
                        @error('jam')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-inline">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                    <div class="d-inline">
                        <a href="{{ route('sesi') }}" class="btn btn-success">Kembali</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
