@extends('admin.admin_master')

@section('styles')
    <style>
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
    </style>
@endsection
@section('admin')
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">

                    @php
                        $activeTab = null;

                        // Tentukan tab aktif berdasarkan urutan prioritas role
                        if (Auth::user()->hasRole('pimpinanProdi')) {
                            $activeTab = 'pimpinan';
                        } elseif (Auth::user()->hasRole('pembimbingPkl') || Auth::user()->hasRole('pembimbingSempro')) {
                            $activeTab = 'pembimbing';
                        } elseif (Auth::user()->hasRole('pengujiPkl') || Auth::user()->hasRole('pengujiSempro')) {
                            $activeTab = 'penguji';
                        } elseif (Auth::user()->hasRole('mahasiswa')) {
                            $activeTab = 'mahasiswa';
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

                        @hasrole('pembimbingPkl|pembimbingSempro')
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === 'pembimbing' ? 'active' : '' }} ps-0 ms-2"
                                    id="pembimbing-tab" data-bs-toggle="tab" href="#pembimbing" role="tab"
                                    aria-controls="pembimbing"
                                    aria-selected="{{ $activeTab === 'pembimbing' ? 'true' : 'false' }}">Pembimbing</a>
                            </li>
                        @endhasrole

                        @hasrole('pengujiPkl|pengujiSempro')
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
                                            <div class="col-lg-8 d-flex align-items-stretch">
                                                <div class="card w-100">
                                                    <div class="card-body">
                                                        <div
                                                            class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                                                            <div class="mb-3 mb-sm-0">
                                                            </div>
                                                            <div>

                                                            </div>
                                                        </div>
                                                        <div id="chartKaprodi"></div>
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
                                                                    <h5 class="card-title mb-2 fw-semibold">Belum Terdaftar</h5>
                                                                    <h5 class="fw-semibold mb-4">{{ $pklBelumDiterima }}</h5>
                                                                    <div class="d-flex align-items-center pb-1"></div>

                                                                    <h5 class="card-title mb-2 fw-semibold">Sudah Terdaftar</h5>
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
                                                                    <h5 class="card-title mb-2 fw-semibold">Mahasiswa
                                                                        Sempro </h5>
                                                                    <h4 class="fw-semibold mb-4">
                                                                        {{ $banyak_pengunggahan_sempro }}
                                                                    </h4>
                                                                    <div class="d-flex align-items-center pb-1">
                                                                    </div>
                                                                    <h5 class="card-title mb-2 fw-semibold">Selesai
                                                                        Sempro</h5>
                                                                    <h4 class="fw-semibold">
                                                                        {{ $banyak_verifikasi_sempro }}</h4>
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
                                            </div>
                                        </div>

                                        <div class="card mt-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- RPS Chart -->
                                                    <div class="col-lg-6 chart-container">
                                                        <h3 class="text-center">PKL</h3>
                                                        <div id="chartkaprodiPKL"></div>
                                                    </div>

                                                    <!-- UAS Chart -->
                                                    <div class="col-lg-6 chart-container">
                                                        <h3 class="text-center">Sempro</h3>
                                                        <div id="chartkaprodiSempro"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    @endhasrole



                    @hasrole('pembimbingPkl|pembimbingSempro')
                        <div class="tab-pane fade {{ $activeTab === 'pembimbing' ? 'show active' : '' }}" id="pembimbing"
                            role="tabpanel" aria-labelledby="pembimbing-tab">
                            <div class="container-fluid">

                                <div class="container-fluid">
                                    <div class="container-fluid">

                                        <h3 class="fw-semibold text-center mb-5">Pembimbing</h3>

                                        <div class="row">
                                            <div class="col-lg-8 d-flex align-items-stretch">
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
                                            </div>


                                            <div class="col-lg-4">
                                                <div class="col-lg-12">

                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row align-items-start">
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
                                            </div>
                                        </div>

                                        <div class="card mt-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- RPS Chart -->
                                                    <div class="col-lg-6 chart-container">
                                                        <h3 class="text-center">PKL</h3>
                                                        <div id="chartPembimbingPKL"></div>
                                                    </div>

                                                    <!-- UAS Chart -->
                                                    <div class="col-lg-6 chart-container">
                                                        <h3 class="text-center">Sempro</h3>
                                                        <div id="chartPembimbingSempro"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                @endhasrole



                @hasrole('pengujiPkl|pengujiSempro')
                    <div class="tab-pane fade {{ $activeTab === 'penguji' ? 'show active' : '' }}" id="penguji"
                        role="tabpanel" aria-labelledby="penguji-tab">
                        <div class="container-fluid">

                            <div class="container-fluid">
                                <div class="container-fluid">

                                    <h3 class="fw-semibold text-center mb-5">Penguji</h3>

                                    <div class="row">
                                        <div class="col-lg-8 d-flex align-items-stretch">
                                            <div class="card w-100">
                                                <div class="card-body">
                                                    <div
                                                        class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                                                        <div class="mb-3 mb-sm-0">
                                                        </div>
                                                        <div>

                                                        </div>
                                                    </div>
                                                    <div id="pengujiChart"></div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-4">
                                            <div class="col-lg-12">

                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row align-items-start">
                                                            <div class="col-8">
                                                                <h5 class="card-title mb-9 fw-semibold">
                                                                    Mahasiswa Diterima</h5>
                                                                <h4 class="fw-semibold mb-9">
                                                                    {{ $pklDiterimaPenguji }}
                                                                </h4>
                                                                <div class="d-flex align-items-center pb-1">
                                                                </div>
                                                                <h5 class="card-title my-9 fw-semibold">Mahasiswa
                                                                    Selesai
                                                                </h5>
                                                                <h4 class="fw-semibold">
                                                                    {{ $pklSelesaiPenguji }}</h4>
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
                                                            <div class="col-8">
                                                                <h5 class="card-title mb-9 fw-semibold">
                                                                    Mahasiswa
                                                                    Diterima</h5>
                                                                <h4 class="fw-semibold mb-9">
                                                                    {{ $semproDiterimaPenguji }}
                                                                </h4>
                                                                <div class="d-flex align-items-center pb-1">
                                                                </div>
                                                                <h5 class="card-title my-9 fw-semibold">
                                                                    Mahasiswa Selesai</h5>
                                                                <h4 class="fw-semibold">
                                                                    {{ $semproSelesaiPenguji }}</h4>
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
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- RPS Chart -->
                                                <div class="col-lg-6 chart-container">
                                                    <h3 class="text-center">PKL</h3>
                                                    <div id="chartPengujiPKL"></div>
                                                </div>

                                                <!-- UAS Chart -->
                                                <div class="col-lg-6 chart-container">
                                                    <h3 class="text-center">Sempro</h3>
                                                    <div id="chartPengujiSempro"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

            @endhasrole

        </div>
    </div>
    </div>
    </div>
