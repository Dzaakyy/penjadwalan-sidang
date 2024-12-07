@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Jabatan Pimpinan</h4>
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

            <a href="{{ route('jabatan_pimpinan.create') }}" class="btn btn-primary me-2 mb-3">
                <i class="bi bi-file-earmark-plus"></i> New
            </a>


            <div class="table-responsive">
                <table class="table table-hover dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th>Jabatan Pimpinan</th>
                            <th>Kode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_jabatan_pimpinan as $data)
                            <tr class="table-Light">
                                <td>{{ $data->id_jabatan_pimpinan }}</td>
                                <td>{{ $data->jabatan_pimpinan }}</td>
                                <td>{{ $data->kode_jabatan_pimpinan }}</td>
                                <td>
                                    @if ($data->status_jabatan_pimpinan == 0)
                                        Tidak Aktif
                                    @else
                                        Aktif
                                    @endif
                                </td>
                                <td style="width: 10%;">
                                    <div class="d-flex">
                                        <a href="{{ route('jabatan_pimpinan.edit', ['id' => $data->id_jabatan_pimpinan]) }}"
                                            class="btn btn-primary mb-2 me-2 align-items-center"><span
                                                class="bi bi-pencil-square"></span>Edit</a>

                                        <a data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $data->id_jabatan_pimpinan }}"
                                            class="btn btn-danger mb-2 align-items-center"><span
                                                class="bi bi-trash"></span>Hapus</a>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Konfirmasi hapus data --}}
                            <div class="modal fade" id="staticBackdrop{{ $data->id_jabatan_pimpinan }}" data-bs-backdrop="static"
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
                                            <p>Apakah kamu yakin ingin menghapus
                                                <b>{{ $data->jabatan_pimpinan }}</b>
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-between">

                                            <form action="{{ route('jabatan_pimpinan.delete', ['id' => $data->id_jabatan_pimpinan]) }}"
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
