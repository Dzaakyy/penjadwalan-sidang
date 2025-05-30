@extends('admin.admin_master')
@section('admin')
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Verifikasi File TA</h4>
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

            <div class="table-responsive">
                <table class="table table-hover dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th>Nama</th>
                            <th>File</th>
                            {{-- <th>Laporan TA</th>
                            <th>Tugas Akhir</th> --}}
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_mahasiswa_ta as $data)
                            @if (!empty($data->proposal_final) || !empty($data->laporan_ta) || !empty($data->tugas_akhir))
                                <tr class="table-Light">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $data->r_mahasiswa->nama ?? '' }}</td>
                                    <td style="max-width: 100px; word-break: break-all; white-space: normal;">
                                        <a href="{{ asset('storage/uploads/mahasiswa/ta/proposal/' . $data->proposal_final) }}"
                                            class="btn btn-primary mb-2 d-flex align-items-center me-1" target="_blank">
                                            <i class="mdi mdi-file-document-outline me-3"></i>
                                            Proposal Final
                                        </a>

                                        <a href="{{ asset('storage/uploads/mahasiswa/ta/laporan/' . $data->laporan_ta) }}"
                                            class="btn btn-primary mb-2 d-flex align-items-center me-1" target="_blank"><i
                                                class="mdi mdi-file-document-outline me-3"></i>Laporan TA</a>


                                        <a href="{{ asset('storage/uploads/mahasiswa/ta/tugasAkhir/' . $data->tugas_akhir) }}"
                                            class="btn btn-primary mb-2 d-flex align-items-center me-1" target="_blank"><i
                                                class="mdi mdi-file-document-outline me-3"></i>Tugas Akhir</a>
                                    </td>
                                    <td style="max-width: 90px; word-break: break-all; white-space: normal;">
                                        @if ($data->status_berkas == 0)
                                            <span class="badge badge-warning" style="font-weight: bold;">Belum
                                                Diverifikasi</span>
                                        @else
                                            <span class="badge badge-success" style="font-weight: bold;">Sudah
                                                Diverifikasi</span>
                                        @endif
                                    </td>
                                    </td>
                                    <td style="width: 10%;">
                                        <div class="d-flex">

                                            @if ($data->status_berkas == 0)
                                                <a data-bs-toggle="modal" data-bs-target="#verifikasi{{ $data->id_ta }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span> Verifikasi
                                                </a>
                                            @else
                                                <span class="badge badge-success" style="font-weight: bold;">Sudah
                                                    Diverifikasi</span>
                                            @endif


                                            {{-- <a data-bs-toggle="modal" data-bs-target="#edit{{ $data->id_ta }}"
                                                class="btn btn-success mb-2 me-2 align-items-center">
                                                <span class="bi bi-pencil-square"></span>Edit
                                            </a> --}}


                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Veifikasi Berkas --}}
                                <div class="modal fade" id="verifikasi{{ $data->id_ta }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title fs-5" id="staticBackdropLabel">Verifikasi Berkas</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah kamu yakin ingin memverifikasi Berkas
                                                    <b>{{ $data->r_mahasiswa->nama }}</b>
                                                </p>

                                                <form id="daftar_sidang{{ $data->id_ta }}"
                                                    action="{{ route('verifikasi_berkas_ta_admin.update', ['id' => $data->id_ta]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status_berkas" value="1">


                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="submit" class="btn btn-primary">Ya, Verifikasi</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
