@extends('admin.admin_master')
@section('admin')
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Data Mahasiswa</h4>
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

            @if ($errors->has('duplicate_data'))
                <div id="delay" class="alert alert-danger" role="alert">
                    <strong>Error:</strong> <br>
                    {!! $errors->first('duplicate_data') !!}
                </div>
            @endif



            <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary me-2 mb-3">
                <i class="bi bi-file-earmark-plus"></i> New
            </a>
            <a href="{{ route('mahasiswa.export') }}" class="btn btn-primary me-2 mb-3">
                <i class="bi bi-file-earmark-plus"></i> Export
            </a>
            <a data-bs-toggle="modal" data-bs-target="#import" class="btn btn-primary me-2 mb-3">
                <i class="bi bi-file-earmark-plus"></i> Import
            </a>


            <div class="modal fade" id="import" tabindex="-1" aria-labelledby="importLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="importlabel">New message
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="{{ route('mahasiswa.import') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="file" class="col-form-label">Import File</label>
                                    <input type="file" class="form-control" name="file" id="file">
                                    @error('file')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <div class="table-responsive">
                <table class="table table-hover dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th>Nama Mahasiswa</th>
                            <th>Nim</th>
                            {{-- <th>Jurusan</th> --}}
                            <th>Prodi</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_mahasiswa as $data)
                            <tr class="table-Light">
                                <td>{{ $data->id_mahasiswa }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->nim }}</td>
                                {{-- <td>{{ $data->r_prodi->r_jurusan->nama_jurusan}}</td> --}}
                                <td>{{ $data->r_prodi->prodi }}</td>
                                <td>
                                    @if ($data->gender == 0)
                                        Laki-Laki
                                    @else
                                        Perempuan
                                    @endif
                                </td>
                                <td>
                                    @if ($data->status_mahasiswa == 0)
                                        Tidak Aktif
                                    @else
                                        Aktif
                                    @endif
                                </td>
                                <td style="width: 10%;">
                                    <div class="d-flex">
                                        <a href="{{ route('mahasiswa.detail', ['id' => $data->id_mahasiswa]) }}"
                                            class="btn btn-success mb-2 me-2 align-items-center">
                                            <span class="bi bi-pencil-square"></span>Detail</a>

                                        <a href="{{ route('mahasiswa.edit', ['id' => $data->id_mahasiswa]) }}"
                                            class="btn btn-primary mb-2 me-2 align-items-center"><span
                                                class="bi bi-pencil-square"></span>Edit</a>

                                        <a data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $data->id_mahasiswa }}"
                                            class="btn btn-danger mb-2 align-items-center"><span
                                                class="bi bi-trash"></span>Hapus</a>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Konfirmasi hapus data --}}
                            <div class="modal fade" id="staticBackdrop{{ $data->id_mahasiswa }}" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">>
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi
                                                Hapus Data</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah kamu yakin ingin menghapus data
                                                <b>{{ $data->nama }}</b>
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-between">

                                            <form action="{{ route('mahasiswa.delete', ['id' => $data->id_mahasiswa]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-primary">Ya,
                                                    Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
