@extends('admin.admin_master')

@section('admin')
@if($isMahasiswaInTa)
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

            <a data-bs-toggle="modal" data-bs-target="#upload" class="btn btn-primary me-2 mb-3">
                <i class="bi bi-file-earmark-plus"></i> Upload
            </a>

            {{-- Modal Upload Bimbingan --}}
            @foreach ($mahasiswa_ta as $mhs_ta)
            <div class="modal fade" id="upload" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fs-5" id="staticBackdropLabel">Bimbingan</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bimbingan
                            <b>{{ $mhs_ta->r_mahasiswa->nama }}</b>
                        </p>

                        <form id="upload{{ $mhs_ta->id_ta }}" action="{{ route('bimbingan_ta.create') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <div class="form-group">
                                <input type="hidden" class="form-control" id="id_bimbingan_ta"
                                name="id_bimbingan_ta" value="{{ $nextNumber }}" readonly>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="ta_id" value="{{ $ta_id }}">
                                @error('ta_id')
                                <small>{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="dosen_id">Pilih Dosen Pembimbing</label>
                                <select name="dosen_id" class="form-select" required>
                                    <option value="" disabled selected>Pilih Dosen Pembimbing</option>
                                    @foreach ($dosen_pembimbing as $dosen)
                                    @if ($dosen)
                                    <option value="{{ $dosen->id_dosen }}">
                                        {{ $dosen->nama_dosen }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tgl_bimbingan">Tgl Bimbingan</label>
                                <input type="date" class="form-control" id="tgl_bimbingan" name="tgl_bimbingan"
                                placeholder="tgl_bimbingan" required>
                                @error('tgl_bimbingan')
                                <small>{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="pembahasan">Pembahasan</label>
                                <textarea type="textarea" rows="6" class="form-control" id="pembahasan" name="pembahasan" placeholder="pembahasan"
                                required style="height: 100px;"></textarea>
                                @error('pembahasan')
                                <small>{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label style="font-size:15px;">Laporan Bimbingan</label>
                                <input type="file" name="file_bimbingan" class="form-control" id="file_bimbingan"
                                required>
                                @error('file_bimbingan')
                                <small>{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="table-responsive">
            <table class="table table-hover dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="table-info">
                        <th>#</th>
                        <th>pembahasan</th>
                        <th>Dosen</th>
                        <th>Tanggal</th>
                        <th>Validasi-Komentar</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                            $counter = 1;
                            @endphp
                        @foreach ($data_bimbingan_ta as $data)
                        @if ($data->r_ta->r_mahasiswa && $data->r_ta->r_mahasiswa->id_mahasiswa == $mahasiswa->id_mahasiswa)
                        <tr class="table-light">
                            <td>{{ $counter++ }}</td>
                            <td style="max-width: 10px; word-break: break-all; white-space: normal;">
                                {{ $data->pembahasan }}
                            </td>
                            <td style="max-width: 70px; word-break: break-all; white-space: normal;">
                                {{ $data->r_dosen->nama_dosen }}
                            </td>
                            <td style="max-width: 70px; word-break: break-all; white-space: normal;">
                                {{ $data->tgl_bimbingan }}
                            </td>

                            <td style="max-width: 90px; word-break: break-all; white-space: normal;">
                                @if ($data->status == 0)
                                Belum
                                @else
                                Sudah
                                @endif - {{ $data->komentar }}
                            </td>
                            <td style="max-width: 50px; word-break: break-all; white-space: normal;">

                                <a href="{{ asset('storage/uploads/mahasiswa/bimbingan/ta/' . $data->file_bimbingan) }}"
                                    class="btn btn-primary mb-2 d-flex align-items-center me-1" target="_blank">
                                    <i class="mdi mdi-file-document-outline me-3"></i>
                                    file
                                </a>
                            </td>
                        </tr>
                        @endif
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
