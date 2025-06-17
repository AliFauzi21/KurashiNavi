<?php
session_start();
require_once 'models/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);
    
    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'すべての項目を入力してください。';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '有効なメールアドレスを入力してください。';
    } elseif (strlen($password) < 6) {
        $error = 'パスワードは6文字以上で入力してください。';
    } else {
        try {
            // Cek username sudah ada atau belum
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->rowCount() > 0) {
                $error = 'このユーザー名は既に使用されています。';
            } else {
                // Cek email sudah ada atau belum
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->rowCount() > 0) {
                    $error = 'このメールアドレスは既に登録されています。';
                } else {
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Simpan ke database
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, status) VALUES (?, ?, ?, ?, 'active')");
                    $stmt->execute([$username, $email, $hashed_password, $full_name]);
                    
                    $success = 'アカウントが正常に作成されました。ログインページに移動します。';
                    // Redirect ke halaman login setelah 2 detik
                    echo "<script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 2000);
                    </script>";
                }
            }
        } catch (PDOException $e) {
            $error = '登録中にエラーが発生しました。後でもう一度お試しください。';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録 - くらしナビ</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .register-section {
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
        .register-header {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 120px 20px 80px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .register-header h2 {
            font-size: 56px;
            font-weight: 700;
            margin-bottom: 24px;
            letter-spacing: 2px;
        }
        .register-header p {
            font-size: 18px;
            margin-top: 15px;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            white-space: nowrap;
        }
        .register-content {
            position: relative;
            z-index: 2;
            background: white;
            padding: 80px 20px;
            margin-top: -40px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
        }
        .register-card {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            right: 50%;
            height: 4px;
            background: #1a73e8;
            transition: all 0.5s ease;
        }
        .register-card:hover::before {
            left: 0;
            right: 0;
        }
        .register-card h3 {
            color: #1a73e8;
            text-align: center;
            margin-bottom: 35px;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
            position: relative;
        }
        .register-card h3::before {
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
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        .form-group label svg {
            color: #1a73e8;
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
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231a73e8'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z'/%3E%3C/svg%3E");
        }
        .form-group:nth-child(3) label::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231a73e8'%3E%3Cpath d='M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z'/%3E%3C/svg%3E");
        }
        .form-group:nth-child(4) label::before {
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
        .register-btn {
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
        .register-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        .register-btn:hover::before {
            left: 100%;
        }
        .register-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.2);
            background: #1557b0;
        }
        .login-link {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 15px;
            position: relative;
        }
        .login-link::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 1px;
            background: #e0e0e0;
        }
        .login-link a {
            color: #1a73e8;
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        .login-link a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #333;
            transform: scaleX(0);
            transition: transform 0.3s ease;
            transform-origin: right;
        }
        .login-link a:hover::after {
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
        .success-message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #c8e6c9;
            position: relative;
            padding-left: 40px;
        }
        .success-message::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232e7d32'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
        }
        @media (max-width: 768px) {
            .register-header h2 {
                font-size: 42px;
            }
            .register-header p {
                font-size: 18px;
            }
            .register-content {
                padding: 60px 20px;
            }
            .register-card {
                padding: 30px;
            }
        }
        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-right: none;
            padding: 0.375rem 0.75rem;
            color: #6c757d;
            border-radius: 0.375rem 0 0 0.375rem;
        }

        .input-group .form-control {
            border-radius: 0 0.375rem 0.375rem 0;
        }

        .input-group .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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
                <?php if(isset($_SESSION['username'])): ?>
                    <div class="user-info">
                        <span>ようこそ、<?php echo htmlspecialchars($_SESSION['username']); ?>さん</span>
                        <a href="logout.php" class="logout-button">ログアウト</a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="login-button" data-translate="login">ログイン</a>
                <?php endif; ?>
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
        <section class="register-section">
            <div class="register-header" data-aos="fade-down">
                <h2 data-translate="registerTitle">新規登録</h2>
                <p data-translate="registerSubtitle">くらしナビのサービスをご利用いただくには、アカウントの作成が必要です。</p>
            </div>
            <div class="register-content">
                <div class="register-card" data-aos="fade-up">
                    <h3 data-translate="registerFormTitle">アカウントを作成</h3>
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="success-message"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="username" data-translate="username">
                                ユーザー名
                            </label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="full_name" data-translate="full_name">
                                氏名
                            </label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="form-group">
                            <label for="email" data-translate="email">
                                メールアドレス
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password" data-translate="password">
                                パスワード
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="register-btn" data-translate="register">登録</button>
                    </form>
                    <div class="login-link">
                        <p data-translate="haveAccount">すでにアカウントをお持ちの方は<a href="login.php">こちら</a>からログインしてください。</p>
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