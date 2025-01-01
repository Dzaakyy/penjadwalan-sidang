@extends('admin.admin_master')

@section('admin')
    @php
        $mahasiswa = Auth::user()->r_mahasiswa;
        $mhsPkl = App\Models\MahasiswaPkl::where('mahasiswa_id', $mahasiswa->id_mahasiswa)->first();
    @endphp

    @if ($mhsPkl)
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Data Bimbingan</h4>

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

                @if ($errors->has('duplicate_data'))
                    <div id="delay" class="alert alert-danger" role="alert">
                        <strong>Error:</strong> <br>
                        {!! $errors->first('duplicate_data') !!}
                    </div>
                @endif


                @php
                    $mentoringCount = App\Models\BimbinganPkl::whereHas('r_pkl', function ($query) use ($mahasiswa) {
                        $query->where('mahasiswa_id', $mahasiswa->id_mahasiswa);
                    })->count();
                @endphp

                @if ($mentoringCount < 16)
                    <a href="{{ route('bimbingan_pkl.create') }}" class="btn btn-primary me-2 mb-3">
                        <i class="bi bi-file-earmark-plus"></i> New
                    </a>
                @else
                    <p class="badge badge-success" style="color: green; font-weight: bold;">Anda Sudah Melakukan Bimbingan
                        {{ $mentoringCount }} Kali</p>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="table-info">
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Status-Komentar</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($data_bimbingan_pkl as $data)
                                @if ($data->r_pkl->mahasiswa_id == $mahasiswa->id_mahasiswa)
                                    <tr class="table-light">
                                        <td>{{ $counter++ }}</td>
                                        <td style="max-width: 70px; word-break: break-all; white-space: normal;">
                                            {{ \Carbon\Carbon::parse($data->tgl_kegiatan_awal)->locale('id')->format('d-m-Y') }}
                                            /
                                            {{ \Carbon\Carbon::parse($data->tgl_kegiatan_akhir)->locale('id')->format('d-m-Y') }}
                                        </td>

                                        <td style="max-width: 10px; word-break: break-all; white-space: normal;">
                                            {{ $data->kegiatan }}
                                        </td>
                                        <td style="max-width: 90px; word-break: break-all; white-space: normal;">
                                            @if ($data->status == 0)
                                                Belum Diverifikasi
                                            @else
                                                Diverifikasi
                                            @endif - {{ $data->komentar }}
                                        </td>
                                        <td style="max-width: 50px; word-break: break-all; white-space: normal;">
                                            <a href="{{ asset('storage/uploads/mahasiswa/bimbingan/' . $data->file_dokumentasi) }}"
                                                target="_blank">File Dokumentasi</a>
                                        </td>

                                        <td style="width: 10%;">
                                            <div class="d-flex">
                                                <a href="{{ route('bimbingan_pkl.edit', ['id' => $data->id_bimbingan_pkl]) }}"
                                                    class="btn btn-primary mb-2 me-2 align-items-center">
                                                    <span class="bi bi-pencil-square"></span>Edit
                                                </a>

                                                <a data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop{{ $data->id_bimbingan_pkl }}"
                                                    class="btn btn-danger mb-2 align-items-center">
                                                    <span class="bi bi-trash"></span>Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                {{-- Modal Konfirmasi hapus data --}}
                                <div class="modal fade" id="staticBackdrop{{ $data->id_bimbingan_pkl }}"
                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus Data
                                                </h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah kamu yakin ingin menghapus data
                                                    <b>{{ $data->r_pkl->r_pkl->r_mahasiswa->nama }}</b>
                                                </p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <form
                                                    action="{{ route('bimbingan_pkl.delete', ['id' => $data->id_bimbingan_pkl]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-primary">Ya, Hapus</button>
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
        @endif
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
