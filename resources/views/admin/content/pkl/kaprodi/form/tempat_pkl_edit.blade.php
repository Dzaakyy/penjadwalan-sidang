@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Data Perusahaan</h4>


                <form class="forms-sample" method="post"
                    action="{{ route('tempat_pkl.update', ['id' => $data_perusahaan->id_perusahaan]) }}">
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
                        <input type="hidden" class="form-control" id="id_perusahaan" name="id_perusahaan"
                            value="{{ $data_perusahaan->id_perusahaan }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama_perusahaan"> Nama Perusahaan</label>
                        <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan"
                            value="{{ $data_perusahaan->nama_perusahaan }}" placeholder="Nama Perusahaan">
                        @error('nama_perusahaan')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi" style="height: 100px;">{{ $data_perusahaan->deskripsi }}</textarea>
                        @error('deskripsi')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="kuota">Kuota Perusahaan</label>
                        <input type="text" class="form-control" id="kuota" name="kuota"
                            value="{{ $data_perusahaan->kuota }}" placeholder="Kuota">
                        @error('kuota')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="status">Status</label>
                        <div class="d-flex">

                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" id="aktif" value="1" {{ $data_perusahaan->status == 1 ? 'checked' : '' }}> Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" id="tidak-aktif" value="0" {{ $data_perusahaan->status == 0 ? 'checked' : '' }}> Tidak Aktif
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
                        <a href="{{ route('tempat_pkl') }}" class="btn btn-success">Kembali</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
