@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Verifikasi Berkas PKL</h4>
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
                            <th>File Nilai Industri</th>
                            <th>Laporan PKL</th>
                            <th>Nilai Industri</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_mhs_pkl as $data)
                            @if ($data->dokumen_nilai_industri && $data->laporan_pkl)
                                <tr class="table-Light">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $data->r_pkl->r_mahasiswa->nama }}</td>
                                    <td><a href="{{ asset('storage/uploads//mahasiswa/dokumen_nilai_industri/' . $data->dokumen_nilai_industri) }}"
                                            target="_blank">Nilai Industri</a></td>
                                    <td><a href="{{ asset('storage/uploads//mahasiswa/laporan_pkl/' . $data->laporan_pkl) }}"
                                            target="_blank"> Lapran PKL</a></td>
                                    <td>{{ $data->nilai_pembimbing_industri }}</td>
                                    <td>
                                        @if ($data->status_admin == 0)
                                            <span style="color: red; font-weight: bold;">Belum Diverifikasi</span>
                                        @else
                                            <span style="color: green; font-weight: bold;">Diverifikasi</span>
                                        @endif
                                    </td>
                                    <td style="width: 10%;">
                                        <div class="d-flex">
                                            @if ($data->status_admin == 0)
                                                <a data-bs-toggle="modal" data-bs-target="#verif{{ $data->id_mhs_pkl }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center"><span
                                                        class="bi bi-pencil-square"></span>Verifikasi</a>
                                            @else
                                                <span>Data sudah diverifikasi</span>
                                            @endif


                                            {{-- <a data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $data->id_ver_pkl }}"
                                            class="btn btn-danger mb-2 align-items-center"><span
                                                class="bi bi-trash"></span>Hapus</a> --}}
                                        </div>
                                    </td>
                                </tr>


                                {{-- Modal Verifikasi --}}
                                <div class="modal fade" id="verif{{ $data->id_mhs_pkl }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">>
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title fs-5" id="staticBackdropLabel">Verifikasi Data</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah kamu yakin ingin memverifikasi data
                                                    <b>{{ $data->r_pkl->r_mahasiswa->nama }}</b>
                                                </p>

                                                <form id="status_admin{{ $data->id_mhs_pkl }}"
                                                    action="{{ route('ver_berkas_pkl.update', ['id' => $data->id_mhs_pkl]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="nilai_pembimbing_industri">Nilai Pembimbing
                                                            Industri</label>
                                                        <input type="text" class="form-control"
                                                            id="nilai_pembimbing_industri" name="nilai_pembimbing_industri"
                                                            placeholder="nilai pembimbing industri" required>
                                                    </div>
                                                    <input type="hidden" name="status_admin" value="1">
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="submit" class="btn btn-primary">Ya,Verifikasi</button>
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
