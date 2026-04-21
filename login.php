<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/helpers.php';

startSession();
if (isLoggedIn()) {
    redirect('/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $result = loginUser($username, $password);
        if ($result['success']) {
            redirect('/dashboard.php');
        } else {
            $error = $result['message'];
        }
    } else {
        $error = 'Username dan password wajib diisi';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1a56db;
            --font: 'Plus Jakarta Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font);
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 36px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .login-logo {
            width: 52px; height: 52px;
            background: var(--primary);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; color: #fff; margin-bottom: 20px;
        }
        h1 { font-size: 24px; font-weight: 800; color: #0f172a; }
        .subtitle { font-size: 14px; color: #64748b; margin-top: 4px; margin-bottom: 28px; }
        label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; display: block; }
        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-family: var(--font);
            font-size: 14px;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,86,219,.12);
            outline: none;
        }
        .input-group .form-control { border-right: none; }
        .input-group-text {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            cursor: pointer;
            color: #94a3b8;
        }
        .btn-login {
            width: 100%;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-family: var(--font);
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: background .15s;
            margin-top: 8px;
        }
        .btn-login:hover { background: #1343b0; }
        .alert-error {
            background: #fee2e2; color: #991b1b;
            border-radius: 10px; padding: 11px 14px;
            font-size: 13px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .mb-4 { margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo"><i class="bi bi-shop"></i></div>
        <h1><?= APP_NAME ?></h1>
        <p class="subtitle">Masuk ke sistem kasir toko</p>

        <?php if ($error): ?>
        <div class="alert-error">
            <i class="bi bi-exclamation-circle"></i>
            <?= sanitize($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control"
                    placeholder="Masukkan username"
                    value="<?= sanitize($_POST['username'] ?? '') ?>"
                    autocomplete="username" required>
            </div>
            <div class="mb-4">
                <label for="password">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Masukkan password"
                        autocomplete="current-password" required>
                    <span class="input-group-text" onclick="togglePw()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </button>
        </form>
    </div>

    <script>
    function togglePw() {
        const pw = document.getElementById('password');
        const ic = document.getElementById('eyeIcon');
        if (pw.type === 'password') {
            pw.type = 'text';
            ic.className = 'bi bi-eye-slash';
        } else {
            pw.type = 'password';
            ic.className = 'bi bi-eye';
        }
    }
    </script>
</body>
</html>
