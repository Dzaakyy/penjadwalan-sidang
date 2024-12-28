@extends('admin.admin_master')
@section('admin')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h4 class="card-title mt-4">Data PKL</h4>
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

    <div class="col-12 grid-margin d-flex justify-content-center">
        <div class="row" style="width: 100%;">

            @foreach ($data_mahasiswa_pkl as $data)
                @if (auth()->user()->id == $data->r_pkl->r_mahasiswa->user_id)
                    <div class="col-md-7 mb-3">
                        <div class="card" style="width: 100%; max-width: 800px;">
                            <div class="card-body">
                                <form action="{{ route('daftar_sidang.update', $data->id_mhs_pkl) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Nama
                                                    Mahasiswa</label>
                                                <div class="col-sm-8">
                                                    <label class="col-form-label" style="font-size: 15px;">:
                                                        &nbsp;&nbsp;{{ $data->r_pkl->r_mahasiswa->nama }}</label>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Tempat
                                                    PKL</label>
                                                <div class="col-sm-8">
                                                    <label class="col-form-label" style="font-size: 15px;">:
                                                        &nbsp;&nbsp;{{ $data->r_pkl->r_perusahaan->nama_perusahaan }}</label>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Tahun
                                                    PKL</label>
                                                <div class="col-sm-8">
                                                    <label class="col-form-label" style="font-size: 15px;">:
                                                        &nbsp;&nbsp;{{ $data->tahun_pkl }}</label>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label"
                                                    style="font-size: 15px;">Judul</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="judul" name="judul"
                                                        placeholder="Judul" value="{{ old('judul', $data->judul ?? '') }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Pembimbing
                                                    Industri</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="pembimbing_pkl"
                                                        name="pembimbing_pkl" placeholder="Pembimbing Industri"
                                                        value="{{ old('pembimbing_pkl', $data->pembimbing_pkl ?? '') }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Dokumen
                                                    Nilai Industri</label>
                                                <div class="col-sm-8">
                                                    <input type="file" name="dokumen_nilai_industri" class="form-control"
                                                        id="dokumen_nilai_industri">
                                                    @if (isset($data->dokumen_nilai_industri))
                                                        <p class="mt-2">Current file: <a
                                                                href="{{ asset('storage/uploads/mahasiswa/dokumen_nilai_industri/' . $data->dokumen_nilai_industri) }}"
                                                                target="_blank">{{ $data->dokumen_nilai_industri }}</a></p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row mb-3">
                                                <label class="col-sm-4 col-form-label" style="font-size: 15px;">Laporan
                                                    PKL</label>
                                                <div class="col-sm-8">
                                                    <input type="file" name="laporan_pkl" class="form-control"
                                                        id="laporan_pkl">
                                                    @if (isset($data->laporan_pkl))
                                                        <p class="mt-2">Current file: <a
                                                                href="{{ asset('storage/uploads/mahasiswa/laporan_pkl/' . $data->laporan_pkl) }}"
                                                                target="_blank">{{ $data->laporan_pkl }}</a></p>
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label"
                                                    style="font-size: 15px;">Status</label>
                                                <div class="col-sm-8">
                                                    <label
                                                        class="badge {{ $data->status_admin == 1 ? 'badge-success' : 'badge-danger' }} mt-2"
                                                        style="font-size: 15px;">
                                                        {{ $data->status_admin == 1 ? 'Diverifikasi' : 'Belum Diverifikasi' }}
                                                    </label>
                                                </div>
                                            </div>

                                            @if (!empty($data->r_nilai_bimbingan) && !empty($data->r_nilai_bimbingan->nilai_bimbingan))
                                                <button type="submit" class="btn btn-primary">Upload</button>
                                            @else
                                                <span class="badge badge-dark" style="font-weight: bold;">Belum Ada Nilai
                                                    Bimbingan</span>
                                            @endif


                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 mb-3">
                        <div class="card" style="width: 100%; max-width: 800px;">
                            <div class="card-body">
                                <h5 class="card-title">Status <i class="menu-icon mdi mdi-school"></i></h5>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Pembimbing</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;{{ $data->r_dosen_pembimbing->nama_dosen }}</label>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Dosen Penguji</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">
                                            &nbsp;&nbsp;
                                            {{ $data->r_dosen_penguji ? $data->r_dosen_penguji->nama_dosen : '-' }}
                                        </label>

                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Ruang Sidang</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;{{ $data->r_ruang ? $data->r_ruang->kode_ruang : '-' }}</label>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Jam Sidang</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">:
                                            &nbsp;&nbsp;{{ $data->r_sesi ? $data->r_sesi->jam : '-' }}</label>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label" style="font-size: 15px;">Tanggal Sidang</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label" style="font-size: 15px;">
                                            &nbsp;&nbsp;
                                            {{ $data->tgl_sidang? \Carbon\Carbon::parse($data->tgl_sidang)->locale('id')->format('d-m-Y'): '-' }}
                                        </label>

                                    </div>
                                </div>
                                @if (!empty($data->nilai_mahasiswa) && !empty($data->nilai_mahasiswa))
                                    <a data-bs-toggle="modal" data-bs-target="#nilai{{ $data->id_mhs_pkl }}"
                                        class="btn btn-primary">
                                        <span class="bi bi-pencil-square"></span>Lihat Nilai
                                    </a>
                                @else
                                    <span class="badge badge-dark" style="font-weight: bold;">Belum Ada Nilai</span>
                                @endif
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
                @endif
            @endforeach
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
