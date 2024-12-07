<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item nav-category">Admin</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#master" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Table Master</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="master">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('jurusan') }}">Jurusan</a></li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('prodi') }}">Prodi</a></li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('dosen') }}">Dosen</a></li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('mahasiswa') }}">Mahasiswa</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('ruang') }}">Ruangan</a></li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('sesi') }}">Sesi</a></li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('jabatan_pimpinan') }}">Jabatan
                            Pimpinan</a></li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('pimpinan') }}">Pimpinan</a>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('thn_ajaran') }}">Tahun Ajaran</a>
                    </li>
                </ul>
            </div>
        </li>





        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#pkl_kaprodi" aria-expanded="false"
                aria-controls="form-elements">
                <i class="menu-icon mdi mdi-account-tie"></i>
                <span class="menu-title">PKL (Kaprodi)</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="pkl_kaprodi">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('tempat_pkl') }}">Tempat PKL</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('konfirmasi_usulan_pkl') }}">Usulan PKL</a></li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('daftar_sidang_kaprodi') }}">Daftar Sidang PKL</a></li>
                </ul>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#pkl_admin" aria-expanded="false"
                aria-controls="form-elements">
                <i class="menu-icon mdi mdi-account-tie"></i>
                <span class="menu-title">PKL (Admin)</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="pkl_admin">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('verif_pkl') }}">Verifikasi Berkas PKL</a></li>
                    {{-- <li class="nav-item"> <a class="nav-link large-text" href="#">Verifikasi Berkas PKL</a></li> --}}
                </ul>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#dosen_bimbingan_PKL" aria-expanded="false"
                aria-controls="form-elements">
                <i class="menu-icon mdi mdi-account-tie"></i>
                <span class="menu-title">PKL (Pembimbing)</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="dosen_bimbingan_PKL">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('dosen_bimbingan_pkl') }}">Bimbingan PKL</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#sidang_pkl" aria-expanded="false"
                aria-controls="form-elements">
                <i class="menu-icon mdi mdi-account-tie"></i>
                <span class="menu-title">Sidang PKL</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sidang_pkl">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('nilai_sidang_pkl') }}">Sidang PKL</a></li>
                </ul>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#pkl" aria-expanded="false"
                aria-controls="form-elements">
                <i class="menu-icon mdi mdi-account-tie"></i>
                <span class="menu-title">PKL</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="pkl">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('usulan_pkl') }}">Usulan PKL</a></li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('bimbingan_pkl') }}">Bimbingan PKL</a></li>
                    <li class="nav-item"> <a class="nav-link large-text" href="{{ route('daftar_sidang') }}">Daftar Sidang</a></li>
                </ul>
            </div>
        </li>



        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#sempro" aria-expanded="false" aria-controls="charts">
                <i class="menu-icon mdi mdi-bookshelf"></i>
                <span class="menu-title">Sempro</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sempro">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link  large-text" href="#">Ajukan Judul</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ta" aria-expanded="false" aria-controls="tables">
                <i class="menu-icon mdi mdi-school"></i>
                <span class="menu-title">TA</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ta">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link large-text" href="#">TA</a></li>
                </ul>
            </div>
        </li>




        {{-- <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
          <i class="menu-icon mdi mdi-card-text-outline"></i>
          <span class="menu-title">Form elements</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="form-elements">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic Elements</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
          <i class="menu-icon mdi mdi-chart-line"></i>
          <span class="menu-title">Charts</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="charts">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
          <i class="menu-icon mdi mdi-table"></i>
          <span class="menu-title">Tables</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="tables">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
          <i class="menu-icon mdi mdi-layers-outline"></i>
          <span class="menu-title">Icons</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="icons">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/icons/font-awesome.html">Font Awesome</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
          <i class="menu-icon mdi mdi-account-circle-outline"></i>
          <span class="menu-title">User Pages</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="pages/samples/blank-page.html"> Blank Page </a></li>
            <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
            <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
            <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
            <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
          </ul>
        </div>
      </li> --}}
    </ul>
</nav>
