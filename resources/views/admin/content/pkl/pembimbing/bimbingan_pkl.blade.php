    @extends('admin.admin_master')

    @section('admin')
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Log Book</h4>


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
                                <th>Tempat Magang</th>
                                <th>Nilai</th>
                                <th>Log Book</th>
                                <th>Bimbingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($data_dosen_bimbingan_pkl as $data)
                                <tr class="table-light">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $data->r_usulan_pkl->r_mahasiswa->nama }}</td>
                                    <td>{{ $data->r_usulan_pkl->r_perusahaan->nama_perusahaan }}</td>
                                    <td>{{ $data->r_nilai_bimbingan ? $data->r_nilai_bimbingan->nilai_bimbingan : 0 }}</td>
                                    <td>
                                        @if (isset($data->r_nilai_bimbingan) && !is_null($data->r_nilai_bimbingan->nilai_bimbingan))
                                            <a data-bs-toggle="modal" data-bs-target="#Editnilai{{ $data->id_mhs_pkl }}"
                                                class="btn btn-success">
                                                <span class="bi bi-pencil-square"></span> Edit
                                            @else
                                                <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_mhs_pkl }}"
                                                    class="btn btn-primary">
                                                    <span class="bi bi-pencil-square"></span>Nilai
                                                </a>
                                        @endif


                                    </td>
                                    <td>
                                        @if ($data->r_bimbingan->count() > 0)
                                            <a href="{{ route('dosen_bimbingan_pkl.detail', ['id' => $data->id_mhs_pkl]) }}"
                                                class="btn btn-primary">
                                                <span class="bi bi-pencil-square"></span> Log Book
                                            </a>
                                        @else
                                            <button class="btn btn-dark" style="cursor: not-allowed;">
                                                <span class="bi bi-pencil-square"></span> Log Book
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                        </tbody>
                    </table>


                    {{-- Modal Nilai Bimbingan --}}
                    <div class="modal fade" id="nilai{{ $data->id_mhs_pkl }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Nilai Bimbingan Pkl ->
                                        {{ $data->r_usulan_pkl->r_mahasiswa->nama }}
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form id="nilai_dosen_pembimbing{{ $data->id_mhs_pkl }}"
                                        action="{{ route('nilai_dosen_bimbingan_pkl.post', ['id' => $data->id_mhs_pkl]) }}"
                                        method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="id_nilai_bimbingan_pkl"
                                                name="id_nilai_bimbingan_pkl" value="{{ $nextNumber }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" name="mhs_pkl_id" value="{{ $data->id_mhs_pkl }}">
                                            @error('mhs_pkl_id')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="keaktifan">Keaktifan Bimbingan</label>
                                            <input type="number" class="form-control keaktifan" name="keaktifan"
                                                placeholder="Keaktifan Bimbingan"
                                                value="{{ $data->r_nilai_bimbingan->keaktifan ?? '' }}" required
                                                oninput="hitungTotalNilai(this)">
                                        </div>

                                        <div class="form-group">
                                            <label for="komunikatif">Komunikatif</label>
                                            <input type="number" class="form-control komunikatif" name="komunikatif"
                                                placeholder="Komunikatif"
                                                value="{{ $data->r_nilai_bimbingan->komunikatif ?? '' }}" required
                                                oninput="hitungTotalNilai(this)">
                                        </div>

                                        <div class="form-group">
                                            <label for="problem_solving">Problem Solving</label>
                                            <input type="number" class="form-control problem_solving"
                                                name="problem_solving" placeholder="Problem Solving"
                                                value="{{ $data->r_nilai_bimbingan->problem_solving ?? '' }}" required
                                                oninput="hitungTotalNilai(this)">
                                        </div>

                                        <div class="form-group">
                                            <label for="nilai_bimbingan">Total Nilai</label>
                                            <input type="text" class="form-control nilai_bimbingan"
                                                name="nilai_bimbingan" placeholder="Total Nilai"
                                                value="{{ $data->r_nilai_bimbingan->nilai_bimbingan ?? '' }}" readonly
                                                style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                        </div>


                                        <div class="modal-footer justify-content-between">
                                            <button type="submit" class="btn btn-primary">Konfrmasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Modal Edit Nilai Bimbingan --}}
                    <div class="modal fade" id="Editnilai{{ $data->id_mhs_pkl }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Nilai Bimbingan Pkl ->
                                        {{ $data->r_usulan_pkl->r_mahasiswa->nama }}
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form id="nilai_dosen_pembimbing{{ $data->id_mhs_pkl }}"
                                        action="{{ route('nilai_dosen_bimbingan_pkl.post', ['id' => $data->id_mhs_pkl]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="id_nilai_bimbingan_pkl"
                                                name="id_nilai_bimbingan_pkl"
                                                value="{{ isset($data->r_nilai_bimbingan) ? $data->r_nilai_bimbingan->id_nilai_bimbingan_pkl : '' }}"
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
                                                    <th style="width: 20%;">Materi Penilaian</th>
                                                    <th style="width: 30%;">bobot(%)</th>
                                                    <th style="width: 30%;">skor</th>
                                                    <th style="width: 50%;">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td style="width:80px; word-break: break-all; white-space: normal;">
                                                        Keaktifan Bimbingan</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal;">
                                                        30</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control keaktifan"
                                                            name="keaktifan" placeholder="Keaktifan Bimbingan"
                                                            value="{{ $data->r_nilai_bimbingan->keaktifan ?? 0 }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-wrap: break-word; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai (%)" value=""
                                                            readonly
                                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Komunikatif</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        30</td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control komunikatif"
                                                            name="komunikatif" placeholder="Komunikatif"
                                                            value="{{ $data->r_nilai_bimbingan->komunikatif ?? 0 }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai (%)" value=""
                                                            readonly
                                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td style="width: 80px; word-break: break-all; white-space: normal;">
                                                        Problem Solving</td>
                                                    <td style="width: 20px; word-wrap: break-word; white-space: normal; ">
                                                        40
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="number" class="form-control problem_solving"
                                                            name="problem_solving" placeholder="Problem Solving"
                                                            value="{{ $data->r_nilai_bimbingan->problem_solving ?? 0 }}"
                                                            required oninput="hitungTotalNilai(this)">
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_persen"
                                                            name="nilai_persen" placeholder="Nilai" value=""
                                                            readonly
                                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="4" class="text-start"><strong>Total Nilai</strong>
                                                    </td>
                                                    <td style="width: 50px; word-break: break-all; white-space: normal;">
                                                        <input type="text" class="form-control nilai_bimbingan"
                                                            name="nilai_bimbingan" placeholder="Total Nilai"
                                                            value="{{ $data->r_nilai_bimbingan->nilai_bimbingan ?? '' }}"
                                                            readonly
                                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                                    </td>
                                                </tr>
                                        </table>


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


            // Fungsi untuk menghitung total nilai
            function hitungTotalNilai(element) {
                let container = element.closest("table");

                // Ambil nilai skor dari setiap input
                let keaktifan = parseFloat(container.querySelector(".keaktifan").value) || 0;
                let komunikatif = parseFloat(container.querySelector(".komunikatif").value) || 0;
                let problemSolving = parseFloat(container.querySelector(".problem_solving").value) || 0;

                // Bobot
                const bobotKeaktifan = 30;
                const bobotKomunikatif = 30;
                const bobotProblemSolving = 40;

                // Hitung total nilai
                let totalNilai = (keaktifan * 0.3) + (komunikatif * 0.3) + (problemSolving * 0.4);

                // Tampilkan total nilai
                let totalInput = container.querySelector(".nilai_bimbingan");
                if (totalInput) {
                    totalInput.value = totalNilai.toFixed(2);
                }

                // Hitung persentase masing-masing
                let nilaiKeaktifan = (keaktifan * bobotKeaktifan / 100).toFixed(2);
                let nilaiKomunikatif = (komunikatif * bobotKomunikatif / 100).toFixed(2);
                let nilaiProblemSolving = (problemSolving * bobotProblemSolving / 100).toFixed(2);

                // Tampilkan persentase di kolom masing-masing
                let rows = container.querySelectorAll("tr");
                rows.forEach(row => {
                    if (row.querySelector(".keaktifan")) {
                        row.querySelector(".nilai_persen").value = nilaiKeaktifan;
                    } else if (row.querySelector(".komunikatif")) {
                        row.querySelector(".nilai_persen").value = nilaiKomunikatif;
                    } else if (row.querySelector(".problem_solving")) {
                        row.querySelector(".nilai_persen").value = nilaiProblemSolving;
                    }
                });
            }

            // Fungsi untuk menghitung nilai saat halaman pertama kali dimuat
            function hitungSaatLoad() {
                document.querySelectorAll(".keaktifan, .komunikatif, .problem_solving").forEach(input => {
                    // Simulasikan pemanggilan fungsi hitungTotalNilai untuk setiap input
                    hitungTotalNilai(input);
                });
            }

            // Panggil fungsi hitungSaatLoad saat halaman selesai dimuat
            window.addEventListener("load", hitungSaatLoad);
        </script>
    @endsection
