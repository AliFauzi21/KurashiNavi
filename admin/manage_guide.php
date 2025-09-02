<?php
require_once '../models/database.php';
require_once '../models/Guide.php';
require_once 'auth_check.php';

// Sistem keamanan sudah otomatis berjalan dari auth_check.php

$message = '';
$error = '';

$db = new Database();
$conn = $db->getConnection();
$guideModel = new Guide($conn);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $title = trim($_POST['title']);
            $category = trim($_POST['category']);
            $content = trim($_POST['content']);
            $status = $_POST['status'];
            if (empty($title) || empty($category) || empty($content)) {
                $error = '全ての項目を入力してください。';
            } else {
                try {
                    if ($_POST['action'] === 'add') {
                        $guideModel->title = $title;
                        $guideModel->content = $content;
                        $guideModel->category = $category;
                        $guideModel->status = $status;
                        $guideModel->create();
                        $message = 'ガイドが正常に追加されました。';
                    } else {
                        $guideModel->id = $_POST['id'];
                        $guideModel->title = $title;
                        $guideModel->content = $content;
                        $guideModel->category = $category;
                        $guideModel->status = $status;
                        $guideModel->update();
                        $message = 'ガイドが正常に更新されました。';
                    }
                } catch(PDOException $e) {
                    $error = 'データベースエラーが発生しました: ' . $e->getMessage();
                }
            }
        } elseif ($_POST['action'] === 'delete') {
            $guideModel->id = $_POST['id'];
            try {
                $guideModel->delete();
                $message = 'ガイドが正常に削除されました。';
            } catch(PDOException $e) {
                $error = '削除エラーが発生しました: ' . $e->getMessage();
            }
        }
    }
}

// Ambil data guide
global $guides;
try {
    $stmt = $guideModel->readAll();
    $guides = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = 'データ取得エラーが発生しました: ' . $e->getMessage();
    $guides = [];
}

