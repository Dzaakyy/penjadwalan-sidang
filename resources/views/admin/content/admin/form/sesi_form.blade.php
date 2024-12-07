@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Data Sesi</h4>


                <form class="forms-sample" method="post" action="{{ route('sesi.store') }}">
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
                        <input type="hidden" class="form-control" id="id_sesi" name="id_sesi"
                            value="{{ $nextNumber }}"readonly>
                    </div>

                    <div class="form-group">
                        <label for="sesi">Sesi</label>
                        <input type="text" class="form-control" id="sesi" name="sesi" placeholder="Sesi" required>
                        @error('sesi')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jam">Jam</label>
                        <input type="text" class="form-control" id="jam" name="jam" placeholder="Jam" required>
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
