<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>くらしナビ - 外国人向け生活サポート</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="index.php"><h1 data-translate="siteTitle">くらしナビ</h1></a>
            </div>
            <ul class="nav-links">
                <li><a href="#home" class="active" data-translate="home">ホーム</a></li>
                <li><a href="services.php" data-translate="services">サービス</a></li>
                <li><a href="guide.php" data-translate="guide">生活ガイド</a></li>
                <li><a href="community.php" data-translate="community">コミュニティ</a></li>
                <li><a href="contact.php" data-translate="contact">お問い合わせ</a></li>
            </ul>
            <div class="nav-buttons">
                <?php if(isset($_SESSION['username'])): ?>
                    <div class="user-info">
                        <span>ようこそ、<?php echo htmlspecialchars($_SESSION['username']); ?>さん</span>
                        <a href="logout.php" class="logout-button" data-translate="logout">ログアウト</a>
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

    <main style="background-image: url('images/Index Bg.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <section id="hero">
            <div class="hero-content" data-aos="fade-up">
                <h2 data-aos="fade-up" data-translate="heroTitle">日本での生活を、もっと快適に</h2>
                <p data-aos="fade-up" data-aos-delay="300" data-translate="heroSubtitle">外国人向けの生活サポートサービス</p>
                <a href="services.php" class="cta-button" data-aos="zoom-in" data-aos-delay="600" data-translate="viewServices">サービスを見る</a>
            </div>
        </section>

        <section id="services">
            <h2 data-aos="fade-up" data-translate="mainServices">主なサービス</h2>
            <div class="service-grid">
                <div class="service-card" data-aos="fade-right">
                    <h3 data-translate="lifeGuide">生活ガイド</h3>
                    <p data-translate="lifeGuideDesc">住居、医療、教育など、生活に必要な情報を提供</p>
                    <a href="guide.php" class="service-link" data-translate="viewDetails">詳細を見る</a>
                </div>
                <div class="service-card" data-aos="fade-right" data-aos-delay="200">
                    <h3 data-translate="workSupport">仕事サポート</h3>
                    <p data-translate="workSupportDesc">就職活動、ビザ申請、職場でのコミュニケーション支援</p>
                </div>
                <div class="service-card" data-aos="fade-right" data-aos-delay="400">
                    <h3 data-translate="community">コミュニティ</h3>
                    <p data-translate="communityDesc">同じ地域の外国人との交流の場を提供</p>
                    <a href="community.php" class="service-link" data-translate="viewDetails">詳細を見る</a>
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

        // Set initial language from localStorage or default to Japanese
        const currentLang = localStorage.getItem('language') || 'ja';
        document.getElementById('languageSelect').value = currentLang;
        changeLanguage(currentLang);

        function changeLanguage(lang) {
            localStorage.setItem('language', lang);
            document.documentElement.lang = lang;
            
            // Update all elements with data-translate attribute
            document.querySelectorAll('[data-translate]').forEach(element => {
                const key = element.getAttribute('data-translate');
                if (translations[lang] && translations[lang][key]) {
                    element.textContent = translations[lang][key];
                }
            });
        }
    </script>
</body>
</html> 