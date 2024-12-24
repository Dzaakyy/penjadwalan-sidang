@extends('admin.admin_master')
@section('admin')
    <div class="card">
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
                                                <a data-bs-toggle="modal" data-bs-target="#edit{{ $data->id_sempro }}"
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

                                {{-- Modal Daftar Sidang --}}
                                <div class="modal fade" id="daftar{{ $data->id_sempro }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title fs-5" id="staticBackdropLabel">Daftar Sidang</h4>
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


                                                    <div class="form-group">
                                                        <label for="penguji">Pilih Dosen Penguji</label>
                                                        <select id="penguji" name="penguji" class="form-select" required>
                                                            <option value="" disabled selected>Pilih Dosen Penguji</option>
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

                                                    <div class="form-group">
                                                        <label for="tanggal_sempro">Tgl Sidang</label>
                                                        <input type="date" class="form-control" id="tanggal_sempro"
                                                            name="tanggal_sempro" placeholder="Tanggal Sempro" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="ruangan_id">Pilih Ruang Sidang</label>
                                                        <select id="ruangan_id" name="ruangan_id" class="form-select" required>
                                                            <option value="" disabled selected>Pilih Ruang Sidang</option>
                                                            @foreach ($data_ruangan as $ruang)
                                                                <option value="{{ $ruang->id_ruang }}" {{ old('ruangan_id') == $ruang->id_ruang ? 'selected' : '' }}>
                                                                    {{ $ruang->kode_ruang }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="sesi_id">Pilih Jam Sidang</label>
                                                        <select id="sesi_id" name="sesi_id" class="form-select" required>
                                                            <option value="" disabled selected>Pilih Jam Sidang</option>
                                                            @foreach ($jam_sidang as $jam)
                                                                <option value="{{ $jam->id_sesi }}" {{ old('sesi_id') == $jam->id_sesi ? 'selected' : '' }}>
                                                                    {{ $jam->jam }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <input type="hidden" name="status" value="1">

                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="submit" class="btn btn-primary">Ya, Daftar</button>
                                                </form>
                                            </div>
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

                                                        <div class="form-group">
                                                            <label for="penguji">Pilih Dosen Penguji</label>
                                                            <select name="penguji" class="form-select" required>
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

                                                        <div class="form-group">
                                                            <label for="tanggal_sempro">Tgl Sidang</label>
                                                            <input type="date" class="form-control" id="tanggal_sempro"
                                                                name="tanggal_sempro" value="{{ $data->tanggal_sempro }}"
                                                                placeholder="Tanggal Sempro" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="ruangan_id">Pilih Ruang Sidang</label>
                                                            <select name="ruangan_id" class="form-select" required>
                                                                <option>Pilih Ruang Sidang</option>
                                                                @foreach ($data_ruangan as $ruang)
                                                                    <option value="{{ $ruang->id_ruang }}"
                                                                        {{ old('ruangan_id', $data->ruangan_id) == $ruang->id_ruang ? 'selected' : '' }}>
                                                                        {{ $ruang->kode_ruang }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="sesi_id">Pilih Jam Sidang</label>
                                                            <select name="sesi_id" class="form-select" required>
                                                                <option>Pilih Jam Sidang</option>
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

                                                    <div class="form-group">
                                                        <label for="">Nilai Pembimbing 1 -
                                                            {{ $data->r_pembimbing_satu->nama_dosen ?? '' }}</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $data->r_nilai_pembimbing_satu->nilai_sempro ?? '' }}"
                                                            readonly
                                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">Nilai Pembimbing 2-
                                                            {{ $data->r_pembimbing_dua->nama_dosen ?? '' }}</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $data->r_nilai_pembimbing_dua->nilai_sempro ?? '' }}"
                                                            readonly
                                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="">Nilai Penguji-
                                                            {{ $data->r_penguji ? $data->r_penguji->nama_dosen : '-' }}</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $data->r_nilai_penguji->nilai_sempro ?? '' }}"
                                                            readonly
                                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="nilai_bimbingan">Total Nilai</label>
                                                        <input type="text" class="form-control nilai_bimbingan"
                                                            name="nilai_bimbingan" placeholder="Total Nilai"
                                                            value="{{ $data->nilai_mahasiswa ?? '' }}" readonly
                                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                                    </div>
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

        document.addEventListener("DOMContentLoaded", () => {
            const penguji = document.getElementById("penguji");
            const tanggalSempro = document.getElementById("tanggal_sempro");
            const ruanganId = document.getElementById("ruangan_id");
            const sesiId = document.getElementById("sesi_id");

            let existingSchedules = [];

            async function fetchSchedules() {
                try {
                    const response = await fetch('/api/get-existing-schedules');
                    existingSchedules = await response.json();
                } catch (error) {
                    console.error("Gagal mengambil data jadwal:", error);
                }
            }

            function filterRuangan() {
                const selectedTanggal = tanggalSempro.value;

                Array.from(ruanganId.options).forEach(option => {
                    const ruanganId = option.value;

                    const conflict = existingSchedules.some(schedule => {
                        return (
                            schedule.ruanganId == ruanganId &&
                            schedule.tanggal === selectedTanggal
                        );
                    });

                    option.style.display = conflict ? "none" : "";
                });
            }

            function filterSesi() {
                const selectedTanggal = tanggalSempro.value;
                const selectedRuanganId = ruanganId.value;

                Array.from(sesiId.options).forEach(option => {
                    const sesiId = option.value;

                    const conflict = existingSchedules.some(schedule => {
                        return (
                            schedule.sesiId == sesiId &&
                            schedule.tanggal === selectedTanggal &&
                            schedule.ruanganId == selectedRuanganId
                        );
                    });

                    option.style.display = conflict ? "none" : "";
                });
            }

            tanggalSempro.addEventListener("change", () => {
                filterRuangan();
                filterSesi();
            });

            ruanganId.addEventListener("change", filterSesi);

            fetchSchedules(); // Muat data saat halaman dimuat
        });
    </script>
@endsection
