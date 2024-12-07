@extends('admin.admin_master')
@section('admin')
    <div class="card">
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
                                    <td>{{ $data->tgl_sidang ? \Carbon\Carbon::parse($data->tgl_sidang)->locale('id')->format('d-m-Y') : '-' }}</td>
                                    <td style="width: 10%;">
                                        <div class="d-flex">
                                            @if (is_null($data->dosen_penguji) || is_null($data->ruang_sidang) || is_null($data->tgl_sidang))
                                                <a data-bs-toggle="modal" data-bs-target="#daftar{{ $data->id_mhs_pkl }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Daftar
                                                </a>
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#edit{{ $data->id_mhs_pkl }}"
                                                    class="btn btn-success mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Edit
                                                </a>
                                                <a href="{{ route('cetak_surat_tugas_pkl.download', ['id' => $data->id_mhs_pkl]) }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center"
                                                    id="downloadButton"
                                                    target="_blank">
                                                     <i class="bi bi-pencil-square"></i>Cetak
                                                 </a>

                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Daftar Sidang --}}
                                <div class="modal fade" id="daftar{{ $data->id_mhs_pkl }}" data-bs-backdrop="static"
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
                                                    <b>{{ $data->r_pkl->r_mahasiswa->nama }}</b>
                                                </p>

                                                <form id="status_admin{{ $data->id_mhs_pkl }}"
                                                    action="{{ route('daftar_sidang_kaprodi.update', ['id' => $data->id_mhs_pkl]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="dosen_penguji">Pilih Dosen Penguji</label>
                                                        <select name="dosen_penguji" class="form-select" required>
                                                            <option value="" disabled selected>Pilih Dosen Penguji
                                                            </option>
                                                            @foreach ($dosen_penguji as $dosen)
                                                                @if ($data->r_dosen_pembimbing->id_dosen != $dosen->id_dosen)
                                                                    <option value="{{ $dosen->id_dosen }}"
                                                                        {{ old('dosen_penguji') == $dosen->id_dosen ? 'selected' : '' }}>
                                                                        {{ $dosen->nama_dosen }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="tgl_sidang">Tgl Sidang</label>
                                                        <input type="date" class="form-control" id="tgl_sidang"
                                                            name="tgl_sidang" placeholder="Tanggal sidang" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="ruang_sidang">Pilih Ruang Sidang</label>
                                                        <select name="ruang_sidang" class="form-select" required>
                                                            <option value="" disabled selected>Pilih Ruang Sidang
                                                            </option>
                                                            @foreach ($data_ruangan as $ruang)
                                                                <option value="{{ $ruang->id_ruang }}"
                                                                    {{ old('ruang_sidang') == $ruang->id_ruang ? 'selected' : '' }}>
                                                                    {{ $ruang->kode_ruang }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="jam_sidang">Pilih Jam Sidang</label>
                                                        <select name="jam_sidang" class="form-select" required>
                                                            <option value="" disabled selected>Pilih Jam Sidang
                                                            </option>
                                                            @foreach ($jam_sidang as $jam)
                                                                <option value="{{ $jam->id_sesi }}"
                                                                    {{ old('jam_sidang') == $jam->id_sesi ? 'selected' : '' }}>
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

                                {{-- Modal Edit Daftar Sidang --}}
                                <div class="modal fade" id="edit{{ $data->id_mhs_pkl }}" data-bs-backdrop="static"
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
                                                    <b>{{ $data->r_pkl->r_mahasiswa->nama }}</b>
                                                </p>

                                                <form id="status_admin{{ $data->id_mhs_pkl }}"
                                                    action="{{ route('daftar_sidang_kaprodi.update', ['id' => $data->id_mhs_pkl]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="dosen_penguji">Pilih Dosen Penguji</label>
                                                        <select name="dosen_penguji" class="form-select" required>
                                                            <option>Pilih Dosen Penguji</option>
                                                            @foreach ($dosen_penguji as $dosenItem)
                                                                @if ($data->r_dosen_pembimbing->id_dosen != $dosenItem->id_dosen)
                                                                    <option value="{{ $dosenItem->id_dosen }}"
                                                                        {{ old('dosen_penguji', $data->dosen_penguji) == $dosenItem->id_dosen ? 'selected' : '' }}>
                                                                        {{ $dosenItem->nama_dosen }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="tgl_sidang">Tgl Sidang</label>
                                                        <input type="date" class="form-control" id="tgl_sidang"
                                                            name="tgl_sidang" placeholder="Tanggal sidang"
                                                            value="{{ $data->tgl_sidang }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="ruang_sidang">Pilih Ruang Sidang</label>
                                                        <select name="ruang_sidang" class="form-select" required>
                                                            <option>Pilih Ruang Sidang</option>
                                                            @foreach ($data_ruangan as $ruang)
                                                                <option value="{{ $ruang->id_ruang }}"
                                                                    {{ old('ruang_sidang', $data->ruang_sidang) == $ruang->id_ruang ? 'selected' : '' }}>
                                                                    {{ $ruang->kode_ruang }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="jam_sidang">Pilih Jam Sidang</label>
                                                        <select name="jam_sidang" class="form-select" required>
                                                            <option>Pilih Jam Sidang</option>
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
                                                <button type="submit" class="btn btn-primary">Ya, Edit</button>
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
@endsection
