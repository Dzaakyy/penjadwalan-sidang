@extends('admin.admin_master')
@section('admin')

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

<div class="d-flex justify-content-between align-items-center mb-5">
    <h4 class="card-title mt-4">Profile</h4>
</div>

<div class="col-12 grid-margin d-flex justify-content-center">
    <div class="row" style="width: 100%;">
        <div class="d-flex justify-content-between">

            <div class="card me-3 shadow-sm" style="width: 60%;">
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="post">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group row mb-3 ms-1 me-2">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Nama user" value="{{ Auth::user()->name }}" required>
                                    @error('name')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group row mb-3 ms-1 me-2">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Email" value="{{ Auth::user()->email }}" required>
                                    @error('email')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group row mb-1 ms-1 me-2">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Kosongkan Jika Tidak Ingin Mengubah Password">
                                    @error('password')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-inline">
                                    <button type="submit" class="btn btn-primary mt-4 ms-1">Simpan</button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
               
                <div class="card-footer">
                    <a href="{{ route('dashboard') }}" class="btn btn-success ms-2">Kembali</a>
                </div>
            </div>

            <div class="card shadow-sm" style="width: 35%;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    @if (Auth::user()->r_dosen && Auth::user()->r_dosen->image)
                        <img src="{{ asset('storage/uploads/dosen/image/' . Auth::user()->r_dosen->image) }}"
                            style="width: 200px; height: 250px; object-fit: cover; border: 2px solid black; padding: 5px;">
                    @elseif (Auth::user()->r_mahasiswa && Auth::user()->r_mahasiswa->image)
                        <img src="{{ asset('storage/uploads/mahasiswa/image/' . Auth::user()->r_mahasiswa->image) }}"
                            style="width: 200px; height: 250px; object-fit: cover; border: 2px solid black; padding: 5px;">
                    @else
                        <img src="{{ asset('admin/assets/images/faces/default.png') }}"
                            style="width: 200px; height: 250px; object-fit: cover; border: 2px solid black; padding: 5px;">
                    @endif

                    <div class="d-flex flex-row mt-3">
                        <a data-bs-toggle="modal" data-bs-target="#uploadgambar"
                            class="btn btn-primary me-5">
                            <span class="bi bi-upload"></span> Upload Gambar
                        </a>

                        <a data-bs-toggle="modal" data-bs-target="#hapusgambar"
                            class="btn btn-danger">
                            <span class="bi bi-trash"></span> Hapus Gambar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal for Upload Gambar -->
            <div class="modal fade" id="uploadgambar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title fs-5" id="staticBackdropLabel">Silahkan Upload Gambar</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('profile.image.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="image">Edit Gambar</label>
                                    <input type="file" class="form-control" id="image" name="image" required>
                                    @error('image')
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

            <!-- Modal for Hapus Gambar -->
            <div class="modal fade" id="hapusgambar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus Gambar</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah kamu yakin ingin menghapus gambar <b>{{ Auth::user()->name }}</b>?</p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <form action="{{ route('profile.image.delete') }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus Gambar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

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
