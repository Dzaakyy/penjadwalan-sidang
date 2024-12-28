@extends('admin.admin_master')
@section('admin')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h4 class="card-title mt-4">Data Sempro</h4>
    </div>

    @if (Session::has('success'))
        <div id="delay" class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    @if (Session::has('error'))
        <div id="delay" class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
    @endif

    @php
        $mahasiswa_id = Auth::user()->r_mahasiswa->id_mahasiswa;

        $usulanPkl = Auth::user()->r_mahasiswa->usulan_pkl;

        $nilaiMahasiswaNotNull = false;
        if ($usulanPkl) {
            $mahasiswaPkl = \App\Models\MahasiswaPkl::where('mahasiswa_id', $mahasiswa_id)->first();

            if ($mahasiswaPkl && !is_null($mahasiswaPkl->nilai_mahasiswa)) {
                $nilaiMahasiswaNotNull = true;
            }
        }

        $semproTerdaftar = $data_mahasiswa_sempro->where('mahasiswa_id', $mahasiswa_id)->isNotEmpty();
    @endphp

    @if ($nilaiMahasiswaNotNull && !$semproTerdaftar)
        <a data-bs-toggle="modal" data-bs-target="#daftar_sempro" class="btn btn-primary me-2 mb-3">
            <i class="bi bi-file-earmark-plus"></i> Daftar Sempro
        </a>
    @endif



    {{-- Modal Daftar Sempro --}}
    <div class="modal fade" id="daftar_sempro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Daftar Sempro</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Daftar Sempro
                        <b>{{ $mahasiswa->nama }}</b>
                    </p>

                    <form id="daftar_sempro" action="{{ route('daftar_sempro.post') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input type="text" class="form-control" id="id_sempro" name="id_sempro"
                                placeholder="id_sempro" value="{{ $nextNumber }}" hidden>
                        </div>

                        @if (Auth::user()->r_mahasiswa)
                            <input type="hidden" name="mahasiswa_id" value="{{ Auth::user()->r_mahasiswa->id_mahasiswa }}">
                        @endif


                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul"
                                required>
                            @error('Judul')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- <div class="form-group">
                        <label for="file_sempro">File Sempro</label>
                        <input type="file" class="form-control" id="file_sempro" name="file_sempro"
                        placeholder="File Sempro" required>
                        @error('file_sempro')
                        <small>{{ $message }}</small>
                        @enderror
                    </div> --}}

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($data_mahasiswa_sempro as $data)
        @if (auth()->user()->id == $data->r_mahasiswa->user_id)
            <div class="col-12 grid-margin d-flex justify-content-center">
                <div class="row" style="width: 100%;">


                    <div class="col-md-7 mb-3">
                        <div class="card" style="width: 100%; max-width: 800px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-4 col-form-label" style="font-size: 15px;">Nama
                                                Mahasiswa</label>
                                            <div class="col-sm-8">
                                                <label class="col-form-label" style="font-size: 15px;">:
                                                    &nbsp;&nbsp;{{ $data->r_mahasiswa->nama ?? '' }}</label>
                                            </div>
                                        </div>


                                        <form action="{{ route('daftar_sempro.post') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group">
                                                <input type="text" class="form-control" id="id_sempro" name="id_sempro"
                                                    placeholder="id_sempro" value="{{ $data->id_sempro }}" hidden>
                                            </div>

                                            @if (Auth::user()->r_mahasiswa)
                                                <input type="hidden" name="mahasiswa_id"
                                                    value="{{ Auth::user()->r_mahasiswa->id_mahasiswa }}">
                                            @endif
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label"
                                                    style="font-size: 15px;">Judul</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="judul" name="judul"
                                                        placeholder="Judul"
                                                        value="{{ old('judul', $data->status_judul != 1 ? $data->judul : '') }}"
                                                        @if ($data->status_judul == 1) style="background-color: #ffffff; color: #000000;" required
                                                    @else
                                                        readonly style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;" @endif>

                                                    @if ($data->status_judul == 1)
                                                        <small class="form-text text-muted">Judul sebelumnya:
                                                            {{ $data->judul }}</small>
                                                    @endif
                                                </div>
                                            </div>


                                            <input type="hidden" name="status_judul" value="0">

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Status
                                                    Judul</label>
                                                <div class="col-sm-8">
                                                    <label
                                                        class="badge
                                                        {{ $data->status_judul == 2 ? 'badge-success' : ($data->status_judul == 1 ? 'badge-danger' : 'badge-warning') }} mt-2 mb-2"
                                                        style="font-size: 15px;">
                                                        {{ $data->status_judul == 2 ? 'Diterima' : ($data->status_judul == 1 ? 'Ditolak' : 'Belum Diverifikasi') }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                @if ($data->status_judul == 1)
                                                    <button type="submit" class="btn btn-primary">Upload Judul</button>
                                                @endif
                                            </div>
                                        </form>




                                        @if ($data->status_judul == 2)
                                            <form action="{{ route('daftar_sempro.post') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="id_sempro"
                                                        name="id_sempro" placeholder="id_sempro"
                                                        value="{{ $data->id_sempro }}" hidden>
                                                </div>

                                                @if (Auth::user()->r_mahasiswa)
                                                    <input type="hidden" name="mahasiswa_id"
                                                        value="{{ Auth::user()->r_mahasiswa->id_mahasiswa }}">
                                                @endif

                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="judul"
                                                        name="judul" placeholder="judul" value="{{ $data->judul }}"
                                                        hidden>
                                                </div>

                                                <input type="hidden" name="status_judul"
                                                    value="{{ $data->status_judul }}">

                                                <div class="form-group row mb-3">
                                                    <label class="col-sm-4 col-form-label" for="file_sempro"
                                                        style="font-size: 15px;">Dokumen
                                                        Sempro</label>
                                                    <div class="col-sm-8">
                                                        <input type="file" class="form-control" id="file_sempros"
                                                            name="file_sempro" placeholder="File Sempro" required>
                                                        @error('file_sempro')
                                                            <small>{{ $message }}</small>
                                                        @enderror
                                                        @if (isset($data->file_sempro))
                                                            <p class="mt-2">File: <a
                                                                    href="{{ asset('storage/uploads/mahasiswa/sempro/' . $data->file_sempro) }}"
                                                                    target="_blank">File Sempro</a></p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row mb-3">
                                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Status
                                                        Berkas</label>
                                                    <div class="col-sm-8">
                                                        <label
                                                            class="badge {{ $data->status_berkas == 1 ? 'badge-success' : 'badge-warning' }} mt-2"
                                                            style="font-size: 15px;">
                                                            {{ $data->status_berkas == 1 ? 'Diverifikasi' : 'Belum Diverifikasi' }}
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    @if ($data->status_berkas == 0 && !isset($data->file_sempro))
                                                        <button type="submit" class="btn btn-primary">Upload
                                                            Berkas</button>
                                                    @else
                                                        @if (isset($data->file_sempro) && $data->status_berkas == 0 && $data->status_berkas != 1)
                                                            {{-- <span class="badge badge-warning" style="font-size: 15px;">
                                                            Belum Diverifikasi
                                                        </span> --}}
                                                        @endif
                                                    @endif


                                                </div>
                                            </form>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 mb-3">
                        <div class="card" style="width: 100%; max-width: 800px;">
                            <div class="card-body">
                                <h5 class="card-title">Status <i class="menu-icon mdi mdi-school"></i></h5>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Pembimbing 1</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;{{ $data->r_pembimbing_satu->nama_dosen ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Pembimbing 2</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;{{ $data->r_pembimbing_dua->nama_dosen ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Dosen Penguji</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;
                                            {{ $data->r_penguji ? $data->r_penguji->nama_dosen : '-' }}
                                        </label>

                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Tanggal Sempro</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">
                                            &nbsp;&nbsp;
                                            {{ $data->tanggal_sempro? \Carbon\Carbon::parse($data->tanggal_sempro)->locale('id')->format('d-m-Y'): '-' }}
                                        </label>

                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Ruang Sidang</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;{{ $data->r_ruangan ? $data->r_ruangan->kode_ruang : '-' }}</label>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Jam Sidang</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;{{ $data->r_sesi ? $data->r_sesi->jam : '-' }}</label>
                                    </div>
                                </div>

                                @if (!empty($data->nilai_mahasiswa) && !empty($data->nilai_mahasiswa))
                                    <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_sempro }}"
                                        class="btn btn-primary">
                                        <span class="bi bi-pencil-square"></span>Lihat Nilai
                                    </a>
                                @else
                                    <span class="badge badge-dark" style="font-weight: bold;">Belum Ada Nilai</span>
                                @endif
                            </div>
                        </div>
                    </div>


                    {{-- Modal Nilai Sempro --}}
                    <div class="modal fade" id="nilai{{ $data->id_sempro }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Nilai Sempro ->
                                        {{ $data->r_mahasiswa->nama }}
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form id="nilai{{ $data->id_sempro }}">


                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">No</th>
                                                    <th style="width: 40%;">Jabatan</th>
                                                    <th style="width: 20%;">Nama</th>
                                                    <th style="width: 20%;">Total Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td style="width:80px; word-break: break-all; white-space: normal;">
                                                        Pembimbing 1</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal;">
                                                        {{ $data->r_pembimbing_satu->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_pembimbing_satu->nilai_sempro ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Pembimbing 2</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        {{ $data->r_pembimbing_dua->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_pembimbing_dua->nilai_sempro ?? '' }}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Penguji</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        {{ $data->r_penguji ? $data->r_penguji->nama_dosen : '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_penguji->nilai_sempro ?? '' }}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-start"><strong>Total Nilai</strong>
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->nilai_mahasiswa }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
        @endif
    @endforeach
    </div>
    </div>
@endsection
@section('scripts')
    <script>
        setTimeout(function() {
            var element = document.getElementById('delay');
            if (element) {
                element.parentNode.removeChild(element);
            }
        }, 5000);
    </script>
@endsection
