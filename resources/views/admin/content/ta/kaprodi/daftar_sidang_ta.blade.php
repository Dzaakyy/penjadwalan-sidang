@extends('admin.admin_master')
@section('admin')
@section('style')
    <style>
        .custom-table {
            overflow: hidden;
            transition: height 0.6s ease;
            /* Animasi perubahan tinggi */
            height: 0;
            /* Default tertutup */
            visibility: visible;
            /* Selalu terlihat selama animasi berlangsung */
        }

        .collapsed-custom {
            height: 0 !important;
            /* Saat tertutup, tinggi menjadi 0 */
            visibility: visible;
            /* Tetap terlihat selama animasi */
            display: block;
            /* Tetap dirender untuk memungkinkan animasi */
        }

        .open {
            height: auto !important;
            visibility: visible;
        }
    </style>
@endsection

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
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="py-3 bg-transparent d-flex justify-content-between align-items-center">
                <h4 class="card-title">Daftar Sidang TA</h4>
                <button id="toggleTableBtn" class="btn btn-primary" onclick="toggleTable('tableContent1')">Toggle
                    Tabel</button>
            </div>


            <div class="table-responsive custom-table" id="tableContent1">
                <table class="table table-hover dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Pembimbing</th>
                            <th>Ketua</th>
                            <th>Sekretaris</th>
                            <th>Penguji 1</th>
                            <th>Penguji 2</th>
                            <th>Tanggal Sidang</th>
                            <th>Ruang Sidang</th>
                            <th>Jam Sidang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_mahasiswa_ta as $data)
                            @if (
                                $data->acc_pembimbing_satu == 1 &&
                                    $data->acc_pembimbing_dua == 1 &&
                                    $data->keterangan == 0 ||
                                    $data->keterangan == 1)

                                <tr class="table-Light">
                                    <td>{{ $counter++ }}</td>
                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_mahasiswa->nama }}</td>
                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_pembimbing_satu->nama_dosen ?? '' }}(1) -
                                        {{ $data->r_pembimbing_dua->nama_dosen ?? '' }}(2)</td>

                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_ketua->nama_dosen ?? '' }}</td>
                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_sekretaris->nama_dosen ?? '' }}</td>
                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_penguji_1->nama_dosen ?? '' }}</td>
                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_penguji_2->nama_dosen ?? '' }}</td>
                                    <td style="max-width: 70px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->tanggal_ta? \Carbon\Carbon::parse($data->tanggal_ta)->locale('id')->format('d-m-Y'): '' }}
                                    <td style="max-width: 50px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_ruangan->kode_ruang ?? '' }}</td>
                                    <td style="max-width: 30px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_sesi->jam ?? '' }}</td>
                                    </td>
                                    <td style="width: 10%;">
                                        <div class="d-flex flex-column">
                                            @if (is_null($data->pembimbing_satu_id) ||
                                                    is_null($data->pembimbing_dua_id) ||
                                                    is_null($data->ketua) ||
                                                    is_null($data->sekretaris) ||
                                                    is_null($data->penguji_1) ||
                                                    is_null($data->penguji_2) ||
                                                    is_null($data->tanggal_ta) ||
                                                    is_null($data->ruangan_id) ||
                                                    is_null($data->sesi_id))
                                                <a data-bs-toggle="modal" data-bs-target="#daftar{{ $data->id_ta }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Daftar
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#daftar{{ $data->id_ta }}"
                                                    class="btn btn-success mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Edit
                                                </a>
                                                <a href="{{ route('cetak_surat_tugas_ta.download', ['id' => $data->id_ta]) }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center" id="downloadButton"
                                                    target="_blank">
                                                    <i class="bi bi-pencil-square"></i>Cetak
                                                </a>
                                            @endif
                                            @if (!is_null($data->nilai_mahasiswa))
                                                <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_ta }}"
                                                    class="btn btn-dark mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Nilai
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="card mt-5 shadow-sm">
        <div class="card-body">
            <div class="py-3 bg-transparent d-flex justify-content-between align-items-center">
                <h4 class="card-title">Selesai Sidang TA</h4>
                <button id="toggleTableBtn" class="btn btn-primary" onclick="toggleTable('tableContent2')">Toggle
                    Tabel</button>
            </div>

            <div class="table-responsive custom-table" id="tableContent2">
                <table class="table table-hover dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th>Nama</th>
                            <th>Pembimbing</th>
                            <th>Ketua</th>
                            <th>Sekretaris</th>
                            <th>Penguji 1</th>
                            <th>Penguji 2</th>
                            <th>Tanggal Sidang</th>
                            <th>Ruang Sidang</th>
                            <th>Jam Sidang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_mahasiswa_ta as $data)
                            @if (
                                $data->acc_pembimbing_satu == 1 &&
                                    $data->acc_pembimbing_dua == 1 &&
                                    !is_null($data->tanggal_ta) &&
                                    !is_null($data->ruangan_id) &&
                                    !is_null($data->sesi_id) &&
                                    $data->keterangan == 2)
                                <tr class="table-Light">
                                    <td>{{ $counter++ }}</td>
                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_mahasiswa->nama }}</td>
                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_pembimbing_satu->nama_dosen ?? '' }}(1) -
                                        {{ $data->r_pembimbing_dua->nama_dosen ?? '' }}(2)</td>

                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_ketua->nama_dosen ?? '' }}</td>
                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_sekretaris->nama_dosen ?? '' }}</td>
                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_penguji_1->nama_dosen ?? '' }}</td>
                                    <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_penguji_2->nama_dosen ?? '' }}</td>
                                    <td style="max-width: 70px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->tanggal_ta? \Carbon\Carbon::parse($data->tanggal_ta)->locale('id')->format('d-m-Y'): '' }}
                                    <td style="max-width: 50px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_ruangan->kode_ruang ?? '' }}</td>
                                    <td style="max-width: 30px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_sesi->jam ?? '' }}</td>
                                    </td>
                                    <td style="width: 10%;">
                                        <div class="d-flex flex-column">
                                            @if (is_null($data->pembimbing_satu_id) &&
                                                    is_null($data->pembimbing_dua_id) &&
                                                    is_null($data->ketua) &&
                                                    is_null($data->sekretaris) &&
                                                    is_null($data->penguji_1) &&
                                                    is_null($data->penguji_2) &&
                                                    is_null($data->tanggal_ta) &&
                                                    is_null($data->ruangan_id) &&
                                                    is_null($data->sesi_id))
                                                <a data-bs-toggle="modal" data-bs-target="#daftar{{ $data->id_ta }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Daftar
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#daftar{{ $data->id_ta }}"
                                                    class="btn btn-success mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Edit
                                                </a>
                                                <a href="{{ route('cetak_surat_tugas_ta.download', ['id' => $data->id_ta]) }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center" id="downloadButton"
                                                    target="_blank">
                                                    <i class="bi bi-pencil-square"></i>Cetak
                                                </a>
                                            @endif
                                            @if (!is_null($data->nilai_mahasiswa))
                                                <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_ta }}"
                                                    class="btn btn-dark mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Nilai
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}

    @foreach ($data_mahasiswa_ta as $data)
        {{-- Modal Daftar Sidang --}}
        <div class="modal fade" id="daftar{{ $data->id_ta }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Daftar Sidang</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>Apakah kamu yakin ingin mendaftarkan sidang
                            <b>{{ $data->r_mahasiswa->nama }}</b>
                        </p>

                        <form id="daftar_sidang{{ $data->id_ta }}"
                            action="{{ route('daftar_sidang_ta_kaprodi.update', ['id' => $data->id_ta]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')


                            <div class="form-group">
                                <label for="ketua{{ $data->id_ta }}">Pilih Ketua Sidang</label>
                                <select id="ketua{{ $data->id_ta }}" name="ketua" class="form-select" required>
                                    <option value="" disabled selected>Pilih Ketua Sidang
                                    </option>
                                    @if (isset($data->r_pembimbing_satu))
                                        <option value="{{ $data->r_pembimbing_satu->id_dosen }}"
                                            {{ (isset($data->r_ketua) && $data->r_ketua->id_dosen == $data->r_pembimbing_satu->id_dosen) || old('ketua') == $data->r_pembimbing_satu->id_dosen ? 'selected' : '' }}>
                                            {{ $data->r_pembimbing_satu->nama_dosen }}
                                        </option>
                                    @endif
                                    @if (isset($data->r_pembimbing_dua))
                                        <option value="{{ $data->r_pembimbing_dua->id_dosen }}"
                                            {{ (isset($data->r_ketua) && $data->r_ketua->id_dosen == $data->r_pembimbing_dua->id_dosen) || old('ketua') == $data->r_pembimbing_dua->id_dosen ? 'selected' : '' }}>
                                            {{ $data->r_pembimbing_dua->nama_dosen }}
                                        </option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sekretaris{{ $data->id_ta }}">Pilih Sekretaris
                                    Sidang</label>
                                <select id="sekretaris{{ $data->id_ta }}" name="sekretaris" class="form-select"
                                    required>
                                    <option value="" disabled selected>Pilih Sekretaris
                                        Sidang
                                    </option>
                                    @foreach ($dosen as $dosenItem)
                                        @if (!isset($data->r_ketua) || $data->r_ketua->id_dosen != $dosenItem->id_dosen)
                                            @if (!isset($data->r_penguji_1) || $data->r_penguji_1->id_dosen != $dosenItem->id_dosen)
                                                @if (!isset($data->r_penguji_2) || $data->r_penguji_2->id_dosen != $dosenItem->id_dosen)
                                                    <option value="{{ $dosenItem->id_dosen }}"
                                                        {{ (isset($data->r_sekretaris) && $data->r_sekretaris->id_dosen == $dosenItem->id_dosen) || old('sekretaris') == $dosenItem->id_dosen ? 'selected' : '' }}>
                                                        {{ $dosenItem->nama_dosen }}
                                                    </option>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="penguji_1{{ $data->id_ta }}">Pilih Dosen
                                    Penguji 1</label>
                                <select id="penguji_1{{ $data->id_ta }}" name="penguji_1" class="form-select" required>
                                    <option value="" disabled selected>Pilih Dosen
                                        Penguji 1
                                    </option>
                                    @foreach ($dosen as $dosenItem)
                                        @if (!isset($data->r_ketua) || $data->r_ketua->id_dosen != $dosenItem->id_dosen)
                                            @if (!isset($data->r_sekretaris) || $data->r_sekretaris->id_dosen != $dosenItem->id_dosen)
                                                @if (!isset($data->r_penguji_2) || $data->r_penguji_2->id_dosen != $dosenItem->id_dosen)
                                                    <option value="{{ $dosenItem->id_dosen }}"
                                                        {{ (isset($data->r_penguji_1) && $data->r_penguji_1->id_dosen == $dosenItem->id_dosen) || old('penguji_1') == $dosenItem->id_dosen ? 'selected' : '' }}>
                                                        {{ $dosenItem->nama_dosen }}
                                                    </option>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="penguji_2{{ $data->id_ta }}">Pilih Dosen
                                    Penguji 2</label>
                                <select id="penguji_2{{ $data->id_ta }}" name="penguji_2" class="form-select" required>
                                    <option value="" disabled selected>Pilih Dosen
                                        Penguji 2
                                    </option>
                                    @foreach ($dosen as $dosenItem)
                                        @if (!isset($data->r_ketua) || $data->r_ketua->id_dosen != $dosenItem->id_dosen)
                                            @if (!isset($data->r_sekretaris) || $data->r_sekretaris->id_dosen != $dosenItem->id_dosen)
                                                @if (!isset($data->r_penguji_1) || $data->r_penguji_1->id_dosen != $dosenItem->id_dosen)
                                                    <option value="{{ $dosenItem->id_dosen }}"
                                                        {{ (isset($data->r_penguji_2) && $data->r_penguji_2->id_dosen == $dosenItem->id_dosen) || old('penguji_2') == $dosenItem->id_dosen ? 'selected' : '' }}>
                                                        {{ $dosenItem->nama_dosen }}
                                                    </option>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="tanggal_ta{{ $data->id_ta }}">Tanggal
                                    Sidang</label>
                                <input type="date" id="tanggal_ta{{ $data->id_ta }}" name="tanggal_ta"
                                    class="form-control" required value="{{ old('tanggal_ta', $data->tanggal_ta) }}">
                            </div>


                            <div class="form-group">
                                <label for="ruangan_id{{ $data->id_ta }}">Pilih Ruang
                                    Sidang</label>
                                <select id="ruangan_id{{ $data->id_ta }}" name="ruangan_id" class="form-select"
                                    required
                                    {{ !isset($data->ketua) || empty($data->ketua) || (!isset($data->sekretaris) || empty($data->sekretaris)) || (!isset($data->penguji_1) || empty($data->penguji_1)) || (!isset($data->penguji_2) || empty($data->penguji_2)) ? 'disabled' : '' }}>
                                    <option value="" disabled selected>Pilih Ruangan</option>
                                    @foreach ($data_ruangan as $ruang)
                                        <option value="{{ $ruang->id_ruang }}"
                                            {{ old('ruangan_id', $data->ruangan_id) == $ruang->id_ruang ? 'selected' : '' }}>
                                            {{ $ruang->kode_ruang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="sesi_id{{ $data->id_ta }}">Pilih Jam Sidang</label>
                                <select id="sesi_id{{ $data->id_ta }}" name="sesi_id" class="form-select" required
                                    {{ !isset($data->ketua) || empty($data->ketua) || (!isset($data->sekretaris) || empty($data->sekretaris)) || (!isset($data->penguji_1) || empty($data->penguji_1)) || (!isset($data->penguji_2) || empty($data->penguji_2)) ? 'disabled' : '' }}>
                                    <option value="" disabled selected>Pilih Jam Sidang
                                    </option>
                                    @foreach ($jam_sidang as $jam)
                                        <option value="{{ $jam->id_sesi }}"
                                            {{ old('sesi_id', $data->sesi_id) == $jam->id_sesi ? 'selected' : '' }}>
                                            {{ $jam->jam }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>



                            <input type="hidden" name="keterangan" value="0">
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">Ya, Daftar</button>
                    </div>
                    </form>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                $nilaiPembimbing1 = $data->r_nilai_pembimbing_1->nilai_sidang ?? 0;
                                                $nilaiPembimbing2 = $data->r_nilai_pembimbing_2->nilai_sidang ?? 0;

                                                $nilaiRataRata = ($nilaiPembimbing1 + $nilaiPembimbing2) / 2;
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
                                                $nilaiSekretaris = $data->r_nilai_sekretaris->nilai_sidang ?? 0;
                                                $nilaiPenguji1 = $data->r_nilai_penguji_1->nilai_sidang ?? 0;
                                                $nilaiPenguji2 = $data->r_nilai_penguji_2->nilai_sidang ?? 0;
                                                $nilaiRataRata =
                                                    ($nilaiKetua + $nilaiSekretaris + $nilaiPenguji1 + $nilaiPenguji2) /
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
@endsection
@section('scripts')
    <script>
        setTimeout(function() {
            var element = document.getElementById('delay');
            if (element) {
                element.parentNode.removeChild(element);
            }
        }, 5000);

        function toggleTable(tableId) {
            var tableContent = document.getElementById(tableId);

            if (!tableContent.classList.contains('collapsed-custom')) {
                // Animasi menutup tabel
                tableContent.style.height = tableContent.scrollHeight + 'px'; // Mulai dari tinggi saat ini
                setTimeout(() => {
                    tableContent.style.height = '0'; // Turunkan tinggi menjadi 0
                    tableContent.addEventListener('transitionend', function onClose() {
                        tableContent.classList.add(
                            'collapsed-custom'); // Tambahkan kelas collapsed-custom setelah animasi
                        tableContent.style.height = '0'; // Pastikan tetap 0 setelah animasi selesai
                        tableContent.removeEventListener('transitionend', onClose); // Hapus listener
                    });
                }, 10); // Delay kecil untuk memastikan transisi dimulai
                sessionStorage.setItem(`tableState-${tableId}`, 'closed'); // Simpan state tertutup
            } else {
                // Animasi membuka tabel
                tableContent.classList.remove('collapsed-custom');
                tableContent.style.height = tableContent.scrollHeight + 'px'; // Tinggi asli untuk membuka
                tableContent.addEventListener('transitionend', function onOpen() {
                    tableContent.style.height = 'auto'; // Reset ke auto setelah selesai
                    tableContent.removeEventListener('transitionend', onOpen);
                });
                sessionStorage.setItem(`tableState-${tableId}`, 'open'); // Simpan state terbuka
            }
        }

        function loadTableState() {
            // Dapatkan semua elemen dengan kelas "custom-table"
            var tables = document.querySelectorAll('.custom-table');

            tables.forEach((table) => {
                var tableId = table.id; // Gunakan ID tabel untuk state unik
                var tableState = sessionStorage.getItem(`tableState-${tableId}`);

                if (tableState === 'closed') {
                    // Jika state terakhir adalah tertutup
                    table.classList.add('collapsed-custom');
                    table.style.height = '0'; // Tinggi menjadi 0
                } else {
                    // Jika state terakhir adalah terbuka atau tidak ada state
                    table.classList.remove('collapsed-custom');
                    table.style.height = 'auto'; // Tinggi menyesuaikan konten
                }

                table.style.visibility = 'visible'; // Pastikan tabel terlihat
            });
        }

        // Jalankan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', loadTableState);



        document.addEventListener("DOMContentLoaded", () => {
            function updatePengujiOptions(ketua, sekretaris, penguji_1, penguji_2) {
                const selectedValues = new Set([
                    ketua.value,
                    sekretaris.value,
                    penguji_1.value,
                    penguji_2.value,
                ]);

                [ketua, sekretaris, penguji_1, penguji_2].forEach((dropdown) => {
                    Array.from(dropdown.options).forEach((option) => {

                        option.style.display = "";


                        if (selectedValues.has(option.value) && option.value !== dropdown.value) {
                            option.style.display = "none";
                        }
                    });
                });
            }

            document.querySelectorAll('.modal').forEach((modal) => {
                modal.addEventListener('show.bs.modal', function() {
                    const ketua = modal.querySelector(`#ketua${modal.id.split('daftar')[1]}`);
                    const sekretaris = modal.querySelector(
                        `#sekretaris${modal.id.split('daftar')[1]}`);
                    const penguji_1 = modal.querySelector(
                        `#penguji_1${modal.id.split('daftar')[1]}`);
                    const penguji_2 = modal.querySelector(
                        `#penguji_2${modal.id.split('daftar')[1]}`);


                    updatePengujiOptions(ketua, sekretaris, penguji_1, penguji_2);


                    [ketua, sekretaris, penguji_1, penguji_2].forEach((dropdown) => {
                        dropdown.addEventListener("change", () =>
                            updatePengujiOptions(ketua, sekretaris, penguji_1,
                                penguji_2)
                        );
                    });
                });
            });
        });



        $(document).ready(function() {
            $(document).on('change', '[id^="penguji_"]', function() {
                let penguji = $(this).val();
                let modalId = $(this).attr('id').split('penguji_')[1];

                if (!penguji) {
                    alert('Penguji harus dipilih.');
                    return;
                }

                $('#tanggal_ta' + modalId).val('').prop('disabled', false);
                resetDropdowns(modalId);
            });

            $(document).on('change', '[id^="tanggal_ta"]', function() {
                let tanggal = $(this).val();
                let modalId = $(this).attr('id').split('tanggal_ta')[1];
                let penguji = $('#penguji_1' + modalId).val();

                if (!tanggal || !penguji) {
                    alert('Harap pilih tanggal dan penguji terlebih dahulu.');
                    return;
                }

                $.ajax({
                    url: '/get-available-rooms-ta',
                    type: 'GET',
                    data: {
                        tanggal: tanggal,
                        penguji: penguji
                    },
                    beforeSend: () => $('#loader').show(),
                    success: (data) => {
                        let $ruanganDropdown = $('#ruangan_id' + modalId).prop('disabled',
                            false).empty();
                        $ruanganDropdown.append(
                            '<option value="" disabled selected>Pilih Ruangan</option>');

                        if (data?.length > 0) {
                            data.forEach(room => {
                                $ruanganDropdown.append('<option value="' + room
                                    .id_ruang + '">' + room.nama_ruangan +
                                    '</option>');
                            });
                        } else {
                            $ruanganDropdown.append(
                                '<option value="" disabled>Tidak ada ruangan tersedia</option>'
                            );
                        }
                    },
                    error: (xhr) => {
                        alert(xhr.responseJSON?.message || 'Error mengambil data ruangan.');
                        resetDropdowns(modalId);
                    },
                    complete: () => $('#loader').hide(),
                });
            });

            $(document).on('change', '[id^="ruangan_id"]', function() {
                let modalId = $(this).attr('id').split('ruangan_id')[1];
                let tanggal = $('#tanggal_ta' + modalId).val();
                let idRuangan = $(this).val();

                if (!tanggal || !idRuangan) {
                    alert('Harap pilih tanggal dan ruangan terlebih dahulu.');
                    return;
                }

                $.ajax({
                    url: '/get-available-sessions-ta',
                    type: 'GET',
                    data: {
                        tanggal: tanggal,
                        id_ruang: idRuangan
                    },
                    beforeSend: () => $('#loader').show(),
                    success: (data) => {
                        let $sesiDropdown = $('#sesi_id' + modalId).prop('disabled', false)
                            .empty();
                        $sesiDropdown.append(
                            '<option value="" disabled selected>Pilih Sesi</option>');

                        if (data?.length > 0) {
                            data.forEach(session => {
                                $sesiDropdown.append('<option value="' + session
                                    .id_sesi + '">' + session.sesi + ' - ' + session
                                    .jam + '</option>');
                            });
                        } else {
                            $sesiDropdown.append(
                                '<option value="" disabled>Tidak ada sesi tersedia</option>'
                            );
                        }
                    },
                    error: (xhr) => {
                        alert(xhr.responseJSON?.message || 'Error mengambil data sesi.');
                        $('#sesi_id' + modalId).prop('disabled', true).empty();
                    },
                    complete: () => $('#loader').hide(),
                });
            });

            function resetDropdowns(modalId) {
                $('#ruangan_id' + modalId).prop('disabled', true).empty();
                $('#sesi_id' + modalId).prop('disabled', true).empty();
            }
        });
    </script>
@endsection
