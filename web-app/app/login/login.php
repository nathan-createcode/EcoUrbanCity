<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EcoUrbanCity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="login.css">
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error')) {
                let errorMessage;
                switch (urlParams.get('error')) {
                    case 'empty_fields':
                        errorMessage = "Email atau password tidak boleh kosong!";
                        break;
                    case 'wrong_password':
                        errorMessage = "Password salah!";
                        break;
                    case 'email_not_found':
                        errorMessage = "Email tidak terdaftar!";
                        break;
                    case 'query_failed':
                        errorMessage = "Terjadi kesalahan pada server. Silakan coba lagi.";
                        break;
                    default:
                        errorMessage = "Terjadi kesalahan. Silakan coba lagi.";
                }
                alert(errorMessage); // Show default alert
            }
        };
    </script>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1 class="welcome-text">Welcome back<br>to EcoUrbanCity!</h1>
        </div>

        <div class="right-section">
            <div class="login-card">
                <form class="login-form" id="loginForm" method="POST" action="login_L.php" autocomplete="off">
                    <div class="form-header">
                        <h2>Selamat Datang Kembali</h2>
                        <p>Your gateway to a smarter, greener city life awaits.</p>
                    </div>

                    <!-- Input Email -->
                    <div class="form-group">
                      <label for="email">Email</label>
                      <i class="fas fa-envelope"></i>
                      <input type="email" id="email" name="email" required
                            placeholder="Masukkan email Anda"
                            autocomplete="email">
                    </div>

                    <!-- Input Password -->
                    <div class="form-group">
                      <label for="password">Password</label>
                      <i class="fas fa-key"></i>
                      <input type="password" id="password" name="password" required
                            placeholder="Masukkan password Anda"
                            autocomplete="current-password">
                    </div>

                    <!-- Link Registrasi -->
                    <div class="form-group account-link">
                        <p>Belum punya akun?
                          <a href="../registration/registration.html" class="signup-link">Registrasi di sini</a>
                        </p>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="submit-btn">Masuk</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>