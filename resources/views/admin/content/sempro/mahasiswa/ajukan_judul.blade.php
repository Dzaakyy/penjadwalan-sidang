@extends('admin.admin_master')

@section('admin')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Sempro</h4>

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

            <a data-bs-toggle="modal" data-bs-target="#daftar_sempro" class="btn btn-primary me-2 mb-3">
                <i class="bi bi-file-earmark-plus"></i> Daftar Sempro
            </a>

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
                                @method('POST')
                                <div class="form-group">
                                    <input type="text" class="form-control" id="id_sempro" name="id_sempro"
                                        placeholder="id_sempro" value="{{ $nextNumber }}" hidden>
                                </div>

                                @if (Auth::user()->r_mahasiswa)
                                    <input type="hidden" name="mahasiswa_id"
                                        value="{{ Auth::user()->r_mahasiswa->id_mahasiswa }}">
                                @endif


                                <div class="form-group">
                                    <label for="judul">Judul</label>
                                    <input type="text" class="form-control" id="judul" name="judul"
                                        placeholder="Judul" required>
                                    @error('Judul')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="file_sempro">File Sempro</label>
                                    <input type="file" class="form-control" id="file_sempro" name="file_sempro"
                                        placeholder="File Sempro" required>
                                    @error('file_sempro')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-primary">Upload</button>
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
                            <th>Judul</th>
                            <th>File Sempro</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_mahasiswa_sempro as $data)
                            <tr class="table-light">
                                <td>{{ $counter++ }}</td>
                                <td style="max-width: 10px; word-break: break-all; white-space: normal;">
                                    {{ $data->judul ?? '' }}
                                </td>
                                <td style="max-width: 50px; word-break: break-all; white-space: normal;">
                                    <a href="{{ asset('storage/uploads/mahasiswa/sempro/' . $data->file_sempro ?? '') }}"
                                        target="_blank">File Sempro</a>
                                </td>
                                <td style="max-width: 90px; word-break: break-all; white-space: normal;">
                                    @if ($data->status_judul == 0)
                                        <span class="badge badge-danger" style="font-weight: bold;">Belum Dikonfirmasi</span>
                                    @elseif ($data->status_judul == 1)
                                        <span class="badge badge-warning" style="font-weight: bold;">Judul Ditolak</span>
                                    @elseif ($data->status_judul == 2)
                                        <span class="badge badge-success" style="font-weight: bold;">Judul Diterima</span>
                                    @else
                                        <span class="badge badge-secondary" style="font-weight: bold;">Status Tidak Dikenal</span>
                                    @endif
                                </td>


                                <td style="width: 10%;">
                                    <div class="d-flex">
                                        <a data-bs-toggle="modal" data-bs-target="#edit{{ $data->id_sempro }}"
                                            class="btn btn-primary mb-2 me-2 align-items-center">
                                            <span class="bi bi-pencil-square"></span>Edit
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#hapus{{ $data->id_sempro }}"
                                            class="btn btn-danger mb-2 align-items-center">
                                            <span class="bi bi-trash"></span>Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Daftar Sempro --}}
                            <div class="modal fade" id="edit{{ $data->id_sempro }}" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title fs-5" id="staticBackdropLabel">Update Data Sempro</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Data Sempro
                                                <b>{{ $mahasiswa->nama }}</b>
                                            </p>

                                            <form id="update_sempro"
                                                action="{{ route('daftar_sempro.update', ['id' => $data->id_sempro]) }}"
                                                method="POST" enctype="multipart/form-data">
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
                                                    <label for="judul">Judul</label>
                                                    <input type="text" class="form-control" id="judul"
                                                        name="judul" placeholder="Judul" value="{{ $data->judul }}"
                                                        required>
                                                    @error('Judul')
                                                        <small>{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="file_sempro">File Sempro</label>
                                                    <input type="file" class="form-control" id="file_sempro"
                                                        name="file_sempro" placeholder="File Sempro"
                                                        value="{{ $data->file_sempro }}">
                                                    @error('file_sempro')
                                                        <small>{{ $message }}</small>
                                                    @enderror
                                                </div>

                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="submit" class="btn btn-primary">Ya, Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Konfirmasi hapus data --}}
                            <div class="modal fade" id="hapus{{ $data->id_sempro }}" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus Data
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah kamu yakin ingin menghapus data
                                                <b>{{ $data->r_mahasiswa->nama }}</b>
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <form action="{{ route('daftar_sempro.delete', ['id' => $data->id_sempro]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-primary">Ya, Hapus</button>
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
