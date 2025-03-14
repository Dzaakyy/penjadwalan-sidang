@extends('admin.admin_master')
@section('admin')
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Daftar Sidang PKL</h4>
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
                            <th>Dosen Pembimbing</th>
                            <th>Dosen Penguji</th>
                            <th>Ruang Sidang</th>
                            <th>Jam Sidang</th>
                            <th>Tanggal Sidang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_mahasiswa_pkl as $data)
                            @if ($data->status_admin == 1)
                                <tr class="table-Light">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $data->r_pkl->r_mahasiswa->nama }}</td>
                                    <td>{{ $data->r_dosen_pembimbing->nama_dosen }}</td>
                                    <td>{{ $data->r_dosen_penguji->nama_dosen ?? '-' }}</td>
                                    <td>{{ $data->r_ruang->kode_ruang ?? '-' }}</td>
                                    <td>{{ $data->r_sesi->jam ?? '-' }}</td>
                                    <td>{{ $data->tgl_sidang? \Carbon\Carbon::parse($data->tgl_sidang)->locale('id')->format('d-m-Y'): '-' }}
                                    </td>
                                    <td style="width: 10%;">
                                        <div class="d-flex flex-column">
                                            @if (is_null($data->dosen_penguji) || is_null($data->ruang_sidang) || is_null($data->tgl_sidang))
                                                <a data-bs-toggle="modal" data-bs-target="#daftar{{ $data->id_mhs_pkl }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Daftar
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#daftar{{ $data->id_mhs_pkl }}"
                                                    class="btn btn-success mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Edit
                                                </a>
                                                <a href="{{ route('cetak_surat_tugas_pkl.download', ['id' => $data->id_mhs_pkl]) }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center" id="downloadButton"
                                                    target="_blank">
                                                    <i class="bi bi-pencil-square"></i>Cetak
                                                </a>
                                                <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_mhs_pkl }}"
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

                @foreach ($data_mahasiswa_pkl as $data)
                    {{-- Modal Daftar Sidang --}}
                    <div class="modal fade" id="daftar{{ $data->id_mhs_pkl }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Daftar Sidang</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah kamu yakin ingin mendaftarkan sidang
                                        <b>{{ $data->r_pkl->r_mahasiswa->nama }}</b>
                                    </p>

                                    <form id="status_admin{{ $data->id_mhs_pkl }}"
                                        action="{{ route('daftar_sidang_kaprodi.update', ['id' => $data->id_mhs_pkl]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="dosen_pembimbing" value="{{ $data->dosen_pembimbing }}">

                                        {{-- Dosen Penguji --}}
                                        <div class="form-group">
                                            <label for="dosen_penguji{{ $data->id_mhs_pkl }}">Pilih Dosen Penguji</label>
                                            <select id="dosen_penguji{{ $data->id_mhs_pkl }}" name="dosen_penguji"
                                                class="form-select"  required>
                                                <option value="" disabled>Pilih Dosen Penguji</option>
                                                @foreach ($dosen_penguji as $dosen)
                                                    @if ($data->r_dosen_pembimbing->id_dosen != $dosen->id_dosen)
                                                        <option value="{{ $dosen->id_dosen }}"
                                                            {{ old('dosen_penguji', $data->dosen_penguji) == $dosen->id_dosen ? 'selected' : '' }}>
                                                            {{ $dosen->nama_dosen }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Tanggal Sidang --}}
                                        <div class="form-group">
                                            <label for="tgl_sidang{{ $data->id_mhs_pkl }}">Tgl Sidang</label>
                                            <input type="date" class="form-control"
                                                id="tgl_sidang{{ $data->id_mhs_pkl }}" name="tgl_sidang"
                                                value="{{ old('tgl_sidang', $data->tgl_sidang) }}" required>
                                        </div>

                                        {{-- Ruang Sidang --}}
                                        <div class="form-group">
                                            <label for="ruang_sidang{{ $data->id_mhs_pkl }}">Pilih Ruang Sidang</label>
                                            <select id="ruang_sidang{{ $data->id_mhs_pkl }}" name="ruang_sidang"
                                                class="form-select" required>
                                                <option value="" disabled>Pilih Ruang Sidang</option>
                                                @foreach ($data_ruangan as $ruang)
                                                    <option value="{{ $ruang->id_ruang }}"
                                                        {{ old('ruang_sidang', $data->ruang_sidang) == $ruang->id_ruang ? 'selected' : '' }}>
                                                        {{ $ruang->kode_ruang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Jam Sidang --}}
                                        <div class="form-group">
                                            <label for="jam_sidang{{ $data->id_mhs_pkl }}">Pilih Jam Sidang</label>
                                            <select id="jam_sidang{{ $data->id_mhs_pkl }}" name="jam_sidang"
                                                class="form-select" required>
                                                <option value="" disabled>Pilih Jam Sidang</option>
                                                @foreach ($jam_sidang as $jam)
                                                    <option value="{{ $jam->id_sesi }}"
                                                        {{ old('jam_sidang', $data->jam_sidang) == $jam->id_sesi ? 'selected' : '' }}>
                                                        {{ $jam->jam }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary">Ya, Daftar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>




                    {{-- Modal Nilai PKL --}}
                    <div class="modal fade" id="nilai{{ $data->id_mhs_pkl }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Nilai PKL ->
                                        {{ $data->r_pkl->r_mahasiswa->nama }}
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form id="nilai{{ $data->id_mhs_pkl }}">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">No</th>
                                                    <th style="width: 40%;">Jabatan</th>
                                                    <th style="width: 20%;">Nama</th>
                                                    <th style="width: 10%;">Nilai</th>
                                                    <th style="width: 10%;">Bobot(%)</th>
                                                    <th style="width: 20%;">Total Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td style="width:80px; word-break: break-all; white-space: normal;">
                                                        Pembimbing Program Studi</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal;">
                                                        {{ $data->r_dosen_pembimbing->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_bimbingan->nilai_bimbingan ?? '' }}
                                                    </td>
                                                    <td>35</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Pembimbing dari Industri</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        {{ $data->pembimbing_pkl ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->nilai_pembimbing_industri ?? '' }}
                                                    </td>
                                                    <td>30</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-start"><strong>PENGUJI</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Penguji 1</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        {{ $data->r_dosen_pembimbing->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_pembimbing->nilai_pkl ?? '' }}
                                                    </td>
                                                    <td rowspan="2">35</td>
                                                    <td rowspan="2"></td>

                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Penguji 2</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        {{ $data->r_dosen_penguji->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_penguji->nilai_pkl ?? '' }}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-start"><strong>Total Nilai</strong>
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ number_format($data->nilai_mahasiswa, 2) }}
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

        $(document).ready(function() {
            $(document).on('change', '[id^="dosen_penguji"]', function() {
                let penguji = $(this).val();
                let modalId = $(this).attr('id').split('dosen_penguji')[1];

                if (!penguji) {
                    alert('Penguji harus dipilih.');
                    return;
                }

                $('#tgl_sidang' + modalId).val('').prop('disabled', false);
                $('#ruang_sidang' + modalId).prop('disabled', true).empty();
                $('#jam_sidang' + modalId).prop('disabled', true).empty();
            });

            $(document).on('change', '[id^="tgl_sidang"]', function() {
                let tanggal = $(this).val();
                let modalId = $(this).attr('id').split('tgl_sidang')[1];
                let penguji = $('#dosen_penguji' + modalId).val();
                let pembimbing = $('input[name="dosen_pembimbing"]').val();

                if (!tanggal || !penguji) {
                    alert('Harap pilih penguji dan tanggal terlebih dahulu.');
                    return;
                }

                $.ajax({
                    url: '/get-available-rooms-pkl',
                    type: 'GET',
                    data: {
                        tanggal: tanggal,
                        penguji: penguji,
                        pembimbing: pembimbing
                    },
                    beforeSend: () => $('#loader').show(),
                    success: (data) => {
                        let $ruanganDropdown = $('#ruang_sidang' + modalId).prop('disabled',
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
                        $('#ruang_sidang' + modalId).prop('disabled', true).empty();
                    },
                    complete: () => $('#loader').hide(),
                });
            });

            $(document).on('change', '[id^="ruang_sidang"]', function() {
                let modalId = $(this).attr('id').split('ruang_sidang')[1];
                let tanggal = $('#tgl_sidang' + modalId).val();
                let idRuangan = $(this).val();

                if (!tanggal || !idRuangan) {
                    alert('Harap pilih tanggal dan ruangan terlebih dahulu.');
                    return;
                }

                $.ajax({
                    url: '/get-available-sessions-pkl',
                    type: 'GET',
                    data: {
                        tanggal: tanggal,
                        id_ruang: idRuangan
                    },
                    beforeSend: () => $('#loader').show(),
                    success: (data) => {
                        let $sesiDropdown = $('#jam_sidang' + modalId).prop('disabled', false)
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
                        $('#jam_sidang' + modalId).prop('disabled', true).empty();
                    },
                    complete: () => $('#loader').hide(),
                });
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            function hitungTotalNilai(container) {
                let totalNilai = 0;
                let nilaiPenguji1 = 0;
                let nilaiPenguji2 = 0;
                let bobotPenguji = 0.35;
                let totalNilaiPenguji = 0;

                const rows = container.querySelectorAll("tbody tr");

                rows.forEach((row, index) => {
                    const nilaiCell = row.querySelector("td:nth-child(4)");
                    const bobotCell = row.querySelector("td:nth-child(5)");
                    const totalCell = row.querySelector("td:nth-child(6)");

                    if (nilaiCell) {
                        const nilai = parseFloat(nilaiCell.textContent) || 0;

                        if (index === 3) {
                            nilaiPenguji1 = nilai;
                        } else if (index === 4) {
                            nilaiPenguji2 = nilai;


                            const rataRataPenguji = (nilaiPenguji1 + nilaiPenguji2) / 2;
                            totalNilaiPenguji = rataRataPenguji * bobotPenguji;


                            const penguji1TotalCell = rows[3].querySelector("td:nth-child(6)");
                            if (penguji1TotalCell) {
                                penguji1TotalCell.textContent = totalNilaiPenguji.toFixed(2);
                            }
                        } else if (index !== 2) {
                            const bobot = parseFloat(bobotCell?.textContent) ||
                                0;
                            const nilaiPersen = (nilai * bobot) / 100;


                            if (totalCell) {
                                totalCell.textContent = nilaiPersen.toFixed(2);
                            }

                            totalNilai += nilaiPersen;
                        }
                    }
                });


                const totalNilaiCell = container.querySelector("tfoot .total-nilai");
                if (totalNilaiCell) {
                    totalNilaiCell.textContent = totalNilai.toFixed(2);
                }
            }

            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('show.bs.modal', function() {
                    const table = modal.querySelector("table");
                    if (table) {
                        hitungTotalNilai(table);
                    }
                });
            });
        });
    </script>
@endsection
