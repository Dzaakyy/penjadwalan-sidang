@extends('admin.admin_master')
@section('admin')
@section('style')
    <style>
        .custom-table {
            overflow: hidden;
            transition: height 0.6s ease;
            /* Animasi perubahan tinggi */
            height: 0;
            /* Default tertutup */
            visibility: visible;
            /* Selalu terlihat selama animasi berlangsung */
        }

        .collapsed-custom {
            height: 0 !important;
            /* Saat tertutup, tinggi menjadi 0 */
            visibility: visible;
            /* Tetap terlihat selama animasi */
            display: block;
            /* Tetap dirender untuk memungkinkan animasi */
        }

        .open {
            height: auto !important;
            visibility: visible;
        }
    </style>
@endsection
@if (session('success'))
    <div class="alert alert-success" id="delay">
        {{ session('success') }}
    </div>
@endif
@foreach ($data_usulan_pkl as $data)
    @if (Auth::user()->r_mahasiswa && Auth::user()->r_mahasiswa->id_mahasiswa == $data->mahasiswa_id)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">

                <h4 class="card-title mb-4">Usulan PKL</h4>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="flex: 1;">
                            <h5 style="font-size: 0.9rem; color: #6c757d; margin-bottom: 0.5rem;">Nama Mahasiswa</h5>
                        </div>
                        <div style="flex: 2;">
                            <p style="font-size: 1rem; color: #333; margin-bottom: 0;"> : {{ $data->r_mahasiswa->nama }}
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="flex: 1;">
                            <h5 style="font-size: 0.9rem; color: #6c757d; margin-bottom: 0.5rem;">Nama Perusahaan</h5>
                        </div>
                        <div style="flex: 2;">
                            <p style="font-size: 1rem; color: #333; margin-bottom: 0;"> :
                                {{ $data->r_perusahaan->nama_perusahaan }}</p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="flex: 1;">
                            <h5 style="font-size: 0.9rem; color: #6c757d; margin-bottom: 0.5rem;">Status Konfirmasi</h5>
                        </div>
                        <div style="flex: 2;">
                            @if ($data->konfirmasi == 0)
                                : <span
                                    style="font-size: 0.9rem; padding: 0.5rem 0.75rem; background-color: #dc3545; color: white; border-radius: 0.25rem;">Belum
                                    Dikonfirmasi</span>
                            @else
                                : <span
                                    style="font-size: 0.9rem; padding: 0.5rem 0.75rem; background-color: #28a745; color: white; border-radius: 0.25rem;">Sudah
                                    Dikonfirmasi</span>
                            @endif
                        </div>
                    </div>


                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="flex: 1;">
                            <button data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $data->id_usulan_pkl }}"
                                style="background-color: #dc3545; border-color: #dc3545; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; text-decoration: none; border: none; cursor: pointer;">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus Data -->
        <div class="modal fade" id="staticBackdrop{{ $data->id_usulan_pkl }}" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Konfirmasi Hapus Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah kamu yakin ingin menghapus data <b>{{ $data->r_mahasiswa->nama }}</b>?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('usulan_pkl.delete', ['id' => $data->id_usulan_pkl]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach


<div class="card mt-5 shadow-sm">
    <div class="card-body">
        <div class="py-3 bg-transparent d-flex justify-content-between align-items-center">
            <h4 class="card-title">Tempat Pkl</h4>
            <button id="toggleTableBtn" class="btn btn-primary" onclick="toggleTable('tableTempat')">Toggle
                Tabel</button>
        </div>



        {{-- Usulan Mahasiswa --}}
        <div class="table-responsive custom-table" id="tableTempat">
            <table class="table table-hover dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="table-info">
                        <th>#</th>
                        <th>Nama Perusahaan</th>
                        <th>Deskripsi</th>
                        <th>Kuota</th>
                        <th>
                            Kuota<br>Tersedia</th>
                        <th>Nama Mahasiswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_perusahaan as $perusahaan)
                        <tr class="table-Light">
                            @if ($perusahaan->status == 1)
                                <td>{{ $perusahaan->id_perusahaan }}</td>
                                <td style="max-width: 150px; word-break: break-all; white-space: normal;">
                                    {{ $perusahaan->nama_perusahaan }}</td>
                                <td style="max-width: 150px; word-break: break-all; white-space: normal;">
                                    {{ $perusahaan->deskripsi }}</td>
                                <td style="text-align: center;">{{ $perusahaan->kuota }}</td>


                                <td style="text-align: center;">
                                    @php

                                        $jumlahMahasiswaTerkonfirmasi = $perusahaan->r_usulan_pkl
                                            ->where('konfirmasi', 1)
                                            ->count();
                                        $sisaKuota = $perusahaan->kuota - $jumlahMahasiswaTerkonfirmasi;
                                    @endphp
                                    {{ $sisaKuota }}
                                </td>

                                <td>
                                    @php

                                        $confirmedUsulan = $perusahaan->r_usulan_pkl->where('konfirmasi', 1);
                                    @endphp

                                    @if ($confirmedUsulan->isEmpty())
                                        <p>-</p>
                                    @else
                                        @foreach ($confirmedUsulan as $usulan)
                                            <div>{{ $usulan->r_mahasiswa->nama }}</div>
                                        @endforeach
                                    @endif
                                </td>



                                <td>
                                    @if ($sisaKuota > 0)
                                        @if (Auth::user()->r_mahasiswa)
                                            @php
                                                $mahasiswa_id = Auth::user()->r_mahasiswa->id_mahasiswa;

                                                $usulanTerdaftar = $data_usulan_pkl
                                                    ->where('mahasiswa_id', $mahasiswa_id)
                                                    ->where('perusahaan_id', $perusahaan->id_perusahaan)
                                                    ->isNotEmpty();

                                                $mahasiswaTerdaftarDiPerusahaanLain = $data_usulan_pkl
                                                    ->where('mahasiswa_id', $mahasiswa_id)
                                                    ->where('perusahaan_id', '!=', $perusahaan->id_perusahaan)
                                                    ->isNotEmpty();
                                            @endphp

                                            @if ($usulanTerdaftar)
                                                <span class="badge badge-primary" style="font-weight: bold;">Anda Sudah
                                                    Mendaftar</span>
                                            @elseif ($mahasiswaTerdaftarDiPerusahaanLain)
                                                <button class="btn btn-dark" style="cursor: not-allowed;">
                                                    <span class="bi bi-pencil-square"></span> Daftar
                                                </button>
                                            @else
                                                <a data-bs-toggle="modal"
                                                    data-bs-target="#daftar{{ $perusahaan->id_perusahaan }}"
                                                    class="btn btn-primary mb-2 align-items-center">
                                                    <span class="bi bi-trash"></span> Daftar
                                                </a>
                                            @endif
                                        @else
                                            <button class="btn btn-dark" style="cursor: not-allowed;">
                                                <span class="bi bi-pencil-square"></span> Daftar
                                            </button>
                                        @endif
                                    @else
                                        <span class="badge badge-danger" style="font-weight: bold;">Kuota Penuh</span>
                                    @endif
                                </td>
                        </tr>

                        {{-- Modal Daftar --}}
                        <div class="modal fade" id="daftar{{ $perusahaan->id_perusahaan }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title fs-5" id="staticBackdropLabel">Usulan Tempat Magang</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah kamu yakin ingin megusulkan
                                            <b>{{ $perusahaan->nama_perusahaan }}</b> sebagai tempat PKL?
                                        </p>
                                    </div>
                                    <div class="modal-footer justify-content-between">

                                        <form action="{{ route('usulan_pkl.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="perusahaan_id"
                                                value="{{ $perusahaan->id_perusahaan }}">

                                            @if (Auth::user()->r_mahasiswa)
                                                <input type="hidden" name="mahasiswa_id"
                                                    value="{{ Auth::user()->r_mahasiswa->id_mahasiswa }}">
                                            @endif

                                            <input type="hidden" id="id_usulan_pkl" name="id_usulan_pkl"
                                                value="{{ $nextNumber }}" readonly>

                                            <button type="submit" class="btn btn-primary">Daftar</button>



                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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

    function toggleTable(tableId) {
        var tableContent = document.getElementById(tableId);

        if (!tableContent.classList.contains('collapsed-custom')) {
            // Animasi menutup tabel
            tableContent.style.height = tableContent.scrollHeight + 'px'; // Mulai dari tinggi saat ini
            setTimeout(() => {
                tableContent.style.height = '0'; // Turunkan tinggi menjadi 0
                tableContent.addEventListener('transitionend', function onClose() {
                    tableContent.classList.add(
                        'collapsed-custom'); // Tambahkan kelas collapsed-custom setelah animasi
                    tableContent.style.height = '0'; // Pastikan tetap 0 setelah animasi selesai
                    tableContent.removeEventListener('transitionend', onClose); // Hapus listener
                });
            }, 10); // Delay kecil untuk memastikan transisi dimulai
            sessionStorage.setItem(`tableState-${tableId}`, 'closed'); // Simpan state tertutup
        } else {
            // Animasi membuka tabel
            tableContent.classList.remove('collapsed-custom');
            tableContent.style.height = tableContent.scrollHeight + 'px'; // Tinggi asli untuk membuka
            tableContent.addEventListener('transitionend', function onOpen() {
                tableContent.style.height = 'auto'; // Reset ke auto setelah selesai
                tableContent.removeEventListener('transitionend', onOpen);
            });
            sessionStorage.setItem(`tableState-${tableId}`, 'open'); // Simpan state terbuka
        }
    }

    function loadTableState() {
        // Dapatkan semua elemen dengan kelas "custom-table"
        var tables = document.querySelectorAll('.custom-table');

        tables.forEach((table) => {
            var tableId = table.id; // Gunakan ID tabel untuk state unik
            var tableState = sessionStorage.getItem(`tableState-${tableId}`);

            if (tableState === 'closed') {
                // Jika state terakhir adalah tertutup
                table.classList.add('collapsed-custom');
                table.style.height = '0'; // Tinggi menjadi 0
            } else {
                // Jika state terakhir adalah terbuka atau tidak ada state
                table.classList.remove('collapsed-custom');
                table.style.height = 'auto'; // Tinggi menyesuaikan konten
            }

            table.style.visibility = 'visible'; // Pastikan tabel terlihat
        });
    }

    // Jalankan saat halaman dimuat
    document.addEventListener('DOMContentLoaded', loadTableState);
</script>
@endsection
