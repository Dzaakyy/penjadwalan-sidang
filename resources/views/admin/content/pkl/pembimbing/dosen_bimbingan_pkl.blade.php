@extends('admin.admin_master')

@section('admin')
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="card-title">
                <a href="{{ route('dosen_bimbingan_pkl') }}" class="text-decoration-none"
                    style="color: black; transition: color 0.3s ease;"
                    onmouseover="this.style.color='blue'"
                    onmouseout="this.style.color='black'">
                    Data Log Book
                </a>
                -> {{ $data_bimbingan->first()->r_pkl->r_usulan_pkl->r_mahasiswa->nama }}
            </h6>

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
                        @foreach ($data_bimbingan as $data)
                            <tr class="table-light">
                                <td>{{ $counter++ }}</td>
                                <td style="max-width: 40px; word-wrap: break-word; white-space: normal;">
                                    {{ $data->r_pkl->r_usulan_pkl->r_mahasiswa->nama }}
                                </td>
                                <td style="max-width: 40px; word-wrap: break-word; white-space: normal;">
                                    {{ \Carbon\Carbon::parse($data->tgl_kegiatan_awal)->locale('id')->format('d-m-Y') }}
                                    /
                                    {{ \Carbon\Carbon::parse($data->tgl_kegiatan_akhir)->locale('id')->format('d-m-Y') }}
                                </td>

                                <td style="max-width: 100px; word-wrap: break-word; white-space: normal;">
                                    {{ $data->kegiatan }}
                                </td>
                                <td>
                                    @if ($data->status == 0)
                                        Belum Diverifikasi
                                    @else
                                        Diverifikasi
                                    @endif - {{ $data->komentar }}
                                </td>
                                <td>
                                    <a href="{{ asset('storage/uploads/mahasiswa/bimbingan/' . $data->file_dokumentasi) }}"
                                        target="_blank">File Dokumentasi</a>
                                </td>

                                <td style="width: 10%;">
                                    @if ($data->status == 0)
                                        <div class="d-flex">
                                            <a data-bs-toggle="modal"
                                                data-bs-target="#konfirmasi{{ $data->id_bimbingan_pkl }}"
                                                class="btn btn-primary mb-2 align-items-center">
                                                <span class="bi bi-pencil-square">Verifikasi</span>
                                            </a>
                                        </div>
                                    @else
                                        <span style="color: green; font-weight: bold;">Data sudah diverifikasi</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Modal Konfirmasi --}}
                            <div class="modal fade" id="konfirmasi{{ $data->id_bimbingan_pkl }}" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title fs-5" id="staticBackdropLabel">Verifikasi Data</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah kamu yakin ingin memverifikasi data
                                                <b>{{ $data->r_pkl->r_usulan_pkl->r_mahasiswa->nama }}</b>
                                            </p>

                                            <form id="status{{ $data->id_bimbingan_pkl }}"
                                                action="{{ route('dosen_bimbingan_pkl.update', ['id' => $data->id_bimbingan_pkl]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="form-group">
                                                    <label for="komentar">Komentar</label>
                                                    <textarea class="form-control" id="komentar" name="komentar" placeholder="Komentar" required></textarea>
                                                </div>
                                                <input type="hidden" name="status" value="1">

                                                <div class="modal-footer justify-content-between">
                                                    <button type="submit" class="btn btn-primary">Ya, Verifikasi</button>
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
