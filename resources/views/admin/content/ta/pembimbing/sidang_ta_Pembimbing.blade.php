@extends('admin.admin_master')

@section('admin')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Nilai Sidang TA</h4>


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
                            <th>Prodi</th>
                            <th>Jadwal Sidang</th>
                            <th>Judul</th>
                            <th>Pembimbing 1</th>
                            <th>Pembimbing 2</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_dosen_ta as $data)
                            @if (
                                !is_null($data->pembimbing_satu_id) &&
                                    !is_null($data->pembimbing_dua_id) &&
                                    !is_null($data->ketua) &&
                                    !is_null($data->sekretaris) &&
                                    !is_null($data->penguji_1) &&
                                    !is_null($data->penguji_2) &&
                                    !is_null($data->tanggal_ta) &&
                                    !is_null($data->ruangan_id) &&
                                    !is_null($data->sesi_id))
                                <tr class="table-light">
                                    <td style="max-width: 60px; word-wrap: break-word; white-space: normal;">
                                        {{ $counter++ }}</td>
                                    <td style="max-width: 50px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_mahasiswa->nama }}</td>
                                    <td style="max-width: 50px; word-wrap: break-word; white-space: normal;">
                                        {{ $data->r_mahasiswa->r_prodi->prodi }}</td>
                                    <td style="max-width: 20px; word-wrap: break-word; white-space: normal;">
                                        {{ \Carbon\Carbon::parse($data->tanggal_ta)->locale('id')->translatedFormat('l, d-m-Y') }}
                                        {{ $data->r_sesi->jam }}</td>
                                    <td style="max-width: 60px; word-wrap: break-word; white-space: normal;">
                                        {{ $judulSempro[$data->mahasiswa_id] ?? 'Judul tidak tersedia' }}</td>
                                    <td style="max-width: 50px; word-wrap: break-word; white-space: normal;">
                                        @if ($data->r_pembimbing_satu)
                                            @if ($data->r_nilai_pembimbing_1)
                                                {{ $data->r_pembimbing_satu->nama_dosen . ' - ' . $data->r_nilai_pembimbing_1->nilai_sidang }}
                                            @else
                                                {{ $data->r_pembimbing_satu->nama_dosen . ' - ' }}
                                            @endif
                                        @else
                                            {{ 'Belum Ada Pembimbing 1' }}
                                        @endif
                                    </td>
                                    <td style="max-width: 50px; word-wrap: break-word; white-space: normal;">
                                        @if ($data->r_pembimbing_dua)
                                            @if ($data->r_nilai_pembimbing_2)
                                                {{ $data->r_pembimbing_dua->nama_dosen . ' - ' . $data->r_nilai_pembimbing_2->nilai_sidang }}
                                            @else
                                                {{ $data->r_pembimbing_dua->nama_dosen . ' - ' }}
                                            @endif
                                        @else
                                            {{ 'Belum Ada Pembimbing 2' }}
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $roles = $rolesPerMahasiswa[$data->mahasiswa_id] ?? [
                                                'isPembimbing1' => $data->pembimbing_satu_id == $dosen_penilai,
                                                'isPembimbing2' => $data->pembimbing_dua_id == $dosen_penilai,
                                            ];

                                            $roleString = '';
                                            if ($roles['isPembimbing1']) {
                                                $roleString = 'Pembimbing 1';
                                            } elseif ($roles['isPembimbing2']) {
                                                $roleString = 'Pembimbing 2';
                                            }else {
                                                $roleString = 'Pembimbing';
                                            }
                                        @endphp

                                        @if ($roles['isPembimbing1'])
                                            @if (isset($data->r_nilai_pembimbing_1) &&
                                                    !is_null($data->r_nilai_pembimbing_1->nilai_sidang) &&
                                                    $data->r_nilai_pembimbing_1->status == 4)
                                                <a data-bs-toggle="modal" data-bs-target="#Editnilai{{ $data->id_ta }}"
                                                    class="btn btn-success">
                                                    <span class="bi bi-pencil-square"></span> Edit
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_ta }}"
                                                    class="btn btn-primary">
                                                    <span class="bi bi-pencil-square"></span> Nilai
                                                </a>
                                            @endif
                                        @elseif ($roles['isPembimbing2'])
                                            @if (isset($data->r_nilai_pembimbing_2) &&
                                                    !is_null($data->r_nilai_pembimbing_2->nilai_sidang) &&
                                                    $data->r_nilai_pembimbing_2->status == 5)
                                                <a data-bs-toggle="modal" data-bs-target="#Editnilai{{ $data->id_ta }}"
                                                    class="btn btn-success">
                                                    <span class="bi bi-pencil-square"></span> Edit
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_ta }}"
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


                @foreach ($data_dosen_ta as $data)
                    @php
                        $roles = $rolesPerMahasiswa[$data->mahasiswa_id] ?? [
                            'isPembimbing1' => false,
                            'isPembimbing2' => false,
                        ];
                        $roleString = '';
                                            if ($roles['isPembimbing1']) {
                                                $roleString = 'Pembimbing 1';
                                            } elseif ($roles['isPembimbing2']) {
                                                $roleString = 'Pembimbing 2';
                                            }else {
                                                $roleString = 'Pembimbing';
                                            }
                    @endphp
                    <div class="modal fade" id="nilai{{ $data->id_ta }}" data-bs-backdrop="static"
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
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Nilai Sidang ta
                                        ->
                                        {{ $data->r_mahasiswa->nama }}
                                    </h4>

                                    <form id="nilai_sidang_ta{{ $data->id_ta }}"
                                        action="{{ route('nilai_sidang_pembimbing.post', ['id' => $data->id_ta]) }}"
                                        method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="id_nilai_sidang_ta"
                                                name="id_nilai_sidang_ta" value="{{ $nextNumber }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" name="ta_id" value="{{ $data->id_ta }}">
                                            @error('ta_id')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">No</th>
                                                    <th style="width: 50%;">Kriteria</th>
                                                    <th style="width: 10%;">Bobot(%)</th>
                                                    <th style="width: 20%;">Skor</th>
                                                    <th style="width: 20%;">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td colspan="3" class="text-start"><strong>PRESENTASI</strong></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Sikap Dan Penampilan</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control sikap_penampilan"
                                                            name="sikap_penampilan" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->sikap_penampilan ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->sikap_penampilan ?? ''
                                                                    : '') }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai" value="" readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Komunikasi dan Sistematika</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal; ">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control komunikasi_sistematika"
                                                            name="komunikasi_sistematika" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->komunikasi_sistematika ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->komunikasi_sistematika ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Penguasaan Materi</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal; ">
                                                        20
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control penguasaan_materi"
                                                            name="penguasaan_materi" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->penguasaan_materi ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->penguasaan_materi ?? ''
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
                                                    <td>2</td>
                                                    <td colspan="3" class="text-start"><strong>MAKALAH</strong></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Identifikasi Masalah, tujuan dan kontribusi penelitian</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control identifikasi_masalah"
                                                            name="identifikasi_masalah" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->identifikasi_masalah ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->identifikasi_masalah ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Relevansi teori/ referensi pustaka dan konsep dengan masalah
                                                        penelitian</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control relevansi_teori"
                                                            name="relevansi_teori" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->relevansi_teori ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->relevansi_teori ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Metoda/Algoritma yang digunakan</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        10</td>
                                                    <td>
                                                        <input type="number" class="form-control metode_algoritma"
                                                            name="metode_algoritma" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->metode_algoritma ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->metode_algoritma ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Hasil dan Pembahasan</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        15</td>
                                                    <td>
                                                        <input type="number" class="form-control hasil_pembahasan"
                                                            name="hasil_pembahasan" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->hasil_pembahasan ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->hasil_pembahasan ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Kesimpulan dan Saran</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control kesimpulan_saran"
                                                            name="kesimpulan_saran" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->kesimpulan_saran ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->kesimpulan_saran ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Penggunaan Bahasa dan Tata tulis</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control bahasa_tata_tulis"
                                                            name="bahasa_tata_tulis" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->bahasa_tata_tulis ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->bahasa_tata_tulis ?? ''
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
                                                    <td>3</td>
                                                    <td colspan="3" class="text-start"><strong>PRODUK</strong></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Kesesuaian fungsionalitas sistem</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        25</td>
                                                    <td>
                                                        <input type="number" class="form-control kesesuaian_fungsional"
                                                            name="kesesuaian_fungsional" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->kesesuaian_fungsional ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->kesesuaian_fungsional ?? ''
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
                                                    <td>
                                                        <input type="text" class="form-control nilai_sidang"
                                                            name="nilai_sidang" placeholder="Total Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->nilai_sidang ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->nilai_sidang ?? ''
                                                                    : '') }}"
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>


                                                <input type="hidden" name="status"
                                                    value="{{ $data->status ?? ($roles['isPembimbing1'] ? '4' : ($roles['isPembimbing2'] ? '5' : '')) }}">
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
                    <div class="modal fade" id="Editnilai{{ $data->id_ta }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                        aria-hidden="true">
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

                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Edit Nilai Sidang
                                        ta
                                        ->
                                        {{ $data->r_mahasiswa->nama }}
                                    </h4>
                                    <form id="nilai_sidang_ta{{ $data->id_ta }}"
                                        action="{{ route('nilai_sidang_pembimbing.update', ['id' => $data->id_ta]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="id_nilai_sidang_ta"
                                                name="id_nilai_sidang_ta"
                                                value="{{ $roles['isPembimbing1']
                                                    ? (isset($data->r_nilai_pembimbing_1)
                                                        ? $data->r_nilai_pembimbing_1->id_nilai_sidang_ta
                                                        : '')
                                                    : ($roles['isPembimbing2']
                                                        ? (isset($data->r_nilai_pembimbing_2)
                                                            ? $data->r_nilai_pembimbing_2->id_nilai_sidang_ta
                                                            : '')
                                                        : '') }}"
                                                readonly>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" name="ta_id"
                                                value="{{ old('ta_id', $data->id_ta) }}">
                                            @error('ta_id')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">No</th>
                                                    <th style="width: 50%;">Kriteria</th>
                                                    <th style="width: 10%;">Bobot(%)</th>
                                                    <th style="width: 20%;">Skor</th>
                                                    <th style="width: 20%;">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td colspan="3" class="text-start"><strong>PRESENTASI</strong></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Sikap Dan Penampilan</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control sikap_penampilan"
                                                            name="sikap_penampilan" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->sikap_penampilan ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->sikap_penampilan ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Komunikasi dan Sistematika</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal; ">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control komunikasi_sistematika"
                                                            name="komunikasi_sistematika" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->komunikasi_sistematika ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->komunikasi_sistematika ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Penguasaan Materi</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal; ">
                                                        20
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control penguasaan_materi"
                                                            name="penguasaan_materi" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->penguasaan_materi ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->penguasaan_materi ?? ''
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
                                                    <td>2</td>
                                                    <td colspan="3" class="text-start"><strong>MAKALAH</strong></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Identifikasi Masalah, tujuan dan kontribusi penelitian</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control identifikasi_masalah"
                                                            name="identifikasi_masalah" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->identifikasi_masalah ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->identifikasi_masalah ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Relevansi teori/ referensi pustaka dan konsep dengan masalah
                                                        penelitian</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control relevansi_teori"
                                                            name="relevansi_teori" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->relevansi_teori ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->relevansi_teori ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Metoda/Algoritma yang digunakan</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        10</td>
                                                    <td>
                                                        <input type="number" class="form-control metode_algoritma"
                                                            name="metode_algoritma" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->metode_algoritma ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->metode_algoritma ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Hasil dan Pembahasan</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        15</td>
                                                    <td>
                                                        <input type="number" class="form-control hasil_pembahasan"
                                                            name="hasil_pembahasan" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->hasil_pembahasan ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->hasil_pembahasan ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Kesimpulan dan Saran</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control kesimpulan_saran"
                                                            name="kesimpulan_saran" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->kesimpulan_saran ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->kesimpulan_saran ?? ''
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
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Penggunaan Bahasa dan Tata tulis</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        5</td>
                                                    <td>
                                                        <input type="number" class="form-control bahasa_tata_tulis"
                                                            name="bahasa_tata_tulis" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->bahasa_tata_tulis ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->bahasa_tata_tulis ?? ''
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
                                                    <td>3</td>
                                                    <td colspan="3" class="text-start"><strong>PRODUK</strong></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="width: 20px; word-break: break-all; white-space: normal;">
                                                        Kesesuaian fungsionalitas sistem</td>
                                                    <td style="width: 100px; word-wrap: break-word; white-space: normal;">
                                                        25</td>
                                                    <td>
                                                        <input type="number" class="form-control kesesuaian_fungsional"
                                                            name="kesesuaian_fungsional" placeholder="Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->kesesuaian_fungsional ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->kesesuaian_fungsional ?? ''
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
                                                    <td>
                                                        <input type="text" class="form-control nilai_sidang"
                                                            name="nilai_sidang" placeholder="Total Nilai"
                                                            value="{{ $roles['isPembimbing1']
                                                                ? $data->r_nilai_pembimbing_1->nilai_sidang ?? ''
                                                                : ($roles['isPembimbing2']
                                                                    ? $data->r_nilai_pembimbing_2->nilai_sidang ?? ''
                                                                    : '') }}"
                                                            readonly
                                                            style="background-color: #8fe44d63; color: #000000; cursor: not-allowed; text-align: center; vertical-align: middle;">
                                                    </td>
                                                </tr>


                                                <input type="hidden" name="status"
                                                    value="{{ $data->status ?? ($roles['isPembimbing1'] ? '4' : ($roles['isPembimbing2'] ? '5' : '')) }}">
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


            let sikap_penampilan = parseFloat(container.querySelector(".sikap_penampilan").value) || 0;
            let komunikasi_sistematika = parseFloat(container.querySelector(".komunikasi_sistematika").value) || 0;
            let penguasaan_materi = parseFloat(container.querySelector(".penguasaan_materi").value) || 0;
            let identifikasi_masalah = parseFloat(container.querySelector(".identifikasi_masalah").value) || 0;
            let relevansi_teori = parseFloat(container.querySelector(".relevansi_teori").value) || 0;
            let metode_algoritma = parseFloat(container.querySelector(".metode_algoritma").value) || 0;
            let hasil_pembahasan = parseFloat(container.querySelector(".hasil_pembahasan").value) || 0;
            let kesimpulan_saran = parseFloat(container.querySelector(".kesimpulan_saran").value) || 0;
            let bahasa_tata_tulis = parseFloat(container.querySelector(".bahasa_tata_tulis").value) || 0;
            let kesesuaian_fungsional = parseFloat(container.querySelector(".kesesuaian_fungsional").value) || 0;


            let totalNilai = (sikap_penampilan * 0.05) + (komunikasi_sistematika * 0.05) +
                (penguasaan_materi * 0.20) + (identifikasi_masalah * 0.05) + (relevansi_teori * 0.05) +
                (metode_algoritma * 0.10) + (hasil_pembahasan * 0.15) +
                (kesimpulan_saran * 0.05) + (bahasa_tata_tulis * 0.05) +
                (kesesuaian_fungsional * 0.25);



            let totalInput = container.querySelector(".nilai_sidang");
            if (totalInput) {
                totalInput.value = totalNilai.toFixed(2);
            }
        }


        function hitungTotalNilai(element) {
            let container = element.closest("table");


            let sikap_penampilan = parseFloat(container.querySelector(".sikap_penampilan").value) || 0;
            let komunikasi_sistematika = parseFloat(container.querySelector(".komunikasi_sistematika").value) || 0;
            let penguasaan_materi = parseFloat(container.querySelector(".penguasaan_materi").value) || 0;
            let identifikasi_masalah = parseFloat(container.querySelector(".identifikasi_masalah").value) || 0;
            let relevansi_teori = parseFloat(container.querySelector(".relevansi_teori").value) || 0;
            let metode_algoritma = parseFloat(container.querySelector(".metode_algoritma").value) || 0;
            let hasil_pembahasan = parseFloat(container.querySelector(".hasil_pembahasan").value) || 0;
            let kesimpulan_saran = parseFloat(container.querySelector(".kesimpulan_saran").value) || 0;
            let bahasa_tata_tulis = parseFloat(container.querySelector(".bahasa_tata_tulis").value) || 0;
            let kesesuaian_fungsional = parseFloat(container.querySelector(".kesesuaian_fungsional").value) || 0;

            const bobotSikap = 5;
            const bobotKomunikasi = 5;
            const bobotPenguasaan = 20;
            const bobotIdentifikasi = 5;
            const bobotRelevansi = 5;
            const bobotMetode = 10;
            const bobotHasil = 15;
            const bobotKesimpulan = 5;
            const bobotBahasa = 5;
            const bobotKesesuaian = 25;

            let totalNilai = (sikap_penampilan * 0.05) + (komunikasi_sistematika * 0.05) +
                (penguasaan_materi * 0.20) + (identifikasi_masalah * 0.05) + (relevansi_teori * 0.05) +
                (metode_algoritma * 0.10) + (hasil_pembahasan * 0.15) +
                (kesimpulan_saran * 0.05) + (bahasa_tata_tulis * 0.05) +
                (kesesuaian_fungsional * 0.25);

            let totalInput = container.querySelector(".nilai_sidang");
            if (totalInput) {
                totalInput.value = totalNilai.toFixed(2);
            }

            let nilaiSikap = (sikap_penampilan * bobotSikap / 100).toFixed(2);
            let nilaiKomunikasi = (komunikasi_sistematika * bobotKomunikasi / 100).toFixed(2);
            let nilaiPenguasaan = (penguasaan_materi * bobotPenguasaan / 100).toFixed(2);
            let nilaiIdentifikasi = (identifikasi_masalah * bobotIdentifikasi / 100).toFixed(2);
            let nilaiRelevansi = (relevansi_teori * bobotRelevansi / 100).toFixed(2);
            let nilaiMetode = (metode_algoritma * bobotMetode / 100).toFixed(2);
            let nilaiHasil = (hasil_pembahasan * bobotHasil / 100).toFixed(2);
            let nilaiKesimpulan = (kesimpulan_saran * bobotKesimpulan / 100).toFixed(2);
            let nilaiBahasa = (bahasa_tata_tulis * bobotBahasa / 100).toFixed(2);
            let nilaiKesesuaian = (kesesuaian_fungsional * bobotKesesuaian / 100).toFixed(2);

            let rows = container.querySelectorAll("tr");
            rows.forEach(row => {
                if (row.querySelector(".sikap_penampilan")) {
                    row.querySelector(".nilai_persen").value = nilaiSikap;
                } else if (row.querySelector(".komunikasi_sistematika")) {
                    row.querySelector(".nilai_persen").value = nilaiKomunikasi;
                } else if (row.querySelector(".penguasaan_materi")) {
                    row.querySelector(".nilai_persen").value = nilaiPenguasaan;
                } else if (row.querySelector(".identifikasi_masalah")) {
                    row.querySelector(".nilai_persen").value = nilaiIdentifikasi;
                } else if (row.querySelector(".relevansi_teori")) {
                    row.querySelector(".nilai_persen").value = nilaiRelevansi;
                } else if (row.querySelector(".metode_algoritma")) {
                    row.querySelector(".nilai_persen").value = nilaiMetode;
                } else if (row.querySelector(".hasil_pembahasan")) {
                    row.querySelector(".nilai_persen").value = nilaiHasil;
                } else if (row.querySelector(".kesimpulan_saran")) {
                    row.querySelector(".nilai_persen").value = nilaiKesimpulan;
                } else if (row.querySelector(".bahasa_tata_tulis")) {
                    row.querySelector(".nilai_persen").value = nilaiBahasa;
                } else if (row.querySelector(".kesesuaian_fungsional")) {
                    row.querySelector(".nilai_persen").value = nilaiKesesuaian;
                }
            });
        }

        function hitungSaatLoad() {
            document.querySelectorAll(
                ".sikap_penampilan, .komunikasi_sistematika, .penguasaan_materi, .identifikasi_masalah, .relevansi_teori, .metode_algoritma, .hasil_pembahasan, .kesimpulan_saran, .bahasa_tata_tulis, .kesesuaian_fungsional"
            ).forEach(input => {

                hitungTotalNilai(input);
            });
        }


        window.addEventListener("load", hitungSaatLoad);
    </script>
@endsection
