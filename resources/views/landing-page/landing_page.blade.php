<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Sidang</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('frontend/landing-page/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('frontend/landing-page/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('frontend/landing-page/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/landing-page/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}"
        rel="stylesheet">
    <link href="{{ asset('frontend/landing-page/assets/vendor/aos/aos.css" rel="stylesheet') }}">
    <link href="{{ asset('frontend/landing-page/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/landing-page/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('frontend/landing-page/assets/css/main.css') }}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: QuickStart
  * Template URL: https://bootstrapmade.com/quickstart-bootstrap-startup-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="/" class="logo d-flex align-items-center me-auto">
                <img src="{{ asset('frontend/landing-page/assets/img/logo.png') }}" alt="">
                <h1 class="sitename">Sidang</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="l#hero" class="active">Beranda</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="#features">Alur</a></li>
                    <li><a href="#services">Layanan</a></li>
                    {{-- <li><a href="index.html#pricing">Pricing</a></li> --}}
                    {{-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i
                                class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="#">Dropdown 1</a></li>
                            <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i
                                        class="bi bi-chevron-down toggle-dropdown"></i></a>
                                <ul>
                                    <li><a href="#">Deep Dropdown 1</a></li>
                                    <li><a href="#">Deep Dropdown 2</a></li>
                                    <li><a href="#">Deep Dropdown 3</a></li>
                                    <li><a href="#">Deep Dropdown 4</a></li>
                                    <li><a href="#">Deep Dropdown 5</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Dropdown 2</a></li>
                            <li><a href="#">Dropdown 3</a></li>
                            <li><a href="#">Dropdown 4</a></li>
                        </ul>
                    </li> --}}
                    <li><a href="#contact">Kontak</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>


            {{-- <a class="btn-getstarted" href="{{ route('login') }}">Login</a> --}}

            <!-- Bagian Login di Navbar atau Header -->
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="btn-getstarted">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="btn-getstarted">Log
                            in</a>
                    @endauth
                </div>
            @endif

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section">
            <div class="hero-bg">
                <img src="{{ asset('frontend/landing-page/assets/img/hero-bg-light.webp') }}" alt="">
            </div>
            <div class="container text-center">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h1 data-aos="fade-up"><span>Selamat Datang</span></h1>
                    <p data-aos="fade-up" data-aos-delay="100">Sidang adalah momen penting yang menandai perjalanan
                        akademis dan profesional Anda. Mari kita mulai dengan semangat untuk mengevaluasi dan merayakan
                        hasil kerja keras Anda dalam PKL, Sempro, dan TA.<br></p>
                    <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ route('login') }}" class="btn-get-started">Get Started</a>
                        {{-- <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                            class="glightbox btn-watch-video d-flex align-items-center"><i
                                class="bi bi-play-circle"></i><span>Watch Video</span></a> --}}
                    </div>
                    <img src="{{ asset('frontend/landing-page/assets/img/hero-services-img.webp') }}"
                        class="img-fluid hero-img" alt="" data-aos="zoom-out" data-aos-delay="300">
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- Featured Services Section -->
        <section id="featured-services" class="featured-services section light-background">

            <div class="container">

                <div class="row gy-4">

                    <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-briefcase"></i></div>
                            <div>
                                <h4 class="title"><a href="#" class="stretched-link">PKL</a></h4>
                                <p class="description">PKL adalah program mahasiswa menerapkan ilmu langsung di dunia
                                    kerja.</p>
                            </div>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-file-earmark-text"></i></div>
                            <div>
                                <h4 class="title"><a href="#" class="stretched-link">Sempro</a></h4>
                                <p class="description">Sempro adalah presentasi proposal penelitian sebagai langkah awal
                                    penyusunan tugas akhir.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-xl-4 col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-file-earmark-check"></i></div>
                            <div>
                                <h4 class="title"><a href="#" class="stretched-link">Tugas Akhir</a>
                                </h4>
                                <p class="description">Tugas akhir adalah karya penelitian mahasiswa sebagai syarat
                                    kelulusan.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                </div>

            </div>

        </section><!-- /Featured Services Section -->

        <!-- About Section -->
        <section id="about" class="about section">

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
                        <p class="who-we-are">Tentang Sidang</p>
                        <h3>Mewujudkan Potensi Melalui PKL, Sempro, dan TA</h3>
                        <p class="fst-italic">
                            Sidang ini adalah momen penting bagi mahasiswa untuk mempresentasikan hasil kerja keras
                            mereka dalam Praktik Kerja Lapangan (PKL), Seminar Proposal (Sempro), dan Tugas Akhir (TA).
                        </p>
                        <ul>
                            <li><i class="bi bi-check-circle"></i> <span>PKL: Menerapkan ilmu langsung di dunia kerja
                                    nyata.</span></li>
                            <li><i class="bi bi-check-circle"></i> <span>Sempro: Presentasi proposal penelitian sebagai
                                    langkah awal.</span></li>
                            <li><i class="bi bi-check-circle"></i> <span>Tugas Akhir: Karya penelitian sebagai syarat
                                    kelulusan.</span></li>
                        </ul>
                        <a href="#" class="read-more"><span>Selengkapnya</span><i
                                class="bi bi-arrow-right"></i></a>
                    </div>

                    <div class="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
                        <div class="row gy-4">
                            <div class="col-lg-6">
                                <img src="{{ asset('frontend/landing-page/assets/img/about-company-1.jpg') }}"
                                    class="img-fluid" alt="">
                            </div>
                            <div class="col-lg-6">
                                <div class="row gy-4">
                                    <div class="col-lg-12">
                                        <img src="{{ asset('frontend/landing-page/assets/img/about-company-2.jpg') }}"
                                            class="img-fluid" alt="">
                                    </div>
                                    <div class="col-lg-12">
                                        <img src="{{ asset('frontend/landing-page/assets/img/about-company-3.jpg') }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </section><!-- /About Section -->

        <!-- Clients Section -->
        {{-- <section id="clients" class="clients section">

            <div class="container" data-aos="fade-up">

                <div class="row gy-4">

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="{{asset('frontend/landing-page/assets/img/clients/client-1.png')}}" class="img-fluid" alt="">
                    </div><!-- End Client Item -->

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="{{asset('frontend/landing-page/assets/img/clients/client-2.png')}}" class="img-fluid" alt="">
                    </div><!-- End Client Item -->

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="{{asset('frontend/landing-page/assets/img/clients/client-3.png')}}" class="img-fluid" alt="">
                    </div><!-- End Client Item -->

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="{{asset('frontend/landing-page/assets/img/clients/client-4.png')}}" class="img-fluid" alt="">
                    </div><!-- End Client Item -->

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="{{asset('frontend/landing-page/assets/img/clients/client-5.png')}}" class="img-fluid" alt="">
                    </div><!-- End Client Item -->

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="{{asset('frontend/landing-page/assets/img/clients/client-6.png')}}" class="img-fluid" alt="">
                    </div><!-- End Client Item -->

                </div>

            </div>

        </section><!-- /Clients Section --> --}}

        <!-- Features Section -->
        <section id="features" class="features section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Alur Sidang</h2>
                <p>Alur untuk sidang PKL, Sempro, dan Tugas Akhir</p>
            </div><!-- End Section Title -->

            <div class="container">
                <div class="row justify-content-between">

                    <div class="col-lg-5 d-flex align-items-center">

                        <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">
                            <li class="nav-item">
                                <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#pkl-tab-1">
                                    <i class="bi bi-calendar-check"></i>
                                    <div>
                                        <h4 class="d-none d-lg-block">Pendaftaran PKL</h4>
                                        <p>
                                            Mahasiswa mendaftar PKL dengan memilih lokasi dan mengajukan proposal
                                            kegiatan kepada pihak kampus.
                                        </p>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#pkl-tab-2">
                                    <i class="bi bi-briefcase"></i>
                                    <div>
                                        <h4 class="d-none d-lg-block">Pelaksanaan PKL</h4>
                                        <p>
                                            Mahasiswa melaksanakan PKL di lokasi yang telah disetujui, mengikuti aturan
                                            perusahaan/instansi, dan mengumpulkan data.
                                        </p>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#pkl-tab-3">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <div>
                                        <h4 class="d-none d-lg-block">Penyusunan Laporan</h4>
                                        <p>
                                            Mahasiswa menyusun laporan PKL berdasarkan data yang dikumpulkan selama
                                            pelaksanaan.
                                        </p>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#pkl-tab-4">
                                    <i class="bi bi-person-check"></i>
                                    <div>
                                        <h4 class="d-none d-lg-block">Sidang PKL</h4>
                                        <p>
                                            Mahasiswa mempresentasikan hasil PKL di depan dosen pembimbing dan penguji
                                            untuk dievaluasi.
                                        </p>
                                    </div>
                                </a>
                            </li>
                        </ul><!-- End Tab Nav -->
                    </div>

                    <div class="col-lg-6">

                        <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

                            <div class="tab-pane fade active show" id="features-tab-1">
                                <img src="{{ asset('frontend/landing-page/assets/img/tabs-1.jpg') }}" alt=""
                                    class="img-fluid">
                            </div><!-- End Tab Content Item -->

                            <div class="tab-pane fade" id="features-tab-2">
                                <img src="{{ asset('frontend/landing-page/assets/img/tabs-2.jpg') }}" alt=""
                                    class="img-fluid">
                            </div><!-- End Tab Content Item -->

                            <div class="tab-pane fade" id="features-tab-3">
                                <img src="{{ asset('frontend/landing-page/assets/img/tabs-3.jpg') }}" alt=""
                                    class="img-fluid">
                            </div><!-- End Tab Content Item -->
                        </div>

                    </div>

                </div>

            </div>

        </section><!-- /Features Section -->

        <!-- Features Details Section -->
        <section id="features-details" class="features-details section">

            <div class="container">

                <div class="row gy-4 justify-content-between features-item">

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{ asset('frontend/landing-page/assets/img/features-1.jpg') }}" class="img-fluid"
                            alt="">
                    </div>

                    <div class="col-lg-5 d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="content">
                            <h3>Alur Seminar Proposal (Sempro)</h3>
                            <p>
                                Seminar Proposal (Sempro) adalah tahap penting dalam proses penelitian mahasiswa.
                                Berikut adalah alur yang harus dilalui:
                            </p>
                            <ul>
                                <li><i class="bi bi-check-circle"></i> <span>Pemilihan topik penelitian dan konsultasi
                                        dengan dosen pembimbing.</span></li>
                                <li><i class="bi bi-check-circle"></i> <span>Penyusunan proposal penelitian yang
                                        mencakup latar belakang, tujuan, dan metode.</span></li>
                                <li><i class="bi bi-check-circle"></i> <span>Presentasi proposal di depan dosen penguji
                                        untuk mendapatkan masukan dan persetujuan.</span></li>
                                <li><i class="bi bi-check-circle"></i> <span>Revisi proposal berdasarkan masukan dari
                                        dosen penguji.</span></li>
                            </ul>
                            {{-- <a href="#" class="btn more-btn">Selengkapnya</a> --}}
                        </div>
                    </div>

                </div><!-- Features Item -->

                <div class="row gy-4 justify-content-between features-item">

                    <div class="col-lg-5 d-flex align-items-center order-2 order-lg-1" data-aos="fade-up"
                        data-aos-delay="100">

                        <div class="content">
                            <h3>Alur Penyusunan Tugas Akhir</h3>
                            <p>
                                Tugas Akhir adalah tahap akhir dalam perjalanan akademis mahasiswa. Berikut adalah alur
                                yang harus dilalui:
                            </p>
                            <ul>
                                {{-- <li><i class="bi bi-file-earmark-text flex-shrink-0"></i> <span>Pemilihan topik penelitian dan konsultasi dengan dosen pembimbing.</span></li> --}}
                                <li><i class="bi bi-journal-check flex-shrink-0"></i> <span>Penyusunan proposal dan
                                        pengajuan judul tugas akhir.</span></li>
                                <li><i class="bi bi-search flex-shrink-0"></i> <span>Penelitian dan pengumpulan data
                                        sesuai dengan metode yang telah ditentukan.</span></li>
                                <li><i class="bi bi-file-earmark-arrow-up flex-shrink-0"></i> <span>Penyusunan laporan
                                        tugas akhir dan revisi berdasarkan masukan dosen pembimbing.</span></li>
                                <li><i class="bi bi-person-check flex-shrink-0"></i> <span>Sidang tugas akhir untuk
                                        mempresentasikan hasil penelitian.</span></li>
                            </ul>
                            {{-- <a href="#" class="btn more-btn">Selengkapnya</a> --}}
                        </div>

                    </div>

                    <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="200">
                        <img src="{{ asset('frontend/landing-page/assets/img/features-2.jpg') }}" class="img-fluid"
                            alt="">
                    </div>

                </div><!-- Features Item -->

            </div>

        </section><!-- /Features Details Section -->

        <!-- Services Section -->
        <section id="services" class="services section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Layanan Kami</h2>
                <p>Kami menyediakan berbagai layanan untuk mendukung kesuksesan akademis Anda dalam PKL, Sempro, dan
                    Tugas Akhir.</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row g-5">

                    <!-- Layanan 1: Konsultasi PKL -->
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item item-cyan position-relative">
                            <i class="bi bi-briefcase icon"></i>
                            <div>
                                <h3>Konsultasi PKL</h3>
                                <p>Bimbingan dan konsultasi untuk mempersiapkan Praktik Kerja Lapangan (PKL), mulai dari
                                    pemilihan lokasi hingga penyusunan laporan.</p>
                                {{-- <a href="#" class="read-more stretched-link">Selengkapnya <i class="bi bi-arrow-right"></i></a> --}}
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <!-- Layanan 2: Bimbingan Sempro -->
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item item-orange position-relative">
                            <i class="bi bi-file-earmark-text icon"></i>
                            <div>
                                <h3>Bimbingan Sempro</h3>
                                <p>Panduan lengkap untuk menyusun proposal penelitian dan persiapan presentasi Seminar
                                    Proposal (Sempro).</p>
                                {{-- <a href="#" class="read-more stretched-link">Selengkapnya <i class="bi bi-arrow-right"></i></a> --}}
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <!-- Layanan 3: Pendampingan Tugas Akhir -->
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item item-teal position-relative">
                            <i class="bi bi-journal-bookmark icon"></i>
                            <div>
                                <h3>Pendampingan Tugas Akhir</h3>
                                <p>Bimbingan intensif untuk penyusunan Tugas Akhir, mulai dari penelitian hingga
                                    penulisan laporan.</p>
                                {{-- <a href="#" class="read-more stretched-link">Selengkapnya <i class="bi bi-arrow-right"></i></a> --}}
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <!-- Layanan 4: Review Laporan -->
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-item item-red position-relative">
                            <i class="bi bi-file-earmark-check icon"></i>
                            <div>
                                <h3>Review Laporan</h3>
                                <p>Layanan review dan revisi laporan PKL, Sempro, dan Tugas Akhir untuk memastikan
                                    kualitas dan kesesuaian dengan standar.</p>
                                {{-- <a href="#" class="read-more stretched-link">Selengkapnya <i class="bi bi-arrow-right"></i></a> --}}
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <!-- Layanan 5: Persiapan Sidang -->
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="500">
                        <div class="service-item item-indigo position-relative">
                            <i class="bi bi-person-check icon"></i>
                            <div>
                                <h3>Persiapan Sidang</h3>
                                <p>Pelatihan dan simulasi sidang untuk mempersiapkan presentasi dan menghadapi
                                    pertanyaan dari penguji.</p>
                                {{-- <a href="#" class="read-more stretched-link">Selengkapnya <i class="bi bi-arrow-right"></i></a> --}}
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <!-- Layanan 6: Konsultasi Metode Penelitian -->
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="600">
                        <div class="service-item item-pink position-relative">
                            <i class="bi bi-search icon"></i>
                            <div>
                                <h3>Konsultasi Metode Penelitian</h3>
                                <p>Bantuan dalam memilih dan merancang metode penelitian yang sesuai untuk PKL, Sempro,
                                    dan Tugas Akhir.</p>
                                {{-- <a href="#" class="read-more stretched-link">Selengkapnya <i class="bi bi-arrow-right"></i></a> --}}
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                </div>

            </div>

        </section><!-- /Services Section -->

        <!-- More Features Section -->
        <section id="more-features" class="more-features section">

            <div class="container">

                <div class="row justify-content-around gy-4">

                    <div class="col-lg-6 d-flex flex-column justify-content-center order-2 order-lg-1"
                        data-aos="fade-up" data-aos-delay="100">
                        <h3>Fitur Unggulan Kami</h3>
                        <p>
                            Kami menyediakan berbagai fitur unggulan untuk memastikan kesuksesan Anda dalam PKL, Sempro,
                            dan Tugas Akhir.
                        </p>

                        <div class="row">

                            <!-- Fitur 1: Bimbingan Personal -->
                            <div class="col-lg-6 icon-box d-flex">
                                <i class="bi bi-person-lines-fill flex-shrink-0"></i>
                                <div>
                                    <h4>Bimbingan Personal</h4>
                                    <p>Bimbingan langsung dari dosen pembimbing yang berpengalaman.</p>
                                </div>
                            </div><!-- End Icon Box -->

                            <!-- Fitur 2: Akses Referensi -->
                            <div class="col-lg-6 icon-box d-flex">
                                <i class="bi bi-book flex-shrink-0"></i>
                                <div>
                                    <h4>Akses Referensi</h4>
                                    <p>Akses ke perpustakaan digital dan sumber referensi terpercaya.</p>
                                </div>
                            </div><!-- End Icon Box -->

                            <!-- Fitur 3: Konsultasi Online -->
                            <div class="col-lg-6 icon-box d-flex">
                                <i class="bi bi-laptop flex-shrink-0"></i>
                                <div>
                                    <h4>Konsultasi Online</h4>
                                    <p>Konsultasi online fleksibel untuk memudahkan proses bimbingan.</p>
                                </div>
                            </div><!-- End Icon Box -->

                            <!-- Fitur 4: Review Laporan -->
                            <div class="col-lg-6 icon-box d-flex">
                                <i class="bi bi-file-earmark-check flex-shrink-0"></i>
                                <div>
                                    <h4>Review Laporan</h4>
                                    <p>Layanan review laporan untuk memastikan kualitas dan kesesuaian.</p>
                                </div>
                            </div><!-- End Icon Box -->

                        </div>

                    </div>

                    <div class="features-image col-lg-5 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="200">
                        <img src="{{ asset('frontend/landing-page/assets/img/features-3.jpg') }}" alt="">
                    </div>

                </div>

            </div>

        </section><!-- /More Features Section -->

        <!-- Pricing Section -->
        {{-- <section id="pricing" class="pricing section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Pricing</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="100">
                        <div class="pricing-item">
                            <h3>Free Plan</h3>
                            <p class="description">Ullam mollitia quasi nobis soluta in voluptatum et sint palora dex
                                strater</p>
                            <h4><sup>$</sup>0<span> / month</span></h4>
                            <a href="#" class="cta-btn">Start a free trial</a>
                            <p class="text-center small">No credit card required</p>
                            <ul>
                                <li><i class="bi bi-check"></i> <span>Quam adipiscing vitae proin</span></li>
                                <li><i class="bi bi-check"></i> <span>Nec feugiat nisl pretium</span></li>
                                <li><i class="bi bi-check"></i> <span>Nulla at volutpat diam uteera</span></li>
                                <li class="na"><i class="bi bi-x"></i> <span>Pharetra massa massa ultricies</span>
                                </li>
                                <li class="na"><i class="bi bi-x"></i> <span>Massa ultricies mi quis
                                        hendrerit</span></li>
                                <li class="na"><i class="bi bi-x"></i> <span>Voluptate id voluptas qui sed aperiam
                                        rerum</span></li>
                                <li class="na"><i class="bi bi-x"></i> <span>Iure nihil dolores recusandae odit
                                        voluptatibus</span></li>
                            </ul>
                        </div>
                    </div><!-- End Pricing Item -->

                    <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="200">
                        <div class="pricing-item featured">
                            <p class="popular">Popular</p>
                            <h3>Business Plan</h3>
                            <p class="description">Ullam mollitia quasi nobis soluta in voluptatum et sint palora dex
                                strater</p>
                            <h4><sup>$</sup>29<span> / month</span></h4>
                            <a href="#" class="cta-btn">Start a free trial</a>
                            <p class="text-center small">No credit card required</p>
                            <ul>
                                <li><i class="bi bi-check"></i> <span>Quam adipiscing vitae proin</span></li>
                                <li><i class="bi bi-check"></i> <span>Nec feugiat nisl pretium</span></li>
                                <li><i class="bi bi-check"></i> <span>Nulla at volutpat diam uteera</span></li>
                                <li><i class="bi bi-check"></i> <span>Pharetra massa massa ultricies</span></li>
                                <li><i class="bi bi-check"></i> <span>Massa ultricies mi quis hendrerit</span></li>
                                <li><i class="bi bi-check"></i> <span>Voluptate id voluptas qui sed aperiam
                                        rerum</span></li>
                                <li class="na"><i class="bi bi-x"></i> <span>Iure nihil dolores recusandae odit
                                        voluptatibus</span></li>
                            </ul>
                        </div>
                    </div><!-- End Pricing Item -->

                    <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="300">
                        <div class="pricing-item">
                            <h3>Developer Plan</h3>
                            <p class="description">Ullam mollitia quasi nobis soluta in voluptatum et sint palora dex
                                strater</p>
                            <h4><sup>$</sup>49<span> / month</span></h4>
                            <a href="#" class="cta-btn">Start a free trial</a>
                            <p class="text-center small">No credit card required</p>
                            <ul>
                                <li><i class="bi bi-check"></i> <span>Quam adipiscing vitae proin</span></li>
                                <li><i class="bi bi-check"></i> <span>Nec feugiat nisl pretium</span></li>
                                <li><i class="bi bi-check"></i> <span>Nulla at volutpat diam uteera</span></li>
                                <li><i class="bi bi-check"></i> <span>Pharetra massa massa ultricies</span></li>
                                <li><i class="bi bi-check"></i> <span>Massa ultricies mi quis hendrerit</span></li>
                                <li><i class="bi bi-check"></i> <span>Voluptate id voluptas qui sed aperiam
                                        rerum</span></li>
                                <li><i class="bi bi-check"></i> <span>Iure nihil dolores recusandae odit
                                        voluptatibus</span></li>
                            </ul>
                        </div>
                    </div><!-- End Pricing Item -->

                </div>

            </div>

        </section><!-- /Pricing Section -->

        <!-- Faq Section -->
        <section id="faq" class="faq section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Frequently Asked Questions</h2>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row justify-content-center">

                    <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

                        <div class="faq-container">

                            <div class="faq-item faq-active">
                                <h3>Non consectetur a erat nam at lectus urna duis?</h3>
                                <div class="faq-content">
                                    <p>Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus
                                        laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor
                                        rhoncus dolor purus non.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Feugiat scelerisque varius morbi enim nunc faucibus?</h3>
                                <div class="faq-content">
                                    <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id
                                        interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus
                                        scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim.
                                        Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Dolor sit amet consectetur adipiscing elit pellentesque?</h3>
                                <div class="faq-content">
                                    <p>Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci.
                                        Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl
                                        suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis
                                        convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?</h3>
                                <div class="faq-content">
                                    <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id
                                        interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus
                                        scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim.
                                        Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Tempus quam pellentesque nec nam aliquam sem et tortor?</h3>
                                <div class="faq-content">
                                    <p>Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse
                                        in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl
                                        suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Perspiciatis quod quo quos nulla quo illum ullam?</h3>
                                <div class="faq-content">
                                    <p>Enim ea facilis quaerat voluptas quidem et dolorem. Quis et consequatur non sed
                                        in suscipit sequi. Distinctio ipsam dolore et.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                        </div>

                    </div><!-- End Faq Column-->

                </div>

            </div>

        </section><!-- /Faq Section -->

        <!-- Testimonials Section -->
        <section id="testimonials" class="testimonials section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Testimonials</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="swiper init-swiper">
                    <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 40
                },
                "1200": {
                  "slidesPerView": 3,
                  "spaceBetween": 1
                }
              }
            }
          </script>
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit
                                    rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam,
                                    risus at semper.
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img"
                                        alt="">
                                    <h3>Saul Goodman</h3>
                                    <h4>Ceo &amp; Founder</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid
                                    cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet
                                    legam anim culpa.
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img"
                                        alt="">
                                    <h3>Sara Wilsson</h3>
                                    <h4>Designer</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem
                                    veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint
                                    minim.
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img"
                                        alt="">
                                    <h3>Jena Karlis</h3>
                                    <h4>Store Owner</h4>
                                </div>
                            </div>
                        </div>
                        <!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim
                                    fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem
                                    dolore labore illum veniam.
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img"
                                        alt="">
                                    <h3>Matt Brandon</h3>
                                    <h4>Freelancer</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i>
                                </div>
                                <p>
                                    Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster
                                    veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam
                                    culpa fore nisi cillum quid.
                                </p>
                                <div class="profile mt-auto">
                                    <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img"
                                        alt="">
                                    <h3>John Larson</h3>
                                    <h4>Entrepreneur</h4>
                                </div>
                            </div>
                        </div><!-- End testimonial item -->

                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>

        </section> --}}

        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Kontak</h2>
                <p>Hubungi kami untuk informasi lebih lanjut.</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-6">
                        <div class="info-item d-flex flex-column justify-content-center align-items-center"
                            data-aos="fade-up" data-aos-delay="200">
                            <i class="bi bi-geo-alt"></i>
                            <h3>Address</h3>
                            <p>Jl. Kampus, Limau Manis, Kec. Pauh, Kota Padang, Sumatera Barat 25164</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="info-item d-flex flex-column justify-content-center align-items-center"
                            data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-telephone"></i>
                            <h3>Telepon</h3>
                            <p>+62 751 72590</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="info-item d-flex flex-column justify-content-center align-items-center"
                            data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-envelope"></i>
                            <h3>Email</h3>
                            <p>@pnp.ac.id </p>
                        </div>
                    </div><!-- End Info Item -->

                </div>

                <div class="row gy-4 mt-1">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4744.115785752874!2d100.46377214554374!3d-0.914644776418929!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4b7be9e52a171%3A0x609ef1cc57a38e32!2sPoliteknik%20Negeri%20Padang!5e0!3m2!1sid!2sid!4v1735485123548!5m2!1sid!2sid"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div><!-- End Google Maps -->

                    <div class="col-lg-6">
                        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
                            data-aos-delay="400">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Your Name" required="">
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Your Email" required="">
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Your message has been sent. Thank you!</div>

                                    <button type="submit">Send Message</button>
                                </div>

                            </div>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>

    <footer id="footer" class="footer position-relative light-background">

        <div class="container footer-top">
            <div class="row gy-4">
                <!-- Bagian Tentang -->
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">Politeknik Negeri Padang</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>Jl. Kampus, Limau Manis, Kec. Pauh</p>
                        <p>Kota Padang, Sumatera Barat 25164</p>
                        <p class="mt-3"><strong>Telepon:</strong> <span>+62 751 72590</span></p>
                        <p><strong>Email:</strong> <span>info@pnp.ac.id</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href="https://twitter.com/politeknikpadang" target="_blank"><i
                                class="bi bi-twitter-x"></i></a>
                        <a href="https://www.facebook.com/politekniknegeripadang" target="_blank"><i
                                class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/politekniknegeripadang" target="_blank"><i
                                class="bi bi-instagram"></i></a>
                        <a href="https://www.linkedin.com/school/politeknik-negeri-padang" target="_blank"><i
                                class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <!-- Tautan Berguna -->
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Tautan Berguna</h4>
                    <ul>
                        <li><a href="https://www.pnp.ac.id" target="_blank">Situs Resmi</a></li>
                        <li><a href="https://akademik.pnp.ac.id" target="_blank">Sistem Akademik</a></li>
                        <li><a href="https://e-ppmb.pnp.ac.id" target="_blank">Penerimaan Mahasiswa Baru</a></li>
                        <li><a href="https://pnp.ac.id/berita" target="_blank">Berita</a></li>
                        <li><a href="https://pnp.ac.id/kontak" target="_blank">Kontak</a></li>
                    </ul>
                </div>

                <!-- Layanan Kami -->
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Layanan Kami</h4>
                    <ul>
                        <li><a href="https://pnp.ac.id/pendaftaran" target="_blank">Pendaftaran</a></li>
                        <li><a href="https://pnp.ac.id/beasiswa" target="_blank">Beasiswa</a></li>
                        <li><a href="https://pnp.ac.id/konsultasi" target="_blank">Konsultasi</a></li>
                        <li><a href="https://pnp.ac.id/perpustakaan" target="_blank">Perpustakaan</a></li>
                        <li><a href="https://pnp.ac.id/faq" target="_blank">FAQ</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="col-lg-4 col-md-12 footer-newsletter">
                    <h4>Newsletter</h4>
                    <p>Berlangganan newsletter kami untuk mendapatkan informasi terbaru tentang Politeknik Negeri
                        Padang.</p>
                    <form action="forms/newsletter.php" method="post" class="php-email-form">
                        <div class="newsletter-form">
                            <input type="email" name="email" placeholder="Masukkan email Anda" required>
                            <input type="submit" value="Berlangganan">
                        </div>
                        <div class="loading">Memproses...</div>
                        <div class="error-message">Terjadi kesalahan. Silakan coba lagi.</div>
                        <div class="sent-message">Terima kasih! Anda telah berlangganan newsletter kami.</div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hak Cipta -->
        {{-- <div class="container copyright text-center mt-4">
            <p>© <span>Hak Cipta</span> <strong class="px-1 sitename">Politeknik Negeri Padang</strong><span>2024. Semua Hak Dilindungi.</span></p>
            <div class="credits">
                Dirancang oleh <a href="https://bootstrapmade.com/" target="_blank">BootstrapMade</a> | Didistribusikan oleh <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
            </div>
        </div> --}}

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('frontend/landing-page/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/landing-page/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('frontend/landing-page/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('frontend/landing-page/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('frontend/landing-page/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('frontend/landing-page/assets/js/main.js') }}"></script>

</body>

</html>