@endsection


@section('scripts')
    <!-- Load ApexCharts Library -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    {{-- <script src="{{ asset('backend/assets/js/apexchart.js') }}"></script> --}}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Data from Blade variables

            // Kaprodi
            var banyakPKLBelum = @json($pklBelumDiterima ?? 0);
            var banyakPKL = @json($banyak_pengunggahan_pkl ?? 0);
            var banyakSelesaiPKL = @json($banyak_verifikasi_pkl ?? 0);
            var banyakAjukanJudul = @json($banyak_pengunggahan_sempro ?? 0);
            var banyakSelesaiSempro = @json($banyak_verifikasi_sempro ?? 0);
            var percentUploadedPKL = @json($percentUploadedPKL ?? 0);
            var percentVerifiedPKL = @json($percentVerifiedPKL ?? 0);
            var percentUploadedSempro = @json($percentUploadedSempro ?? 0);
            var percentVerifiedSempro = @json($percentVerifiedSempro ?? 0);

            // Penguji
            var percentpklDiterimaPenguji = @json($pklDiterimaPenguji ?? 0);
            var percentpklSelesaiPenguji = @json($pklSelesaiPenguji ?? 0);
            var percentsemproDiterimaPenguji = @json($semproDiterimaPenguji ?? 0);
            var percentsemproSelesaiPenguji = @json($semproSelesaiPenguji ?? 0);

            // Pembimbing
            var percentpklDiterimaPembimbing = @json($pklDiterimaPembimbing ?? 0);
            var percentpklSelesaiPembimbing = @json($pklSelesaiPembimbing ?? 0);
            var percentsemproDiterimaPembimbing = @json($semproDiterimaPembimbing ?? 0);
            var percentsemproSelesaiPembimbing = @json($semproSelesaiPembimbing ?? 0);

            // chart PIMPINAN PRODI
            var barChartKaprodi = {
                series: [{
                        name: "Belum PKL",
                        data: [banyakPKLBelum]
                    },
                    {
                        name: "PKL",
                        data: [banyakPKL]
                    },
                    {
                        name: "Selesai PKL",
                        data: [banyakSelesaiPKL]
                    },
                    {
                        name: "Ajukan Judul",
                        data: [banyakAjukanJudul]
                    },
                    {
                        name: "Selesai Sempro",
                        data: [banyakSelesaiSempro]
                    }
                ],
                chart: {
                    type: "bar",
                    height: 400,
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
                colors: ["#001A6E", "#5D87FF", "#49BEFF", "#50B498", "#9CDBA6"],
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
                    max: Math.max(banyakPKLBelum, banyakPKL, banyakSelesaiPKL,
                        banyakAjukanJudul, banyakSelesaiSempro) + 25,
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
                    }
                ],
                chart: {
                    type: "bar",
                    height: 400,
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
                colors: ["#5D87FF", "#49BEFF", "#50B498", "#9CDBA6"],
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
                        percentsemproSelesaiPenguji) + 25,
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
                    }
                ],
                chart: {
                    type: "bar",
                    height: 400,
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
                colors: ["#5D87FF", "#49BEFF", "#50B498", "#9CDBA6"],
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
                        percentsemproDiterimaPembimbing, percentsemproSelesaiPembimbing) + 25,
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

            // Render Bar Chart
            var kaprodiChart = new ApexCharts(document.querySelector("#chartKaprodi"), barChartKaprodi);
            var pengujiChart = new ApexCharts(document.querySelector("#pengujiChart"), barChartPenguji);
            var pembimbingChart = new ApexCharts(document.querySelector("#pembimbingChart"), barChartPembimbing);
            kaprodiChart.render();
            pengujiChart.render();
            pembimbingChart.render();


            // KAPRODI
            // chart PKL
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
                            // Custom tooltip text based on the series index
                            if (seriesIndex === 0) {
                                return banyakPKLBelum + ' data';
                            } else if (seriesIndex === 1) {
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
                colors: ["#001A6E", "#5D87FF", "#49BEFF"]
            };

            // Options for RPS Chart
            var optionsPKL = {
                ...commonoptionsPKL,
                series: [banyakPKLBelum, banyakPKL, banyakSelesaiPKL],
                labels: ['Belum PKL', 'PKL', 'Selesai']
            };

            // Render RPS Chart
            var chartPKL = new ApexCharts(document.querySelector("#chartkaprodiPKL"), optionsPKL);
            chartPKL.render();

            // Options for UAS Chart
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
                            // Custom tooltip text based on the series index
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
                labels: ['Judul', 'Selesai']
            };

            // Render UAS Chart
            var chartSempro = new ApexCharts(document.querySelector("#chartkaprodiSempro"), optionsSempro);
            chartSempro.render();

            // PEMBIMBING
            // chart PKL
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
                            // Custom tooltip text based on the series index
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

            // Options for RPS Chart
            var optionspembimbingPKL = {
                ...pembimbingPKL,
                series: [percentpklDiterimaPembimbing, percentpklSelesaiPembimbing],
                labels: ['Mahasiswa', 'Selesai']
            };

            // Render RPS Chart
            var chartpembimbingPKL = new ApexCharts(document.querySelector("#chartPembimbingPKL"),
                optionspembimbingPKL);
            chartpembimbingPKL.render();

            // Options for UAS Chart
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
                            // Custom tooltip text based on the series index
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

            // Render UAS Chart
            var chartpembimbingSempro = new ApexCharts(document.querySelector("#chartPembimbingSempro"),
                optionspembimbingSempro);
            chartpembimbingSempro.render();


            // PENGUJI
            // chart PKL
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
                            // Custom tooltip text based on the series index
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

            // Options for RPS Chart
            var optionspengujiPKL = {
                ...pengujiPKL,
                series: [percentpklDiterimaPenguji, percentpklSelesaiPenguji],
                labels: ['Mahasiswa', 'Selesai']
            };

            // Render RPS Chart
            var chartpengujiPKL = new ApexCharts(document.querySelector("#chartPengujiPKL"), optionspengujiPKL);
            chartpengujiPKL.render();

            // Options for UAS Chart
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
                            // Custom tooltip text based on the series index
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

            // Render UAS Chart
            var chartpengujiSempro = new ApexCharts(document.querySelector("#chartPengujiSempro"),optionspengujiSempro);
            chartpengujiSempro.render();
        });
    </script>
