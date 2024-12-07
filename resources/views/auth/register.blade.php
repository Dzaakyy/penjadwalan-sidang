
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi</title>
    <link rel="shortcut icon" type="image/png" href="backend/assets/images/logos/Logo-E-KBK-White.svg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('frontend/login-register/css/signin.css') }}" rel="stylesheet">

</head>

<body class="text-center">

    <div class="background-image"></div>
    <main class="form-box-register">
        <div class="form-value">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <h2>Registrasi</h2>
                <div class="form-text ">
                    @error('name')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                    @error('email')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                    @error('password')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                    @error('password_confirmation')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="name" placeholder="Username" name="name"
                        required autofocus autocomplete="name" value="{{old('name')}}">
                    <label for="name">Username</label>
                </div>

                <div class="form-floating">
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email"
                        required autocomplete="username" value="{{old('email')}}">
                    <label for="email">Email</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="password" placeholder="New Password"
                        name="password" required autocomplete="new-password">
                    <label for="password">New Password</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="password_confirmation"
                        placeholder="Konfirmasi Password" name="password_confirmation" required
                        autocomplete="new-password">
                    <label for="password_confirmation">Konfirmasi Password</label>
                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Register</button>
                <div class="register">
                    <p><a href="{{ url('login') }}">Kembali</a></p>
                </div>

            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
