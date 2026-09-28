<?php /* Filename: login.php */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login | Inventory POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(17, 25, 40, 0.75);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --input-bg: rgba(15, 23, 42, 0.6);
            --accent-color: #3b82f6;
            --accent-hover: #60a5fa;
            --error-bg: rgba(239, 68, 68, 0.1);
            --error-text: #fca5a5;
            --error-border: rgba(239, 68, 68, 0.2);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .ambient-light {
            position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.4; z-index: 1;
        }
        .light-1 { background: #1e3a8a; width: 500px; height: 500px; top: -10%; left: -10%; }
        .light-2 { background: #0f766e; width: 400px; height: 400px; bottom: -10%; right: -5%; }
        .login-wrapper { width: 100%; max-width: 400px; padding: 20px; z-index: 10; }
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            padding: 40px 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .header-section { text-align: center; margin-bottom: 35px; }
        .logo-icon {
            font-size: 28px; color: var(--accent-color); margin-bottom: 15px; display: inline-block;
            padding: 15px; background: rgba(59, 130, 246, 0.1); border-radius: 16px; border: 1px solid rgba(59, 130, 246, 0.2);
        }
        .title { font-size: 24px; font-weight: 600; margin-bottom: 8px; }
        .subtitle { font-size: 14px; color: var(--text-secondary); }
        .form-group { position: relative; margin-bottom: 20px; }
        .input-icon { position: absolute; top: 50%; left: 16px; transform: translateY(-50%); color: var(--text-secondary); }
        .form-control {
            width: 100%; background: var(--input-bg); border: 1px solid var(--border-color);
            color: var(--text-primary); padding: 14px 16px 14px 44px; border-radius: 12px; font-size: 14px; outline: none;
        }
        .form-control:focus { border-color: var(--accent-color); box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .toggle-password { position: absolute; top: 50%; right: 16px; transform: translateY(-50%); color: var(--text-secondary); cursor: pointer; }
        .btn-login {
            width: 100%; background: var(--accent-color); color: #fff; border: none; padding: 14px;
            border-radius: 12px; font-weight: 500; cursor: pointer; transition: 0.3s; margin-top: 10px;
        }
        .btn-login:hover { background: var(--accent-hover); }
        .alert {
            background: var(--error-bg); border: 1px solid var(--error-border); color: var(--error-text);
            padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 24px;
        }
        .demo-credentials { margin-top: 30px; text-align: center; font-size: 12px; color: var(--text-secondary); border-top: 1px solid var(--border-color); padding-top: 20px; }
        .demo-credentials span { color: var(--text-primary); font-weight: 500; background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="ambient-light light-1"></div>
    <div class="ambient-light light-2"></div>
    <div class="login-wrapper">
        <div class="glass-card">
            <div class="header-section">
                <div class="logo-icon"><i class="fas fa-layer-group"></i></div>
                <h2 class="title">Inventory POS</h2>
                <p class="subtitle">Sistem Manajemen Cerdas & Modern</p>
            </div>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert"><i class="fas fa-exclamation-triangle mr-2"></i><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>
            <form action="<?= site_url('auth/proses') ?>" method="post">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="off">
                    <i class="fas fa-user input-icon"></i>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye toggle-password" id="togglePasswordBtn" onclick="togglePassword()"></i>
                </div>
                <button type="submit" class="btn-login">Masuk <i class="fas fa-arrow-right ml-2"></i></button>
            </form>
            <div class="demo-credentials">
                Akses Demo: <br><br>
                Admin <span>admin / admin123</span><br>
                Kasir <span>kasir / admin123</span>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePasswordBtn');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.classList.remove('fa-eye'); toggleBtn.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleBtn.classList.remove('fa-eye-slash'); toggleBtn.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>