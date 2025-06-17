<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="guidePageTitle">生活ガイド - くらしナビ</title>
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
                <li><a href="services.php" data-translate="services">サービス</a></li>
                <li><a href="guide.php" class="active" data-translate="guide">生活ガイド</a></li>
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
        <section class="guide-hero">
            <div class="guide-hero-content" data-aos="fade-up">
                <h1 data-aos="fade-up" data-translate="guideTitle">生活ガイド</h1>
                <p data-aos="fade-up" data-aos-delay="300" data-translate="guideSubtitle">日本での生活に必要な情報をまとめました</p>
                <div class="guide-search" data-aos="fade-up" data-aos-delay="500">
                    <input type="text" data-translate-placeholder="searchPlaceholder" placeholder="情報を検索...">
                    <button type="button" data-translate="searchButton">検索</button>
                </div>
            </div>
        </section>

        <section class="guide-content">
            <div class="guide-container">
                <!-- 住居探し -->
                <div class="guide-section" data-aos="fade-right">
                    <div class="guide-icon">
                        <img src="images/life-guide-icon.svg" alt="住居探し" data-translate-alt="housingSearchAlt">
                    </div>
                    <div class="guide-text">
                        <h2 data-translate="housingSearchTitle">住居探し</h2>
                        <div class="guide-details">
                            <div class="guide-detail-item">
                                <h3 data-translate="requiredDocuments">必要な書類</h3>
                                <ul>
                                    <li data-translate="documents.0">在留カード</li>
                                    <li data-translate="documents.1">パスポート</li>
                                    <li data-translate="documents.2">収入証明書</li>
                                    <li data-translate="documents.3">連帯保証人</li>
                                </ul>
                            </div>
                            <div class="guide-detail-item">
                                <h3 data-translate="costs">費用</h3>
                                <ul>
                                    <li data-translate="costs.0">敷金（2ヶ月分）</li>
                                    <li data-translate="costs.1">礼金（1-2ヶ月分）</li>
                                    <li data-translate="costs.2">仲介手数料（1ヶ月分）</li>
                                    <li data-translate="costs.3">火災保険</li>
                                </ul>
                            </div>
                        </div>
                        <div class="guide-tips-box">
                            <h4 data-translate="usefulInfo">便利な情報</h4>
                            <p data-translate="housingTips">物件探しは、駅からの距離や周辺施設も確認しましょう。また、契約前に内見することをお勧めします。</p>
                        </div>
                    </div>
                </div>

                <!-- 医療機関 -->
                <div class="guide-section" data-aos="fade-right" data-aos-delay="200">
                    <div class="guide-icon">
                        <img src="images/medical-icon.svg" alt="医療機関" data-translate-alt="medicalAlt">
                    </div>
                    <div class="guide-text">
                        <h2 data-translate="medicalTitle">医療機関</h2>
                        <div class="guide-details">
                            <div class="guide-detail-item">
                                <h3 data-translate="healthInsurance">健康保険</h3>
                                <ul>
                                    <li data-translate="insurance.0">国民健康保険</li>
                                    <li data-translate="insurance.1">社会保険</li>
                                    <li data-translate="insurance.2">保険証の取得方法</li>
                                </ul>
                            </div>
                            <div class="guide-detail-item">
                                <h3 data-translate="hospitalUsage">病院の利用方法</h3>
                                <ul>
                                    <li data-translate="hospital.0">予約方法</li>
                                    <li data-translate="hospital.1">診察料金</li>
                                    <li data-translate="hospital.2">薬の処方</li>
                                </ul>
                            </div>
                        </div>
                        <div class="guide-tips-box">
                            <h4 data-translate="emergencyInfo">緊急時の対応</h4>
                            <p data-translate="emergencyTips">夜間や休日の急病の場合は、救急病院を利用できます。救急車は119番に電話してください。</p>
                        </div>
                    </div>
                </div>

                <!-- 教育機関 -->
                <div class="guide-section" data-aos="fade-right" data-aos-delay="400">
                    <div class="guide-icon">
                        <img src="images/education-icon.svg" alt="教育機関" data-translate-alt="educationAlt">
                    </div>
                    <div class="guide-text">
                        <h2 data-translate="educationTitle">教育機関</h2>
                        <div class="guide-details">
                            <div class="guide-detail-item">
                                <h3 data-translate="schoolTypes">学校の種類</h3>
                                <ul>
                                    <li data-translate="schools.0">公立学校</li>
                                    <li data-translate="schools.1">私立学校</li>
                                    <li data-translate="schools.2">インターナショナルスクール</li>
                                </ul>
                            </div>
                            <div class="guide-detail-item">
                                <h3 data-translate="enrollment">入学手続き</h3>
                                <ul>
                                    <li data-translate="enrollment.0">必要な書類</li>
                                    <li data-translate="enrollment.1">入学試験</li>
                                    <li data-translate="enrollment.2">学費</li>
                                </ul>
                            </div>
                        </div>
                        <div class="guide-tips-box">
                            <h4 data-translate="schoolSelection">学校選びのポイント</h4>
                            <p data-translate="schoolTips">学校の教育方針、通学時間、学費、言語サポート体制などを確認しましょう。</p>
                        </div>
                    </div>
                </div>

                <!-- 行政手続き -->
                <div class="guide-section" data-aos="fade-right" data-aos-delay="600">
                    <div class="guide-icon">
                        <img src="images/admin-icon.svg" alt="行政手続き" data-translate-alt="adminAlt">
                    </div>
                    <div class="guide-text">
                        <h2 data-translate="adminTitle">行政手続き</h2>
                        <div class="guide-details">
                            <div class="guide-detail-item">
                                <h3 data-translate="residenceRegistration">住民登録</h3>
                                <ul>
                                    <li data-translate="registration.0">転入届</li>
                                    <li data-translate="registration.1">マイナンバー</li>
                                    <li data-translate="registration.2">住民票</li>
                                </ul>
                            </div>
                            <div class="guide-detail-item">
                                <h3 data-translate="variousNotifications">各種届出</h3>
                                <ul>
                                    <li data-translate="notifications.0">婚姻届</li>
                                    <li data-translate="notifications.1">出生届</li>
                                    <li data-translate="notifications.2">死亡届</li>
                                </ul>
                            </div>
                        </div>
                        <div class="guide-tips-box">
                            <h4 data-translate="procedureNotes">手続きの注意点</h4>
                            <p data-translate="procedureTips">手続きには本人確認書類が必要です。また、期限がある届出は早めに準備しましょう。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="guide-tips">
            <h2 data-aos="fade-up" data-translate="lifeTips">生活のヒント</h2>
            <div class="tips-grid">
                <div class="tip-item" data-aos="zoom-in">
                    <div class="tip-icon">🗑️</div>
                    <h3 data-translate="garbageTitle">ゴミの分別</h3>
                    <p data-translate="garbageDesc">地域ごとのルールを確認しましょう</p>
                    <a href="#" class="tip-link" data-translate="viewDetails">詳細を見る</a>
                </div>
                <div class="tip-item" data-aos="zoom-in" data-aos-delay="200">
                    <div class="tip-icon">🚇</div>
                    <h3 data-translate="publicMannersTitle">公共マナー</h3>
                    <p data-translate="publicMannersDesc">電車内での携帯電話使用など</p>
                    <a href="#" class="tip-link" data-translate="viewDetails">詳細を見る</a>
                </div>
                <div class="tip-item" data-aos="zoom-in" data-aos-delay="400">
                    <div class="tip-icon">🏥</div>
                    <h3 data-translate="disasterTitle">防災対策</h3>
                    <p data-translate="disasterDesc">非常用品の準備と避難場所の確認</p>
                    <a href="#" class="tip-link" data-translate="viewDetails">詳細を見る</a>
                </div>
                <div class="tip-item" data-aos="zoom-in" data-aos-delay="600">
                    <div class="tip-icon">🗣️</div>
                    <h3 data-translate="communityTitle">コミュニティに参加</h3>
                    <p data-translate="communityDesc">地域のコミュニティに参加して、新しい友達を作りましょう。</p>
                    <a href="community.php" class="tip-link" data-translate="goToCommunity">コミュニティページへ →</a>
                </div>
            </div>
        </section>

        <section class="guide-cta">
            <div class="cta-content animate-fade-in">
                <h2 data-translate="ctaTitle">お困りのことはありませんか？</h2>
                <p data-translate="ctaSubtitle">専門スタッフがサポートいたします</p>
                <a href="contact.php" class="cta-button" data-translate="contactUs">お問い合わせ</a>
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
        // Initialize AOS
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