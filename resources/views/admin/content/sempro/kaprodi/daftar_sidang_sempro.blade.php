@extends('admin.admin_master')
@section('admin')
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Daftar Sidang Sempro</h4>
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
                            <th>Pembimbing 1</th>
                            <th>Pembimbing 2</th>
                            <th>Penguji</th>
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
                        @foreach ($data_mahasiswa_sempro as $data)
                            @if ($data->status_judul == 2 && $data->status_berkas == 1)
                                <tr class="table-Light">
                                    <td>{{ $counter++ }}</td>
                                    <td style="max-width: 100px; word-break: break-all; white-space: normal;">
                                        {{ $data->r_mahasiswa->nama }}</td>
                                    <td style="max-width: 150px; word-break: break-all; white-space: normal;">
                                        {{ $data->r_pembimbing_satu->nama_dosen ?? '-' }}</td>
                                    <td style="max-width: 150px; word-break: break-all; white-space: normal;">
                                        {{ $data->r_pembimbing_dua->nama_dosen ?? '-' }}</td>
                                    <td style="max-width: 150px; word-break: break-all; white-space: normal;">
                                        {{ $data->r_penguji->nama_dosen ?? '-' }}</td>
                                    <td>{{ $data->r_ruangan->kode_ruang ?? '-' }}</td>
                                    <td>{{ $data->r_sesi->jam ?? '-' }}</td>
                                    <td>{{ $data->tanggal_sempro? \Carbon\Carbon::parse($data->tanggal_sempro)->locale('id')->format('d-m-Y'): '-' }}
                                    </td>
                                    <td style="width: 10%;">
                                        <div class="d-flex flex-column">
                                            @if (is_null($data->pembimbing_satu) ||
                                                    is_null($data->pembimbing_dua) ||
                                                    is_null($data->penguji) ||
                                                    is_null($data->tanggal_sempro) ||
                                                    is_null($data->ruangan_id) ||
                                                    is_null($data->sesi_id))
                                                <a data-bs-toggle="modal" data-bs-target="#daftar{{ $data->id_sempro }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Daftar
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#daftar{{ $data->id_sempro }}"
                                                    class="btn btn-success mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Edit
                                                </a>
                                                <a href="{{ route('cetak_surat_tugas_sempro.download', ['id' => $data->id_sempro]) }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center" id="downloadButton"
                                                    target="_blank">
                                                    <i class="bi bi-pencil-square"></i>Cetak
                                                </a>
                                                @if (!is_null($data->nilai_mahasiswa))
                                                    <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_sempro }}"
                                                        class="btn btn-dark mb-2 me-2 align-items-center">
                                                        <span class="bi bi-pencil-square"></span>Nilai
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>




                @foreach ($data_mahasiswa_sempro as $data)
                    {{-- Modal Daftar Sidang --}}
                    <div class="modal fade" id="daftar{{ $data->id_sempro }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Daftar Sidang</h5>
                                    <!-- Close Button (Tombol silang) -->
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <p>Apakah kamu yakin ingin mendaftarkan sidang
                                        <b>{{ $data->r_mahasiswa->nama }}</b>
                                    </p>

                                    <form id="daftar_sidang{{ $data->id_sempro }}"
                                        action="{{ route('daftar_sidang_sempro_kaprodi.update', ['id' => $data->id_sempro]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" id="pembimbing_satu{{ $data->id_sempro }}"
                                            value="{{ $data->r_pembimbing_satu->id_dosen ?? '' }}">
                                        <input type="hidden" id="pembimbing_dua{{ $data->id_sempro }}"
                                            value="{{ $data->r_pembimbing_dua->id_dosen ?? '' }}">



                                        <!-- Dosen Penguji -->
                                        <div class="form-group">
                                            <label for="penguji{{ $data->id_sempro }}">Pilih Dosen
                                                Penguji</label>
                                            <select id="penguji{{ $data->id_sempro }}" name="penguji" class="form-select"
                                                required>
                                                <option value="" disabled selected>Pilih Dosen Penguji
                                                </option>
                                                @foreach ($dosen as $dosenItem)
                                                    @if (!isset($data->r_pembimbing_satu) || $data->r_pembimbing_satu->id_dosen != $dosenItem->id_dosen)
                                                        @if (!isset($data->r_pembimbing_dua) || $data->r_pembimbing_dua->id_dosen != $dosenItem->id_dosen)
                                                            <option value="{{ $dosenItem->id_dosen }}"
                                                                {{ (isset($data->r_penguji) && $data->r_penguji->id_dosen == $dosenItem->id_dosen) || old('penguji') == $dosenItem->id_dosen ? 'selected' : '' }}>
                                                                {{ $dosenItem->nama_dosen }}
                                                            </option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Tanggal Sidang -->
                                        <div class="form-group">
                                            <label for="tanggal_sempro{{ $data->id_sempro }}">Tanggal
                                                Sidang</label>
                                            <input type="date" id="tanggal_sempro{{ $data->id_sempro }}"
                                                name="tanggal_sempro" class="form-control" required
                                                value="{{ old('tanggal_sempro', $data->tanggal_sempro) }}"
                                                {{ !isset($data->penguji) || empty($data->penguji) ? 'disabled' : '' }}>
                                        </div>

                                        <!-- Ruang Sidang -->
                                        <div class="form-group">
                                            <label for="ruangan_id{{ $data->id_sempro }}">Pilih Ruang
                                                Sidang</label>
                                            <select id="ruangan_id{{ $data->id_sempro }}" name="ruangan_id"
                                                class="form-select" required
                                                {{ !isset($data->penguji) || empty($data->penguji) ? 'disabled' : '' }}>
                                                <option value="" disabled selected>Pilih Ruangan</option>
                                                @foreach ($data_ruangan as $ruang)
                                                    <option value="{{ $ruang->id_ruang }}"
                                                        {{ old('ruangan_id', $data->ruangan_id) == $ruang->id_ruang ? 'selected' : '' }}>
                                                        {{ $ruang->kode_ruang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Jam Sidang -->
                                        <div class="form-group">
                                            <label for="sesi_id{{ $data->id_sempro }}">Pilih Jam Sidang</label>
                                            <select id="sesi_id{{ $data->id_sempro }}" name="sesi_id" class="form-select"
                                                required
                                                {{ !isset($data->penguji) || empty($data->penguji) ? 'disabled' : '' }}>
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


                                        <!-- Status -->
                                        <input type="hidden" name="status" value="1">
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary">Ya, Daftar</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>




                    {{-- Modal Edit Daftar Sidang --}}
                    <div class="modal fade" id="edit{{ $data->id_sempro }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Edit Daftar Sidang
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah kamu yakin ingin mengubah daftar sidang
                                        <b>{{ $data->r_mahasiswa->nama }}</b>
                                    </p>

                                    <form id="status_admin{{ $data->id_sempro }}"
                                        action="{{ route('daftar_sidang_sempro_kaprodi.update', ['id' => $data->id_sempro]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')

                                        {{-- Dosen Penguji --}}
                                        <div class="form-group">
                                            <label for="penguji{{ $data->id_sempro }}">Pilih Dosen
                                                Penguji</label>
                                            <select id="penguji{{ $data->id_sempro }}" name="penguji"
                                                class="form-select" required>
                                                <option value="" disabled selected>Pilih Dosen Penguji
                                                </option>
                                                @foreach ($dosen as $dosenItem)
                                                    @if (!isset($data->r_pembimbing_satu) || $data->r_pembimbing_satu->id_dosen != $dosenItem->id_dosen)
                                                        @if (!isset($data->r_pembimbing_dua) || $data->r_pembimbing_dua->id_dosen != $dosenItem->id_dosen)
                                                            <option value="{{ $dosenItem->id_dosen }}"
                                                                {{ (isset($data->r_penguji) && $data->r_penguji->id_dosen == $dosenItem->id_dosen) || old('penguji') == $dosenItem->id_dosen ? 'selected' : '' }}>
                                                                {{ $dosenItem->nama_dosen }}
                                                            </option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Tanggal Sidang --}}
                                        <div class="form-group">
                                            <label for="tanggal_sempro{{ $data->id_sempro }}">Tanggal
                                                Sidang</label>
                                            <input type="date" id="tanggal_sempro{{ $data->id_sempro }}"
                                                name="tanggal_sempro" class="form-control" required
                                                value="{{ old('tanggal_sempro', $data->tanggal_sempro) }}"
                                                {{ empty($dosen) ? 'disabled' : '' }}>
                                        </div>

                                        {{-- Ruang Sidang --}}
                                        <div class="form-group">
                                            <label for="ruangan_id{{ $data->id_sempro }}">Pilih Ruang
                                                Sidang</label>
                                            <select id="ruangan_id{{ $data->id_sempro }}" name="ruangan_id"
                                                class="form-select" {{ empty($dosen) ? 'disabled' : '' }} required>
                                                <option value="" disabled selected>Pilih Ruangan</option>
                                                @foreach ($data_ruangan as $ruang)
                                                    <option value="{{ $ruang->id_ruang }}"
                                                        {{ old('ruangan_id', $data->ruangan_id) == $ruang->id_ruang ? 'selected' : '' }}>
                                                        {{ $ruang->kode_ruang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Jam Sidang --}}
                                        <div class="form-group">
                                            <label for="sesi_id{{ $data->id_sempro }}">Pilih Jam
                                                Sidang</label>
                                            <select id="sesi_id{{ $data->id_sempro }}" name="sesi_id"
                                                class="form-select" {{ empty($dosen) ? 'disabled' : '' }} required>
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
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="submit" class="btn btn-primary">Ya, Edit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Modal Nilai Sempro --}}
                    <div class="modal fade" id="nilai{{ $data->id_sempro }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Nilai Sempro ->
                                        {{ $data->r_mahasiswa->nama }}
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form id="nilai{{ $data->id_sempro }}">


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
                                                        {{ $data->r_nilai_pembimbing_satu->nilai_sempro ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Pembimbing 2</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        {{ $data->r_pembimbing_dua->nama_dosen ?? '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_pembimbing_dua->nilai_sempro ?? '' }}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Penguji</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        {{ $data->r_penguji ? $data->r_penguji->nama_dosen : '' }}</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ $data->r_nilai_penguji->nilai_sempro ?? '' }}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-start"><strong>Total Nilai</strong>
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        {{ number_format($data->nilai_mahasiswa, 2) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-start"><strong>Keterangan : </strong>
                                                        @if ($data->keterangan == '0')
                                                        @elseif ($data->keterangan == '1')
                                                            Lulus
                                                        @elseif ($data->keterangan == '2')
                                                           Tidak Lulus
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
            $(document).on('change', '[id^="penguji"]', function() {
                let penguji = $(this).val();
                let modalId = $(this).attr('id').split('penguji')[1];
                let pembimbingSatu = $('#pembimbing_satu' + modalId).val();
                let pembimbingDua = $('#pembimbing_dua' + modalId).val();

                if (!penguji) {
                    alert('Penguji harus dipilih.');
                    return;
                }

                if (penguji === pembimbingSatu || penguji === pembimbingDua) {
                    alert('Penguji tidak boleh sama dengan Pembimbing 1 atau Pembimbing 2.');
                    $(this).val('');
                    return;
                }

                $('#tanggal_sempro' + modalId).val('').prop('disabled', false);
                $('#ruangan_id' + modalId).prop('disabled', true).empty();
                $('#sesi_id' + modalId).prop('disabled', true).empty();
            });

            $(document).on('change', '[id^="tanggal_sempro"]', function() {
                let tanggal = $(this).val();
                let modalId = $(this).attr('id').split('tanggal_sempro')[1];
                let penguji = $('#penguji' + modalId).val();

                if (!tanggal || !penguji) {
                    alert('Harap pilih tanggal dan penguji terlebih dahulu.');
                    return;
                }

                $.ajax({
                    url: '/get-available-rooms',
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
                        $('#ruangan_id' + modalId).prop('disabled', true).empty();
                    },
                    complete: () => $('#loader').hide(),
                });
            });

            $(document).on('change', '[id^="ruangan_id"]', function() {
                let modalId = $(this).attr('id').split('ruangan_id')[1];
                let tanggal = $('#tanggal_sempro' + modalId).val();
                let idRuangan = $(this).val();

                if (!tanggal || !idRuangan) {
                    alert('Harap pilih tanggal dan ruangan terlebih dahulu.');
                    return;
                }

                $.ajax({
                    url: '/get-available-sessions',
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