// Ambil data untuk edit (jika ada)
$editGuide = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $guideModel->id = $_GET['edit'];
    $guideModel->readOne();
    $editGuide = [
        'id' => $guideModel->id,
        'title' => $guideModel->title,
        'category' => $guideModel->category,
        'content' => $guideModel->content,
        'status' => $guideModel->status
    ];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ガイド管理 - くらしナビ管理システム</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; background: #f8f9fa; }
        .sidebar { width: 280px; background: #1a73e8; color: white; padding: 30px 20px; position: fixed; height: 100vh; overflow-y: auto; }
        .sidebar-header { padding: 0 10px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px; }
        .sidebar-header h2 { font-size: 24px; font-weight: 700; margin: 0; color: white; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li { margin-bottom: 5px; }
        .sidebar-menu a { color: rgba(255,255,255,0.8); text-decoration: none; display: flex; align-items: center; padding: 12px 15px; border-radius: 8px; transition: all 0.3s ease; }
        .sidebar-menu a:hover { background: rgba(255,255,255,0.1); color: white; }
        .sidebar-menu a.active { background: rgba(255,255,255,0.2); color: white; }
        .sidebar-menu a i { margin-right: 10px; width: 20px; text-align: center; }
        .main-content { flex: 1; margin-left: 280px; padding: 30px; }
        .content-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .content-header h1 { font-size: 28px; font-weight: 700; color: #1a73e8; margin: 0; }
        .add-button { padding: 12px 24px; background: #1a73e8; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .add-button:hover { background: #1557b0; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(26, 115, 232, 0.2); }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        .message.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .form-section { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .form-section h2 { margin-top: 0; color: #333; font-size: 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .form-section h2 i { color: #1a73e8; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; font-family: inherit; transition: all 0.3s ease; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color: #1a73e8; outline: none; box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1); }
        .form-group textarea { height: 120px; resize: vertical; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: #1a73e8; color: white; }
        .btn-primary:hover { background: #1557b0; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(26, 115, 232, 0.2); }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #545b62; transform: translateY(-1px); }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; transform: translateY(-1px); }
        .guides-table { width: 100%; background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden; }
        .guides-table th, .guides-table td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #eee; }
        .guides-table th { background: #f8f9fa; font-weight: 600; color: #333; font-size: 14px; }
        .guides-table tr:last-child td { border-bottom: none; }
        .guides-table tr:hover { background: #f8f9fa; }
        .action-buttons { display: flex; gap: 8px; }
        .status-active { color: #28a745; font-weight: 500; }
        .status-inactive { color: #dc3545; font-weight: 500; }
        .admin-info { display: flex; align-items: center; gap: 10px; color: white; padding: 10px; border-radius: 8px; background: rgba(255,255,255,0.1); }
        .admin-info span { font-size: 14px; }
        .logout-btn { color: rgba(255,255,255,0.8); text-decoration: none; padding: 8px 15px; border-radius: 6px; transition: all 0.3s ease; }
        .logout-btn:hover { background: rgba(255,255,255,0.1); color: white; }
        @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } .action-buttons { flex-direction: column; } }
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
                <h1>ガイド管理</h1>
                <?php if (!$editGuide): ?>
                    <a href="#form-section" class="add-button">
                        <i class="fas fa-plus"></i>新しいガイド追加
                    </a>
                <?php endif; ?>
            </div>
            <?php if ($message): ?>
                <div class="message success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="message error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <!-- Form untuk tambah/edit guide -->
            <div class="form-section" id="form-section">
                <h2><i class="fas fa-edit"></i><?php echo $editGuide ? 'ガイド編集' : '新しいガイド追加'; ?></h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="<?php echo $editGuide ? 'edit' : 'add'; ?>">
                    <?php if ($editGuide): ?>
                        <input type="hidden" name="id" value="<?php echo $editGuide['id']; ?>">
                    <?php endif; ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">タイトル *</label>
                            <input type="text" id="title" name="title" value="<?php echo $editGuide ? htmlspecialchars($editGuide['title']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="category">カテゴリ *</label>
                            <select id="category" name="category" required>
                                <option value="housing" <?php echo ($editGuide && $editGuide['category'] === 'housing') ? 'selected' : ''; ?>>住居</option>
                                <option value="healthcare" <?php echo ($editGuide && $editGuide['category'] === 'healthcare') ? 'selected' : ''; ?>>医療</option>
                                <option value="education" <?php echo ($editGuide && $editGuide['category'] === 'education') ? 'selected' : ''; ?>>教育</option>
                                <option value="work" <?php echo ($editGuide && $editGuide['category'] === 'work') ? 'selected' : ''; ?>>仕事</option>
                                <option value="daily_life" <?php echo ($editGuide && $editGuide['category'] === 'daily_life') ? 'selected' : ''; ?>>日常生活</option>
                                <option value="admin" <?php echo ($editGuide && $editGuide['category'] === 'admin') ? 'selected' : ''; ?>>行政手続き</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content">内容 *</label>
                        <textarea id="content" name="content" required><?php echo $editGuide ? htmlspecialchars($editGuide['content']) : ''; ?></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">ステータス</label>
                            <select id="status" name="status">
                                <option value="active" <?php echo ($editGuide && $editGuide['status'] === 'active') ? 'selected' : ''; ?>>アクティブ</option>
                                <option value="inactive" <?php echo ($editGuide && $editGuide['status'] === 'inactive') ? 'selected' : ''; ?>>非アクティブ</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i><?php echo $editGuide ? '更新' : '追加'; ?>
                    </button>
                    <?php if ($editGuide): ?>
                        <a href="manage_guide.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i>キャンセル
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <!-- Tabel guides -->
            <div class="form-section">
                <h2><i class="fas fa-list"></i>ガイド一覧</h2>
                <table class="guides-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>タイトル</th>
                            <th>カテゴリ</th>
                            <th>内容</th>
                            <th>ステータス</th>
                            <th>作成日</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($guides)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px; color: #666;">まだガイドがありません。</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($guides as $guide): ?>
                                <tr>
                                    <td><?php echo $guide['id']; ?></td>
                                    <td><?php echo htmlspecialchars($guide['title']); ?></td>
                                    <td><?php echo htmlspecialchars($guide['category']); ?></td>
                                    <td><?php echo htmlspecialchars(mb_strimwidth($guide['content'], 0, 50, '...')); ?></td>
                                    <td class="<?php echo $guide['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo $guide['status'] === 'active' ? 'アクティブ' : '非アクティブ'; ?>
                                    </td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($guide['created_at'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="?edit=<?php echo $guide['id']; ?>" class="btn btn-secondary"><i class="fas fa-edit"></i>編集</a>
                                            <form method="POST" action="" style="display: inline;" onsubmit="return confirm('本当に削除しますか？');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $guide['id']; ?>">
                                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>削除</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 