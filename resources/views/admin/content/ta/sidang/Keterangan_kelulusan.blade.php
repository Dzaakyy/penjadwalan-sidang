@extends('admin.admin_master')
@section('admin')
    <div class="card">
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
                            <th>Nilai</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_mahasiswa_ta as $data)
                            @if (!empty($data->nilai_mahasiswa))
                                <tr class="table-Light">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $data->r_mahasiswa->nama ?? '' }}</td>

                                    <td style="max-width: 100px; word-break: break-all; white-space: normal;">
                                        @if (!is_null($data->nilai_mahasiswa))
                                        <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_ta }}"
                                            class="btn btn-dark mb-2 me-2 align-items-center">
                                            <span class="bi bi-pencil-square"></span>Nilai
                                        </a>
                                        @endif
                                    </td>
                                    <td style="max-width: 90px; word-break: break-all; white-space: normal;">
                                        @if ($data->keterangan == 0)
                                            <span class="badge badge-warning" style="font-weight: bold;">Belum Sidang</span>
                                        @elseif ($data->keterangan == 1)
                                            <span class="badge badge-danger" style="font-weight: bold;">Tidak Lulus
                                                Sidang</span>
                                        @elseif ($data->keterangan == 2)
                                            <span class="badge badge-success" style="font-weight: bold;">Lulus Sidang</span>
                                        @endif
                                    </td>
                                    </td>
                                    <td style="width: 10%;">
                                        <div class="d-flex">

                                            @if ($data->keterangan == 0)
                                            @if ($data->r_nilai_ketua && $data->r_nilai_sekretaris && $data->r_nilai_penguji_1 && $data->r_nilai_penguji_2 && $data->r_nilai_pembimbing_1 && $data->r_nilai_pembimbing_2)
                                                <a data-bs-toggle="modal" data-bs-target="#keterangan{{ $data->id_ta }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span> Keterangan
                                                </a>
                                            @endif
                                        @elseif ($data->keterangan == 1)
                                            <span class="badge badge-danger" style="font-weight: bold;">Tidak Lulus Sidang</span>
                                        @elseif ($data->keterangan == 2)
                                            <span class="badge badge-success" style="font-weight: bold;">Lulus Sidang</span>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                @foreach ($data_mahasiswa_ta as $data)
                    {{-- Modal Keterangan Sidang --}}
                    <div class="modal fade" id="keterangan{{ $data->id_ta }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Keterangan</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Keterangan Sidang
                                        <b>{{ $data->r_mahasiswa->nama }}</b>
                                    </p>

                                    <form id="daftar_sidang{{ $data->id_ta }}"
                                        action="{{ route('keterangan_ta_ketua.update', ['id' => $data->id_ta]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="d-flex">
                                            <div class="col-sm-4">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input keterangan"
                                                            name="keterangan" value="2"> Lulus
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input keterangan"
                                                            name="keterangan" value="1"> Tidak Lulus
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary">Ya, Verifikasi</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Nilai ta --}}
                    <div class="modal fade" id="nilai{{ $data->id_ta }}" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Nilai ta ->
                                        {{ $data->r_mahasiswa->nama }}
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form id="nilai{{ $data->id_ta }}">


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
                                                        {{ $data->r_nilai_pembimbing_1->nilai_sidang ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td style="width:80px; word-break: break-all; white-space: normal;">
                                                        Pembimbing 2</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal;">
                                                        {{ $data->r_pembimbing_dua->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_pembimbing_2->nilai_sidang ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-start"><strong>Nilai Rata - Rata
                                                            Pendidikan : </strong>
                                                        @php
                                                            $nilaiPembimbing1 =
                                                                $data->r_nilai_pembimbing_1->nilai_sidang ?? 0;
                                                            $nilaiPembimbing2 =
                                                                $data->r_nilai_pembimbing_2->nilai_sidang ?? 0;

                                                            $nilaiRataRata =
                                                                ($nilaiPembimbing1 + $nilaiPembimbing2) / 2;
                                                        @endphp
                                                        {{ number_format($nilaiRataRata, 2) }}
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td style="width:80px; word-break: break-all; white-space: normal;">
                                                        Ketua</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal;">
                                                        {{ $data->r_ketua->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_ketua->nilai_sidang ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Sekretaris</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        {{ $data->r_sekretaris->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_sekretaris->nilai_sidang ?? '' }}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Penguji 1</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        {{ $data->r_penguji_1->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_penguji_1->nilai_sidang ?? '' }}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Penguji 2</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        {{ $data->r_penguji_2->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_penguji_2->nilai_sidang ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-start"><strong>Nilai Rata - Rata Pengji
                                                            : </strong>
                                                        @php
                                                            $nilaiKetua = $data->r_nilai_ketua->nilai_sidang ?? 0;
                                                            $nilaiSekretaris =
                                                                $data->r_nilai_sekretaris->nilai_sidang ?? 0;
                                                            $nilaiPenguji1 =
                                                                $data->r_nilai_penguji_1->nilai_sidang ?? 0;
                                                            $nilaiPenguji2 =
                                                                $data->r_nilai_penguji_2->nilai_sidang ?? 0;
                                                            $nilaiRataRata =
                                                                ($nilaiKetua +
                                                                    $nilaiSekretaris +
                                                                    $nilaiPenguji1 +
                                                                    $nilaiPenguji2) /
                                                                4;
                                                        @endphp
                                                        {{ number_format($nilaiRataRata, 2) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-start"><strong>Nilai Akhir</strong>
                                                        {{ number_format($data->nilai_mahasiswa, 2) }}
                                                    </td>


                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-start"><strong>Keterangan : </strong>
                                                        @if ($data->keterangan == '0')
                                                        @elseif ($data->keterangan == '1')
                                                            Tidak Lulus
                                                        @elseif ($data->keterangan == '2')
                                                            Lulus
                                                        @endif
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
