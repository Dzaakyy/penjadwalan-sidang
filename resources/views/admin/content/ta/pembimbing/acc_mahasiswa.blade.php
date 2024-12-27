@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Acc Mahasiswa</h4>
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
                            <th>Status Admin</th>
                            <th>Status Pembimbing 1</th>
                            <th>Status Pembimbing 2</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_mahasiswa_ta as $data)
                            @if ($data->status_berkas == 1)
                                <tr class="table-Light">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $data->r_mahasiswa->nama ?? '' }}</td>
                                    <td style="max-width: 150px; word-wrap: break-word; white-space: normal;">
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
                                    <td style="max-width: 90px; word-break: break-all; white-space: normal;">
                                        @if ($data->acc_pembimbing_satu == 0)
                                            <span class="badge badge-warning" style="font-weight: bold;">Belum
                                                Diacc</span>
                                        @else
                                            <span class="badge badge-success" style="font-weight: bold;">Sudah
                                                DiAcc</span>
                                        @endif
                                    </td>
                                    <td style="max-width: 90px; word-break: break-all; white-space: normal;">
                                        @if ($data->acc_pembimbing_dua == 0)
                                            <span class="badge badge-warning" style="font-weight: bold;">Belum
                                                Diacc</span>
                                        @else
                                            <span class="badge badge-success" style="font-weight: bold;">Sudah
                                                DiAcc</span>
                                        @endif
                                    </td>
                                    </td>
                                    <td style="width: 10%;">
                                        <div class="d-flex">

                                            @if ($data->pembimbing_satu_id == auth()->user()->r_dosen->id_dosen && $data->acc_pembimbing_satu == 0)
                                                <a data-bs-toggle="modal" data-bs-target="#verifikasi{{ $data->id_ta }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span> Acc Mahasiswa
                                                </a>
                                            @elseif ($data->pembimbing_dua_id == auth()->user()->r_dosen->id_dosen && $data->acc_pembimbing_dua == 0)
                                                <a data-bs-toggle="modal" data-bs-target="#verifikasi{{ $data->id_ta }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span> Acc Mahasiswa
                                                </a>
                                            @else
                                                <span class="badge badge-success" style="font-weight: bold;">Sudah
                                                    DiAcc</span>
                                            @endif
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
                                                <h4 class="modal-title fs-5" id="staticBackdropLabel">Acc Data</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah kamu yakin ingin menyetujui
                                                    <b>{{ $data->r_mahasiswa->nama }}</b> untuk lanjut sidang?
                                                </p>

                                                @if ($data->pembimbing_satu_id == auth()->user()->r_dosen->id_dosen && $data->acc_pembimbing_satu == 0)
                                                    <form
                                                        action="{{ route('acc_pembimbing_satu.update', ['id' => $data->id_ta]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="acc_pembimbing_satu" value="1">
                                                        <div class="modal-footer text-end">
                                                            <button type="submit" class="btn btn-primary">Ya, Acc</button>
                                                        </div>
                                                    </form>
                                                @endif

                                                @if ($data->pembimbing_dua_id == auth()->user()->r_dosen->id_dosen && $data->acc_pembimbing_dua == 0)
                                                    <form
                                                        action="{{ route('acc_pembimbing_dua.update', ['id' => $data->id_ta]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="acc_pembimbing_dua" value="1">
                                                        <div class="modal-footer text-end">
                                                            <button type="submit" class="btn btn-primary">Ya, Acc</button>
                                                        </div>
                                                    </form>
                                                @endif

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
