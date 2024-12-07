    @extends('admin.admin_master')
    @section('admin')
        @if (session('success'))
            <div class="alert alert-success" id="delay">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Usulan Mahasiswa PKL</h4>


                <div class="table-responsive">
                    <table class="table table-hover dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="table-info">
                                <th>#</th>
                                <th>Nama Mahasiswa</th>
                                <th>Nama Perusahaan</th>
                                <th>Konfirmasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($data_usulan_pkl as $data)
                                <tr class="table-Light">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $data->r_mahasiswa->nama }}</td>
                                    <td style="max-width: 150px; word-break: break-all; white-space: normal;">
                                        {{ $data->r_perusahaan->nama_perusahaan }}</td>
                                    <td>
                                        @if ($data->konfirmasi == 0)
                                            <span style="color: red; font-weight: bold;">Belum Dikonfirmasi</span>
                                        @else
                                            <span style="color: green; font-weight: bold;">Sudah Dikonfirmasi</span>
                                        @endif
                                    </td>

                                    <td style="width: 10%;">
                                        <div class="d-flex">
                                            @if ($data->konfirmasi == 0)
                                                <a data-bs-toggle="modal"
                                                    data-bs-target="#konfirmasi{{ $data->id_usulan_pkl }}"
                                                    class="btn btn-primary mb-2 align-items-center"><span
                                                        class="bi bi-pencil-square"></span>Konfirmasi</a>
                                            @else
                                                <span>Data sudah dikonfirmasi</span>
                                            @endif

                                            {{-- <a data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $data->id_usulan_pkl}}"
                                                class="btn btn-danger mb-2 align-items-center"><span
                                                    class="bi bi-trash"></span>Hapus</a> --}}
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Konfirmasi hapus data --}}
                                {{-- <div class="modal fade" id="konfirmasi{{ $data->id_usulan_pkl }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Usulan PKL</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah kamu yakin ingin mengkonfirmasi usulan PKL
                                                    <b>{{ $data->r_mahasiswa->nama }}</b>?
                                                </p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <form id="konfirmasi{{ $data->id_usulan_pkl }}"
                                                    action="{{ route('konfirmasi_usulan_pkl.confirm', ['id' => $data->id_usulan_pkl]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="konfirmasi" value="1">
                                                    <button type="submit" class="btn btn-primary">
                                                        <span class="bi bi-pencil-square"></span> Konfirmasi
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="modal fade" id="konfirmasi{{ $data->id_usulan_pkl }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Usulan PKL
                                                </h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah kamu yakin ingin mengkonfirmasi usulan PKL
                                                    <b>{{ $data->r_mahasiswa->nama }}</b>?
                                                </p>
                                                <form id="konfirmasi{{ $data->id_usulan_pkl }}"
                                                    action="{{ route('konfirmasi_usulan_pkl.confirm', ['id' => $data->id_usulan_pkl]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <input type="hidden" id="id_mhs_pkl" name="id_mhs_pkl"
                                                        value="{{ $nextNumber }}" readonly>
                                                    <input type="hidden" name="mahasiswa_id"
                                                        value="{{ $data->r_mahasiswa->id_mahasiswa }}">



                                                    <div class="form-group">
                                                        <label for="dosen_pembimbing">Pilih Dosen Pembimbing</label>
                                                        <select name="dosen_pembimbing" class="form-select" required>
                                                            <option value="" disabled selected>Pilih Dosen Pembimbing
                                                            </option>
                                                            @foreach ($dosen_pembimbing as $dosen)
                                                                <option value="{{ $dosen->id_dosen }}"
                                                                    {{ old('dosen_pembimbing') == $dosen->id_dosen ? 'selected' : '' }}>
                                                                    {{ $dosen->nama_dosen }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <input type="hidden" name="tahun_pkl" value="{{ date('Y') }}">
                                                    <input type="hidden" name="konfirmasi" value="1">

                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">
                                                        <span class="bi bi-pencil-square"></span> Konfirmasi
                                                    </button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-body">
                <h4 class="card-title">Data Mahasiswa Pkl</h4>


                @if ($data_mahasiswa_pkl->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="table-info">
                                    <th>#</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Dosen Pembimbing</th>
                                    <th>Tahun PKL</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach ($data_mahasiswa_pkl as $data)
                                    <tr class="table-Light">
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $data->r_pkl->r_mahasiswa->nama }}</td>
                                        <td style="max-width: 150px; word-break: break-all; white-space: normal;">
                                            {{ $data->r_pkl->r_perusahaan->nama_perusahaan }}</td>
                                        <td>{{ $data->r_dosen_pembimbing->nama_dosen }}</td>
                                        <td>{{ $data->tahun_pkl }}</td>
                                        <td style="width: 10%;">
                                            <div class="d-flex">
                                                <a data-bs-toggle="modal"
                                                    data-bs-target="#editPembimbing{{ $data->id_mhs_pkl }}"
                                                    class="btn btn-primary mb-2 align-items-center"><span
                                                        class="bi bi-pencil-square"></span>Edit</a>
                                            </div>
                                        </td>
                                    </tr>


                                    {{-- Modal Edit Pembimbing --}}
                                    <div class="modal fade" id="editPembimbing{{ $data->id_mhs_pkl }}"
                                        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Ganti Pembimbing
                                                        PKL</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah kamu yakin ingin mengganti Pembimbing PKL
                                                        <b>{{ $data->r_pkl->r_mahasiswa->nama }}</b>?
                                                    </p>
                                                    <form id="konfirmasi{{ $data->id_mhs_pkl }}"
                                                        action="{{ route('konfirmasi_edit_pembimbing.update', ['id' => $data->id_mhs_pkl]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <label for="dosen_pembimbing">Pilih Dosen Pembimbing</label>
                                                            <select name="dosen_pembimbing" class="form-select" required>
                                                                <option>Pilih Dosen Pembimbing</option>
                                                                @forelse ($dosen_pembimbing as $dosenItem)
                                                                    <option value="{{ $dosenItem->id_dosen }}"
                                                                        {{ old('dosen_pembimbing', $data->dosen_pembimbing) == $dosenItem->id_dosen ? 'selected' : '' }}>
                                                                        {{ $dosenItem->nama_dosen }}
                                                                    </option>
                                                                @empty
                                                                    <option disabled>No Dosen available</option>
                                                                @endforelse
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="konfirmasi" value="1">
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="submit" class="btn btn-primary">
                                                                <span class="bi bi-pencil-square"></span> Konfirmasi
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">Tidak ada data mahasiswa PKL untuk ditampilkan.</p>
                @endif
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
