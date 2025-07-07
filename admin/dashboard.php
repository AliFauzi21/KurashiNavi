<?php
session_start();
require_once '../models/db.php';

// Cek apakah admin sudah login
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Ambil statistik
try {
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
    $total_users = $stmt->fetch()['total'];

    // Total contacts
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM contacts");
    $total_contacts = $stmt->fetch()['total'];

    // Total community posts
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM community_posts");
    $total_posts = $stmt->fetch()['total'];

    // Recent users
    $stmt = $pdo->query("SELECT username, full_name, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC LIMIT 5");
    $recent_users = $stmt->fetchAll();

    // Recent contacts
    $stmt = $pdo->query("SELECT name, email, message, created_at FROM contacts ORDER BY created_at DESC LIMIT 5");
    $recent_contacts = $stmt->fetchAll();

} catch(PDOException $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $error = 'データの取得中にエラーが発生しました。';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ダッシュボード - くらしナビ</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .sidebar {
            width: 280px;
            background: #1a73e8;
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 0 10px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            color: white;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        .sidebar-menu a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .sidebar-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }
        .content-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a73e8;
            margin: 0;
        }
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            font-size: 16px;
            color: #666;
            margin: 0 0 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .stat-card h3 i {
            color: #1a73e8;
        }
        .stat-card p {
            font-size: 32px;
            font-weight: 700;
            color: #1a73e8;
            margin: 0;
        }
        .recent-activity {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .recent-activity h2 {
            font-size: 20px;
            color: #333;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .recent-activity h2 i {
            color: #1a73e8;
        }
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .activity-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-icon {
            width: 40px;
            height: 40px;
            background: #e8f0fe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1a73e8;
        }
        .activity-content {
            flex: 1;
        }
        .activity-title {
            font-weight: 500;
            color: #333;
            margin: 0 0 5px 0;
        }
        .activity-time {
            font-size: 14px;
            color: #666;
        }
        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            padding: 10px;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
        }
        .admin-info span {
            font-size: 14px;
        }
        .logout-btn {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>管理者パネル</h2>
                <?php if (isset($_SESSION['admin_username'])): ?>
                <div class="admin-info">
                    <span><i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
                <?php endif; ?>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':''; ?>"><i class="fas fa-tachometer-alt"></i> ダッシュボード</a></li>
                <li><a href="manage_users.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='manage_users.php'?'active':''; ?>"><i class="fas fa-users"></i> ユーザー管理</a></li>
                <li><a href="manage_services.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='manage_services.php'?'active':''; ?>"><i class="fas fa-cogs"></i> サービス管理</a></li>
                <li><a href="manage_guide.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='manage_guide.php'?'active':''; ?>"><i class="fas fa-book"></i> ガイド管理</a></li>
                <li><a href="manage_community.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='manage_community.php'?'active':''; ?>"><i class="fas fa-comments"></i> コミュニティ管理</a></li>
                <li><a href="manage_contact.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='manage_contact.php'?'active':''; ?>"><i class="fas fa-envelope"></i> お問い合わせ管理</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> ログアウト</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="content-header">
                <h1>ダッシュボード</h1>
            </div>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3><i class="fas fa-users"></i>総ユーザー数</h3>
                    <p><?php echo number_format($total_users); ?></p>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-envelope"></i>お問い合わせ数</h3>
                    <p><?php echo number_format($total_contacts); ?></p>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-comments"></i>コミュニティ投稿数</h3>
                    <p><?php echo number_format($total_posts); ?></p>
                </div>
            </div>
            
            <div class="recent-activity">
                <h2><i class="fas fa-history"></i>最近のアクティビティ</h2>
                <ul class="activity-list">
                    <?php if (!empty($recent_users)): ?>
                        <?php foreach ($recent_users as $user): ?>
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <h4 class="activity-title">新規ユーザー登録: <?php echo htmlspecialchars($user['full_name']); ?></h4>
                                    <p class="activity-time"><?php echo date('Y/m/d H:i', strtotime($user['created_at'])); ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($recent_contacts)): ?>
                        <?php foreach ($recent_contacts as $contact): ?>
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="activity-content">
                                    <h4 class="activity-title">新規お問い合わせ: <?php echo htmlspecialchars($contact['name']); ?></h4>
                                    <p class="activity-time"><?php echo date('Y/m/d H:i', strtotime($contact['created_at'])); ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php if (empty($recent_users) && empty($recent_contacts)): ?>
                        <li class="activity-item">
                            <div class="activity-content">
                                <p>まだアクティビティはありません。</p>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html> 