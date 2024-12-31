@extends('admin.admin_master')

@section('admin')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Bimbingan TA</h4>


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
                            <th>Pembimbing 1 - status</th>
                            <th>Pembimbing 2 - status</th>
                            <th>Acc</th>
                            <th>Bimbingan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_dosen_bimbingan_ta as $data)
                            <tr class="table-light">
                                <td>{{ $counter++ }}</td>
                                <td>{{ $data->r_mahasiswa->nama }}</td>
                                <td>{{ $data->r_pembimbing_satu->nama_dosen }} - @if ($data->acc_pembimbing_satu == 0)
                                        <span class="badge badge-warning" style="font-weight: bold;">Belum
                                            Diacc</span>
                                    @else
                                        <span class="badge badge-success" style="font-weight: bold;">Sudah
                                            DiAcc</span>
                                    @endif
                                </td>
                                <td>{{ $data->r_pembimbing_dua->nama_dosen }} -
                                    @if ($data->acc_pembimbing_dua == 0)
                                        <span class="badge badge-warning" style="font-weight: bold;">Belum
                                            Diacc</span>
                                    @else
                                        <span class="badge badge-success" style="font-weight: bold;">Sudah
                                            DiAcc</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex">
                                        @php
                                            $mahasiswaId = $data->mahasiswa_id;

                                            $bimbinganPembimbing1 = \App\Models\BimbinganTa::where(
                                                'ta_id',
                                                $data->id_ta,
                                            )
                                                ->where('dosen_id', $data->pembimbing_satu_id)
                                                ->where('status', '1')
                                                ->count();

                                            $bimbinganPembimbing2 = \App\Models\BimbinganTa::where(
                                                'ta_id',
                                                $data->id_ta,
                                            )
                                                ->where('dosen_id', $data->pembimbing_dua_id)
                                                ->where('status', '1')
                                                ->count();

                                            $sudahBimbingan9KaliPembimbing1 = $bimbinganPembimbing1 >= 1;

                                            $sudahBimbingan9KaliPembimbing2 = $bimbinganPembimbing2 >= 1;
                                        @endphp

                                        @if ($data->pembimbing_satu_id == auth()->user()->r_dosen->id_dosen)
                                            @if ($data->acc_pembimbing_satu == 0 && $sudahBimbingan9KaliPembimbing1)
                                                <a data-bs-toggle="modal" data-bs-target="#verifikasi{{ $data->id_ta }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span> Acc Mahasiswa
                                                </a>
                                            @elseif ($data->acc_pembimbing_satu == 0 && !$sudahBimbingan9KaliPembimbing1)
                                                <button class="btn btn-dark mb-2 me-2 align-items-center"
                                                    style="cursor: not-allowed;">
                                                    <span class="bi bi-pencil-square"></span> Belum Di Acc
                                                </button>
                                            @elseif ($data->acc_pembimbing_satu == 1)
                                                <button class="btn btn-success mb-2 me-2 align-items-center"
                                                    style="cursor: not-allowed;">
                                                    <span class="bi bi-x-circle"></span> Sudah Di Acc
                                                </button>
                                            @endif
                                        @endif

                                        @if ($data->pembimbing_dua_id == auth()->user()->r_dosen->id_dosen)
                                            @if ($data->acc_pembimbing_dua == 0 && $sudahBimbingan9KaliPembimbing2)
                                                <a data-bs-toggle="modal" data-bs-target="#verifikasi{{ $data->id_ta }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span> Acc Mahasiswa
                                                </a>
                                            @elseif ($data->acc_pembimbing_dua == 0 && !$sudahBimbingan9KaliPembimbing2)
                                                <button class="btn btn-dark mb-2 me-2 align-items-center"
                                                    style="cursor: not-allowed;">
                                                    <span class="bi bi-x-circle"></span> Belum Di Acc
                                                </button>
                                            @elseif ($data->acc_pembimbing_dua == 1)
                                                <button class="btn btn-success mb-2 me-2 align-items-center"
                                                    style="cursor: not-allowed;">
                                                    <span class="bi bi-x-circle"></span> Sudah Di Acc
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    @if ($data->r_bimbingan->count() > 0)
                                        <a href="{{ route('dosen_bimbingan_ta.detail', ['id' => $data->id_ta]) }}"
                                            class="btn btn-primary">
                                            <span class="bi bi-pencil-square"></span> Bimbingan
                                        </a>
                                    @else
                                        <button class="btn btn-dark" style="cursor: not-allowed;">
                                            <span class="bi bi-pencil-square"></span> Bimbingan
                                        </button>
                                    @endif
                                </td>
                            </tr>


                            <div class="modal fade" id="verifikasi{{ $data->id_ta }}" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title fs-5" id="staticBackdropLabel">Acc Data</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah kamu yakin ingin menyetujui
                                                <b>{{ $data->r_mahasiswa->nama }}</b> untuk lanjut sidang?
                                            </p>

                                            @if ($data->pembimbing_satu_id == auth()->user()->r_dosen->id_dosen && $data->acc_pembimbing_satu == 0)
                                                <form
                                                    action="{{ route('acc_pembimbing_satu.update', ['id' => $data->id_ta]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="acc_pembimbing_satu" value="1">
                                                    <div class="modal-footer text-end">
                                                        <button type="submit" class="btn btn-primary">Ya, Acc</button>
                                                    </div>
                                                </form>
                                            @endif

                                            @if ($data->pembimbing_dua_id == auth()->user()->r_dosen->id_dosen && $data->acc_pembimbing_dua == 0)
                                                <form
                                                    action="{{ route('acc_pembimbing_dua.update', ['id' => $data->id_ta]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="acc_pembimbing_dua" value="1">
                                                    <div class="modal-footer text-end">
                                                        <button type="submit" class="btn btn-primary">Ya, Acc</button>
                                                    </div>
                                                </form>
                                            @endif

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
