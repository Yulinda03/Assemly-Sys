<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laptop Assembly</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            /* Teal to Green gradient */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-title {
            color: #198754;
            /* Darker green/teal text */
            font-weight: 500;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .form-control {
            height: 50px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #20c997;
            box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.25);
        }

        .btn-login {
            background-color: #008080;
            /* Teal color */
            border: none;
            color: white;
            font-weight: bold;
            height: 50px;
            width: 100%;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #006666;
        }

        .form-check-input:checked {
            background-color: #20c997;
            border-color: #20c997;
        }

        .links {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }

        .links a {
            color: #008080;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h2 class="login-title">Login</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Username" required autofocus
                    value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                    required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="showPass">
                <label class="form-check-label text-muted" for="showPass">Show Password</label>
            </div>

            <button type="submit" class="btn btn-login">SIGN IN</button>

            <div class="links">
                <p class="mb-1 text-muted">Forgot Username / Password?</p>
                <p class="mb-0 text-muted">Don't have an account? <a href="#">Sign up</a></p>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script>
        const passInput = document.getElementById('password');
        const showPass = document.getElementById('showPass');

        showPass.addEventListener('change', function () {
            if (this.checked) {
                passInput.type = 'text';
            } else {
                passInput.type = 'password';
            }
        });
    </script>
</body>

</html>