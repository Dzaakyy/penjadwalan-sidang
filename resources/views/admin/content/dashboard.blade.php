@extends('admin.admin_master')

@section('styles')
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/main.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.15/index.global.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css' rel='stylesheet'>


    <style>
        .fc-toolbar {
            font-size: 1.2em;
            padding: 10px;
        }

        .fc-toolbar .fc-button {
            font-size: 1em;
            padding: 5px 10px;
        }

        .event-detail {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .apexcharts-legend {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .apexcharts-legend-series {
            width: calc(50% - 10px);
            display: flex;
            justify-content: center;
            margin: 5px;
        }

        .apexcharts-legend-series .apexcharts-legend-item {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .apexcharts-legend-series .apexcharts-legend-marker {
            width: 10px;
            height: 10px;
            margin-right: 5px;
        }

        #calender {
            width: 100%;
            min-height: 500px;
            /* Sesuaikan dengan kebutuhan */
            margin: 0 auto;
        }
    </style>
@endsection
@section('admin')
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">

                    @php
                        $activeTab = null;

                        if (Auth::user()->hasRole('pimpinanProdi')) {
                            $activeTab = 'pimpinan';
                        } elseif (
                            Auth::user()->hasRole('pembimbingPkl') ||
                            Auth::user()->hasRole('pembimbingSempro') ||
                            Auth::user()->hasRole('pembimbingTa')
                        ) {
                            $activeTab = 'pembimbing';
                        } elseif (
                            Auth::user()->hasRole('pengujiPkl') ||
                            Auth::user()->hasRole('pengujiSempro') ||
                            Auth::user()->hasRole('pengujiTa')
                        ) {
                            $activeTab = 'penguji';
                        } elseif (Auth::user()->hasRole('mahasiswa')) {
                            $activeTab = 'mahasiswa';
                        } elseif (Auth::user()->hasRole('admin')) {
                            $activeTab = 'admin';
                        }
                    @endphp

                    <ul class="nav nav-tabs" role="tablist">
                        @hasrole('pimpinanProdi')
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'pimpinan' ? 'active' : '' }} ps-0" id="pimpinan-tab"
                                    data-bs-toggle="tab" href="#pimpinan" role="tab" aria-controls="pimpinan"
                                    aria-selected="{{ $activeTab === 'pimpinan' ? 'true' : 'false' }}">Pimpinan Prodi</a>
                            </li>
                        @endhasrole

                        @hasrole('pembimbingPkl|pembimbingSempro|pembimbingTa')
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'pembimbing' ? 'active' : '' }} ps-0 ms-2"
                                    id="pembimbing-tab" data-bs-toggle="tab" href="#pembimbing" role="tab"
                                    aria-controls="pembimbing"
                                    aria-selected="{{ $activeTab === 'pembimbing' ? 'true' : 'false' }}">Pembimbing</a>
                            </li>
                        @endhasrole

                        @hasrole('pengujiPkl|pengujiSempro|pengujiTa')
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'penguji' ? 'active' : '' }} ps-0 ms-2" id="penguji-tab"
                                    data-bs-toggle="tab" href="#penguji" role="tab" aria-controls="penguji"
                                    aria-selected="{{ $activeTab === 'penguji' ? 'true' : 'false' }}">Penguji</a>
                            </li>
                        @endhasrole

                        @hasrole('mahasiswa')
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'mahasiswa' ? 'active' : '' }} ps-0 ms-2"
                                    id="mahasiswa-tab" data-bs-toggle="tab" href="#mahasiswa" role="tab"
                                    aria-controls="mahasiswa"
                                    aria-selected="{{ $activeTab === 'mahasiswa' ? 'true' : 'false' }}">Mahasiswa</a>
                            </li>
                        @endhasrole
                        @hasrole('admin')
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'admin' ? 'active' : '' }} ps-0 ms-2" id="admin-tab"
                                    data-bs-toggle="tab" href="#admin" role="tab" aria-controls="admin"
                                    aria-selected="{{ $activeTab === 'admin' ? 'true' : 'false' }}">Admin</a>
                            </li>
                        @endhasrole
                    </ul>
                </div>


                <div class="tab-content tab-content-basic">
                    @hasrole('pimpinanProdi')
                        <div class="tab-pane fade {{ $activeTab === 'pimpinan' ? 'show active' : '' }}"" id="pimpinan"
                            role="tabpanel" aria-labelledby="pimpinan-tab">
                            <div class="container-fluid">

                                <div class="container-fluid">
                                    <div class="container-fluid">

                                        <h3 class="fw-semibold text-center mb-5">Pimpinan Prodi</h3>

                                        <div class="row">

                                            <div class="col-lg-8 d-flex flex-column align-items-stretch">

                                                <div class="card h-50 w-100">
                                                    <div class="card-body">
                                                        <div
                                                            class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                                                            <div class="mb-3 mb-sm-0"></div>
                                                            <div></div>
                                                        </div>
                                                        <div id="chartKaprodi"></div>
                                                    </div>
                                                </div>


                                                <div class="card h-100 mt-3">
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">PKL</h3>
                                                                <div id="chartkaprodiPKL"></div>
                                                            </div>

                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">Sempro</h3>
                                                                <div id="chartkaprodiSempro"></div>
                                                            </div>


                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">TA</h3>
                                                                <div id="chartkaprodiTA"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-4">
                                                <div class="col-lg-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">PKL</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title mb-2 fw-semibold">Mahasiswa PKL</h5>
                                                                    <h4 class="fw-semibold mb-4">{{ $banyak_pengunggahan_pkl }}
                                                                    </h4>
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title mb-2 fw-semibold">Selesai PKL</h5>
                                                                    <h4 class="fw-semibold mb-2">{{ $banyak_verifikasi_pkl }}
                                                                    </h4>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div
                                                                            class="text-white bg-primary rounded-circle p-1 d-flex align-items-center justify-content-center">
                                                                            <i class="menu-icon mdi mdi-account-tie fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-lg-12 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">Sempro</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="card-title mb-2 fw-semibold">Mahasiswa Sempro
                                                                    </h5>
                                                                    <h4 class="fw-semibold mb-4">
                                                                        {{ $banyak_pengunggahan_sempro }}</h4>
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title mb-2 fw-semibold">Selesai Sempro</h5>
                                                                    <h4 class="fw-semibold">{{ $banyak_verifikasi_sempro }}
                                                                    </h4>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div class="text-white rounded-circle p-1 d-flex align-items-center justify-content-center"
                                                                            style="background-color: #50B498;">
                                                                            <i class="menu-icon mdi mdi-bookshelf fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">TA</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="card-title mb-2 fw-semibold">Mahasiswa TA</h5>
                                                                    <h4 class="fw-semibold mb-4">{{ $banyak_pengunggahan_ta }}
                                                                    </h4>
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title mb-2 fw-semibold">Selesai TA</h5>
                                                                    <h4 class="fw-semibold mb-4">{{ $banyak_verifikasi_ta }}
                                                                    </h4>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div class="text-white rounded-circle p-1 d-flex align-items-center justify-content-center"
                                                                            style="background-color: #be9200;">
                                                                            <i class="menu-icon mdi mdi-school fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5">
                                                <div id="calendarKaprodi"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var calendarEl = document.getElementById('calendarKaprodi');
                                var calendar = new FullCalendar.Calendar(calendarEl, {
                                    themeSystem: 'bootstrap5',
                                    initialView: 'dayGridMonth',
                                    locale: 'id',
                                    events: @json($eventsKaprodi),
                                    eventBackgroundColor: '#213555',
                                    eventContent: function(arg) {
                                        // Custom tampilan event
                                        return {
                                            html: `
                    <div style="white-space: normal; word-wrap: break-word; padding: 2px;">
                        <b>${arg.event.title}</b><br>
                        ${arg.event.extendedProps.room} - ${arg.event.extendedProps.session}
                    </div>
                `
                                        };
                                    }
                                });
                                calendar.render();
                            });
                        </script> --}}
                    @endhasrole


                    @hasrole('admin')
                        <div class="tab-pane fade {{ $activeTab === 'admin' ? 'show active' : '' }}"" id="admin"
                            role="tabpanel" aria-labelledby="admin-tab">
                            <div class="container-fluid">

                                <div class="container-fluid">
                                    <div class="container-fluid">

                                        <h3 class="fw-semibold text-center mb-5">Admin</h3>

                                        <div class="row">

                                            <div class="col-lg-8 d-flex flex-column align-items-stretch">

                                                <div class="card h-50 w-100">
                                                    <div class="card-body">
                                                        <div
                                                            class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                                                            <div class="mb-3 mb-sm-0"></div>
                                                            <div></div>
                                                        </div>
                                                        <div id="chartAdmin"></div>
                                                    </div>
                                                </div>


                                                <div class="card h-100 mt-3">
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">PKL</h3>
                                                                <div id="chartadminPKL"></div>
                                                            </div>

                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">Sempro</h3>
                                                                <div id="chartadminSempro"></div>
                                                            </div>


                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">TA</h3>
                                                                <div id="chartadminTA"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-4">
                                                <div class="col-lg-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">PKL</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title mb-2 fw-semibold">Mahasiswa PKL</h5>
                                                                    <h4 class="fw-semibold mb-4">{{ $MahasiswaPkl }}
                                                                    </h4>
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title mb-2 fw-semibold">Selesai PKL</h5>
                                                                    <h4 class="fw-semibold mb-2">{{ $MahasiswaSelesaiPkl }}
                                                                    </h4>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div
                                                                            class="text-white bg-primary rounded-circle p-1 d-flex align-items-center justify-content-center">
                                                                            <i class="menu-icon mdi mdi-account-tie fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-lg-12 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">Sempro</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="card-title mb-2 fw-semibold">Mahasiswa Sempro
                                                                    </h5>
                                                                    <h4 class="fw-semibold mb-4">
                                                                        {{ $MahasiswaSempro }}</h4>
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title mb-2 fw-semibold">Selesai Sempro</h5>
                                                                    <h4 class="fw-semibold">{{ $MahasiswaSelesaiSempro }}
                                                                    </h4>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div class="text-white rounded-circle p-1 d-flex align-items-center justify-content-center"
                                                                            style="background-color: #50B498;">
                                                                            <i class="menu-icon mdi mdi-bookshelf fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">TA</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="card-title mb-2 fw-semibold">Mahasiswa TA</h5>
                                                                    <h4 class="fw-semibold mb-4">{{ $MahasiswaTA }}
                                                                    </h4>
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title mb-2 fw-semibold">Selesai TA</h5>
                                                                    <h4 class="fw-semibold">{{ $MahasiswaSelesaiTA }}</h4>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div class="text-white rounded-circle p-1 d-flex align-items-center justify-content-center"
                                                                            style="background-color: #be9200;">
                                                                            <i class="menu-icon mdi mdi-school fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-5">
                                            <div id="calendarAdmin"></div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    @endhasrole









                    @hasrole('pembimbingPkl|pembimbingSempro|pembimbingTa')
                        <div class="tab-pane fade {{ $activeTab === 'pembimbing' ? 'show active' : '' }}" id="pembimbing"
                            role="tabpanel" aria-labelledby="pembimbing-tab">
                            <div class="container-fluid">

                                <div class="container-fluid">
                                    <div class="container-fluid">

                                        <h3 class="fw-semibold text-center mb-5">Pembimbing</h3>

                                        <div class="row">
                                            <div class="col-lg-8 d-flex flex-column align-items-stretch">
                                                <div class="card w-100">
                                                    <div class="card-body">
                                                        <div
                                                            class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                                                            <div class="mb-3 mb-sm-0">
                                                            </div>
                                                            <div>
                                                            </div>
                                                        </div>
                                                        <div id="pembimbingChart"></div>
                                                    </div>
                                                </div>

                                                <div class="card h-100 mt-3">
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">PKL</h3>
                                                                <div id="chartPembimbingPKL"></div>
                                                            </div>

                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">Sempro</h3>
                                                                <div id="chartPembimbingSempro"></div>
                                                            </div>
                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">TA</h3>
                                                                <div id="chartPembimbingTA"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-4">
                                                <div class="col-lg-12">

                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">PKL</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="card-title mb-9 fw-semibold">
                                                                        Mahasiswa Diterima</h5>
                                                                    <h4 class="fw-semibold mb-9">
                                                                        {{ $pklDiterimaPembimbing }}
                                                                    </h4>
                                                                    <div class="d-flex align-items-center pb-1">
                                                                    </div>
                                                                    <h5 class="card-title my-9 fw-semibold">Mahasiswa
                                                                        Selesai
                                                                    </h5>
                                                                    <h4 class="fw-semibold">
                                                                        {{ $pklSelesaiPembimbing }}</h4>
                                                                    <div class="d-flex align-items-center pb-1">
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div
                                                                            class="text-white bg-primary rounded-circle p-1 d-flex align-items-center justify-content-center">
                                                                            <i class="menu-icon mdi mdi-account-tie fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="earning"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">Sempro</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="card-title mb-9 fw-semibold">
                                                                        Mahasiswa
                                                                        Diterima</h5>
                                                                    <h4 class="fw-semibold mb-9">
                                                                        {{ $semproDiterimaPembimbing }}
                                                                    </h4>
                                                                    <div class="d-flex align-items-center pb-1">
                                                                    </div>
                                                                    <h5 class="card-title my-9 fw-semibold">
                                                                        Mahasiswa Selesai</h5>
                                                                    <h4 class="fw-semibold">
                                                                        {{ $semproSelesaiPembimbing }}</h4>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div class="text-white rounded-circle p-1 d-flex align-items-center justify-content-center"
                                                                            style="background-color: #50B498;">
                                                                            <i class="menu-icon mdi mdi-bookshelf fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="earning"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">TA</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="card-title mb-9 fw-semibold">
                                                                        Mahasiswa</h5>
                                                                    <h4 class="fw-semibold mb-9">
                                                                        {{ $taDiterimaPembimbing }}
                                                                    </h4>
                                                                    <div class="d-flex align-items-center pb-1">
                                                                    </div>
                                                                    <h5 class="card-title my-9 fw-semibold">
                                                                        Mahasiswa Selesai</h5>
                                                                    <h4 class="fw-semibold">
                                                                        {{ $taSelesaiPembimbing }}</h4>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div class="text-white rounded-circle p-1 d-flex align-items-center justify-content-center"
                                                                            style="background-color: #be9200;">
                                                                            <i class="menu-icon mdi mdi-bookshelf fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="earning"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5">
                                                <div id="calendarPembimbing"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endhasrole



                    @hasrole('pengujiPkl|pengujiSempro|pengujiTa')
                        <div class="tab-pane fade {{ $activeTab === 'penguji' ? 'show active' : '' }}" id="penguji"
                            role="tabpanel" aria-labelledby="penguji-tab">
                            <div class="container-fluid">
                                <div class="container-fluid">
                                    <div class="container-fluid">
                                        <h3 class="fw-semibold text-center mb-5">Penguji</h3>
                                        <div class="row">
                                            <div class="col-lg-8 d-flex flex-column align-items-stretch">
                                                <div class="card h-50 w-100">
                                                    <div class="card-body">
                                                        <div
                                                            class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                                                            <div class="mb-3 mb-sm-0"></div>
                                                            <div></div>
                                                        </div>
                                                        <div id="pengujiChart"></div>
                                                    </div>
                                                </div>
                                                <div class="card h-100 mt-3">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">PKL</h3>
                                                                <div id="chartPengujiPKL"></div>
                                                            </div>
                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">Sempro</h3>
                                                                <div id="chartPengujiSempro"></div>
                                                            </div>
                                                            <div class="col-lg-4 chart-container mt-4">
                                                                <h3 class="text-center">TA</h3>
                                                                <div id="chartPengujiTA"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="col-lg-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">PKL</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="card-title mb-9 fw-semibold">Mahasiswa Diterima
                                                                    </h5>
                                                                    <h4 class="fw-semibold mb-9">{{ $pklDiterimaPenguji }}
                                                                    </h4>
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title my-9 fw-semibold">Mahasiswa Selesai
                                                                    </h5>
                                                                    <h4 class="fw-semibold">{{ $pklSelesaiPenguji }}</h4>
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div
                                                                            class="text-white bg-primary rounded-circle p-1 d-flex align-items-center justify-content-center">
                                                                            <i class="menu-icon mdi mdi-account-tie fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="earning"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">Sempro</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="card-title mb-9 fw-semibold">Mahasiswa Diterima
                                                                    </h5>
                                                                    <h4 class="fw-semibold mb-9">{{ $semproDiterimaPenguji }}
                                                                    </h4>
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title my-9 fw-semibold">Mahasiswa Selesai
                                                                    </h5>
                                                                    <h4 class="fw-semibold">{{ $semproSelesaiPenguji }}</h4>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div class="text-white rounded-circle p-1 d-flex align-items-center justify-content-center"
                                                                            style="background-color: #50B498;">
                                                                            <i class="menu-icon mdi mdi-bookshelf fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="earning"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
                                                                <div class="col-12 text-center mb-4">
                                                                    <h3 class="card-title mb-2 fw-semibold">TA</h3>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="card-title mb-9 fw-semibold">Mahasiswa</h5>
                                                                    <h4 class="fw-semibold mb-9">{{ $taDiterimaPenguji }}</h4>
                                                                    <div class="d-flex align-items-center pb-1"></div>
                                                                    <h5 class="card-title my-9 fw-semibold">Mahasiswa Selesai
                                                                    </h5>
                                                                    <h4 class="fw-semibold">{{ $taSelesaiPenguji }}</h4>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="d-flex justify-content-end">
                                                                        <div class="text-white rounded-circle p-1 d-flex align-items-center justify-content-center"
                                                                            style="background-color: #be9200;">
                                                                            <i class="menu-icon mdi mdi-bookshelf fs-1"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="earning"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5">
                                                <div id="calendarPenguji"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var calendarEl = document.getElementById('calendarPenguji');
                                var calendar = new FullCalendar.Calendar(calendarEl, {
                                    themeSystem: 'bootstrap5',
                                    initialView: 'dayGridMonth',
                                    locale: 'id',
                                    events: @json($eventsPenguji),
                                    eventBackgroundColor: '#213555',
                                    eventContent: function(arg) {
                                        return {
                                            html: `
                                            <div style="white-space: normal; word-wrap: break-word; padding: 2px;">
                                                <b>${arg.event.title}</b><br>
                                                ${arg.event.extendedProps.room} - ${arg.event.extendedProps.session}
                                            </div>
                                        `
                                        };
                                    }
                                });
                                calendar.render();
                            });
                        </script> --}}
                    @endhasrole

                    @hasrole('mahasiswa')
                        <div class="tab-pane fade {{ $activeTab === 'mahasiswa' ? 'show active' : '' }}" id="mahasiswa"
                            role="tabpanel" aria-labelledby="mahasiswa-tab">
                            <div class="container-fluid">
                                <div class="container-fluid">
                                    <div class="container-fluid">
                                        <h3 class="fw-semibold text-center mb-5">Mahasiswa</h3>
                                        <div class="row">
                                            <div id="calendarMahasiswa"></div>
                                        </div>
                                        <div id="earning"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var calendarEl = document.getElementById('calender');
                                var calendar = new FullCalendar.Calendar(calendarEl, {
                                    themeSystem: 'bootstrap5',
                                    initialView: 'dayGridMonth',
                                    locale: 'id',
                                    events: @json($eventsMahasiswa),
                                    eventBackgroundColor: '#213555',

                                    eventContent: function(info) {
                                        var session = info.event.extendedProps.session || 'No session info';
                                        var room = info.event.extendedProps.room || 'No room info';

                                        var content = document.createElement('div');
                                        content.innerHTML = `
                        <strong>${info.event.title}</strong><br>
                       ${room} (${session})
                    `;

                                        content.style.whiteSpace = 'normal';
                                        content.style.wordBreak = 'break-word';

                                        return {
                                            domNodes: [content]
                                        };
                                    },
                                });
                                calendar.render();
                            });
                        </script> --}}
                    @endhasrole




                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.15/index.global.min.js"></script>
    {{-- <script src='fullcalendar/dist/index.global.js'></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js"></script> --}}
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.15/index.global.min.js'></script>
    <script>
  document.addEventListener('DOMContentLoaded', function() {
    function initCalendar(calendarId, eventsPembimbing, eventsPenguji, showRole = false) {
        var calendarEl = document.getElementById(calendarId);
        if (calendarEl) {
            var combinedEvents = eventsPembimbing.map(event => {
                return {
                    ...event,
                    title: showRole ? `(${event.role}) ${event.title}` : event.title,
                    backgroundColor: '#213555', // Warna background event
                    borderColor: '#213555' // Warna border event
                };
            }).concat(eventsPenguji.map(event => {
                return {
                    ...event,
                    title: showRole ? `(${event.role}) ${event.title}` : event.title,
                    backgroundColor: '#213555', // Warna background event
                    borderColor: '#213555' // Warna border event
                };
            }));

            var calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: 'bootstrap',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                dayMaxEvents: true,
                locale: 'id',
                events: combinedEvents,
                eventOrder: 'start',
                eventOverlap: false, // Mencegah event bertumpuk secara horizontal
                eventContent: function(arg) {
                    if (arg.view.type === 'listMonth') {
                        return {
                            html: `
                                <div style="white-space: normal; word-wrap: break-word; padding: 2px;">
                                    <b>${arg.event.title}</b><br>
                                    ${arg.event.extendedProps.room} - ${arg.event.extendedProps.session}
                                </div>
                            `
                        };
                    } else {
                        return {
                            html: `
                                <div style="white-space: normal; word-wrap: break-word; padding: 2px; background-color: #213555; color: white; border-radius: 5px;">
                                    <b>${arg.event.title}</b><br>
                                    ${arg.event.extendedProps.room} - ${arg.event.extendedProps.session}
                                </div>
                            `
                        };
                    }
                },
                moreLinkContent: function(args) {
                    return {
                        html: `
                            <span class="badge custom-more-link">
                                ${args.num} Data
                            </span>
                        `
                    };
                },
                slotDuration: '00:30:00',
                slotMinTime: '07:00:00',
                slotMaxTime: '23:00:00',
                views: {
                    timeGridWeek: {
                        eventMaxStack: 0,
                    }
                }
            });
            calendar.render();
        }
    }

    var activeTab = "{{ $activeTab }}";
    if (activeTab === 'pimpinan') {
        initCalendar('calendarKaprodi', @json($eventsKaprodi ?? []), []);
    } else if (activeTab === 'penguji') {
        initCalendar('calendarPenguji', @json($eventsPembimbing ?? []), @json($eventsPenguji ?? []), true);
    } else if (activeTab === 'pembimbing') {
        initCalendar('calendarPembimbing', @json($eventsPembimbing ?? []), @json($eventsPenguji ?? []), true);
    } else if (activeTab === 'mahasiswa') {
        initCalendar('calendarMahasiswa', @json($eventsMahasiswa ?? []), []);
    } else if (activeTab === 'admin') {
        initCalendar('calendarAdmin', @json($eventsAdmin ?? []), []);
    }

    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr("href");
        if (target === '#pimpinan') {
            initCalendar('calendarKaprodi', @json($eventsKaprodi ?? []), []);
        } else if (target === '#penguji') {
            initCalendar('calendarPenguji', @json($eventsPembimbing ?? []), @json($eventsPenguji ?? []), true);
        } else if (target === '#pembimbing') {
            initCalendar('calendarPembimbing', @json($eventsPembimbing ?? []), @json($eventsPenguji ?? []), true);
        } else if (target === '#mahasiswa') {
            initCalendar('calendarMahasiswa', @json($eventsMahasiswa ?? []), []);
        } else if (target === '#admin') {
            initCalendar('calendarAdmin', @json($eventsAdmin ?? []), []);
        }
    });
});

        document.addEventListener('DOMContentLoaded', function() {

            // Kaprodi
            // var banyakPKLBelum = @json($pklBelumDiterima ?? 0);
            var banyakPKL = @json($banyak_pengunggahan_pkl ?? 0);
            var banyakSelesaiPKL = @json($banyak_verifikasi_pkl ?? 0);
            var banyakAjukanJudul = @json($banyak_pengunggahan_sempro ?? 0);
            var banyakSelesaiSempro = @json($banyak_verifikasi_sempro ?? 0);
            var banyakAjukanTA = @json($banyak_pengunggahan_ta ?? 0);
            var banyakSelesaiTA = @json($banyak_verifikasi_ta ?? 0);
            var percentUploadedPKL = @json($percentUploadedPKL ?? 0);
            var percentVerifiedPKL = @json($percentVerifiedPKL ?? 0);
            var percentUploadedSempro = @json($percentUploadedSempro ?? 0);
            var percentVerifiedSempro = @json($percentVerifiedSempro ?? 0);
            var percentUploadedTA = @json($percentUploadedTa ?? 0);
            var percentVerifiedTA = @json($percentVerifiedTa ?? 0);

            // Admin
            var percentpklMahasiswaAdmin = @json($MahasiswaPkl ?? 0);
            var percentpklSelesaiAdmin = @json($MahasiswaSelesaiPkl ?? 0);
            var percentsemproMahasiswaAdmin = @json($MahasiswaSempro ?? 0);
            var percentsemproSelesaiAdmin = @json($MahasiswaSelesaiSempro ?? 0);
            var percenttaMahasiswaAdmin = @json($MahasiswaTA ?? 0);
            var percenttaSelesaiAdmin = @json($MahasiswaSelesaiTA ?? 0);

            // Penguji
            var percentpklDiterimaPenguji = @json($pklDiterimaPenguji ?? 0);
            var percentpklSelesaiPenguji = @json($pklSelesaiPenguji ?? 0);
            var percentsemproDiterimaPenguji = @json($semproDiterimaPenguji ?? 0);
            var percentsemproSelesaiPenguji = @json($semproSelesaiPenguji ?? 0);
            var percenttaDiterimaPenguji = @json($taDiterimaPenguji ?? 0);
            var percenttaSelesaiPenguji = @json($taSelesaiPenguji ?? 0);

            // Pembimbing
            var percentpklDiterimaPembimbing = @json($pklDiterimaPembimbing ?? 0);
            var percentpklSelesaiPembimbing = @json($pklSelesaiPembimbing ?? 0);
            var percentsemproDiterimaPembimbing = @json($semproDiterimaPembimbing ?? 0);
            var percentsemproSelesaiPembimbing = @json($semproSelesaiPembimbing ?? 0);
            var percenttaDiterimaPembimbing = @json($taDiterimaPembimbing ?? 0);
            var percenttaSelesaiPembimbing = @json($taSelesaiPembimbing ?? 0);

            // chart PIMPINAN PRODI
            var barChartKaprodi = {
                series: [{
                        name: "Mahasiswa PKL",
                        data: [banyakPKL]
                    },
                    {
                        name: "Selesai PKL",
                        data: [banyakSelesaiPKL]
                    },
                    {
                        name: "Mahasiswa",
                        data: [banyakAjukanJudul]
                    },
                    {
                        name: "Selesai Sempro",
                        data: [banyakSelesaiSempro]
                    },
                    {
                        name: "Mahasiswa TA",
                        data: [banyakAjukanTA]
                    },
                    {
                        name: "Selesai TA",
                        data: [banyakSelesaiTA]
                    }
                ],
                chart: {
                    type: "bar",
                    height: 300,
                    offsetX: -15,
                    toolbar: {
                        show: true
                    },
                    foreColor: "#adb0bb",
                    fontFamily: 'inherit',
                    sparkline: {
                        enabled: false
                    },
                },
                colors: ["#5D87FF", "#49BEFF", "#50B498", "#9CDBA6", "#be9200", "#FADA7A"],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "50%",
                        borderRadius: [6],
                        borderRadiusApplication: 'end',
                        borderRadiusWhenStacked: 'all'
                    },
                },
                markers: {
                    size: 0
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'left',
                    floating: false,
                    offsetY: 10,
                    offsetX: 0,
                    itemMargin: {
                        horizontal: 2,
                        vertical: 5
                    },
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 5
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName;
                    }
                },
                grid: {
                    borderColor: "rgba(0,0,0,0.1)",
                    strokeDashArray: 3,
                    xaxis: {
                        lines: {
                            show: false,
                        },
                    },
                },
                xaxis: {
                    type: "category",
                    categories: ["Data"],
                    labels: {
                        style: {
                            cssClass: "grey--text lighten-2--text fill-color"
                        },
                    },
                },
                yaxis: {
                    show: true,
                    min: 0,
                    max: Math.max(banyakPKL, banyakSelesaiPKL,
                        banyakAjukanJudul, banyakSelesaiSempro, banyakAjukanTA, banyakSelesaiTA) + 1,
                    tickAmount: 4,
                    labels: {
                        style: {
                            cssClass: "grey--text lighten-2--text fill-color",
                        },
                    },
                },
                stroke: {
                    show: true,
                    width: 3,
                    lineCap: "butt",
                    colors: ["transparent"],
                },
                tooltip: {
                    theme: "light"
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 3,
                            }
                        },
                    }
                }]
            };


            // chart Admin
            var barChartAdmin = {
                series: [{
                        name: "Mahasiswa PKL",
                        data: [percentpklMahasiswaAdmin]
                    },
                    {
                        name: "Selesai PKL",
                        data: [percentpklSelesaiAdmin]
                    },
                    {
                        name: "Mahasiswa",
                        data: [percentsemproMahasiswaAdmin]
                    },
                    {
                        name: "Selesai Sempro",
                        data: [percentsemproSelesaiAdmin]
                    },
                    {
                        name: "Mahasiswa TA",
                        data: [percenttaMahasiswaAdmin]
                    },
                    {
                        name: "Selesai TA",
                        data: [percenttaSelesaiAdmin]
                    }
                ],
                chart: {
                    type: "bar",
                    height: 300,
                    offsetX: -15,
                    toolbar: {
                        show: true
                    },
                    foreColor: "#adb0bb",
                    fontFamily: 'inherit',
                    sparkline: {
                        enabled: false
                    },
                },
                colors: ["#5D87FF", "#49BEFF", "#50B498", "#9CDBA6", "#be9200", "#FADA7A"],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "50%",
                        borderRadius: [6],
                        borderRadiusApplication: 'end',
                        borderRadiusWhenStacked: 'all'
                    },
                },
                markers: {
                    size: 0
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'left',
                    floating: false,
                    offsetY: 10,
                    offsetX: 0,
                    itemMargin: {
                        horizontal: 2,
                        vertical: 5
                    },
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 5
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName;
                    }
                },
                grid: {
                    borderColor: "rgba(0,0,0,0.1)",
                    strokeDashArray: 3,
                    xaxis: {
                        lines: {
                            show: false,
                        },
                    },
                },
                xaxis: {
                    type: "category",
                    categories: ["Data"],
                    labels: {
                        style: {
                            cssClass: "grey--text lighten-2--text fill-color"
                        },
                    },
                },
                yaxis: {
                    show: true,
                    min: 0,
                    max: Math.max(percentpklMahasiswaAdmin, percentpklSelesaiAdmin,
                        percentsemproMahasiswaAdmin, percentsemproSelesaiAdmin, percenttaMahasiswaAdmin,
                        percenttaSelesaiAdmin) + 1,
                    tickAmount: 4,
                    labels: {
                        style: {
                            cssClass: "grey--text lighten-2--text fill-color",
                        },
                    },
                },
                stroke: {
                    show: true,
                    width: 3,
                    lineCap: "butt",
                    colors: ["transparent"],
                },
                tooltip: {
                    theme: "light"
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 3,
                            }
                        },
                    }
                }]
            };

            // chart PENGUJI
            var barChartPenguji = {
                series: [{
                        name: "Mahasiswa PKL",
                        data: [percentpklDiterimaPenguji]
                    },
                    {
                        name: "Selesai PKL",
                        data: [percentpklSelesaiPenguji]
                    },
                    {
                        name: "Mahasiswa Sempro",
                        data: [percentsemproDiterimaPenguji]
                    },
                    {
                        name: "Selesai Sempro",
                        data: [percentsemproSelesaiPenguji]
                    },
                    {
                        name: "Mahasiswa TA",
                        data: [percenttaDiterimaPenguji]
                    },
                    {
                        name: "Selesai TA",
                        data: [percenttaSelesaiPenguji]
                    }
                ],
                chart: {
                    type: "bar",
                    height: 300,
                    offsetX: -15,
                    toolbar: {
                        show: true
                    },
                    foreColor: "#adb0bb",
                    fontFamily: 'inherit',
                    sparkline: {
                        enabled: false
                    },
                },
                colors: ["#5D87FF", "#49BEFF", "#50B498", "#9CDBA6", "#be9200", "#FADA7A"],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "50%",
                        borderRadius: [6],
                        borderRadiusApplication: 'end',
                        borderRadiusWhenStacked: 'all'
                    },
                },
                markers: {
                    size: 0
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'left',
                    floating: false,
                    offsetY: 10,
                    offsetX: 0,
                    itemMargin: {
                        horizontal: 2,
                        vertical: 5
                    },
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 5
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName;
                    }
                },
                grid: {
                    borderColor: "rgba(0,0,0,0.1)",
                    strokeDashArray: 3,
                    xaxis: {
                        lines: {
                            show: false,
                        },
                    },
                },
                xaxis: {
                    type: "category",
                    categories: ["Data"],
                    labels: {
                        style: {
                            cssClass: "grey--text lighten-2--text fill-color"
                        },
                    },
                },
                yaxis: {
                    show: true,
                    min: 0,
                    max: Math.max(percentpklDiterimaPenguji,
                        percentpklSelesaiPenguji, percentsemproDiterimaPenguji,
                        percentsemproSelesaiPenguji, percenttaDiterimaPenguji,
                        percenttaSelesaiPenguji) + 1,
                    tickAmount: 4,
                    labels: {
                        style: {
                            cssClass: "grey--text lighten-2--text fill-color",
                        },
                    },
                },
                stroke: {
                    show: true,
                    width: 3,
                    lineCap: "butt",
                    colors: ["transparent"],
                },
                tooltip: {
                    theme: "light"
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 3,
                            }
                        },
                    }
                }]
            };

            // chart PEMBIMBING
            var barChartPembimbing = {
                series: [{
                        name: "Mahasiswa PKL",
                        data: [percentpklDiterimaPembimbing]
                    },
                    {
                        name: "Selesai PKL",
                        data: [percentpklSelesaiPembimbing]
                    },
                    {
                        name: "Mahasiswa Sempro",
                        data: [percentsemproDiterimaPembimbing]
                    },
                    {
                        name: "Selesai Sempro",
                        data: [percentsemproSelesaiPembimbing]
                    },
                    {
                        name: "Mahasiswa",
                        data: [percenttaDiterimaPembimbing]
                    },
                    {
                        name: "Selesai TA",
                        data: [percenttaSelesaiPembimbing]
                    }
                ],
                chart: {
                    type: "bar",
                    height: 300,
                    offsetX: -15,
                    toolbar: {
                        show: true
                    },
                    foreColor: "#adb0bb",
                    fontFamily: 'inherit',
                    sparkline: {
                        enabled: false
                    },
                },
                colors: ["#5D87FF", "#49BEFF", "#50B498", "#9CDBA6", "#be9200", "#FADA7A"],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "50%",
                        borderRadius: [6],
                        borderRadiusApplication: 'end',
                        borderRadiusWhenStacked: 'all'
                    },
                },
                markers: {
                    size: 0
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'left',
                    floating: false,
                    offsetY: 10,
                    offsetX: 0,
                    itemMargin: {
                        horizontal: 2,
                        vertical: 5
                    },
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 5
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName;
                    }
                },
                grid: {
                    borderColor: "rgba(0,0,0,0.1)",
                    strokeDashArray: 3,
                    xaxis: {
                        lines: {
                            show: false,
                        },
                    },
                },
                xaxis: {
                    type: "category",
                    categories: ["Data"],
                    labels: {
                        style: {
                            cssClass: "grey--text lighten-2--text fill-color"
                        },
                    },
                },
                yaxis: {
                    show: true,
                    min: 0,
                    max: Math.max(percentpklDiterimaPembimbing, percentpklSelesaiPembimbing,
                        percentsemproDiterimaPembimbing, percentsemproSelesaiPembimbing,
                        percenttaDiterimaPembimbing, percenttaSelesaiPembimbing) + 1,
                    tickAmount: 4,
                    labels: {
                        style: {
                            cssClass: "grey--text lighten-2--text fill-color",
                        },
                    },
                },
                stroke: {
                    show: true,
                    width: 3,
                    lineCap: "butt",
                    colors: ["transparent"],
                },
                tooltip: {
                    theme: "light"
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 3,
                            }
                        },
                    }
                }]
            };

            var kaprodiChart = new ApexCharts(document.querySelector("#chartKaprodi"), barChartKaprodi);
            var adminChart = new ApexCharts(document.querySelector("#chartAdmin"), barChartAdmin);
            var pengujiChart = new ApexCharts(document.querySelector("#pengujiChart"), barChartPenguji);
            var pembimbingChart = new ApexCharts(document.querySelector("#pembimbingChart"), barChartPembimbing);
            kaprodiChart.render();
            adminChart.render();
            pengujiChart.render();
            pembimbingChart.render();


            // KAPRODI
            var commonoptionsPKL = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return banyakPKL + ' data';
                            } else if (seriesIndex === 1) {
                                return banyakSelesaiPKL + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#5D87FF", "#49BEFF"]
            };

            var optionsPKL = {
                ...commonoptionsPKL,
                series: [banyakPKL, banyakSelesaiPKL],
                labels: ['Mahasiswa', 'Selesai']
            };

            var chartPKL = new ApexCharts(document.querySelector("#chartkaprodiPKL"), optionsPKL);
            chartPKL.render();

            var commonoptionsSempro = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return banyakAjukanJudul + ' data';
                            } else if (seriesIndex === 1) {
                                return banyakSelesaiSempro + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#50B498", "#9CDBA6"]
            };

            var optionsSempro = {
                ...commonoptionsSempro,
                series: [banyakAjukanJudul, banyakSelesaiSempro],
                labels: ['Mahasiswa', 'Selesai']
            };

            var chartSempro = new ApexCharts(document.querySelector("#chartkaprodiSempro"), optionsSempro);
            chartSempro.render();

            var commonoptionsTA = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return banyakAjukanTA + ' data';
                            } else if (seriesIndex === 1) {
                                return banyakSelesaiTA + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#be9200", "#FADA7A"]
            };

            var optionsTA = {
                ...commonoptionsTA,
                series: [banyakAjukanTA, banyakSelesaiTA],
                labels: ['Mahasiswa', 'Selesai']
            };

            // Render UAS Chart
            var chartTA = new ApexCharts(document.querySelector("#chartkaprodiTA"), optionsTA);
            chartTA.render();


            // Admin
            var commonoptionsPKLAdmin = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return percentpklMahasiswaAdmin + ' data';
                            } else if (seriesIndex === 1) {
                                return percentpklSelesaiAdmin + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#5D87FF", "#49BEFF"]
            };

            var optionsPKLAdmin = {
                ...commonoptionsPKLAdmin,
                series: [percentpklMahasiswaAdmin, percentpklSelesaiAdmin],
                labels: ['Mahasiswa', 'Selesai']
            };

            var chartPKLAdmin = new ApexCharts(document.querySelector("#chartadminPKL"), optionsPKLAdmin);
            chartPKLAdmin.render();

            var commonoptionsSemproAdmin = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return percentsemproMahasiswaAdmin + ' data';
                            } else if (seriesIndex === 1) {
                                return percentsemproSelesaiAdmin + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#50B498", "#9CDBA6"]
            };

            var optionsSemproAdmin = {
                ...commonoptionsSemproAdmin,
                series: [percentsemproMahasiswaAdmin, percentsemproSelesaiAdmin],
                labels: ['Mahasiswa', 'Selesai']
            };

            var chartSemproAdmin = new ApexCharts(document.querySelector("#chartadminSempro"), optionsSemproAdmin);
            chartSemproAdmin.render();

            var commonoptionsTAAdmin = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return percenttaMahasiswaAdmin + ' data';
                            } else if (seriesIndex === 1) {
                                return percenttaSelesaiAdmin + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#be9200", "#FADA7A"]
            };

            var optionsTAAdmin = {
                ...commonoptionsTAAdmin,
                series: [percenttaMahasiswaAdmin, percenttaSelesaiAdmin],
                labels: ['Mahasiswa', 'Selesai']
            };

            var chartTAAdmin = new ApexCharts(document.querySelector("#chartadminTA"), optionsTAAdmin);
            chartTAAdmin.render();

            // Pembimbing
            var pembimbingPKL = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return percentpklDiterimaPembimbing + ' data';
                            } else if (seriesIndex === 1) {
                                return percentpklSelesaiPembimbing + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#001A6E", "#5D87FF", "#49BEFF"]
            };

            var optionspembimbingPKL = {
                ...pembimbingPKL,
                series: [percentpklDiterimaPembimbing, percentpklSelesaiPembimbing],
                labels: ['Mahasiswa', 'Selesai']
            };

            var chartpembimbingPKL = new ApexCharts(document.querySelector("#chartPembimbingPKL"),
                optionspembimbingPKL);
            chartpembimbingPKL.render();

            var pembimbingSempro = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return percentsemproDiterimaPembimbing + ' data';
                            } else if (seriesIndex === 1) {
                                return percentsemproSelesaiPembimbing + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#50B498", "#9CDBA6"]
            };

            var optionspembimbingSempro = {
                ...pembimbingSempro,
                series: [percentsemproDiterimaPembimbing, percentsemproSelesaiPembimbing],
                labels: ['Mahasiswa', 'Selesai']
            };

            var chartpembimbingSempro = new ApexCharts(document.querySelector("#chartPembimbingSempro"),
                optionspembimbingSempro);
            chartpembimbingSempro.render();

            var pembimbingTA = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return percenttaDiterimaPembimbing + ' data';
                            } else if (seriesIndex === 1) {
                                return percenttaSelesaiPembimbing + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#be9200", "#FADA7A"]
            };

            var optionspembimbingTA = {
                ...pembimbingTA,
                series: [percenttaDiterimaPembimbing, percenttaSelesaiPembimbing],
                labels: ['Mahasiswa', 'Selesai']
            };

            var chartpembimbingTA = new ApexCharts(document.querySelector("#chartPembimbingTA"),
                optionspembimbingTA);
            chartpembimbingTA.render();


            // Penguji
            var pengujiPKL = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return percentpklDiterimaPenguji + ' data';
                            } else if (seriesIndex === 1) {
                                return percentpklSelesaiPenguji + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#001A6E", "#5D87FF", "#49BEFF"]
            };

            var optionspengujiPKL = {
                ...pengujiPKL,
                series: [percentpklDiterimaPenguji, percentpklSelesaiPenguji],
                labels: ['Mahasiswa', 'Selesai']
            };


            var chartpengujiPKL = new ApexCharts(document.querySelector("#chartPengujiPKL"), optionspengujiPKL);
            chartpengujiPKL.render();

            var pengujiSempro = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return percentsemproDiterimaPenguji + ' data';
                            } else if (seriesIndex === 1) {
                                return percentsemproSelesaiPenguji + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#50B498", "#9CDBA6"]
            };

            var optionspengujiSempro = {
                ...pengujiSempro,
                series: [percentsemproDiterimaPenguji, percentsemproSelesaiPenguji],
                labels: ['Mahasiswa', 'Selesai']
            };

            var chartpengujiSempro = new ApexCharts(document.querySelector("#chartPengujiSempro"),
                optionspengujiSempro);
            chartpengujiSempro.render();

            var pengujiTA = {
                chart: {
                    type: 'donut',
                    height: 300,
                    width: '100%'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 0) {
                                return percenttaDiterimaPenguji + ' data';
                            } else if (seriesIndex === 1) {
                                return percenttaSelesaiPenguji + ' data';
                            }
                            return value + ' data';
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                colors: ["#be9200", "#FADA7A"]
            };

            var optionspengujiTA = {
                ...pengujiTA,
                series: [percenttaDiterimaPenguji, percenttaSelesaiPenguji],
                labels: ['Mahasiswa', 'Selesai']
            };

            var chartpengujiTA = new ApexCharts(document.querySelector("#chartPengujiTA"),
                optionspengujiTA);
            chartpengujiTA.render();
        });
    </script>
@endsection
