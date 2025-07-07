<?php
session_start();
require_once 'models/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        // Cek di tabel users saja
        $user_stmt = $pdo->prepare("SELECT id, username, full_name, password, role FROM users WHERE username = ?");
        $user_stmt->execute([$username]);
        $user = $user_stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Debug: Tampilkan data user
            error_log("User data: " . print_r($user, true));
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            // Debug: Tampilkan session data
            error_log("Session data: " . print_r($_SESSION, true));
            
            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = 'パスワードが正しくありません。';
        }
    } catch(PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $error = 'ログイン処理中にエラーが発生しました。';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - くらしナビ</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        .login-section {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/Index Bg.png');
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .login-header {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 120px 20px 80px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .login-header h2 {
            font-size: 56px;
            font-weight: 700;
            margin-bottom: 0;
            letter-spacing: 2px;
            white-space: nowrap;
        }
        .login-subtitle {
            font-size: 18px;
            margin-top: 15px;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            white-space: nowrap;
        }
        .login-header p {
            font-size: 18px;
            margin-top: 15px;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            white-space: nowrap;
        }
        .login-content {
            position: relative;
            z-index: 2;
            background: white;
            padding: 80px 20px;
            margin-top: -40px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
        }
        .login-card {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            right: 50%;
            height: 4px;
            background: #1a73e8;
            transition: all 0.5s ease;
        }
        .login-card:hover::before {
            left: 0;
            right: 0;
        }
        .login-card h3 {
            color: #1a73e8;
            text-align: center;
            margin-bottom: 35px;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
            position: relative;
        }
        .login-card h3::before {
            display: none;
        }
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            color: #333;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .form-group label::before {
            content: '';
            width: 20px;
            height: 20px;
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.5;
        }
        .form-group:nth-child(1) label::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231a73e8'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E");
        }
        .form-group:nth-child(2) label::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231a73e8'%3E%3Cpath d='M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z'/%3E%3C/svg%3E");
        }
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        .form-group::before {
            display: none;
        }
        .login-btn {
            width: 100%;
            padding: 16px;
            background: #1a73e8;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        .login-btn:hover::before {
            left: 100%;
        }
        .login-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.2);
            background: #1557b0;
        }
        .register-link {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 15px;
            position: relative;
        }
        .register-link::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 1px;
            background: #e0e0e0;
        }
        .register-link a {
            color: #1a73e8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            position: relative;
        }
        .register-link a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #1a73e8;
            transform: scaleX(0);
            transition: transform 0.3s ease;
            transform-origin: right;
        }
        .register-link a:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }
        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #ffcdd2;
            position: relative;
            padding-left: 40px;
        }
        .error-message::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23c62828'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
        }
        @media (max-width: 768px) {
            .login-header h2 {
                font-size: 42px;
            }
            .login-content {
                padding: 60px 20px;
            }
            .login-card {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="index.php"><h1 data-translate="siteTitle">くらしナビ</h1></a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php" data-translate="home">ホーム</a></li>
                <li><a href="services.php" data-translate="services">サービス</a></li>
                <li><a href="guide.php" data-translate="guide">生活ガイド</a></li>
                <li><a href="community.php" data-translate="community">コミュニティ</a></li>
                <li><a href="contact.php" data-translate="contact">お問い合わせ</a></li>
            </ul>
            <div class="nav-buttons">
                <a href="login.php" class="login-button" data-translate="login">ログイン</a>
                <div class="language-selector">
                    <select id="languageSelect" onchange="changeLanguage(this.value)">
                        <option value="ja">日本語</option>
                        <option value="en">English</option>
                        <option value="zh">中文</option>
                    </select>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="login-section">
            <div class="login-header">
                <h2 data-translate="login data-aos=fade-down">ログイン</h2>
                <p class="login-subtitle" data-translate="loginSubtitle">くらしナビのサービスをご利用いただくには、ログインが必要です。</p>
            </div>
            <div class="login-content">
                <div class="login-card" data-aos="fade-up">
                    <h3 data-translate="loginFormTitle">ログイン</h3>
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="username" data-translate="username">ユーザー名</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password" data-translate="password">パスワード</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="login-btn" data-translate="loginButton">ログイン</button>
                    </form>
                    <div class="register-link">
                        <p data-translate="noAccount">アカウントをお持ちでない方は<a href="register.php">こちら</a>から新規登録してください。</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3 data-translate="footerTitle">くらしナビ</h3>
                <p data-translate="footerSubtitle">外国人向け生活サポートサービス</p>
            </div>
            <div class="footer-section">
                <h3 data-translate="contactUs">お問い合わせ</h3>
                <p>Email: kurashinavi@gmail.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p data-translate="copyright">&copy; 2025 くらしナビ All rights reserved.</p>
        </div>
    </footer>

    <script src="js/translations.js"></script>
    <script src="js/main.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        const currentLang = localStorage.getItem('language') || 'ja';
        document.getElementById('languageSelect').value = currentLang;
        changeLanguage(currentLang);
    </script>
</body>
</html>