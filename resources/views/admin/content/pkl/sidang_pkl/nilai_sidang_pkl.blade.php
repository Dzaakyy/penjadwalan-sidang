@extends('admin.admin_master')

@section('admin')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Nilai Sidang PKL</h4>


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
                            <th>Nilai Pembimbing</th>
                            <th>Nilai Penguji</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_dosen_pembimbing_pkl as $data)
                            @if (
                                !is_null($data->dosen_penguji) &&
                                    !is_null($data->ruang_sidang) &&
                                    !is_null($data->tgl_sidang) &&
                                    !is_null($data->jam_sidang))
                                <tr class="table-light">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $data->r_usulan_pkl->r_mahasiswa->nama }}</td>
                                    <td>
                                        {{ $data->r_nilai_pembimbing
                                            ? $data->r_dosen_pembimbing->nama_dosen . ' - ' . $data->r_nilai_pembimbing->nilai_pkl
                                            : $data->r_dosen_pembimbing->nama_dosen . ' - Belum Ada Nilai' }}
                                    </td>
                                    <td>
                                        {{ $data->r_nilai_penguji ? $data->r_dosen_penguji->nama_dosen . ' - ' . $data->r_nilai_penguji->nilai_pkl : $data->r_dosen_penguji->nama_dosen . ' - Belum Ada Nilai' }}
                                    </td>
                                    <td>
                                        @php
                                            $roles = $rolesPerMahasiswa[$data->mahasiswa_id] ?? [
                                                'isPembimbing' => false,
                                                'isPenguji' => false,
                                            ];
                                        @endphp
                                        @if ($roles['isPenguji'])
                                            @if (isset($data->r_nilai_penguji) && !is_null($data->r_nilai_penguji->nilai_pkl) && $data->r_nilai_penguji->status == 1)
                                                <a data-bs-toggle="modal" data-bs-target="#Editnilai{{ $data->id_mhs_pkl }}"
                                                    class="btn btn-success">
                                                    <span class="bi bi-pencil-square"></span> Edit
                                                @else
                                                    <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_mhs_pkl }}"
                                                        class="btn btn-primary">
                                                        <span class="bi bi-pencil-square"></span> Nilai
                                                    </a>
                                            @endif
                                        @elseif ($roles['isPembimbing'])
                                            @if (isset($data->r_nilai_pembimbing) &&
                                                    !is_null($data->r_nilai_pembimbing->nilai_pkl) &&
                                                    $data->r_nilai_pembimbing->status == 0)
                                                <a data-bs-toggle="modal" data-bs-target="#Editnilai{{ $data->id_mhs_pkl }}"
                                                    class="btn btn-success">
                                                    <span class="bi bi-pencil-square"></span> Edit
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_mhs_pkl }}"
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

                @foreach ($data_dosen_pembimbing_pkl as $data)
                    @php
                        $roles = $rolesPerMahasiswa[$data->mahasiswa_id] ?? [
                            'isPembimbing' => false,
                            'isPenguji' => false,
                        ];
                    @endphp
                    {{-- Modal Nilai Sidang --}}
                    <div class="modal fade" id="nilai{{ $data->id_mhs_pkl }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Nilai Sidang Pkl
                                        ->
                                        {{ $data->r_usulan_pkl->r_mahasiswa->nama }}
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form id="nilai_sidang_pkl{{ $data->id_mhs_pkl }}"
                                        action="{{ route('nilai_sidang_pkl.post', ['id' => $data->id_mhs_pkl]) }}"
                                        method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="id_nilai_pkl" name="id_nilai_pkl"
                                                value="{{ $nextNumber }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" name="mhs_pkl_id" value="{{ $data->id_mhs_pkl }}">
                                            @error('mhs_pkl_id')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">No</th>
                                                    <th style="width: 40%;">Materi Penilaian</th>
                                                    <th style="width: 20%;">bobot(%)</th>
                                                    <th style="width: 20%;">skor</th>
                                                    <th style="width: 50%;">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td style="width:80px; word-break: break-all; white-space: normal;">
                                                        Bahasa dan Tata Tulis Laporan</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal;">
                                                        15</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control bahasa" name="bahasa"
                                                            placeholder="Bahasa"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->bahasa ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->bahasa ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-wrap: break-word; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai (%)" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Analisis Masalah</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        15</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control analisis" name="analisis"
                                                            placeholder="Analisis"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->analisis ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->analisis ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai (%)" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Nilai Sikap</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        15
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control sikap" name="sikap"
                                                            placeholder="Sikap"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->sikap ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->sikap ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Komunikasi</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        15
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control komunikasi"
                                                            name="komunikasi" placeholder="Komunikasi"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->komunikasi ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->komunikasi ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Sistematika Penyajian</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        15
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control penyajian"
                                                            name="penyajian" placeholder="Sistematika Penyajian"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->penyajian ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->penyajian ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Penguasaan Materi</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        15
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control penguasaan"
                                                            name="penguasaan" placeholder="Penguasaan Materi"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->penguasaan ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->penguasaan ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="4" class="text-start"><strong>Total Nilai</strong>
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_pkl"
                                                            name="nilai_pkl" placeholder="Total Nilai"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->nilai_pkl ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->nilai_pkl ?? ''
                                                                    : '') }}"
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <input type="hidden" name="status"
                                            value="{{ $data->status ?? ($roles['isPembimbing'] ? '0' : ($roles['isPenguji'] ? '1' : '')) }}">

                                        <div class="modal-footer text-end">
                                            <button type="submit" class="btn btn-primary">Konfrmasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Modal Edit Nilai Sidang --}}
                    <div class="modal fade" id="Editnilai{{ $data->id_mhs_pkl }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Edit Nilai Sidang
                                        Pkl
                                        ->
                                        {{ $data->r_usulan_pkl->r_mahasiswa->nama }}
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form id="nilai_sidang_pkl{{ $data->id_mhs_pkl }}"
                                        action="{{ route('nilai_sidang_pkl.update', ['id' => $data->id_mhs_pkl]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="id_nilai_pkl"
                                                name="id_nilai_pkl"
                                                value="{{ $roles['isPembimbing'] ? (isset($data->r_nilai_pembimbing) ? $data->r_nilai_pembimbing->id_nilai_pkl : '')
                                                : ($roles['isPenguji'] ? (isset($data->r_nilai_penguji) ? $data->r_nilai_penguji->id_nilai_pkl : '') : '') }}"
                                                readonly>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" name="mhs_pkl_id"
                                                value="{{ old('mhs_pkl_id', $data->id_mhs_pkl) }}">
                                            @error('mhs_pkl_id')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>


                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">No</th>
                                                    <th style="width: 40%;">Materi Penilaian</th>
                                                    <th style="width: 20%;">bobot(%)</th>
                                                    <th style="width: 20%;">skor</th>
                                                    <th style="width: 50%;">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td style="width:80px; word-break: break-all; white-space: normal;">
                                                        Bahasa dan Tata Tulis Laporan</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal;">
                                                        15</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control bahasa" name="bahasa"
                                                            placeholder="Bahasa"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->bahasa ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->bahasa ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-wrap: break-word; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai (%)" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Analisis Masalah</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        15</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control analisis" name="analisis"
                                                            placeholder="Analisis"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->analisis ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->analisis ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai (%)" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Nilai Sikap</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        15
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control sikap" name="sikap"
                                                            placeholder="Sikap"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->sikap ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->sikap ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Komunikasi</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        15
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control komunikasi"
                                                            name="komunikasi" placeholder="Komunikasi"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->komunikasi ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->komunikasi ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Sistematika Penyajian</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        15
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control penyajian"
                                                            name="penyajian" placeholder="Sistematika Penyajian"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->penyajian ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->penyajian ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Penguasaan Materi</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        15
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control penguasaan"
                                                            name="penguasaan" placeholder="Penguasaan Materi"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->penguasaan ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->penguasaan ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai" value=""
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="4" class="text-start"><strong>Total Nilai</strong>
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_pkl"
                                                            name="nilai_pkl" placeholder="Total Nilai"
                                                            value="{{ $roles['isPembimbing']
                                                                ? $data->r_nilai_pembimbing->nilai_pkl ?? ''
                                                                : ($roles['isPenguji']
                                                                    ? $data->r_nilai_penguji->nilai_pkl ?? ''
                                                                    : '') }}"
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <input type="hidden" name="status"
                                            value="{{ $data->status ?? ($roles['isPembimbing'] ? '0' : ($roles['isPenguji'] ? '1' : '')) }}">

                                        <div class="modal-footer text-end">
                                            <button type="submit" class="btn btn-primary">Konfrmasi</button>
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
            let container = element.closest("table");


            let bahasa = parseFloat(container.querySelector(".bahasa").value) || 0;
            let analisis = parseFloat(container.querySelector(".analisis").value) || 0;
            let sikap = parseFloat(container.querySelector(".sikap").value) || 0;
            let komunikasi = parseFloat(container.querySelector(".komunikasi").value) || 0;
            let penyajian = parseFloat(container.querySelector(".penyajian").value) || 0;
            let penguasaan = parseFloat(container.querySelector(".penguasaan").value) || 0;

            const bobotBahasa = 15;
            const bobotAnalisis = 15;
            const bobotSikap = 15;
            const bobotKomunikasi = 15;
            const bobotPenyajian = 15;
            const bobotPenguasaan = 25;

            let totalNilai = (bahasa * 0.15) + (analisis * 0.15) + (sikap * 0.15) + (komunikasi * 0.15)
            + (penyajian * 0.15) + (penguasaan * 0.25);

            let totalInput = container.querySelector(".nilai_pkl");
            if (totalInput) {
                totalInput.value = totalNilai.toFixed(2);
            }

            let nilaiBahasa = (bahasa * bobotBahasa / 100).toFixed(2);
            let nilaiAnalisis = (analisis * bobotAnalisis / 100).toFixed(2);
            let nilaiSikap = (sikap * bobotSikap / 100).toFixed(2);
            let nilaiKomunikasi = (komunikasi * bobotKomunikasi / 100).toFixed(2);
            let nilaiPenyajian = (penyajian * bobotPenyajian / 100).toFixed(2);
            let nilaiPenguasaan = (penguasaan * bobotPenguasaan / 100).toFixed(2);

            let rows = container.querySelectorAll("tr");
            rows.forEach(row => {
                if (row.querySelector(".bahasa")) {
                    row.querySelector(".nilai_persen").value = nilaiBahasa;
                } else if (row.querySelector(".analisis")) {
                    row.querySelector(".nilai_persen").value = nilaiAnalisis;
                } else if (row.querySelector(".sikap")) {
                    row.querySelector(".nilai_persen").value = nilaiSikap;
                }  else if (row.querySelector(".komunikasi")) {
                    row.querySelector(".nilai_persen").value = nilaiKomunikasi;
                } else if (row.querySelector(".penyajian")) {
                    row.querySelector(".nilai_persen").value = nilaiPenyajian;
                } else if (row.querySelector(".penguasaan")) {
                    row.querySelector(".nilai_persen").value = nilaiPenguasaan;
                }
            });
        }

        function hitungSaatLoad() {
            document.querySelectorAll(".bahasa, .analisis, .sikap, .komunikasi, .penyajian, .penguasaan").forEach(input => {
                hitungTotalNilai(input);
            });
        }


        window.addEventListener("load", hitungSaatLoad);
    </script>
@endsection
