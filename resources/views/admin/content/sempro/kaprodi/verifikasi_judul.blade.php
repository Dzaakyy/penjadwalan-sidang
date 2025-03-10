@extends('admin.admin_master')
@section('admin')
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Verifikasi Judul Sempro</h4>
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
                            <th>Judul</th>
                            <th>Pembimbing 1</th>
                            <th>Pembimbing 2</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data_mahasiswa_sempro as $data)
                            <tr class="table-Light">
                                <td>{{ $counter++ }}</td>
                                <td>{{ $data->r_mahasiswa->nama ?? '' }}</td>
                                <td>{{ $data->judul ?? '-' }}</td>
                                <td>{{ $data->r_pembimbing_satu->nama_dosen ?? '' }}</td>
                                <td>{{ $data->r_pembimbing_dua->nama_dosen ?? '' }}</td>
                                <td style="max-width: 90px; word-break: break-all; white-space: normal;">
                                    @if ($data->status_judul == 0)
                                        <span class="badge badge-warning" style="font-weight: bold;">Belum
                                            Dikonfirmasi</span>
                                    @elseif ($data->status_judul == 1)
                                        <span class="badge badge-danger" style="font-weight: bold;">Judul Ditolak</span>
                                    @elseif ($data->status_judul == 2)
                                        <span class="badge badge-success" style="font-weight: bold;">Judul Diterima</span>
                                    @else
                                        <span class="badge badge-secondary" style="font-weight: bold;">Status Tidak
                                            Dikenal</span>
                                    @endif
                                </td>
                                </td>
                                <td style="width: 10%;">
                                    <div class="d-flex">
                                        @if ($data->status_judul == 0)
                                            <a data-bs-toggle="modal" data-bs-target="#verifikasi{{ $data->id_sempro }}"
                                                class="btn btn-primary mb-2 me-2 align-items-center">
                                                <span class="bi bi-pencil-square"></span> Verifikasi
                                            </a>
                                        @elseif ($data->status_judul == 1)
                                            <label class="badge badge-danger" style="font-weight: bold;">
                                                Data Ditolak
                                            </label>
                                        @elseif ($data->status_judul == 2)
                                            <label class="badge badge-success" style="font-weight: bold;">
                                                Data Diterima
                                            </label>
                                        @endif
                                    </div>
                                </td>
                            </tr>


                            {{-- Modal Verif Judul --}}
                            {{-- Modal Verif Judul --}}
                            <div class="modal fade" id="verifikasi{{ $data->id_sempro }}" data-bs-backdrop="static"
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
                                            <p>Apakah kamu yakin ingin memverifikasi judul
                                                <b>{{ $data->r_mahasiswa->nama }}</b>
                                            </p>

                                            <form id="daftar_sidang{{ $data->id_sempro }}"
                                                action="{{ route('verifikasi_judul_sempro_kaprodi.update', ['id' => $data->id_sempro]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="status_judul">Status Judul</label>
                                                    <div class="d-flex">
                                                        <div class="col-sm-4">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="radio"
                                                                        class="form-check-input status_judul"
                                                                        name="status_judul" value="2"
                                                                        onchange="togglePembimbing()"> Diterima
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="radio"
                                                                        class="form-check-input status_judul"
                                                                        name="status_judul" value="1"
                                                                        onchange="togglePembimbing()"> Ditolak
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('status_judul')
                                                        <small>{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="form-group pembimbing-wrapper" style="display: none;">
                                                    <label for="pembimbing_satu">Pilih Dosen Pembimbing Satu</label>
                                                    <select name="pembimbing_satu" id="pembimbing_satu" class="form-select">
                                                        <option value="" disabled selected>Pilih Dosen Pembimbing Satu
                                                        </option>
                                                        @foreach ($dosen as $dosenItem)
                                                            <option value="{{ $dosenItem->id_dosen }}">
                                                                {{ $dosenItem->nama_dosen }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group pembimbing-wrapper" style="display: none;">
                                                    <label for="pembimbing_dua">Pilih Dosen Pembimbing Dua</label>
                                                    <select name="pembimbing_dua" id="pembimbing_dua" class="form-select">
                                                        <option value="" disabled selected>Pilih Dosen Pembimbing Dua
                                                        </option>
                                                        @foreach ($dosen as $dosenItem)
                                                            <option value="{{ $dosenItem->id_dosen }}">
                                                                {{ $dosenItem->nama_dosen }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>


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

        document.addEventListener("DOMContentLoaded", () => {
            function updateDropdownOptions(modal) {
                const pembimbingSatu = modal.querySelector("#pembimbing_satu");
                const pembimbingDua = modal.querySelector("#pembimbing_dua");

                if (!pembimbingSatu || !pembimbingDua) {
                    console.error("Dropdown Pembimbing tidak ditemukan di modal:", modal);
                    return;
                }

                const selectedSatu = pembimbingSatu.value;
                const selectedDua = pembimbingDua.value;

                console.log("Pembimbing Satu Terpilih:", selectedSatu);
                console.log("Pembimbing Dua Terpilih:", selectedDua);

                Array.from(pembimbingSatu.options).forEach(option => {
                    option.style.display = "";
                });

                Array.from(pembimbingDua.options).forEach(option => {
                    option.style.display = "";
                });

                if (selectedSatu) {
                    Array.from(pembimbingDua.options).forEach(option => {
                        if (option.value === selectedSatu) {
                            option.style.display = "none";
                        }
                    });
                }

                if (selectedDua) {
                    Array.from(pembimbingSatu.options).forEach(option => {
                        if (option.value === selectedDua) {
                            option.style.display = "none";
                        }
                    });
                }
            }

            function togglePembimbing(modal) {
                const pembimbingWrapper = modal.querySelectorAll(".pembimbing-wrapper");
                const statusJudul = modal.querySelector('input[name="status_judul"]:checked');

                if (statusJudul && statusJudul.value === "2") {
                    pembimbingWrapper.forEach(wrapper => (wrapper.style.display = "block"));
                    modal.querySelector("#pembimbing_satu").setAttribute("required", "required");
                    modal.querySelector("#pembimbing_dua").setAttribute("required", "required");
                } else {
                    pembimbingWrapper.forEach(wrapper => (wrapper.style.display = "none"));
                    modal.querySelector("#pembimbing_satu").removeAttribute("required");
                    modal.querySelector("#pembimbing_dua").removeAttribute("required");
                }
            }

            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('show.bs.modal', function() {
                    console.log("Modal dibuka:", modal.id);

                    togglePembimbing(modal);

                    updateDropdownOptions(modal);

                    const pembimbingSatu = modal.querySelector("#pembimbing_satu");
                    const pembimbingDua = modal.querySelector("#pembimbing_dua");

                    if (pembimbingSatu && pembimbingDua) {
                        pembimbingSatu.addEventListener("change", () => updateDropdownOptions(
                            modal));
                        pembimbingDua.addEventListener("change", () => updateDropdownOptions(
                        modal));
                    }

                    const statusJudulInputs = modal.querySelectorAll('input[name="status_judul"]');
                    statusJudulInputs.forEach(input => {
                        input.addEventListener("change", () => togglePembimbing(modal));
                    });
                });
            });
        });
    </script>
@endsection
