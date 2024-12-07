@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Sesi</h4>
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

            <a href="{{ route('sesi.create') }}" class="btn btn-primary me-2 mb-3">
                <i class="bi bi-file-earmark-plus"></i> New
            </a>


            <div class="table-responsive">
                <table class="table table-hover dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th>Sesi</th>
                            <th>Jam</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_sesi as $data)
                            <tr class="table-Light">
                                <td>{{ $data->id_sesi }}</td>
                                <td>{{ $data->sesi }}</td>
                                <td>{{ $data->jam }}</td>
                                <td style="width: 10%;">
                                    <div class="d-flex">
                                        <a href="{{ route('sesi.edit', ['id' => $data->id_sesi]) }}"
                                            class="btn btn-primary mb-2 me-2 align-items-center"><span
                                                class="bi bi-pencil-square"></span>Edit</a>

                                        <a data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $data->id_sesi }}"
                                            class="btn btn-danger mb-2 align-items-center"><span
                                                class="bi bi-trash"></span>Hapus</a>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Konfirmasi hapus data --}}
                            <div class="modal fade" id="staticBackdrop{{ $data->id_sesi }}" data-bs-backdrop="static"
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
                                            <p>Apakah kamu yakin ingin menghapus Sesi
                                                <b>{{ $data->sesi }}</b> Jam <b>{{ $data->jam }}</b>
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-between">

                                            <form action="{{ route('sesi.delete', ['id' => $data->id_sesi]) }}"
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
