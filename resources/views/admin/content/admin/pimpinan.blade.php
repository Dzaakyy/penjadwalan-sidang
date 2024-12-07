@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Pimpinan</h4>
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

            <a href="{{ route('pimpinan.create') }}" class="btn btn-primary me-2 mb-3">
                <i class="bi bi-file-earmark-plus"></i> New
            </a>


            <div class="table-responsive">
                <table class="table table-hover dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th>Dosen</th>
                            <th>Jabatan Pimpinan</th>
                            <th>jurusan</th>
                            <th>Prodi</th>
                            <th>periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_pimpinan as $data)
                            <tr class="table-Light">
                                <td>{{ $data->id_pimpinan }}</td>
                                <td>{{ $data->r_dosen->nama_dosen }}</td>
                                <td>{{ $data->r_jabatan_pimpinan->jabatan_pimpinan }}</td>
                                <td>{{ $data->r_dosen->r_jurusan->nama_jurusan }}</td>
                                <td>{{ $data->r_dosen->r_prodi->prodi }}</td>
                                <td>{{ $data->periode}}</td>
                                <td>
                                    @if ($data->status_pimpinan == 0)
                                        Tidak Aktif
                                    @else
                                        Aktif
                                    @endif
                                </td>
                                <td style="width: 10%;">
                                    <div class="d-flex">
                                        <a href="{{ route('pimpinan.edit', ['id' => $data->id_pimpinan]) }}"
                                            class="btn btn-primary mb-2 me-2 align-items-center"><span
                                                class="bi bi-pencil-square"></span>Edit</a>

                                        <a data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $data->id_pimpinan}}"
                                            class="btn btn-danger mb-2 align-items-center"><span
                                                class="bi bi-trash"></span>Hapus</a>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Konfirmasi hapus data --}}
                            <div class="modal fade" id="staticBackdrop{{ $data->id_pimpinan }}" data-bs-backdrop="static"
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
                                                <b>{{ $data->r_dosen->nama_dosen }}</b>
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-between">

                                            <form action="{{ route('pimpinan.delete', ['id' => $data->id_pimpinan]) }}"
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
