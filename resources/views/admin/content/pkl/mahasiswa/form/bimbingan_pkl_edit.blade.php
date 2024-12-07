@extends('admin.admin_master')
@section('admin')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Bimbingan PKL</h4>

                <form class="forms-sample" method="post"
                    action="{{ route('bimbingan_pkl.update', ['id' => $data_pkl->id_bimbingan_pkl]) }}"
                    enctype="multipart/form-data">
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
                        <input type="hidden" class="form-control" id="id_bimbingan_pkl" name="id_bimbingan_pkl"
                            value="{{ $data_pkl->id_bimbingan_pkl }}" readonly>
                    </div>

                    <div class="form-group">
                        @if (Auth::user()->r_mahasiswa && Auth::user()->r_mahasiswa->usulan_pkl && Auth::user()->r_mahasiswa->usulan_pkl->mhs_pkl)
                            <input type="hidden" name="pkl_id" value="{{ Auth::user()->r_mahasiswa->usulan_pkl->mhs_pkl->id_mhs_pkl }}">
                        @else
                            <div class="alert alert-danger">Data PKL untuk mahasiswa ini tidak ditemukan.</div>
                        @endif
                        @error('pkl_id')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group d-flex">
                        <div class="me-3" style="flex: 1;">
                            <label for="tgl_kegiatan_awal">Tgl Kegiatan Awal</label>
                            <input type="date" class="form-control" id="tgl_kegiatan_awal" name="tgl_kegiatan_awal"
                                placeholder="tgl_kegiatan_awal" value="{{ $data_pkl->tgl_kegiatan_awal }}">
                            @error('tgl_kegiatan_awal')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div style="flex: 1;">
                            <label for="tgl_kegiatan_akhir">Tgl Kegiatan Akhir</label>
                            <input type="date" class="form-control" id="tgl_kegiatan_akhir" name="tgl_kegiatan_akhir"
                                placeholder="tgl_kegiatan_akhir" value="{{ $data_pkl->tgl_kegiatan_akhir }}">
                            @error('tgl_kegiatan_akhir')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kegiatan">Kegiatan</label>
                        <textarea type="textarea" rows="6" class="form-control" id="kegiatan" name="kegiatan" placeholder="Kegiatan"
                            style="height: 100px;">{{ old('kegiatan', $data_pkl->kegiatan) }}</textarea>
                        @error('kegiatan')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label style="font-size:15px;">Laporan Dokumentasi</label>
                        <input type="file" name="file_dokumentasi" class="form-control" id="file_dokumentasi" value="{{ $data_pkl->file_dokumentasi }}">
                        @error('file_dokumentasi')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-inline">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                    <div class="d-inline">
                        <a href="{{ route('bimbingan_pkl') }}" class="btn btn-success">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('tgl_kegiatan_awal').addEventListener('change', function() {
            const startDate = this.value;
            document.getElementById('tgl_kegiatan_akhir').setAttribute('min', startDate);
        });
    </script>
@endsection
