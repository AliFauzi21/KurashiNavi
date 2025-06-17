<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="servicesPageTitle">サービス - くらしナビ</title>
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
                <li><a href="index.php" data-translate="home">ホーム</a></li>
                <li><a href="services.php" class="active" data-translate="services">サービス</a></li>
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
        <section class="services-hero">
            <div class="services-hero-content" data-aos="fade-up">
                <h1 data-aos="fade-up" data-translate="servicesTitle">私たちのサービス</h1>
                <p data-aos="fade-up" data-aos-delay="300" data-translate="servicesSubtitle">日本での生活をサポートする総合サービス</p>
            </div>
        </section>

        <section class="services-detail">
            <div class="service-container">
                <div class="service-item" data-aos="fade-right">
                    <div class="service-icon">
                        <img src="images/life-guide-icon.svg" alt="生活ガイド" data-translate-alt="lifeGuideAlt">
                    </div>
                    <div class="service-content">
                        <h2 data-translate="lifeGuideTitle">生活ガイド</h2>
                        <ul class="service-items">
                            <li data-translate="lifeGuideItems.0">住居探しのサポート</li>
                            <li data-translate="lifeGuideItems.1">医療機関の案内</li>
                            <li data-translate="lifeGuideItems.2">教育機関の情報提供</li>
                            <li data-translate="lifeGuideItems.3">行政手続きのサポート</li>
                        </ul>
                        <a href="guide.php" class="service-button" data-translate="viewDetails">詳細を見る</a>
                    </div>
                </div>

                <div class="service-item" data-aos="fade-right" data-aos-delay="200">
                    <div class="service-icon">
                        <img src="images/work-support-icon.svg" alt="仕事サポート" data-translate-alt="workSupportAlt">
                    </div>
                    <div class="service-content">
                        <h2 data-translate="workSupportTitle">仕事サポート</h2>
                        <ul class="service-items">
                            <li data-translate="workSupportItems.0">就職活動支援</li>
                            <li data-translate="workSupportItems.1">ビザ申請サポート</li>
                            <li data-translate="workSupportItems.2">職場でのコミュニケーション支援</li>
                            <li data-translate="workSupportItems.3">キャリアカウンセリング</li>
                        </ul>
                        <a href="#contact" class="service-button" data-translate="viewDetails">詳細を見る</a>
                    </div>
                </div>

                <div class="service-item" data-aos="fade-right" data-aos-delay="400">
                    <div class="service-icon">
                        <img src="images/community-icon.svg" alt="コミュニティ" data-translate-alt="communityAlt">
                    </div>
                    <div class="service-content">
                        <h3 data-translate="communityTitle">コミュニティ</h3>
                        <ul class="service-items">
                            <li data-translate="communityItems.0">言語交換グループ</li>
                            <li data-translate="communityItems.1">文化活動グループ</li>
                            <li data-translate="communityItems.2">スポーツ交流グループ</li>
                            <li data-translate="communityItems.3">地域交流イベント</li>
                        </ul>
                        <a href="community.php" class="service-button" data-translate="viewDetails">詳細を見る</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="service-features">
            <h2 data-aos="fade-up" data-translate="serviceFeatures">サービスの特徴</h2>
            <div class="features-grid">
                <div class="feature-item" data-aos="zoom-in">
                    <h3 data-translate="multilingualSupport">多言語対応</h3>
                    <p data-translate="multilingualDesc">日本語、英語、中国語でのサポート</p>
                </div>
                <div class="feature-item" data-aos="zoom-in" data-aos-delay="200">
                    <h3 data-translate="experiencedStaff">経験豊富なスタッフ</h3>
                    <p data-translate="experiencedStaffDesc">専門知識を持つスタッフがサポート</p>
                </div>
                <div class="feature-item" data-aos="zoom-in" data-aos-delay="400">
                    <h3 data-translate="support24h">24時間サポート</h3>
                    <p data-translate="support24hDesc">緊急時のサポートも対応</p>
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
                <p data-translate="emailContact">Email: info@kurashinavi.jp</p>
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
                if (!translations[lang]) {
                    console.log(`Language not found: ${lang}`);
                    return;
                }

                if (key.includes('.')) {
                    // Handle array items (e.g., "lifeGuideItems.0")
                    const [parentKey, index] = key.split('.');
                    const array = translations[lang][parentKey];
                    if (Array.isArray(array) && array[parseInt(index)] !== undefined) {
                        element.textContent = array[parseInt(index)];
                        console.log(`Array translation: ${key} -> ${array[parseInt(index)]}`);
                    } else {
                        console.log(`Array item not found: ${parentKey}[${index}] in language: ${lang}`);
                    }
                } else {
                    if (translations[lang][key]) {
                        element.textContent = translations[lang][key];
                        console.log(`Simple translation: ${key} -> ${translations[lang][key]}`);
                    } else {
                        console.log(`Translation not found for key: ${key} in language: ${lang}`);
                    }
                }
            });

            // Update alt text for images
            document.querySelectorAll('[data-translate-alt]').forEach(element => {
                const key = element.getAttribute('data-translate-alt');
                if (translations[lang] && translations[lang][key]) {
                    element.alt = translations[lang][key];
                }
            });

            // Update page title
            const titleElement = document.querySelector('title[data-translate]');
            if (titleElement) {
                const key = titleElement.getAttribute('data-translate');
                if (translations[lang] && translations[lang][key]) {
                    document.title = translations[lang][key];
                }
            }
        }
    </script>
</body>
</html> 