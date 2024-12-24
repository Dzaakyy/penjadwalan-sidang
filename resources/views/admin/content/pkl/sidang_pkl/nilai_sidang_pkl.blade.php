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
                                        @if ($isPenguji)
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
                                        @elseif ($isPembimbing)
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
                            </tbody>
                        </table>


                                {{-- Modal Nilai Sidang --}}
                                <div class="modal fade" id="nilai{{ $data->id_mhs_pkl }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
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
                                                        <input type="hidden" class="form-control" id="id_nilai_pkl"
                                                            name="id_nilai_pkl" value="{{ $nextNumber }}" readonly>
                                                    </div>

                                                    <div class="form-group">
                                                        <input type="hidden" name="mhs_pkl_id"
                                                            value="{{ $data->id_mhs_pkl }}">
                                                        @error('mhs_pkl_id')
                                                            <small>{{ $message }}</small>
                                                        @enderror
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="bahasa">Bahasa dan Tata Tulis Laporan
                                                                    (15%)
                                                                </label>
                                                                <input type="number" class="form-control bahasa"
                                                                    name="bahasa" placeholder="Bahasa"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->bahasa ?? '' : ($isPenguji ? $data->r_nilai_penguji->bahasa ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="analisis">Analisis Masalah (15%)</label>
                                                                <input type="number" class="form-control analisis"
                                                                    name="analisis" placeholder="Analisis"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->analisis ?? '' : ($isPenguji ? $data->r_nilai_penguji->analisis ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="sikap">Sikap (15%)</label>
                                                                <input type="number" class="form-control sikap"
                                                                    name="sikap" placeholder="Sikap"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->sikap ?? '' : ($isPenguji ? $data->r_nilai_penguji->sikap ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="komunikasi">Komunikasi (15%)</label>
                                                                <input type="number" class="form-control komunikasi"
                                                                    name="komunikasi" placeholder="Komunikasi"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->komunikasi ?? '' : ($isPenguji ? $data->r_nilai_penguji->komunikasi ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="penyajian">Sistematika Penyajian (15%) </label>
                                                                <input type="number" class="form-control penyajian"
                                                                    name="penyajian" placeholder="Penyajian"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->penyajian ?? '' : ($isPenguji ? $data->r_nilai_penguji->penyajian ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="penguasaan">Penguasaan Materi (25%)</label>
                                                                <input type="number" class="form-control penguasaan"
                                                                    name="penguasaan" placeholder="Penguasaan"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->penguasaan ?? '' : ($isPenguji ? $data->r_nilai_penguji->penguasaan ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="nilai_pkl">Total Nilai</label>
                                                        <input type="text" class="form-control nilai_pkl"
                                                            name="nilai_pkl" placeholder="Total Nilai"
                                                            value="{{ $isPembimbing ? $data->r_nilai_pembimbing->nilai_pkl ?? '' : ($isPenguji ? $data->r_nilai_penguji->nilai_pkl ?? '' : '') }}"
                                                            readonly
                                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                                    </div>

                                                    <input type="hidden" name="status"
                                                        value="{{ $data->status ?? ($isPembimbing ? '0' : ($isPenguji ? '1' : '')) }}">

                                                    <div class="modal-footer justify-content-between">
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
                                    <div class="modal-dialog modal-dialog-centered">
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
                                                            value="{{ $isPembimbing ? (isset($data->r_nilai_pembimbing) ? $data->r_nilai_pembimbing->id_nilai_pkl : '') : ($isPenguji ? (isset($data->r_nilai_penguji) ? $data->r_nilai_penguji->id_nilai_pkl : '') : '') }}"
                                                            readonly>
                                                    </div>

                                                    <div class="form-group">
                                                        <input type="hidden" name="mhs_pkl_id"
                                                            value="{{ old('mhs_pkl_id', $data->id_mhs_pkl) }}">
                                                        @error('mhs_pkl_id')
                                                            <small>{{ $message }}</small>
                                                        @enderror
                                                    </div>


                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="bahasa">Bahasa dan Tata Tulis Laporan
                                                                    (15%)
                                                                </label>
                                                                <input type="number" class="form-control bahasa"
                                                                    name="bahasa" placeholder="Bahasa"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->bahasa ?? '' : ($isPenguji ? $data->r_nilai_penguji->bahasa ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="analisis">Analisis Masalah (15%)</label>
                                                                <input type="number" class="form-control analisis"
                                                                    name="analisis" placeholder="Analisis"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->analisis ?? '' : ($isPenguji ? $data->r_nilai_penguji->analisis ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="sikap">Sikap (15%)</label>
                                                                <input type="number" class="form-control sikap"
                                                                    name="sikap" placeholder="Sikap"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->sikap ?? '' : ($isPenguji ? $data->r_nilai_penguji->sikap ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="komunikasi">Komunikasi (15%)</label>
                                                                <input type="number" class="form-control komunikasi"
                                                                    name="komunikasi" placeholder="Komunikasi"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->komunikasi ?? '' : ($isPenguji ? $data->r_nilai_penguji->komunikasi ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="penyajian">Sistematika Penyajian (15%) </label>
                                                                <input type="number" class="form-control penyajian"
                                                                    name="penyajian" placeholder="Penyajian"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->penyajian ?? '' : ($isPenguji ? $data->r_nilai_penguji->penyajian ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="penguasaan">Penguasaan Materi (25%)</label>
                                                                <input type="number" class="form-control penguasaan"
                                                                    name="penguasaan" placeholder="Penguasaan"
                                                                    value="{{ $isPembimbing ? $data->r_nilai_pembimbing->penguasaan ?? '' : ($isPenguji ? $data->r_nilai_penguji->penguasaan ?? '' : '') }}"
                                                                    required oninput="hitungTotalNilai(this)">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="nilai_pkl">Total Nilai</label>
                                                        <input type="text" class="form-control nilai_pkl"
                                                            name="nilai_pkl" placeholder="Total Nilai"
                                                            value="{{ $isPembimbing ? $data->r_nilai_pembimbing->nilai_pkl ?? '' : ($isPenguji ? $data->r_nilai_penguji->nilai_pkl ?? '' : '') }}"
                                                            readonly
                                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                                    </div>

                                                    <input type="hidden" name="status"
                                                        value="{{ $data->status ?? ($isPembimbing ? '0' : ($isPenguji ? '1' : '')) }}">

                                                    <div class="modal-footer justify-content-between">
                                                        <button type="submit" class="btn btn-primary">Konfrmasi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
            let container = element.closest(".row").parentNode;


            let bahasa = parseFloat(container.querySelector(".bahasa").value) || 0;
            let analisis = parseFloat(container.querySelector(".analisis").value) || 0;
            let sikap = parseFloat(container.querySelector(".sikap").value) || 0;
            let komunikasi = parseFloat(container.querySelector(".komunikasi").value) || 0;
            let penyajian = parseFloat(container.querySelector(".penyajian").value) || 0;
            let penguasaan = parseFloat(container.querySelector(".penguasaan").value) || 0;


            let totalNilai = (bahasa * 0.15) + (analisis * 0.15) + (sikap * 0.15) + (komunikasi * 0.15) + (penyajian *
                0.15) + (penguasaan * 0.25);


            let totalInput = container.querySelector(".nilai_pkl");
            if (totalInput) {
                totalInput.value = totalNilai.toFixed(2);
            }
        }
    </script>
@endsection
