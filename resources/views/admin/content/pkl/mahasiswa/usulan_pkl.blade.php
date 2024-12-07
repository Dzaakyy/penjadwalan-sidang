@extends('admin.admin_master')
@section('admin')
    @foreach ($data_usulan_pkl as $data)
        @if (Auth::user()->r_mahasiswa && Auth::user()->r_mahasiswa->id_mahasiswa == $data->mahasiswa_id)
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Usulan Pkl</h4>

                    {{-- <a href="{{ route('usulan_pkl.create') }}" class="btn btn-primary me-2 mb-3">
                <i class="bi bi-file-earmark-plus"></i> New
            </a> --}}

                    {{-- @if (Auth::user()->r_mahasiswa)
            @php

                $mahasiswa_id = Auth::user()->r_mahasiswa->id_mahasiswa;

                $usulanTerdaftar = $data_usulan_pkl->where('mahasiswa_id', $mahasiswa_id)->isNotEmpty();
            @endphp

            @if ($usulanTerdaftar)
                <p>Anda sudah mendaftar untuk usulan PKL.</p>
            @else
                <a href="{{ route('usulan_pkl.create') }}" class="btn btn-primary me-2 mb-3">
                    <i class="bi bi-file-earmark-plus"></i> New
                </a>
            @endif
        @else
            <div class="alert alert-danger">

                ID Mahasiswa tidak ditemukan. Pastikan akun Anda terhubung dengan data Mahasiswa.
            </div>
        @endif --}}



                    {{-- @if ($data_usulan_pkl->contains(function ($item) {
        return $item->konfirmasi == 1;
    }))
                <p>Anda sudah mendaftar dan status pendaftaran Anda sudah dikonfirmasi.</p>
            @else
                <a href="{{ route('usulan_pkl.create') }}" class="btn btn-primary me-2 mb-3">
                    <i class="bi bi-file-earmark-plus"></i> New
                </a>
            @endif --}}

                    {{-- Usulan Mahasiswa --}}
                    <div class="table-responsive" id="tableMhs">
                        <table class="table table-hover dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="table-info">
                                    {{-- <th>#</th> --}}
                                    <th>Nama</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Konfirmasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr class="table-Light">
                                    {{-- <td>{{ $data->id_usulan_pkl }}</td> --}}
                                    <td>{{ $data->r_mahasiswa->nama }}</td>
                                    <td>{{ $data->r_perusahaan->nama_perusahaan }}</td>
                                    <td>
                                        @if ($data->konfirmasi == 0)
                                            <span class="badge badge-danger" style="font-weight: bold;">Belum
                                                Dikonfirmasi</span>
                                        @else
                                            <span class="badge badge-success" style="font-weight: bold;">Sudah
                                                Dikonfirmasi</span>
                                        @endif
                                    </td>


                                    <td style="width: 10%;">
                                        <div class="d-flex">
                                            {{-- <a href="{{ route('usulan_pkl.edit', ['id' => $data->id_usulan_pkl]) }}"
                                            class="btn btn-primary mb-2 me-2 align-items-center"><span
                                                class="bi bi-pencil-square"></span>Edit</a> --}}


                                            <div class="d-flex">
                                                @if (Auth::user()->r_mahasiswa && Auth::user()->r_mahasiswa->id_mahasiswa == $data->mahasiswa_id)
                                                    <a data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdrop{{ $data->id_usulan_pkl }}"
                                                        class="btn btn-danger mb-2 align-items-center">
                                                        <span class="bi bi-trash"></span> Hapus
                                                    </a>
                                                @endif
                                            </div>


                                        </div>
                                    </td>
                                </tr>


                                {{-- Modal Konfirmasi hapus data --}}
                                <div class="modal fade" id="staticBackdrop{{ $data->id_usulan_pkl }}"
                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">>
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
                                                    <b>{{ $data->r_mahasiswa->nama }}</b>
                                                </p>
                                            </div>
                                            <div class="modal-footer justify-content-between">

                                                <form
                                                    action="{{ route('usulan_pkl.delete', ['id' => $data->id_usulan_pkl]) }}"
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

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        @endif
    @endforeach



    <div class="card mt-5">
        <div class="card-body">
            <h4 class="card-title">Tempat Pkl</h4>


            {{-- Usulan Mahasiswa --}}
            <div class="table-responsive" id="tableTempat">
                <table class="table table-hover dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th>Nama Perusahaan</th>
                            <th>Deskripsi</th>
                            <th style="max-width: 40px; word-break: break-all; white-space: normal;">Kuota</th>
                            <th style="max-width: 53px; word-break: break-all; white-space: normal; text-align: center;">
                                Kuota<br>Tersedia</th>
                            <th>Nama Mahasiswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_perusahaan as $perusahaan)
                            <tr class="table-Light">
                                @if ($perusahaan->status == 1)
                                    <td>{{ $perusahaan->id_perusahaan }}</td>
                                    <td style="max-width: 150px; word-break: break-all; white-space: normal;">
                                        {{ $perusahaan->nama_perusahaan }}</td>
                                    <td style="max-width: 150px; word-break: break-all; white-space: normal;">
                                        {{ $perusahaan->deskripsi }}</td>
                                    <td style="text-align: center;">{{ $perusahaan->kuota }}</td>


                                    <td style="text-align: center;">
                                        @php

                                            $jumlahMahasiswaTerkonfirmasi = $perusahaan->r_usulan_pkl
                                                ->where('konfirmasi', 1)
                                                ->count();
                                            $sisaKuota = $perusahaan->kuota - $jumlahMahasiswaTerkonfirmasi;
                                        @endphp
                                        {{ $sisaKuota }}
                                    </td>

                                    <td>
                                        @php

                                            $confirmedUsulan = $perusahaan->r_usulan_pkl->where('konfirmasi', 1);
                                        @endphp

                                        @if ($confirmedUsulan->isEmpty())
                                            <p>-</p>
                                        @else
                                            @foreach ($confirmedUsulan as $usulan)
                                                <div>{{ $usulan->r_mahasiswa->nama }}</div>
                                            @endforeach
                                        @endif
                                    </td>



                                    <td>
                                        @if ($sisaKuota > 0)
                                            @if (Auth::user()->r_mahasiswa)
                                                @php
                                                    $mahasiswa_id = Auth::user()->r_mahasiswa->id_mahasiswa;

                                                    $usulanTerdaftar = $data_usulan_pkl->where('mahasiswa_id', $mahasiswa_id)
                                                        ->where('perusahaan_id', $perusahaan->id_perusahaan)
                                                        ->isNotEmpty();

                                                    $mahasiswaTerdaftarDiPerusahaanLain = $data_usulan_pkl->where('mahasiswa_id', $mahasiswa_id)
                                                        ->where('perusahaan_id', '!=', $perusahaan->id_perusahaan)
                                                        ->isNotEmpty();
                                                @endphp

                                                @if ($usulanTerdaftar)
                                                    <span class="badge badge-primary" style="font-weight: bold;">Anda Sudah Mendaftar</span>
                                                @elseif ($mahasiswaTerdaftarDiPerusahaanLain)
                                                    <button class="btn btn-dark" style="cursor: not-allowed;">
                                                        <span class="bi bi-pencil-square"></span> Daftar
                                                    </button>
                                                @else
                                                    <a data-bs-toggle="modal"
                                                        data-bs-target="#daftar{{ $perusahaan->id_perusahaan }}"
                                                        class="btn btn-primary mb-2 align-items-center">
                                                        <span class="bi bi-trash"></span> Daftar
                                                    </a>
                                                @endif
                                            @else
                                                <button class="btn btn-dark" style="cursor: not-allowed;">
                                                    <span class="bi bi-pencil-square"></span> Daftar
                                                </button>
                                            @endif
                                        @else

                                            <span class="badge badge-danger" style="font-weight: bold;">Kuota Penuh</span>
                                        @endif
                                    </td>






                            </tr>

                            {{-- Modal Daftar --}}
                            <div class="modal fade" id="daftar{{ $perusahaan->id_perusahaan }}" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title fs-5" id="staticBackdropLabel">Usulan Tempat Magang</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah kamu yakin ingin megusulkan
                                                <b>{{ $perusahaan->nama_perusahaan }}</b> sebagai tempat PKL?
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-between">

                                            <form action="{{ route('usulan_pkl.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="perusahaan_id"
                                                    value="{{ $perusahaan->id_perusahaan }}">

                                                @if (Auth::user()->r_mahasiswa)
                                                    <input type="hidden" name="mahasiswa_id"
                                                        value="{{ Auth::user()->r_mahasiswa->id_mahasiswa }}">
                                                @endif

                                                <input type="hidden" id="id_usulan_pkl" name="id_usulan_pkl"
                                                    value="{{ $nextNumber }}" readonly>

                                                <button type="submit" class="btn btn-primary">Daftar</button>



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
