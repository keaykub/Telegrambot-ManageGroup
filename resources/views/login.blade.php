<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TelegramManage-Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Icon -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                transition: background-color 0.3s, color 0.3s;
            }

            body.dark-mode {
                background-color: #121212;
                color: #ffffff;
            }

            .login-card {
                max-width: 400px;
                width: 100%;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
            }

            .login-card.bg-dark {
                background-color: #1f1f1f;
            }
        </style>
    </head>
    <body class="dark-mode">
        <div class="login-card bg-dark text-white">
            <div class="d-flex justify-content-center align-items-center mb-3">
                <h4 class="text-center mb-0">Login</h4>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="ADMIN_USERNAME" class="form-label">Username</label>
                    <input type="text" class="form-control" id="ADMIN_USERNAME" name="ADMIN_USERNAME" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="ADMIN_PASSWORD" class="form-label">Password</label>
                    <input type="password" class="form-control" id="ADMIN_PASSWORD" name="ADMIN_PASSWORD" placeholder="Enter your password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="#" class="text-decoration-none">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <small>Don't have an account? <a href="#" class="text-decoration-none">Contact Admin</a></small>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
