<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="contactPageTitle">お問い合わせ - くらしナビ</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                <li><a href="contact.php" class="active" data-translate="contact">お問い合わせ</a></li>
            </ul>
            <div class="language-selector">
                <select id="languageSelect" onchange="changeLanguage(this.value)">
                    <option value="ja">日本語</option>
                    <option value="en">English</option>
                    <option value="zh">中文</option>
                </select>
            </div>
        </nav>
    </header>

    <main>
        <div class="contact-hero" data-aos="fade-down">
            <div>
                <h1 data-translate="contactTitle">お問い合わせ</h1>
                <p data-translate="contactSubtitle">ご質問やご相談がございましたら、お気軽にお問い合わせください。</p>
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
                        <h3><i class="fas fa-envelope"></i><span data-translate="contactInfo">お問い合わせ先</span></h3>
                        <p data-translate="emailContact">Email: info@kurashinavi.jp</p>
                        <p data-translate="phoneContact">電話: 03-XXXX-XXXX</p>
                    </div>
                    <div class="contact-info-item">
                        <h3><i class="fas fa-clock"></i><span data-translate="businessHours">営業時間</span></h3>
                        <p data-translate="weekdayHours">平日: 9:00 - 18:00</p>
                        <p data-translate="weekendHours">土日祝日: 休業</p>
                    </div>
                    <div class="contact-info-item">
                        <h3><i class="fas fa-map-marker-alt"></i><span data-translate="location">所在地</span></h3>
                        <p data-translate="postalCode">〒XXX-XXXX</p>
                        <p data-translate="address">東京都XX区XX町X-X-X</p>
                    </div>
                </div>

                <div class="contact-form" data-aos="fade-left">
                    <form action="process_contact.php" method="POST">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-user"></i> <span data-translate="nameLabel">お名前 *</span></label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> <span data-translate="emailLabel">メールアドレス *</span></label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone"><i class="fas fa-phone"></i> <span data-translate="phoneLabel">電話番号</span></label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="subject"><i class="fas fa-tag"></i> <span data-translate="subjectLabel">件名 *</span></label>
                            <select id="subject" name="subject" required>
                                <option value="" data-translate="selectOption">選択してください</option>
                                <option value="general" data-translate="generalQuestion">一般的な質問</option>
                                <option value="service" data-translate="serviceQuestion">サービスについて</option>
                                <option value="support" data-translate="supportQuestion">サポート</option>
                                <option value="other" data-translate="otherQuestion">その他</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message"><i class="fas fa-comment"></i> <span data-translate="messageLabel">メッセージ *</span></label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> <span data-translate="submitButton">送信する</span></button>
                    </form>
                </div>
            </div>
        </div>
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

        // Initialize language
        window.addEventListener('load', function() {
            const currentLang = localStorage.getItem('language') || 'ja';
            const languageSelect = document.getElementById('languageSelect');
            if (languageSelect) {
                languageSelect.value = currentLang;
            }
            if (typeof changeLanguage === 'function') {
                changeLanguage(currentLang);
            }
        });
    </script>
</body>
</html> 