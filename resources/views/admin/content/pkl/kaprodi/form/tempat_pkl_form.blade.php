@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Input Data Tempat PKL</h4>

                <form class="forms-sample" method="post" action="{{ route('tempat_pkl.store') }}">
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
                        <input type="hidden" class="form-control" id="id_perusahaan" name="id_perusahaan"
                            value="{{ $nextNumber }}"readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama_perusahaan">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan"
                        placeholder="Nama perusahaan" required>
                        @error('nama_perusahaan')
                        <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea type="textarea" rows="6" class="form-control" id="deskripsi" name="deskripsi"
                            placeholder="Deskripsi" required style="height: 100px;"></textarea>
                        @error('deskripsi')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kuota">Kuota</label>
                        <input type="text" class="form-control" id="kuota" name="kuota"
                        placeholder="Kuota">
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
                                        <input type="radio" class="form-check-input" name="status"
                                            id="aktif"
                                            value="1"{{ old('status') == '1' ? 'checked' : '' }} checked> Aktif
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status"
                                            id="tidak-aktif" value="0"
                                            {{ old('status') == '0' ? 'checked' : '' }}> Tidak Aktif
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
