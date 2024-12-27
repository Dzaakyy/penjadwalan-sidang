@extends('admin.admin_master')
@section('admin')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h4 class="card-title mt-4">Data Tugas Akhir</h4>
    </div>

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

    @php
        $mahasiswa_id = Auth::user()->r_mahasiswa->id_mahasiswa;

        $usulanPkl = Auth::user()->r_mahasiswa->usulan_pkl;

        $nilaiMahasiswaNotNull = false;
        if ($usulanPkl) {
            $mahasiswaPkl = \App\Models\MahasiswaPkl::where('mahasiswa_id', $mahasiswa_id)->first();

            if ($mahasiswaPkl && !is_null($mahasiswaPkl->nilai_mahasiswa)) {
                $nilaiMahasiswaNotNull = true;
            }
        }

        $nilaiSemproNotNull = false;
        $semproData = \App\Models\MahasiswaSempro::where('mahasiswa_id', $mahasiswa_id)->first();
        if ($semproData && !is_null($semproData->nilai_mahasiswa)) {
            $nilaiSemproNotNull = true;
        }

        $taTerdaftar = $data_mahasiswa_ta->where('mahasiswa_id', $mahasiswa_id)->isNotEmpty();
    @endphp

    @if ($nilaiMahasiswaNotNull && $nilaiSemproNotNull && !$taTerdaftar)
        <a data-bs-toggle="modal" data-bs-target="#daftar_ta" class="btn btn-primary me-2 mb-3">
            <i class="bi bi-file-earmark-plus"></i> Daftar TA
        </a>
    @endif


    {{-- Modal Daftar TA --}}
    <div class="modal fade" id="daftar_ta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Daftar TA</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Daftar TA
                        <b>{{ $mahasiswa->nama }}</b>
                    </p>

                    <form id="daftar_ta" action="{{ route('daftar_ta.post') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input type="text" class="form-control" id="id_ta" name="id_ta" placeholder="id_ta"
                                value="{{ $nextNumber }}" hidden>
                        </div>

                        @if (Auth::user()->r_mahasiswa)
                            <input type="hidden" hidden name="mahasiswa_id" value="{{ Auth::user()->r_mahasiswa->id_mahasiswa }}">
                        @endif

                        <div class="form-group" hidden>
                            <label for="pembimbing_satu">Pembimbing 1</label>
                            <input type="text" class="form-control" id="pembimbing_satu" name="pembimbing_satu" readonly>
                        </div>
                        <div class="form-group" hidden>
                            <label for="pembimbing_dua">Pembimbing 2</label>
                            <input type="text" class="form-control" id="pembimbing_dua" name="pembimbing_dua" readonly>
                        </div>


                        <div class="form-group">
                            <label for="proposal_final">Poposal Final</label>
                            <input type="file" class="form-control" id="proposal_final" name="proposal_final"
                                placeholder="Proposal Final" required>
                            @error('proposal_final')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="laporan_ta">Laporan TA</label>
                            <input type="file" class="form-control" id="laporan_ta" name="laporan_ta"
                                placeholder="Laporan Ta" required>
                            @error('laporan_ta')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tugas_akhir">Tugas Akhir</label>
                            <input type="file" class="form-control" id="tugas_akhir" name="tugas_akhir"
                                placeholder="Tugas Akhir" required>
                            @error('tugas_akhir')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($data_mahasiswa_ta as $data)
        @if (auth()->user()->id == $data->r_mahasiswa->user_id)
            <div class="col-12 grid-margin d-flex justify-content-center">
                <div class="row" style="width: 100%;">
                    <!-- Card Mahasiswa -->
                    <div class="col-md-7 mb-3">
                        <div class="card" style="width: 100%; max-width: 800px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-4 col-form-label" style="font-size: 15px;">Nama
                                                Mahasiswa</label>
                                            <div class="col-sm-8">
                                                <label class="col-form-label" style="font-size: 15px;">:
                                                    &nbsp;&nbsp;{{ $data->r_mahasiswa->nama ?? '' }}</label>
                                            </div>
                                        </div>

                                        <div class="form-group row align-items-center mb-3">
                                            <label class="col-sm-4 col-form-label" style="font-size: 15px;">Proposal
                                                Final</label>
                                            <div class="col-sm-8">
                                                <label class="col-form-label">
                                                    <a href="{{ asset('storage/uploads/mahasiswa/ta/proposal/' . $data->proposal_final) }}"
                                                        class="btn btn-primary mb-2 d-flex align-items-center me-1"
                                                        target="_blank">
                                                        <i class="mdi mdi-file-document-outline me-3"></i>
                                                        Proposal Final
                                                    </a>
                                                </label>
                                            </div>
                                        </div>



                                        <div class="form-group row align-items-center mb-3">
                                            <label class="col-sm-4 col-form-label" style="font-size: 15px;">Laporan
                                                TA</label>
                                            <div class="col-sm-8">
                                                <label class="col-form-label">
                                                    <a href="{{ asset('storage/uploads/mahasiswa/ta/laporan/' . $data->laporan_ta) }}"
                                                        class="btn btn-primary mb-2 d-flex align-items-center me-1"
                                                        target="_blank"><i
                                                            class="mdi mdi-file-document-outline me-3"></i>Laporan TA</a>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row align-items-center mb-3">
                                            <label class="col-sm-4 col-form-label" style="font-size: 15px;">Tugas
                                                Akhir</label>
                                            <div class="col-sm-8">
                                                <label class="col-form-label">
                                                    <a href="{{ asset('storage/uploads/mahasiswa/ta/tugasAkhir/' . $data->tugas_akhir) }}"
                                                        class="btn btn-primary mb-2 d-flex align-items-center me-1"
                                                        target="_blank"><i
                                                            class="mdi mdi-file-document-outline me-3"></i>Tugas Akhir</a>
                                                </label>
                                            </div>
                                        </div>




                                        <div class="form-group row align-items-center mb-3">
                                            <label class="col-sm-4 col-form-label" style="font-size: 15px;">Status
                                                Berkas</label>
                                            <div class="col-sm-8">
                                                <label
                                                    class="badge {{ $data->status_berkas == 1 ? 'badge-success' : 'badge-warning' }}"
                                                    style="font-size: 15px;">
                                                    {{ $data->status_berkas == 1 ? 'Diverifikasi' : 'Belum Diverifikasi' }}
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Status -->
                    <div class="col-md-5 mb-3">
                        <div class="card" style="width: 100%; max-width: 800px;">
                            <div class="card-body">
                                <h5 class="card-title">Status <i class="menu-icon mdi mdi-school"></i></h5>
                                @foreach ([
            'Pembimbing 1' => $data->r_pembimbing_satu->nama_dosen ?? '',
            'Pembimbing 2' => $data->r_pembimbing_dua->nama_dosen ?? '',
            'Ketua Sidang' => $data->r_ketua ? $data->r_ketua->nama_dosen : '-',
            'Sekretaris Sidang' => $data->r_sekretaris ? $data->r_sekretaris->nama_dosen : '-',
            'Dosen Penguji 1' => $data->r_penguji_1 ? $data->r_penguji_1->nama_dosen : '-',
            'Dosen Penguji 2' => $data->r_penguji_2 ? $data->r_penguji_2->nama_dosen : '-',
            'Tanggal Sempro' => $data->tanggal_sempro
                ? \Carbon\Carbon::parse($data->tanggal_sempro)->locale('id')->format('d-m-Y')
                : '-',
            'Ruang Sidang' => $data->r_ruangan ? $data->r_ruangan->kode_ruang : '-',
            'Jam Sidang' => $data->r_sesi ? $data->r_sesi->jam : '-',
        ] as $label => $value)
                                    <div class="form-group row mb-3">
                                        <label class="col-sm-4 col-form-label"
                                            style="font-size: 15px;">{{ $label }}</label>
                                        <div class="col-sm-8">
                                            <label class="col-form-label" style="font-size: 15px;">:
                                                &nbsp;&nbsp;{{ $value }}</label>
                                        </div>
                                    </div>
                                @endforeach

                                @if (!empty($data->nilai_mahasiswa))
                                    <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_ta }}"
                                        class="btn btn-primary">
                                        <span class="bi bi-pencil-square"></span> Lihat Nilai
                                    </a>
                                @else
                                    <span class="badge badge-dark" style="font-weight: bold;">Belum Ada Nilai</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Modal Nilai TA --}}
                    {{-- <div class="modal fade" id="nilai{{ $data->id_sempro }}" data-bs-backdrop="static"
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
                                            value="{{ $data->r_nilai_pembimbing_satu->nilai_sempro ?? '' }}" readonly
                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Nilai Pembimbing 2-
                                            {{ $data->r_pembimbing_dua->nama_dosen ?? '' }}</label>
                                        <input type="text" class="form-control"
                                            value="{{ $data->r_nilai_pembimbing_dua->nilai_sempro ?? '' }}" readonly
                                            style="background-color: #f0f0f0; color: #6c757d; cursor: not-allowed;">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Nilai Penguji-
                                            {{ $data->r_penguji ? $data->r_penguji->nama_dosen : '-' }}</label>
                                        <input type="text" class="form-control"
                                            value="{{ $data->r_nilai_penguji->nilai_sempro ?? '' }}" readonly
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
                </div> --}}
                </div>
            </div>
        @endif
    @endforeach
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
            $('#daftar_ta').on('show.bs.modal', function() {
                let mahasiswaId = $('input[name="mahasiswa_id"]').val();
                $.ajax({
                    url: '/get-pembimbing/' + mahasiswaId,
                    method: 'GET',
                    success: function(response) {
                        $('#pembimbing_satu').val(response.pembimbing_satu);
                        $('#pembimbing_dua').val(response.pembimbing_dua);
                    },
                    error: function() {
                        alert('Gagal mengambil data pembimbing.');
                    }
                });
            });
        });
    </script>
@endsection
