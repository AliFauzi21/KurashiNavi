<?php
session_start();
require_once 'models/db.php';

// Ambil daftar event/group yang sudah diikuti user jika sudah login
$user_joined_events = [];
$user_joined_groups = [];
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    // Event
    $stmt = $pdo->prepare('SELECT event_id FROM event_participants WHERE user_id = ?');
    $stmt->execute([$uid]);
    $user_joined_events = array_column($stmt->fetchAll(), 'event_id');
    // Group
    $stmt = $pdo->prepare('SELECT group_id FROM group_members WHERE user_id = ?');
    $stmt->execute([$uid]);
    $user_joined_groups = array_column($stmt->fetchAll(), 'group_id');
}
?>
<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ja'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="communityPageTitle">コミュニティ - くらしナビ</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
                <a href="index.php"><h1 data-translate="siteTitle">くらしナビ</h1></a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php" data-translate="home">ホーム</a></li>
                <li><a href="services.php" data-translate="services">サービス</a></li>
                <li><a href="guide.php" data-translate="guide">生活ガイド</a></li>
                <li><a href="community.php" class="active" data-translate="community">コミュニティ</a></li>
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
        <section class="community-hero">
            <div class="community-hero-content" data-aos="fade-up">
                <h1 data-aos="fade-up" data-translate="communityTitle">コミュニティ</h1>
                <p data-aos="fade-up" data-aos-delay="300" data-translate="communitySubtitle">同じ地域の外国人と交流しましょう</p>
                <div class="hero-search" data-aos="fade-up" data-aos-delay="500">
                    <input type="text" data-translate-placeholder="searchPlaceholder" placeholder="イベントやグループを検索...">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
        </section>

        <section class="community-stats" data-aos="fade-up">
            <div class="stat-item" data-aos="zoom-in">
                <i class="fas fa-users"></i>
                <h3>1,200+</h3>
                <p data-translate="members">メンバー</p>
            </div>
            <div class="stat-item" data-aos="zoom-in" data-aos-delay="200">
                <i class="fas fa-calendar-alt"></i>
                <h3>50+</h3>
                <p data-translate="monthlyEvents">月間イベント</p>
            </div>
            <div class="stat-item" data-aos="zoom-in" data-aos-delay="400">
                <i class="fas fa-comments"></i>
                <h3>100+</h3>
                <p data-translate="activeGroups">アクティブグループ</p>
            </div>
        </section>

        <section class="community-events" data-aos="fade-up">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title" data-aos="fade-up" data-translate="eventInfo">イベント情報</h2>
                <div class="event-filters" data-aos="fade-up" data-aos-delay="200">
                    <button class="filter-btn active" data-translate="all">すべて</button>
                    <button class="filter-btn" data-translate="languageExchange">言語交換</button>
                    <button class="filter-btn" data-translate="culturalExperience">文化体験</button>
                    <button class="filter-btn" data-translate="sports">スポーツ</button>
                </div>
            </div>
            <div class="events-grid" data-aos="fade-up">
                <div class="event-card" data-aos="fade-right">
                    <div class="event-date">
                        <span class="month" data-translate="june">6月</span>
                        <span class="day">15</span>
                    </div>
                    <div class="event-content">
                        <div class="event-tags">
                            <span class="tag language" data-translate="languageExchange">言語交換</span>
                            <span class="tag level" data-translate="beginnersWelcome">初心者歓迎</span>
                        </div>
                        <h3 data-translate="japaneseExchange">日本語交流会</h3>
                        <p class="event-time"><i class="far fa-clock"></i> <span data-translate="eventTime">14:00 - 16:00</span></p>
                        <p class="event-location"><i class="fas fa-map-marker-alt"></i> <span data-translate="shinjukuCenter">新宿区文化センター</span></p>
                        <p class="event-description" data-translate="japaneseExchangeDesc">日本語を練習しながら、新しい友達を作りましょう。初心者も大歓迎です。</p>
                        <?php if (in_array(1, $user_joined_events)): ?>
                            <button class="event-button" disabled>参加済み</button>
                        <?php else: ?>
                            <form action="process_join.php" method="POST" style="display:inline;">
                                <input type="hidden" name="type" value="event">
                                <input type="hidden" name="id" value="1">
                                <button type="submit" class="event-button" data-translate="join">参加する</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="event-card" data-aos="fade-right" data-aos-delay="200">
                    <div class="event-date">
                        <span class="month" data-translate="june">6月</span>
                        <span class="day">20</span>
                    </div>
                    <div class="event-content">
                        <div class="event-tags">
                            <span class="tag culture" data-translate="culturalExperience">文化体験</span>
                            <span class="tag featured" data-translate="popular">人気</span>
                        </div>
                        <h3 data-translate="culturalEvent">文化体験イベント</h3>
                        <p class="event-time"><i class="far fa-clock"></i> <span data-translate="eventTime2">10:00 - 15:00</span></p>
                        <p class="event-location"><i class="fas fa-map-marker-alt"></i> <span data-translate="asakusaCenter">浅草文化センター</span></p>
                        <p class="event-description" data-translate="culturalEventDesc">茶道、書道、着物体験など、日本の伝統文化を体験できます。</p>
                        <?php if (in_array(2, $user_joined_events)): ?>
                            <button class="event-button" disabled>参加済み</button>
                        <?php else: ?>
                            <form action="process_join.php" method="POST" style="display:inline;">
                                <input type="hidden" name="type" value="event">
                                <input type="hidden" name="id" value="2">
                                <button type="submit" class="event-button" data-translate="join">参加する</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="event-card" data-aos="fade-right" data-aos-delay="400">
                    <div class="event-date">
                        <span class="month" data-translate="june">6月</span>
                        <span class="day">25</span>
                    </div>
                    <div class="event-content">
                        <div class="event-tags">
                            <span class="tag sports" data-translate="sports">スポーツ</span>
                            <span class="tag new" data-translate="new">新規</span>
                        </div>
                        <h3 data-translate="communityParty">地域交流パーティー</h3>
                        <p class="event-time"><i class="far fa-clock"></i> <span data-translate="eventTime3">18:00 - 20:00</span></p>
                        <p class="event-location"><i class="fas fa-map-marker-alt"></i> <span data-translate="shibuyaCenter">渋谷区民会館</span></p>
                        <p class="event-description" data-translate="communityPartyDesc">地域の日本人と外国人との交流パーティー。料理の持ち寄りも歓迎です。</p>
                        <?php if (in_array(3, $user_joined_events)): ?>
                            <button class="event-button" disabled>参加済み</button>
                        <?php else: ?>
                            <form action="process_join.php" method="POST" style="display:inline;">
                                <input type="hidden" name="type" value="event">
                                <input type="hidden" name="id" value="3">
                                <button type="submit" class="event-button" data-translate="join">参加する</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="community-groups">
            <h2 class="section-title" data-aos="fade-up" data-translate="communityGroups">コミュニティグループ</h2>
            <div class="groups-container">
                <div class="group-card" data-aos="fade-right">
                    <div class="group-icon">
                        <img src="images/community-icon.svg" alt="言語交換" data-translate-alt="languageExchange">
                    </div>
                    <div class="group-content">
                        <h3 data-translate="languageExchangeGroup">言語交換グループ</h3>
                        <p data-translate="languageExchangeDesc">日本語と英語の言語交換パートナーを見つけましょう。</p>
                        <div class="group-stats">
                            <span><i class="fas fa-users"></i> <span data-translate="members150">150人</span></span>
                            <span><i class="fas fa-calendar"></i> <span data-translate="weekly">週1回</span></span>
                        </div>
                        <div class="group-tags">
                            <span data-translate="japanese">日本語</span>
                            <span data-translate="english">英語</span>
                            <span data-translate="beginnersWelcome">初心者歓迎</span>
                        </div>
                        <?php if (in_array(1, $user_joined_groups)): ?>
                            <button class="group-button" disabled>参加済み</button>
                        <?php else: ?>
                            <form action="process_join.php" method="POST" style="display:inline;">
                                <input type="hidden" name="type" value="group">
                                <input type="hidden" name="id" value="1">
                                <button type="submit" class="group-button" data-translate="join">参加する</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="group-card" data-aos="fade-right" data-aos-delay="200">
                    <div class="group-icon">
                        <img src="images/culture-icon.svg" alt="文化活動" data-translate-alt="culturalActivities">
                    </div>
                    <div class="group-content">
                        <h3 data-translate="culturalActivitiesGroup">文化活動グループ</h3>
                        <p data-translate="culturalActivitiesDesc">日本の伝統文化を学び、体験する活動を行っています。</p>
                        <div class="group-stats">
                            <span><i class="fas fa-users"></i> <span data-translate="members80">80人</span></span>
                            <span><i class="fas fa-calendar"></i> <span data-translate="monthly">月2回</span></span>
                        </div>
                        <div class="group-tags">
                            <span data-translate="teaCeremony">茶道</span>
                            <span data-translate="calligraphy">書道</span>
                            <span data-translate="kimono">着物</span>
                        </div>
                        <?php if (in_array(2, $user_joined_groups)): ?>
                            <button class="group-button" disabled>参加済み</button>
                        <?php else: ?>
                            <form action="process_join.php" method="POST" style="display:inline;">
                                <input type="hidden" name="type" value="group">
                                <input type="hidden" name="id" value="2">
                                <button type="submit" class="group-button" data-translate="join">参加する</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="group-card" data-aos="fade-right" data-aos-delay="400">
                    <div class="group-icon">
                        <img src="images/sports-icon.svg" alt="スポーツ" data-translate-alt="sports">
                    </div>
                    <div class="group-content">
                        <h3 data-translate="sportsGroup">スポーツ交流グループ</h3>
                        <p data-translate="sportsGroupDesc">サッカー、バスケットボール、テニスなど、様々なスポーツを楽しめます。</p>
                        <div class="group-stats">
                            <span><i class="fas fa-users"></i> <span data-translate="members120">120人</span></span>
                            <span><i class="fas fa-calendar"></i> <span data-translate="weekends">週末</span></span>
                        </div>
                        <div class="group-tags">
                            <span data-translate="soccer">サッカー</span>
                            <span data-translate="tennis">テニス</span>
                            <span data-translate="basketball">バスケ</span>
                        </div>
                        <?php if (in_array(3, $user_joined_groups)): ?>
                            <button class="group-button" disabled>参加済み</button>
                        <?php else: ?>
                            <form action="process_join.php" method="POST" style="display:inline;">
                                <input type="hidden" name="type" value="group">
                                <input type="hidden" name="id" value="3">
                                <button type="submit" class="group-button" data-translate="join">参加する</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="community-forum">
            <h2 class="section-title" data-aos="fade-up" data-translate="communityForum">コミュニティフォーラム</h2>
            <div class="forum-container">
                <div class="forum-categories">
                    <div class="category-card" data-aos="fade-up">
                        <div class="category-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="category-content">
                            <h3 data-translate="lifeConsultation">生活相談</h3>
                            <p data-translate="lifeConsultationDesc">住まい、医療、教育など、生活に関する質問や相談</p>
                            <div class="category-stats">
                                <span><i class="fas fa-comments"></i> <span data-translate="topics45">トピック: 45</span></span>
                                <span><i class="fas fa-eye"></i> <span data-translate="views1200">閲覧: 1,200</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="category-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="category-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="category-content">
                            <h3 data-translate="hobbiesAndFriends">趣味・交流</h3>
                            <p data-translate="hobbiesAndFriendsDesc">趣味の共有や友達作りのためのトピック</p>
                            <div class="category-stats">
                                <span><i class="fas fa-comments"></i> <span data-translate="topics78">トピック: 78</span></span>
                                <span><i class="fas fa-eye"></i> <span data-translate="views2500">閲覧: 2,500</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="category-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="category-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div class="category-content">
                            <h3 data-translate="localInfo">地域情報</h3>
                            <p data-translate="localInfoDesc">地域のイベントやおすすめスポットの情報</p>
                            <div class="category-stats">
                                <span><i class="fas fa-comments"></i> <span data-translate="topics32">トピック: 32</span></span>
                                <span><i class="fas fa-eye"></i> <span data-translate="views1800">閲覧: 1,800</span></span>
                            </div>
                        </div>
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
                <p data-translate="emailContact">Email: kurashinavi@gmail.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p data-translate="copyright">&copy; 2025 くらしナビ All rights reserved.</p>
        </div>
    </footer>

    <script src="js/translations.js"></script>
    <script src="js/main.js"></script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS
        AOS.init({
            duration: 800,    // Durasi animasi
            easing: 'ease-in-out',    // Jenis animasi
            once: true,    // Animasi hanya dijalankan sekali
            offset: 100,    // Jarak trigger animasi dari bawah viewport
            delay: 0    // Delay sebelum animasi dimulai
        });

        // Event listener untuk filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
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

    <?php if (isset($_SESSION['join_message'])): ?>
        <div class="alert alert-info" style="margin: 20px; text-align:center;">
            <?php echo $_SESSION['join_message']; unset($_SESSION['join_message']); ?>
        </div>
    <?php endif; ?>
</body>
</html> 