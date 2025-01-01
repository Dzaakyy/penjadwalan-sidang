@extends('admin.admin_master')

@section('admin')
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Data Nilai Sidang Sempro</h4>


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
                            <th>Nilai Pembimbing 1</th>
                            <th>Nilai Pembimbing 2</th>
                            <th>Nilai Penguji</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_dosen_sempro as $data)
                            @if (
                                !is_null($data->pembimbing_satu) &&
                                    !is_null($data->pembimbing_dua) &&
                                    !is_null($data->penguji) &&
                                    !is_null($data->ruangan_id) &&
                                    !is_null($data->tanggal_sempro) &&
                                    !is_null($data->sesi_id))
                                <tr class="table-light">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $data->r_mahasiswa->nama }}</td>
                                    <td>
                                        @if ($data->r_pembimbing_satu)
                                            @if ($data->r_nilai_pembimbing_satu)
                                                {{ $data->r_pembimbing_satu->nama_dosen . ' - ' . $data->r_nilai_pembimbing_satu->nilai_sempro }}
                                            @else
                                                {{ $data->r_pembimbing_satu->nama_dosen . ' - Belum Ada Nilai' }}
                                            @endif
                                        @else
                                            {{ 'Belum Ada Pembimbing Satu' }}
                                        @endif
                                    </td>

                                    <td>
                                        @if ($data->r_pembimbing_dua)
                                            @if ($data->r_nilai_pembimbing_dua)
                                                {{ $data->r_pembimbing_dua->nama_dosen . ' - ' . $data->r_nilai_pembimbing_dua->nilai_sempro }}
                                            @else
                                                {{ $data->r_pembimbing_dua->nama_dosen . ' - Belum Ada Nilai' }}
                                            @endif
                                        @else
                                            {{ 'Belum Ada Pembimbing Dua' }}
                                        @endif

                                    </td>

                                    <td>
                                        @if ($data->r_penguji)
                                            @if ($data->r_nilai_penguji)
                                                {{ $data->r_penguji->nama_dosen . ' - ' . $data->r_nilai_penguji->nilai_sempro }}
                                            @else
                                                {{ $data->r_penguji->nama_dosen . ' - Belum Ada Nilai' }}
                                            @endif
                                        @else
                                            {{ 'Belum Ada Pembimbing Dua' }}
                                        @endif

                                    </td>
                                    {{-- <td>
                                        {{ $data->r_penguji
                                            ? $data->r_penguji->nama_dosen . ' - ' . $data->r_nilai_penguji->nilai_sempro
                                            : $data->r_penguji->nama_dosen . ' - Belum Ada Nilai' }}
                                    </td> --}}

                                    <td>
                                        @php
                                            $roles = $rolesPerMahasiswa[$data->mahasiswa_id] ?? [
                                                'isPembimbingSatu' => false,
                                                'isPembimbingDua' => false,
                                                'isPenguji' => false,
                                            ];
                                        @endphp

                                        @if ($roles['isPembimbingSatu'])
                                            @if (isset($data->r_nilai_pembimbing_satu) &&
                                                    !is_null($data->r_nilai_pembimbing_satu->nilai_sempro) &&
                                                    $data->r_nilai_pembimbing_satu->status == 0)
                                                <a data-bs-toggle="modal" data-bs-target="#Editnilai{{ $data->id_sempro }}"
                                                    class="btn btn-success">
                                                    <span class="bi bi-pencil-square"></span> Edit
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_sempro }}"
                                                    class="btn btn-primary">
                                                    <span class="bi bi-pencil-square"></span> Nilai
                                                </a>
                                            @endif
                                        @elseif ($roles['isPembimbingDua'])
                                            @if (isset($data->r_nilai_pembimbing_dua) &&
                                                    !is_null($data->r_nilai_pembimbing_dua->nilai_sempro) &&
                                                    $data->r_nilai_pembimbing_dua->status == 1)
                                                <a data-bs-toggle="modal" data-bs-target="#Editnilai{{ $data->id_sempro }}"
                                                    class="btn btn-success">
                                                    <span class="bi bi-pencil-square"></span> Edit
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_sempro }}"
                                                    class="btn btn-primary">
                                                    <span class="bi bi-pencil-square"></span> Nilai
                                                </a>
                                            @endif
                                        @elseif ($roles['isPenguji'])
                                            @if (isset($data->r_nilai_penguji) &&
                                                    !is_null($data->r_nilai_penguji->nilai_sempro) &&
                                                    $data->r_nilai_penguji->status == 2)
                                                <a data-bs-toggle="modal" data-bs-target="#Editnilai{{ $data->id_sempro }}"
                                                    class="btn btn-success">
                                                    <span class="bi bi-pencil-square"></span> Edit
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_sempro }}"
                                                    class="btn btn-primary">
                                                    <span class="bi bi-pencil-square"></span> Nilai
                                                </a>
                                            @endif
                                        @endif
                                    </td>


                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>


                @foreach ($data_dosen_sempro as $data)
                    @php

                        $roles = $rolesPerMahasiswa[$data->mahasiswa_id] ?? [
                            'isPembimbingSatu' => false,
                            'isPembimbingDua' => false,
                            'isPenguji' => false,
                        ];

                        $roleString = '';
                        if ($roles['isPembimbingSatu']) {
                            $roleString = 'Pembimbing 1';
                        } elseif ($roles['isPembimbingDua']) {
                            $roleString = 'Pembimbing 2';
                        } elseif ($roles['isPenguji']) {
                            $roleString = 'Penguji';
                        }  else {
                            $roleString = 'Pembimbing';
                        }
                    @endphp
                    <div class="modal fade" id="nilai{{ $data->id_sempro }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">
                                        {{ $roleString }}
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Nilai Sidang Sempro
                                        ->
                                        {{ $data->r_mahasiswa->nama }}
                                    </h4>

                                    <form id="nilai_sidang_sempro{{ $data->id_sempro }}"
                                        action="{{ route('nilai_sidang_sempro.post', ['id' => $data->id_sempro]) }}"
                                        method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="id_nilai_sempro"
                                                name="id_nilai_sempro" value="{{ $nextNumber }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" name="sempro_id" value="{{ $data->id_sempro }}">
                                            @error('sempro_id')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">No</th>
                                                    <th style="width: 15%;">Kriteria</th>
                                                    <th style="width: 60%;">Deskripsi</th>
                                                    <th style="width: 20%;">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Pendahuluan</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        Mahasiswa mampu menjelaskan latar belakang, tujuan, dan tujuan
                                                        kontribusi penelitian.</td>
                                                    <td>
                                                        <input type="number" class="form-control pendahuluan"
                                                            name="pendahuluan" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->pendahuluan ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->pendahuluan ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->pendahuluan ?? ''
                                                                        : '')) }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Tinjauan Pustaka</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal; ">
                                                        Mahasiswa mampu menampilkan teori yang relevan dan dituliskan secara
                                                        runtun dan lengkap dengan disertai argumentasi ilmiah dari
                                                        pengusulan
                                                        proposal.</td>
                                                    <td>
                                                        <input type="number" class="form-control tinjauan_pustaka"
                                                            name="tinjauan_pustaka" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->tinjauan_pustaka ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->tinjauan_pustaka ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->tinjauan_pustaka ?? ''
                                                                        : '')) }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Metodologi</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal; ">
                                                        Mahasiswa mampu menentukan metode yang selaras dengan permasalahan
                                                        dan
                                                        konsep teori. Detail rancangan penelitian diuraikan dengan runtun
                                                        setiap
                                                        tahapan dan dapat diselesaikan sesuai dengan rencana waktu
                                                        penelitian.
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control metodologi"
                                                            name="metodologi" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->metodologi ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->metodologi ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->metodologi ?? ''
                                                                        : '')) }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Penggunaan Bahasa</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        Mahasiswa mampu menyusun naskah proposal menggunkan ejaan bahasa
                                                        indonesia yang baik dan benar, serta mengikuti aturan dan panduan
                                                        penulisan.</td>
                                                    <td>
                                                        <input type="number" class="form-control penggunaan_bahasa"
                                                            name="penggunaan_bahasa" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->penggunaan_bahasa ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->penggunaan_bahasa ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->penggunaan_bahasa ?? ''
                                                                        : '')) }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Presentasi</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        PKomunikatif, ketepatan waktu, kejelasan, dan kerunutan dalam
                                                        penyampaian materi.</td>
                                                    <td>
                                                        <input type="number" class="form-control presentasi"
                                                            name="presentasi" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->presentasi ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->presentasi ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->presentasi ?? ''
                                                                        : '')) }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-start"><strong>Total Nilai</strong>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control nilai_sempro"
                                                            name="nilai_sempro" placeholder="Total Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->nilai_sempro ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->nilai_sempro ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->nilai_sempro ?? ''
                                                                        : '')) }}"
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                                <input type="hidden" name="status"
                                                    value="{{ $data->status ?? ($roles['isPembimbingSatu'] ? '0' : ($roles['isPembimbingDua'] ? '1' : ($roles['isPenguji'] ? '2' : ''))) }}">
                                        </table>

                                        <div class="modal-footer text-end">
                                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Modal Edit Nilai Sidang --}}
                    <div class="modal fade" id="Editnilai{{ $data->id_sempro }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">{{ $roleString }}
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Edit Nilai Sidang
                                        Sempro
                                        ->
                                        {{ $data->r_mahasiswa->nama }}
                                    </h4>

                                    <form id="nilai_sidang_sempro{{ $data->id_sempro }}"
                                        action="{{ route('nilai_sidang_sempro.update', ['id' => $data->id_sempro]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="id_nilai_sempro"
                                                name="id_nilai_sempro"
                                                value="{{ $roles['isPembimbingSatu']
                                                    ? (isset($data->r_nilai_pembimbing_satu)
                                                        ? $data->r_nilai_pembimbing_satu->id_nilai_sempro
                                                        : '')
                                                    : ($roles['isPembimbingDua']
                                                        ? (isset($data->r_nilai_pembimbing_dua)
                                                            ? $data->r_nilai_pembimbing_dua->id_nilai_sempro
                                                            : '')
                                                        : ($roles['isPenguji']
                                                            ? (isset($data->r_nilai_penguji)
                                                                ? $data->r_nilai_penguji->id_nilai_sempro
                                                                : '')
                                                            : '')) }}"
                                                readonly>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" name="sempro_id"
                                                value="{{ old('sempro_id', $data->id_sempro) }}">
                                            @error('sempro_id')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">No</th>
                                                    <th style="width: 15%;">Kriteria</th>
                                                    <th style="width: 60%;">Deskripsi</th>
                                                    <th style="width: 20%;">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Pendahuluan</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        Mahasiswa mampu menjelaskan latar belakang, tujuan, dan tujuan
                                                        kontribusi penelitian.</td>
                                                    <td>
                                                        <input type="number" class="form-control pendahuluan"
                                                            name="pendahuluan" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->pendahuluan ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->pendahuluan ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->pendahuluan ?? ''
                                                                        : '')) }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Tinjauan Pustaka</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal; ">
                                                        Mahasiswa mampu menampilkan teori yang relevan dan dituliskan secara
                                                        runtun dan lengkap dengan disertai argumentasi ilmiah dari
                                                        pengusulan
                                                        proposal.</td>
                                                    <td>
                                                        <input type="number" class="form-control tinjauan_pustaka"
                                                            name="tinjauan_pustaka" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->tinjauan_pustaka ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->tinjauan_pustaka ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->tinjauan_pustaka ?? ''
                                                                        : '')) }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Metodologi</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal; ">
                                                        Mahasiswa mampu menentukan metode yang selaras dengan permasalahan
                                                        dan
                                                        konsep teori. Detail rancangan penelitian diuraikan dengan runtun
                                                        setiap
                                                        tahapan dan dapat diselesaikan sesuai dengan rencana waktu
                                                        penelitian.
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control metodologi"
                                                            name="metodologi" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->metodologi ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->metodologi ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->metodologi ?? ''
                                                                        : '')) }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Penggunaan Bahasa</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        Mahasiswa mampu menyusun naskah proposal menggunkan ejaan bahasa
                                                        indonesia yang baik dan benar, serta mengikuti aturan dan panduan
                                                        penulisan.</td>
                                                    <td>
                                                        <input type="number" class="form-control penggunaan_bahasa"
                                                            name="penggunaan_bahasa" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->penggunaan_bahasa ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->penggunaan_bahasa ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->penggunaan_bahasa ?? ''
                                                                        : '')) }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Presentasi</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        PKomunikatif, ketepatan waktu, kejelasan, dan kerunutan dalam
                                                        penyampaian materi.</td>
                                                    <td>
                                                        <input type="number" class="form-control presentasi"
                                                            name="presentasi" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->presentasi ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->presentasi ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->presentasi ?? ''
                                                                        : '')) }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-start"><strong>Total Nilai</strong>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control nilai_sempro"
                                                            name="nilai_sempro" placeholder="Total Nilai"
                                                            value="{{ $roles['isPembimbingSatu']
                                                                ? $data->r_nilai_pembimbing_satu->nilai_sempro ?? ''
                                                                : ($roles['isPembimbingDua']
                                                                    ? $data->r_nilai_pembimbing_dua->nilai_sempro ?? ''
                                                                    : ($roles['isPenguji']
                                                                        ? $data->r_nilai_penguji->nilai_sempro ?? ''
                                                                        : '')) }}"
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>


                                                <input type="hidden" name="status"
                                                    value="{{ $data->status ?? ($roles['isPembimbingSatu'] ? '0' : ($roles['isPembimbingDua'] ? '1' : ($roles['isPenguji'] ? '2' : ''))) }}">
                                        </table>

                                        <div class="modal-footer text-end">
                                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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

        function hitungTotalNilai(element) {
            let container = element.closest("tr").parentNode;


            let pendahuluan = parseFloat(container.querySelector(".pendahuluan").value) || 0;
            let tinjauan_pustaka = parseFloat(container.querySelector(".tinjauan_pustaka").value) || 0;
            let metodologi = parseFloat(container.querySelector(".metodologi").value) || 0;
            let penggunaan_bahasa = parseFloat(container.querySelector(".penggunaan_bahasa").value) || 0;
            let presentasi = parseFloat(container.querySelector(".presentasi").value) || 0;


            let totalNilai = ((pendahuluan) + (tinjauan_pustaka) + (metodologi) + (penggunaan_bahasa) + (presentasi)) / 5;


            let totalInput = container.querySelector(".nilai_sempro");
            if (totalInput) {
                totalInput.value = totalNilai.toFixed(2);
            }
        }
    </script>
@endsection
