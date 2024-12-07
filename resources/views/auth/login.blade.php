
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('frontend/login-register/css/signin.css') }}" rel="stylesheet">

</head>

<body class="text-center">

    <div class="background-image"></div>
    <main class="form-box-login">
        <div class="form-value">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h2>Login</h2>
                <div class="form-text ">
                    @error('email')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                    @error('password')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{old('email')}}"
                        required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password"
                        required autocomplete="current-password">
                    <label for="password">Password</label>

                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" value="remember-me">
                    <label class="form-check-label" for="rememberMe">Remember Me</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Log In</button>
                <div class="register">
                    <p>Don't have an account? <a href="{{ url('/register') }}">Sign Up</a></p>
                    <p><a href="{{ url('/') }}">Kembali</a></p>
                </div>

            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
