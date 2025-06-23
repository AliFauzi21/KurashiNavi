<?php
session_start();
require_once 'models/db.php';

// Ambil data services dari database
try {
    $stmt = $pdo->query("SELECT * FROM services WHERE status = 'active' ORDER BY order_number ASC, created_at DESC");
    $services = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Error fetching services: " . $e->getMessage());
    $services = [];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="servicesPageTitle">サービス - くらしナビ</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .services-main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px 60px 20px;
        }
        .services-highlight {
            background: #f5f5f5;
            border-radius: 12px;
            padding: 32px 24px;
            margin-bottom: 40px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .services-highlight h2 {
            color: var(--primary-color);
            font-size: 1.7rem;
            margin-bottom: 10px;
        }
        .services-highlight p {
            color: #555;
            font-size: 1.1rem;
        }
        .service-list {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        .service-box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            border-left: 5px solid var(--primary-color);
            position: relative;
        }
        .service-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .service-title i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        .service-description {
            color: #444;
            margin-bottom: 8px;
        }
        .service-items {
            list-style: none;
            padding: 0;
            margin: 0 0 10px 0;
        }
        .service-items li {
            color: #333;
            padding-left: 22px;
            position: relative;
            margin-bottom: 4px;
        }
        .service-items li:before {
            content: '\f058'; /* fa-check-circle */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--primary-color);
            position: absolute;
            left: 0;
            top: 2px;
            font-size: 1rem;
        }
        .service-link {
            display: inline-block;
            margin-top: 8px;
            color: #fff;
            background: var(--primary-color);
            padding: 8px 22px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: background 0.2s;
        }
        .service-link:hover {
            background: #1557b0;
        }
        .service-divider {
            height: 1px;
            background: #e0e0e0;
            margin: 32px 0 0 0;
            border: none;
        }
        @media (max-width: 700px) {
            .services-main {
                padding: 20px 5px 40px 5px;
            }
            .service-box {
                padding: 20px 10px;
            }
            .services-highlight {
                padding: 18px 8px;
            }
        }

        /* Hero text style konsisten dengan halaman lain */
        .services-hero-content h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
            text-shadow: none;
            letter-spacing: 0.5px;
        }
        .services-hero-content p {
            color: #555;
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 0;
        }
        /* Service Cards Section */
        .services-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px 60px 20px;
        }
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 32px;
        }
        .service-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            padding: 32px 24px 28px 24px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            transition: box-shadow 0.2s, transform 0.2s;
            position: relative;
        }
        .service-card.featured {
            border: 2px solid var(--primary-color);
            box-shadow: 0 4px 18px rgba(26,115,232,0.13);
        }
        .service-card:hover {
            box-shadow: 0 6px 24px rgba(26,115,232,0.13);
            transform: translateY(-4px) scale(1.01);
        }
        .service-card .service-badge {
            position: absolute;
            top: 18px;
            right: 18px;
            background: #e3f0ff;
            color: var(--primary-color);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 12px;
            letter-spacing: 0.5px;
        }
        .service-card .service-icon {
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f7fa;
            border-radius: 50%;
            margin-bottom: 18px;
        }
        .service-card .service-icon img {
            width: 32px;
            height: 32px;
        }
        .service-card .service-icon .default-icon {
            color: var(--primary-color);
            font-size: 2rem;
        }
        .service-card .service-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .service-card .service-category {
            font-size: 0.98rem;
            color: #888;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .service-card .service-category i {
            color: #bbb;
            font-size: 1rem;
        }
        .service-card .service-description {
            color: #444;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        .service-card .service-items {
            list-style: none;
            padding: 0;
            margin: 0 0 12px 0;
        }
        .service-card .service-items li {
            color: #333;
            padding-left: 22px;
            position: relative;
            margin-bottom: 4px;
            font-size: 0.97rem;
        }
        .service-card .service-items li:before {
            content: '\f058';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--primary-color);
            position: absolute;
            left: 0;
            top: 2px;
            font-size: 1rem;
        }
        .service-card .service-meta {
            display: flex;
            gap: 18px;
            align-items: center;
            font-size: 0.93rem;
            color: #888;
            margin-top: 8px;
        }
        .service-card .service-meta i {
            color: #bbb;
            margin-right: 4px;
        }
        .service-card .service-link {
            display: inline-block;
            margin-top: 14px;
            color: #fff;
            background: var(--primary-color);
            padding: 8px 22px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: background 0.2s;
        }
        .service-card .service-link:hover {
            background: #1557b0;
        }
        @media (max-width: 700px) {
            .services-section {
                padding: 20px 5px 40px 5px;
            }
            .services-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .service-card {
                padding: 18px 10px 18px 10px;
            }
        }
        .services-content {
            padding: 5rem 5%;
            background-color: var(--light-gray);
        }
        .services-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .service-section {
            background-color: var(--white);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }
        .service-icon-big {
            flex: 0 0 120px;
            text-align: center;
        }
        .service-icon-big img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .service-icon-big .default-icon {
            color: var(--primary-color);
            font-size: 3rem;
        }
        .service-info {
            flex: 1;
        }
        .service-info h2 {
            color: var(--primary-color);
            margin-bottom: 1.2rem;
            font-size: 1.7rem;
        }
        .service-category {
            font-size: 1.05rem;
            color: #888;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .service-category i {
            color: #bbb;
            font-size: 1rem;
        }
        .service-description {
            color: #444;
            margin-bottom: 1.2rem;
            font-size: 1.08rem;
        }
        .service-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.2rem;
        }
        .service-detail-item {
            background-color: var(--light-gray);
            padding: 1.1rem 1.2rem;
            border-radius: 10px;
        }
        .service-detail-item h3 {
            color: var(--secondary-color);
            margin-bottom: 0.7rem;
            font-size: 1.1rem;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.3rem;
        }
        .service-detail-item ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .service-detail-item ul li {
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
            font-size: 1rem;
        }
        .service-detail-item ul li:before {
            content: "•";
            color: var(--primary-color);
            position: absolute;
            left: 0;
        }
        .service-meta {
            display: flex;
            gap: 18px;
            align-items: center;
            font-size: 0.97rem;
            color: #888;
            margin-top: 10px;
        }
        .service-meta i {
            color: #bbb;
            margin-right: 4px;
        }
        .service-link {
            display: inline-block;
            margin-top: 16px;
            color: #fff;
            background: var(--primary-color);
            padding: 8px 22px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 1.08rem;
            transition: background 0.2s;
        }
        .service-link:hover {
            background: #1557b0;
        }
        @media (max-width: 900px) {
            .service-section {
                flex-direction: column;
                gap: 1.2rem;
                padding: 1.2rem;
            }
            .service-icon-big {
                margin-bottom: 0.5rem;
            }
        }
        .services-hero {
            height: 50vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('images/Index Bg.png');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--white);
            padding-top: 80px;
        }
        .services-hero-content h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--white);
        }
        .services-hero-content p {
            color: #f5f5f5;
            font-size: 1.2rem;
            font-weight: 400;
            margin-bottom: 0;
        }
        @media (max-width: 700px) {
            .services-hero {
                height: 32vh;
                padding-top: 60px;
            }
            .services-hero-content h1 {
                font-size: 2rem;
            }
            .services-hero-content p {
                font-size: 1rem;
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
            <div class="services-hero-content">
                <h1 data-translate="servicesTitle">サービス</h1>
                <p data-translate="servicesSubtitle">日本での生活をサポートする総合サービス</p>
            </div>
        </section>
        <section class="services-content">
            <div class="services-container">
                <?php if (empty($services)): ?>
                    <div class="service-section" style="text-align:center; color:#888; width:100%;">
                        <span class="default-icon"><i class="fas fa-info-circle"></i></span>
                        <div>サービスがありません</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($services as $idx => $service): ?>
                        <?php 
                        $isFeatured = $idx === 0;
                        $badge = $isFeatured ? 'おすすめ' : ($service['status'] === 'active' ? 'アクティブ' : '非アクティブ');
                        $items = json_decode($service['items'], true);
                        $itemCount = is_array($items) ? count($items) : 0;
                        ?>
                        <div class="service-section<?php echo $isFeatured ? ' featured' : ''; ?>">
                            <div class="service-icon-big">
                                <?php if ($service['icon']): ?>
                                    <img src="<?php echo htmlspecialchars($service['icon']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
                                <?php else: ?>
                                    <span class="default-icon"><i class="fas fa-circle-info"></i></span>
                                <?php endif; ?>
                                <div class="service-badge" style="margin-top:10px; display:inline-block; background:#e3f0ff; color:var(--primary-color); font-size:0.85rem; font-weight:600; padding:4px 12px; border-radius:12px; letter-spacing:0.5px;">
                                    <?php echo $badge === 'おすすめ' ? 'おすすめ' : ($badge === 'アクティブ' ? 'アクティブ' : '非アクティブ'); ?>
                                </div>
                            </div>
                            <div class="service-info">
                                <h2><?php echo htmlspecialchars($service['title']); ?></h2>
                                <div class="service-category">
                                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($service['category'] ?: '一般'); ?>
                                </div>
                                <?php if ($service['description']): ?>
                                    <div class="service-description"><?php echo htmlspecialchars($service['description']); ?></div>
                                <?php endif; ?>
                                <?php if ($itemCount > 0): ?>
                                    <div class="service-details">
                                        <div class="service-detail-item">
                                            <h3>サービスの特徴</h3>
                                            <ul>
                                                <?php foreach ($items as $item): ?>
                                                    <li><?php echo htmlspecialchars($item); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="service-meta">
                                    <span><i class="fas fa-list"></i> <?php echo $itemCount; ?> 機能</span>
                                    <span><i class="fas fa-clock"></i> <?php echo date('Y-m-d', strtotime($service['updated_at'])); ?></span>
                                </div>
                                <?php if ($service['link']): ?>
                                    <a href="<?php echo htmlspecialchars($service['link']); ?>" class="service-link" data-translate="viewDetails">
                                        <i class="fas fa-arrow-right"></i> 詳細を見る
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
    <script>
        // Set initial language from localStorage or default to Japanese
        const currentLang = localStorage.getItem('language') || 'ja';
        document.getElementById('languageSelect').value = currentLang;
        changeLanguage(currentLang);
        function changeLanguage(lang) {
            localStorage.setItem('language', lang);
            document.documentElement.lang = lang;
            document.querySelectorAll('[data-translate]').forEach(element => {
                const key = element.getAttribute('data-translate');
                if (!translations[lang]) return;
                if (key.includes('.')) {
                    const [parentKey, index] = key.split('.');
                    const array = translations[lang][parentKey];
                    if (Array.isArray(array) && array[parseInt(index)] !== undefined) {
                        element.textContent = array[parseInt(index)];
                    }
                } else {
                    if (translations[lang][key]) {
                        element.textContent = translations[lang][key];
                    }
                }
            });
            document.querySelectorAll('[data-translate-alt]').forEach(element => {
                const key = element.getAttribute('data-translate-alt');
                if (translations[lang] && translations[lang][key]) {
                    element.alt = translations[lang][key];
                }
            });
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