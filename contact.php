<?php
session_start();
require_once 'models/translations.php';
?>
<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ja'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('contactPageTitle'); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle login form
            window.toggleLoginForm = function() {
                const modal = document.getElementById('loginModal');
                modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
                const modal = document.getElementById('loginModal');
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            // Add click event listener to login button
            const loginBtn = document.querySelector('.login-btn');
            if (loginBtn) {
                loginBtn.addEventListener('click', toggleLoginForm);
            }
        });
    </script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="index.php"><h1><?php echo __('siteTitle'); ?></h1></a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php" data-translate="home">ホーム</a></li>
                <li><a href="services.php" data-translate="services">サービス</a></li>
                <li><a href="guide.php" data-translate="guide">生活ガイド</a></li>
                <li><a href="community.php" data-translate="community">コミュニティ</a></li>
                <li><a href="contact.php" class="active" data-translate="contact">お問い合わせ</a></li>
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
        <div class="contact-hero" data-aos="fade-down">
            <div>
                <h1><?php echo __('contactTitle'); ?></h1>
                <p><?php echo __('contactSubtitle'); ?></p>
                <div class="contact-icons">
                    <i class="fas fa-envelope contact-icon"></i>
                    <i class="fas fa-phone contact-icon"></i>
                    <i class="fas fa-map-marker-alt contact-icon"></i>
                </div>
            </div>
        </div>

        <div class="contact-container">
            <div class="contact-grid">
                <div class="contact-info" data-aos="fade-right">
                    <div class="contact-info-item">
                        <h3><i class="fas fa-envelope"></i><span><?php echo __('contactInfo'); ?></span></h3>
                        <p><?php echo __('emailContact'); ?></p>
                        <p><?php echo __('phoneContact'); ?></p>
                    </div>
                    <div class="contact-info-item">
                        <h3><i class="fas fa-clock"></i><span><?php echo __('businessHours'); ?></span></h3>
                        <p><?php echo __('weekdayHours'); ?></p>
                        <p><?php echo __('weekendHours'); ?></p>
                    </div>
                    <div class="contact-info-item">
                        <h3><i class="fas fa-map-marker-alt"></i><span><?php echo __('location'); ?></span></h3>
                        <p><?php echo __('postalCode'); ?></p>
                        <p><?php echo __('address'); ?></p>
                    </div>
                </div>

                <div class="contact-form" data-aos="fade-left">
                    <form action="process_contact.php" method="POST">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-user"></i> <span><?php echo __('nameLabel'); ?></span></label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> <span><?php echo __('emailLabel'); ?></span></label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone"><i class="fas fa-phone"></i> <span><?php echo __('phoneLabel'); ?></span></label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="subject"><i class="fas fa-tag"></i> <span><?php echo __('subjectLabel'); ?></span></label>
                            <select id="subject" name="subject" required>
                                <option value=""><?php echo __('selectOption'); ?></option>
                                <option value="general"><?php echo __('generalQuestion'); ?></option>
                                <option value="service"><?php echo __('serviceQuestion'); ?></option>
                                <option value="support"><?php echo __('supportQuestion'); ?></option>
                                <option value="other"><?php echo __('otherQuestion'); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message"><i class="fas fa-comment"></i> <span><?php echo __('messageLabel'); ?></span></label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> <span><?php echo __('submitButton'); ?></span></button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3><?php echo __('footerTitle'); ?></h3>
                <p><?php echo __('footerSubtitle'); ?></p>
            </div>
            <div class="footer-section">
                <h3><?php echo __('contactUs'); ?></h3>
                <p><?php echo __('emailContact'); ?></p>
            </div>
        </div>
        <div class="footer-bottom">
            <p><?php echo __('copyright'); ?></p>
        </div>
    </footer>

    <!-- Login Form Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content" data-aos="fade-up">
            <span class="close" onclick="toggleLoginForm()">&times;</span>
            <h2><?php echo __('loginTitle'); ?></h2>
            <?php if(isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> <?php echo __('usernameLabel'); ?></label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> <?php echo __('passwordLabel'); ?></label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="submit-btn"><?php echo __('loginButton'); ?></button>
            </form>
            <div class="login-links">
                <a href="register.php"><?php echo __('registerLink'); ?></a>
                <a href="forgot-password.php"><?php echo __('forgotPasswordLink'); ?></a>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
</body>
</html> 