@endsection





{{-- @section('admin')
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview"
                                role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active ps-0 ms-2" id="home-tab" data-bs-toggle="tab" href="#contoh"
                                role="tab" aria-controls="contoh" aria-selected="false">Contoh</a>
                        </li>
                    </ul>
                    <div>
                        <div class="btn-wrapper">
                            <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i>
                                Share</a>
                            <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                            <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i>
                                Export</a>
                        </div>
                    </div>

                </div>


                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="statistics-title">Bounce Rate</p>
                                        <h3 class="rate-percentage">32.53%</h3>
                                        <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Page Views</p>
                                        <h3 class="rate-percentage">7,682</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="statistics-title">New Sessions</p>
                                        <h3 class="rate-percentage">68.8</h3>
                                        <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span>
                                        </p>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Avg. Time on Site</p>
                                        <h3 class="rate-percentage">2m:35s</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span>
                                        </p>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">New Sessions</p>
                                        <h3 class="rate-percentage">68.8</h3>
                                        <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span>
                                        </p>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Avg. Time on Site</p>
                                        <h3 class="rate-percentage">2m:35s</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Performance Line Chart</h4>
                                                        <h5 class="card-subtitle card-subtitle-dash">Lorem Ipsum is simply
                                                            dummy text of the printing</h5>
                                                    </div>
                                                    <div id="performanceLine-legend"></div>
                                                </div>
                                                <div class="chartjs-wrapper mt-4">
                                                    <canvas id="performanceLine" width=""></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                        <div class="card bg-primary card-rounded">
                                            <div class="card-body pb-0">
                                                <h4 class="card-title card-title-dash text-white mb-4">Status Summary</h4>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <p class="status-summary-ight-white mb-1">Closed Value</p>
                                                        <h2 class="text-info">357</h2>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="status-summary-chart-wrapper pb-4">
                                                            <canvas id="status-summary"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2 mb-sm-0">
                                                            <div class="circle-progress-width">
                                                                <div id="totalVisitors"
                                                                    class="progressbar-js-circle pr-2"></div>
                                                            </div>
                                                            <div>
                                                                <p class="text-small mb-2">Total Visitors</p>
                                                                <h4 class="mb-0 fw-bold">26.80%</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="circle-progress-width">
                                                                <div id="visitperday" class="progressbar-js-circle pr-2">
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <p class="text-small mb-2">Visits per day</p>
                                                                <h4 class="mb-0 fw-bold">9065</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Market Overview</h4>
                                                        <p class="card-subtitle card-subtitle-dash">Lorem ipsum dolor sit
                                                            amet consectetur adipisicing elit</p>
                                                    </div>
                                                    <div>
                                                        <div class="dropdown">
                                                            <button
                                                                class="btn btn-light dropdown-toggle toggle-dark btn-lg mb-0 me-0"
                                                                type="button" id="dropdownMenuButton2"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false"> This month </button>
                                                            <div class="dropdown-menu"
                                                                aria-labelledby="dropdownMenuButton2">
                                                                <h6 class="dropdown-header">Settings</h6>
                                                                <a class="dropdown-item" href="#">Action</a>
                                                                <a class="dropdown-item" href="#">Another action</a>
                                                                <a class="dropdown-item" href="#">Something else
                                                                    here</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Separated link</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-sm-flex align-items-center mt-1 justify-content-between">
                                                    <div class="d-sm-flex align-items-center mt-4 justify-content-between">
                                                        <h2 class="me-2 fw-bold">$36,2531.00</h2>
                                                        <h4 class="me-2">USD</h4>
                                                        <h4 class="text-success">(+1.37%)</h4>
                                                    </div>
                                                    <div class="me-3">
                                                        <div id="marketingOverview-legend"></div>
                                                    </div>
                                                </div>
                                                <div class="chartjs-bar-wrapper mt-3">
                                                    <canvas id="marketingOverview"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded table-darkBGImg">
                                            <div class="card-body">
                                                <div class="col-sm-8">
                                                    <h3 class="text-white upgrade-info mb-0"> Enhance your <span
                                                            class="fw-bold">Campaign</span> for better outreach </h3>
                                                    <a href="#" class="btn btn-info upgrade-btn">Upgrade
                                                        Account!</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Pending Requests</h4>
                                                        <p class="card-subtitle card-subtitle-dash">You have 50+ new
                                                            requests</p>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-primary btn-lg text-white mb-0 me-0"
                                                            type="button"><i class="mdi mdi-account-plus"></i>Add new
                                                            member</button>
                                                    </div>
                                                </div>
                                                <div class="table-responsive  mt-1">
                                                    <table class="table select-table">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <div class="form-check form-check-flat mt-0">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox"
                                                                                class="form-check-input"
                                                                                aria-checked="false" id="check-all"><i
                                                                                class="input-helper"></i></label>
                                                                    </div>
                                                                </th>
                                                                <th>Customer</th>
                                                                <th>Company</th>
                                                                <th>Progress</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-check form-check-flat mt-0">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox"
                                                                                class="form-check-input"
                                                                                aria-checked="false"><i
                                                                                class="input-helper"></i></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex ">
                                                                        <img src="{{ asset('admin/assets/images/faces/face1.jpg') }}"
                                                                            alt="">
                                                                        <div>
                                                                            <h6>Brandon Washington</h6>
                                                                            <p>Head admin</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <h6>Company name 1</h6>
                                                                    <p>company type</p>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                                                            <p class="text-success">79%</p>
                                                                            <p>85/162</p>
                                                                        </div>
                                                                        <div class="progress progress-md">
                                                                            <div class="progress-bar bg-success"
                                                                                role="progressbar" style="width: 85%"
                                                                                aria-valuenow="25" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="badge badge-opacity-warning">In progress
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-check form-check-flat mt-0">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox"
                                                                                class="form-check-input"
                                                                                aria-checked="false"><i
                                                                                class="input-helper"></i></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <img src="{{ asset('admin/assets/images/faces/face2.jpg') }}"
                                                                            alt="">
                                                                        <div>
                                                                            <h6>Laura Brooks</h6>
                                                                            <p>Head admin</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <h6>Company name 1</h6>
                                                                    <p>company type</p>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                                                            <p class="text-success">65%</p>
                                                                            <p>85/162</p>
                                                                        </div>
                                                                        <div class="progress progress-md">
                                                                            <div class="progress-bar bg-success"
                                                                                role="progressbar" style="width: 65%"
                                                                                aria-valuenow="65" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="badge badge-opacity-warning">In progress
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-check form-check-flat mt-0">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox"
                                                                                class="form-check-input"
                                                                                aria-checked="false"><i
                                                                                class="input-helper"></i></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <img src="{{ asset('admin/assets/images/faces/face3.jpg') }}"
                                                                            alt="">
                                                                        <div>
                                                                            <h6>Wayne Murphy</h6>
                                                                            <p>Head admin</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <h6>Company name 1</h6>
                                                                    <p>company type</p>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                                                            <p class="text-success">65%</p>
                                                                            <p>85/162</p>
                                                                        </div>
                                                                        <div class="progress progress-md">
                                                                            <div class="progress-bar bg-warning"
                                                                                role="progressbar" style="width: 38%"
                                                                                aria-valuenow="38" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="badge badge-opacity-warning">In progress
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-check form-check-flat mt-0">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox"
                                                                                class="form-check-input"
                                                                                aria-checked="false"><i
                                                                                class="input-helper"></i></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <img src="{{ asset('admin/assets/images/faces/face4.jpg') }}"
                                                                            alt="">
                                                                        <div>
                                                                            <h6>Matthew Bailey</h6>
                                                                            <p>Head admin</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <h6>Company name 1</h6>
                                                                    <p>company type</p>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                                                            <p class="text-success">65%</p>
                                                                            <p>85/162</p>
                                                                        </div>
                                                                        <div class="progress progress-md">
                                                                            <div class="progress-bar bg-danger"
                                                                                role="progressbar" style="width: 15%"
                                                                                aria-valuenow="15" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="badge badge-opacity-danger">Pending</div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-check form-check-flat mt-0">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox"
                                                                                class="form-check-input"
                                                                                aria-checked="false"><i
                                                                                class="input-helper"></i></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <img src="{{ asset('admin/assets/images/faces/face5.jpg') }}"
                                                                            alt="">
                                                                        <div>
                                                                            <h6>Katherine Butler</h6>
                                                                            <p>Head admin</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <h6>Company name 1</h6>
                                                                    <p>company type</p>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                                                            <p class="text-success">65%</p>
                                                                            <p>85/162</p>
                                                                        </div>
                                                                        <div class="progress progress-md">
                                                                            <div class="progress-bar bg-success"
                                                                                role="progressbar" style="width: 65%"
                                                                                aria-valuenow="65" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="badge badge-opacity-success">Completed
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row flex-grow">
                                    <div class="col-md-6 col-lg-6 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body card-rounded">
                                                <h4 class="card-title  card-title-dash">Recent Events</h4>
                                                <div class="list align-items-center border-bottom py-2">
                                                    <div class="wrapper w-100">
                                                        <p class="mb-2 fw-medium"> Change in Directors </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                <i class="mdi mdi-calendar text-muted me-1"></i>
                                                                <p class="mb-0 text-small text-muted">Mar 14, 2019</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="list align-items-center border-bottom py-2">
                                                    <div class="wrapper w-100">
                                                        <p class="mb-2 fw-medium"> Other Events </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                <i class="mdi mdi-calendar text-muted me-1"></i>
                                                                <p class="mb-0 text-small text-muted">Mar 14, 2019</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="list align-items-center border-bottom py-2">
                                                    <div class="wrapper w-100">
                                                        <p class="mb-2 fw-medium"> Quarterly Report </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                <i class="mdi mdi-calendar text-muted me-1"></i>
                                                                <p class="mb-0 text-small text-muted">Mar 14, 2019</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="list align-items-center border-bottom py-2">
                                                    <div class="wrapper w-100">
                                                        <p class="mb-2 fw-medium"> Change in Directors </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                <i class="mdi mdi-calendar text-muted me-1"></i>
                                                                <p class="mb-0 text-small text-muted">Mar 14, 2019</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="list align-items-center pt-3">
                                                    <div class="wrapper w-100">
                                                        <p class="mb-0">
                                                            <a href="#" class="fw-bold text-primary">Show all <i
                                                                    class="mdi mdi-arrow-right ms-2"></i></a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h4 class="card-title card-title-dash">Activities</h4>
                                                    <p class="mb-0">20 finished, 5 remaining</p>
                                                </div>
                                                <ul class="bullet-line-list">
                                                    <li>
                                                        <div class="d-flex justify-content-between">
                                                            <div><span class="text-light-green">Ben Tossell</span> assign
                                                                you a task</div>
                                                            <p>Just now</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="d-flex justify-content-between">
                                                            <div><span class="text-light-green">Oliver Noah</span> assign
                                                                you a task</div>
                                                            <p>1h</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="d-flex justify-content-between">
                                                            <div><span class="text-light-green">Jack William</span> assign
                                                                you a task</div>
                                                            <p>1h</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="d-flex justify-content-between">
                                                            <div><span class="text-light-green">Leo Lucas</span> assign you
                                                                a task</div>
                                                            <p>1h</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="d-flex justify-content-between">
                                                            <div><span class="text-light-green">Thomas Henry</span> assign
                                                                you a task</div>
                                                            <p>1h</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="d-flex justify-content-between">
                                                            <div><span class="text-light-green">Ben Tossell</span> assign
                                                                you a task</div>
                                                            <p>1h</p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="d-flex justify-content-between">
                                                            <div><span class="text-light-green">Ben Tossell</span> assign
                                                                you a task</div>
                                                            <p>1h</p>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <div class="list align-items-center pt-3">
                                                    <div class="wrapper w-100">
                                                        <p class="mb-0">
                                                            <a href="#" class="fw-bold text-primary">Show all <i
                                                                    class="mdi mdi-arrow-right ms-2"></i></a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h4 class="card-title card-title-dash">Todo list</h4>
                                                            <div class="add-items d-flex mb-0">

                                                                <!-- <input type="text" class="form-control todo-list-input" placeholder="What do you need to do today?"> -->
                                                                <button
                                                                    class="add btn btn-icons btn-rounded btn-primary todo-list-add-btn text-white me-0 pl-12p"><i
                                                                        class="mdi mdi-plus"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="list-wrapper">
                                                            <ul class="todo-list todo-list-rounded">
                                                                <li class="d-block">
                                                                    <div class="form-check w-100">
                                                                        <label class="form-check-label">
                                                                            <input class="checkbox" type="checkbox"> Lorem
                                                                            Ipsum is simply dummy text of the printing <i
                                                                                class="input-helper rounded"></i>
                                                                        </label>
                                                                        <div class="d-flex mt-2">
                                                                            <div class="ps-4 text-small me-3">24 June 2020
                                                                            </div>
                                                                            <div class="badge badge-opacity-warning me-3">
                                                                                Due tomorrow</div>
                                                                            <i class="mdi mdi-flag ms-2 flag-color"></i>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="d-block">
                                                                    <div class="form-check w-100">
                                                                        <label class="form-check-label">
                                                                            <input class="checkbox" type="checkbox"> Lorem
                                                                            Ipsum is simply dummy text of the printing <i
                                                                                class="input-helper rounded"></i>
                                                                        </label>
                                                                        <div class="d-flex mt-2">
                                                                            <div class="ps-4 text-small me-3">23 June 2020
                                                                            </div>
                                                                            <div class="badge badge-opacity-success me-3">
                                                                                Done</div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="form-check w-100">
                                                                        <label class="form-check-label">
                                                                            <input class="checkbox" type="checkbox"> Lorem
                                                                            Ipsum is simply dummy text of the printing <i
                                                                                class="input-helper rounded"></i>
                                                                        </label>
                                                                        <div class="d-flex mt-2">
                                                                            <div class="ps-4 text-small me-3">24 June 2020
                                                                            </div>
                                                                            <div class="badge badge-opacity-success me-3">
                                                                                Done</div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="border-bottom-0">
                                                                    <div class="form-check w-100">
                                                                        <label class="form-check-label">
                                                                            <input class="checkbox" type="checkbox"> Lorem
                                                                            Ipsum is simply dummy text of the printing <i
                                                                                class="input-helper rounded"></i>
                                                                        </label>
                                                                        <div class="d-flex mt-2">
                                                                            <div class="ps-4 text-small me-3">24 June 2020
                                                                            </div>
                                                                            <div class="badge badge-opacity-danger me-3">
                                                                                Expired</div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3">
                                                            <h4 class="card-title card-title-dash">Type By Amount</h4>
                                                        </div>
                                                        <div>
                                                            <canvas class="my-auto" id="doughnutChart"></canvas>
                                                        </div>
                                                        <div id="doughnutChart-legend" class="mt-5 text-center"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3">
                                                            <div>
                                                                <h4 class="card-title card-title-dash">Leave Report</h4>
                                                            </div>
                                                            <div>
                                                                <div class="dropdown">
                                                                    <button
                                                                        class="btn btn-light dropdown-toggle toggle-dark btn-lg mb-0 me-0"
                                                                        type="button" id="dropdownMenuButton3"
                                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false"> Month Wise </button>
                                                                    <div class="dropdown-menu"
                                                                        aria-labelledby="dropdownMenuButton3">
                                                                        <h6 class="dropdown-header">week Wise</h6>
                                                                        <a class="dropdown-item" href="#">Year
                                                                            Wise</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            <canvas id="leaveReport"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3">
                                                            <div>
                                                                <h4 class="card-title card-title-dash">Top Performer</h4>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            <div
                                                                class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                                <div class="d-flex">
                                                                    <img class="img-sm rounded-10"
                                                                        src="{{ asset('admin/assets/images/faces/face1.jpg') }}"
                                                                        alt="profile">
                                                                    <div class="wrapper ms-3">
                                                                        <p class="ms-1 mb-1 fw-bold">Brandon Washington</p>
                                                                        <small class="text-muted mb-0">162543</small>
                                                                    </div>
                                                                </div>
                                                                <div class="text-muted text-small"> 1h ago </div>
                                                            </div>
                                                            <div
                                                                class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                                <div class="d-flex">
                                                                    <img class="img-sm rounded-10"
                                                                        src="{{ asset('admin/assets/images/faces/face2.jpg') }}"
                                                                        alt="profile">
                                                                    <div class="wrapper ms-3">
                                                                        <p class="ms-1 mb-1 fw-bold">Wayne Murphy</p>
                                                                        <small class="text-muted mb-0">162543</small>
                                                                    </div>
                                                                </div>
                                                                <div class="text-muted text-small"> 1h ago </div>
                                                            </div>
                                                            <div
                                                                class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                                <div class="d-flex">
                                                                    <img class="img-sm rounded-10"
                                                                        src="{{ asset('admin/assets/images/faces/face3.jpg') }}"
                                                                        alt="profile">
                                                                    <div class="wrapper ms-3">
                                                                        <p class="ms-1 mb-1 fw-bold">Katherine Butler</p>
                                                                        <small class="text-muted mb-0">162543</small>
                                                                    </div>
                                                                </div>
                                                                <div class="text-muted text-small"> 1h ago </div>
                                                            </div>
                                                            <div
                                                                class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                                <div class="d-flex">
                                                                    <img class="img-sm rounded-10"
                                                                        src="{{ asset('admin/assets/images/faces/face4.jpg') }}"
                                                                        alt="profile">
                                                                    <div class="wrapper ms-3">
                                                                        <p class="ms-1 mb-1 fw-bold">Matthew Bailey</p>
                                                                        <small class="text-muted mb-0">162543</small>
                                                                    </div>
                                                                </div>
                                                                <div class="text-muted text-small"> 1h ago </div>
                                                            </div>
                                                            <div
                                                                class="wrapper d-flex align-items-center justify-content-between pt-2">
                                                                <div class="d-flex">
                                                                    <img class="img-sm rounded-10"
                                                                        src="{{ asset('admin/assets/images/faces/face5.jpg') }}"
                                                                        alt="profile">
                                                                    <div class="wrapper ms-3">
                                                                        <p class="ms-1 mb-1 fw-bold">Rafell John</p>
                                                                        <small class="text-muted mb-0">Alaska, USA</small>
                                                                    </div>
                                                                </div>
                                                                <div class="text-muted text-small"> 1h ago </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
