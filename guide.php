<?php
session_start();
require_once 'models/Guide.php';
require_once 'models/database.php';

// Koneksi database
$db = new Database();
$conn = $db->getConnection();

// Ambil data guide aktif
$guideModel = new Guide($conn);
$stmt = $guideModel->readAll(true);
$guides = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kelompokkan guide berdasarkan kategori
$groupedGuides = [];
foreach ($guides as $guide) {
    $groupedGuides[$guide['category']][] = $guide;
}

$categoryNames = [
    'housing' => '住居',
    'healthcare' => '医療',
    'education' => '教育',
    'work' => '仕事',
    'daily_life' => '日常生活',
    'admin' => '行政手続き',
];
$categoryDescriptions = [
    'housing' => '住居探しのサポートや必要な書類など、日本での住まいに関する情報をまとめています。',
    'healthcare' => '医療機関の利用方法や健康保険、緊急時の対応など、健康に関する情報を掲載。',
    'education' => '学校の種類や入学手続き、教育に関するポイントを紹介します。',
    'work' => '仕事探しや職場でのサポート、ビザ申請などに役立つ情報。',
    'daily_life' => '日常生活に役立つヒントや便利な情報をまとめています。',
    'admin' => '住民登録や各種届出、行政手続きの流れや注意点を解説。',
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="guidePageTitle">生活ガイド - くらしナビ</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            <div class="services-hero-content">
                <h1 data-translate="guideTitle">生活ガイド</h1>
                <p data-translate="guideSubtitle">日本での生活に必要な情報をまとめました</p>
            </div>
        </section>
        <div class="guides-main">
            <div class="guides-grid">
                <?php
                $categoryIcons = [
                    'housing' => 'images/life-guide-icon.svg',
                    'healthcare' => 'images/medical-icon.svg',
                    'education' => 'images/education-icon.svg',
                    'work' => 'images/work-support-icon.svg',
                    'daily_life' => 'images/culture-icon.svg',
                    'admin' => 'images/admin-icon.svg',
                ];
                foreach ($groupedGuides as $category => $guidesList):
                ?>
                <div class="guide-card" data-aos="fade-up">
                    <div class="guide-badge"><?php echo $categoryNames[$category] ?? 'カテゴリ'; ?></div>
                    <div class="guide-icon">
                        <img src="<?php echo isset($categoryIcons[$category]) ? $categoryIcons[$category] : 'images/life-guide-icon.svg'; ?>" alt="<?php echo $categoryNames[$category] ?? $category; ?>">
                    </div>
                    <div class="guide-card-info">
                        <div class="guide-title"><?php echo $categoryNames[$category] ?? $category; ?></div>
                        <?php if (!empty($categoryDescriptions[$category])): ?>
                            <div class="guide-description"><?php echo $categoryDescriptions[$category]; ?></div>
                        <?php endif; ?>
                        <div class="guide-meta">
                            <span class="meta-badge"><i class="fas fa-tag"></i> <?php echo $categoryNames[$category] ?? $category; ?></span>
                            <?php $firstGuide = $guidesList[0]; ?>
                            <span class="meta-badge meta-date"><i class="fas fa-clock"></i> <?php echo date('Y-m-d', strtotime($firstGuide['created_at'])); ?></span>
                            <span class="meta-count"><i class="fas fa-list"></i> <?php echo count($guidesList); ?> 件</span>
                        </div>
                        <div class="guide-info-box">
                            <ul class="guide-items">
                                <?php foreach ($guidesList as $guide): ?>
                                <li>
                                    <span class="guide-item-title"><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($guide['title']); ?></span>
                                    <div class="guide-item-content"><?php echo nl2br(htmlspecialchars($guide['content'])); ?></div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

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