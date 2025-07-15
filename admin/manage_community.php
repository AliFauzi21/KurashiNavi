<?php
session_start();
require_once '../models/db.php';
require_once '../models/Community.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$community = new Community($pdo);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_event':
                $eventData = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'event_date' => $_POST['event_date'],
                    'start_time' => $_POST['start_time'],
                    'end_time' => $_POST['end_time'],
                    'location' => $_POST['location'],
                    'category' => $_POST['category'],
                    'tags' => explode(',', $_POST['tags']),
                    'max_participants' => $_POST['max_participants'],
                    'featured' => isset($_POST['featured']) ? 1 : 0
                ];
                if ($community->createEvent($eventData)) {
                    $success = 'イベントが正常に作成されました。';
                } else {
                    $error = 'イベントの作成中にエラーが発生しました。';
                }
                break;

            case 'update_event':
                $eventData = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'event_date' => $_POST['event_date'],
                    'start_time' => $_POST['start_time'],
                    'end_time' => $_POST['end_time'],
                    'location' => $_POST['location'],
                    'category' => $_POST['category'],
                    'tags' => explode(',', $_POST['tags']),
                    'max_participants' => $_POST['max_participants'],
                    'featured' => isset($_POST['featured']) ? 1 : 0,
                    'status' => $_POST['status']
                ];
                if ($community->updateEvent($_POST['event_id'], $eventData)) {
                    $success = 'イベントが正常に更新されました。';
                } else {
                    $error = 'イベントの更新中にエラーが発生しました。';
                }
                break;

            case 'delete_event':
                if ($community->deleteEvent($_POST['event_id'])) {
                    $success = 'イベントが正常に削除されました。';
                } else {
                    $error = 'イベントの削除中にエラーが発生しました。';
                }
                break;

            case 'create_group':
                $groupData = [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'category' => $_POST['category'],
                    'icon' => $_POST['icon'],
                    'max_members' => $_POST['max_members'],
                    'meeting_frequency' => $_POST['meeting_frequency'],
                    'tags' => explode(',', $_POST['tags'])
                ];
                if ($community->createGroup($groupData)) {
                    $success = 'グループが正常に作成されました。';
                } else {
                    $error = 'グループの作成中にエラーが発生しました。';
                }
                break;

            case 'update_group':
                $groupData = [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'category' => $_POST['category'],
                    'icon' => $_POST['icon'],
                    'max_members' => $_POST['max_members'],
                    'meeting_frequency' => $_POST['meeting_frequency'],
                    'tags' => explode(',', $_POST['tags']),
                    'status' => $_POST['status']
                ];
                if ($community->updateGroup($_POST['group_id'], $groupData)) {
                    $success = 'グループが正常に更新されました。';
                } else {
                    $error = 'グループの更新中にエラーが発生しました。';
                }
                break;

            case 'delete_group':
                if ($community->deleteGroup($_POST['group_id'])) {
                    $success = 'グループが正常に削除されました。';
                } else {
                    $error = 'グループの削除中にエラーが発生しました。';
                }
                break;

            case 'create_category':
                $categoryData = [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'icon' => $_POST['icon'],
                    'order_number' => $_POST['order_number']
                ];
                if ($community->createForumCategory($categoryData)) {
                    $success = 'フォーラムカテゴリが正常に作成されました。';
                } else {
                    $error = 'フォーラムカテゴリの作成中にエラーが発生しました。';
                }
                break;

            case 'update_category':
                $categoryData = [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'icon' => $_POST['icon'],
                    'order_number' => $_POST['order_number'],
                    'status' => $_POST['status']
                ];
                if ($community->updateForumCategory($_POST['category_id'], $categoryData)) {
                    $success = 'フォーラムカテゴリが正常に更新されました。';
                } else {
                    $error = 'フォーラムカテゴリの更新中にエラーが発生しました。';
                }
                break;

            case 'delete_category':
                if ($community->deleteForumCategory($_POST['category_id'])) {
                    $success = 'フォーラムカテゴリが正常に削除されました。';
                } else {
                    $error = 'フォーラムカテゴリの削除中にエラーが発生しました。';
                }
                break;
        }
    }
}

// Get data for display
$events = $community->getAllEvents();
$groups = $community->getAllGroups();
$forumCategories = $community->getAllForumCategories();
$stats = $community->getCommunityStats();

$eventCategories = $community->getEventCategories();
$groupCategories = $community->getGroupCategories();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コミュニティ管理 - くらしナビ</title>
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
        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            padding: 10px;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            margin-top: 10px;
        }
        .admin-info span {
            font-size: 14px;
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        .stat-card h3 {
            font-size: 24px;
            color: #1a73e8;
            margin: 0 0 10px 0;
        }
        .stat-card p {
            color: #666;
            margin: 0;
        }
        .tab-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .tab-buttons {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .tab-button {
            flex: 1;
            padding: 15px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #666;
            transition: all 0.3s ease;
        }
        .tab-button.active {
            background: white;
            color: #1a73e8;
            border-bottom: 3px solid #1a73e8;
        }
        .tab-content {
            display: none;
            padding: 30px;
        }
        .tab-content.active {
            display: block;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #1a73e8;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>管理者パネル</h2>
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

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h1>コミュニティ管理</h1>
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="openModal('eventModal')">
                        <i class="fas fa-plus"></i> イベント追加
                    </button>
                    <button class="btn btn-success" onclick="openModal('groupModal')">
                        <i class="fas fa-plus"></i> グループ追加
                    </button>
                    <button class="btn btn-warning" onclick="openModal('categoryModal')">
                        <i class="fas fa-plus"></i> カテゴリ追加
                    </button>
                </div>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo $stats['total_events']; ?></h3>
                    <p>総イベント数</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $stats['total_groups']; ?></h3>
                    <p>総グループ数</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $stats['total_categories']; ?></h3>
                    <p>フォーラムカテゴリ</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $stats['total_members']; ?></h3>
                    <p>総メンバー数</p>
                </div>
            </div>

            <!-- Tab Container -->
            <div class="tab-container">
                <div class="tab-buttons">
                    <button class="tab-button active" onclick="showTab('events')">イベント</button>
                    <button class="tab-button" onclick="showTab('groups')">グループ</button>
                    <button class="tab-button" onclick="showTab('forum')">フォーラム</button>
                </div>

                <!-- Events Tab -->
                <div id="events" class="tab-content active">
                    <h2>イベント管理</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>タイトル</th>
                                <th>日付</th>
                                <th>時間</th>
                                <th>場所</th>
                                <th>カテゴリ</th>
                                <th>参加者</th>
                                <th>ステータス</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($event['title']); ?></td>
                                <td><?php echo $event['event_date']; ?></td>
                                <td><?php echo $event['start_time'] . ' - ' . $event['end_time']; ?></td>
                                <td><?php echo htmlspecialchars($event['location']); ?></td>
                                <td><?php echo $eventCategories[$event['category']] ?? $event['category']; ?></td>
                                <td><?php echo $event['current_participants'] . '/' . $event['max_participants']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $event['status'] === 'upcoming' ? 'success' : ($event['status'] === 'ongoing' ? 'warning' : 'secondary'); ?>">
                                        <?php echo $event['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-warning" onclick="editEvent(<?php echo $event['id']; ?>)">編集</button>
                                        <button class="btn btn-danger" onclick="deleteEvent(<?php echo $event['id']; ?>)">削除</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Groups Tab -->
                <div id="groups" class="tab-content">
                    <h2>グループ管理</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>名前</th>
                                <th>カテゴリ</th>
                                <th>メンバー数</th>
                                <th>頻度</th>
                                <th>ステータス</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groups as $group): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($group['name']); ?></td>
                                <td><?php echo $groupCategories[$group['category']] ?? $group['category']; ?></td>
                                <td><?php echo $group['member_count'] . '/' . $group['max_members']; ?></td>
                                <td><?php echo htmlspecialchars($group['meeting_frequency']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $group['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo $group['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-warning" onclick="editGroup(<?php echo $group['id']; ?>)">編集</button>
                                        <button class="btn btn-danger" onclick="deleteGroup(<?php echo $group['id']; ?>)">削除</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Forum Tab -->
                <div id="forum" class="tab-content">
                    <h2>フォーラム管理</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>名前</th>
                                <th>説明</th>
                                <th>トピック数</th>
                                <th>閲覧数</th>
                                <th>順序</th>
                                <th>ステータス</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($forumCategories as $category): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                <td><?php echo htmlspecialchars($category['description']); ?></td>
                                <td><?php echo $category['topic_count']; ?></td>
                                <td><?php echo $category['view_count']; ?></td>
                                <td><?php echo $category['order_number']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $category['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo $category['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-warning" onclick="editCategory(<?php echo $category['id']; ?>)">編集</button>
                                        <button class="btn btn-danger" onclick="deleteCategory(<?php echo $category['id']; ?>)">削除</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <h2>イベント追加</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create_event">
                <div class="form-group">
                    <label>タイトル</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>説明</label>
                    <textarea name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label>日付</label>
                    <input type="date" name="event_date" required>
                </div>
                <div class="form-group">
                    <label>開始時間</label>
                    <input type="time" name="start_time" required>
                </div>
                <div class="form-group">
                    <label>終了時間</label>
                    <input type="time" name="end_time" required>
                </div>
                <div class="form-group">
                    <label>場所</label>
                    <input type="text" name="location" required>
                </div>
                <div class="form-group">
                    <label>カテゴリ</label>
                    <select name="category" required>
                        <?php foreach ($eventCategories as $key => $value): ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>タグ (カンマ区切り)</label>
                    <input type="text" name="tags" placeholder="初心者歓迎, 日本語, 英語">
                </div>
                <div class="form-group">
                    <label>最大参加者数</label>
                    <input type="number" name="max_participants" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="featured"> おすすめイベント
                    </label>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">作成</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('eventModal')">キャンセル</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Group Modal -->
    <div id="groupModal" class="modal">
        <div class="modal-content">
            <h2>グループ追加</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create_group">
                <div class="form-group">
                    <label>名前</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>説明</label>
                    <textarea name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label>カテゴリ</label>
                    <select name="category" required>
                        <?php foreach ($groupCategories as $key => $value): ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>アイコン</label>
                    <input type="text" name="icon" placeholder="images/icon.svg">
                </div>
                <div class="form-group">
                    <label>最大メンバー数</label>
                    <input type="number" name="max_members">
                </div>
                <div class="form-group">
                    <label>会合頻度</label>
                    <input type="text" name="meeting_frequency" placeholder="週1回">
                </div>
                <div class="form-group">
                    <label>タグ (カンマ区切り)</label>
                    <input type="text" name="tags" placeholder="日本語, 英語, 初心者歓迎">
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">作成</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('groupModal')">キャンセル</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="modal">
        <div class="modal-content">
            <h2>フォーラムカテゴリ追加</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create_category">
                <div class="form-group">
                    <label>名前</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>説明</label>
                    <textarea name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label>アイコン (Font Awesome)</label>
                    <input type="text" name="icon" placeholder="fas fa-home">
                </div>
                <div class="form-group">
                    <label>表示順序</label>
                    <input type="number" name="order_number" value="0">
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">作成</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('categoryModal')">キャンセル</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div id="editEventModal" class="modal">
        <div class="modal-content">
            <h2>イベント編集</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_event">
                <input type="hidden" name="event_id" id="edit_event_id">
                <div class="form-group">
                    <label>タイトル</label>
                    <input type="text" name="title" id="edit_event_title" required>
                </div>
                <div class="form-group">
                    <label>説明</label>
                    <textarea name="description" id="edit_event_description" required></textarea>
                </div>
                <div class="form-group">
                    <label>日付</label>
                    <input type="date" name="event_date" id="edit_event_date" required>
                </div>
                <div class="form-group">
                    <label>開始時間</label>
                    <input type="time" name="start_time" id="edit_event_start_time" required>
                </div>
                <div class="form-group">
                    <label>終了時間</label>
                    <input type="time" name="end_time" id="edit_event_end_time" required>
                </div>
                <div class="form-group">
                    <label>場所</label>
                    <input type="text" name="location" id="edit_event_location" required>
                </div>
                <div class="form-group">
                    <label>カテゴリ</label>
                    <select name="category" id="edit_event_category" required>
                        <?php foreach ($eventCategories as $key => $value): ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>タグ (カンマ区切り)</label>
                    <input type="text" name="tags" id="edit_event_tags">
                </div>
                <div class="form-group">
                    <label>最大参加者数</label>
                    <input type="number" name="max_participants" id="edit_event_max_participants" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="featured" id="edit_event_featured"> おすすめイベント
                    </label>
                </div>
                <div class="form-group">
                    <label>ステータス</label>
                    <select name="status" id="edit_event_status">
                        <option value="upcoming">upcoming</option>
                        <option value="ongoing">ongoing</option>
                        <option value="completed">completed</option>
                        <option value="cancelled">cancelled</option>
                    </select>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">更新</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editEventModal')">キャンセル</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Group Modal -->
    <div id="editGroupModal" class="modal">
        <div class="modal-content">
            <h2>グループ編集</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_group">
                <input type="hidden" name="group_id" id="edit_group_id">
                <div class="form-group">
                    <label>名前</label>
                    <input type="text" name="name" id="edit_group_name" required>
                </div>
                <div class="form-group">
                    <label>説明</label>
                    <textarea name="description" id="edit_group_description" required></textarea>
                </div>
                <div class="form-group">
                    <label>カテゴリ</label>
                    <select name="category" id="edit_group_category" required>
                        <?php foreach ($groupCategories as $key => $value): ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>アイコン</label>
                    <input type="text" name="icon" id="edit_group_icon">
                </div>
                <div class="form-group">
                    <label>最大メンバー数</label>
                    <input type="number" name="max_members" id="edit_group_max_members">
                </div>
                <div class="form-group">
                    <label>会合頻度</label>
                    <input type="text" name="meeting_frequency" id="edit_group_meeting_frequency">
                </div>
                <div class="form-group">
                    <label>タグ (カンマ区切り)</label>
                    <input type="text" name="tags" id="edit_group_tags">
                </div>
                <div class="form-group">
                    <label>ステータス</label>
                    <select name="status" id="edit_group_status">
                        <option value="active">active</option>
                        <option value="inactive">inactive</option>
                        <option value="full">full</option>
                    </select>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">更新</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editGroupModal')">キャンセル</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="modal">
        <div class="modal-content">
            <h2>フォーラムカテゴリ編集</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_category">
                <input type="hidden" name="category_id" id="edit_category_id">
                <div class="form-group">
                    <label>名前</label>
                    <input type="text" name="name" id="edit_category_name" required>
                </div>
                <div class="form-group">
                    <label>説明</label>
                    <textarea name="description" id="edit_category_description" required></textarea>
                </div>
                <div class="form-group">
                    <label>アイコン (Font Awesome)</label>
                    <input type="text" name="icon" id="edit_category_icon">
                </div>
                <div class="form-group">
                    <label>表示順序</label>
                    <input type="number" name="order_number" id="edit_category_order_number" value="0">
                </div>
                <div class="form-group">
                    <label>ステータス</label>
                    <select name="status" id="edit_category_status">
                        <option value="active">active</option>
                        <option value="inactive">inactive</option>
                    </select>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">更新</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editCategoryModal')">キャンセル</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Data untuk JS agar fungsi edit bisa berjalan
        const eventsData = <?php echo json_encode($events); ?>;
        const groupsData = <?php echo json_encode($groups); ?>;
        const categoriesData = <?php echo json_encode($forumCategories); ?>;

        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function editEvent(eventId) {
            const event = eventsData.find(e => e.id == eventId);
            if (!event) return;
            document.getElementById('edit_event_id').value = event.id;
            document.getElementById('edit_event_title').value = event.title;
            document.getElementById('edit_event_description').value = event.description;
            document.getElementById('edit_event_date').value = event.event_date;
            document.getElementById('edit_event_start_time').value = event.start_time;
            document.getElementById('edit_event_end_time').value = event.end_time;
            document.getElementById('edit_event_location').value = event.location;
            document.getElementById('edit_event_category').value = event.category;
            document.getElementById('edit_event_tags').value = Array.isArray(event.tags) ? event.tags.join(',') : (event.tags ? JSON.parse(event.tags).join(',') : '');
            document.getElementById('edit_event_max_participants').value = event.max_participants;
            document.getElementById('edit_event_featured').checked = event.featured == 1;
            document.getElementById('edit_event_status').value = event.status;
            openModal('editEventModal');
        }

        function deleteEvent(eventId) {
            if (confirm('このイベントを削除しますか？')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_event">
                    <input type="hidden" name="event_id" value="${eventId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editGroup(groupId) {
            const group = groupsData.find(g => g.id == groupId);
            if (!group) return;
            document.getElementById('edit_group_id').value = group.id;
            document.getElementById('edit_group_name').value = group.name;
            document.getElementById('edit_group_description').value = group.description;
            document.getElementById('edit_group_category').value = group.category;
            document.getElementById('edit_group_icon').value = group.icon;
            document.getElementById('edit_group_max_members').value = group.max_members;
            document.getElementById('edit_group_meeting_frequency').value = group.meeting_frequency;
            document.getElementById('edit_group_tags').value = Array.isArray(group.tags) ? group.tags.join(',') : (group.tags ? JSON.parse(group.tags).join(',') : '');
            document.getElementById('edit_group_status').value = group.status;
            openModal('editGroupModal');
        }

        function deleteGroup(groupId) {
            if (confirm('このグループを削除しますか？')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_group">
                    <input type="hidden" name="group_id" value="${groupId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editCategory(categoryId) {
            const cat = categoriesData.find(c => c.id == categoryId);
            if (!cat) return;
            document.getElementById('edit_category_id').value = cat.id;
            document.getElementById('edit_category_name').value = cat.name;
            document.getElementById('edit_category_description').value = cat.description;
            document.getElementById('edit_category_icon').value = cat.icon;
            document.getElementById('edit_category_order_number').value = cat.order_number;
            document.getElementById('edit_category_status').value = cat.status;
            openModal('editCategoryModal');
        }

        function deleteCategory(categoryId) {
            if (confirm('このカテゴリを削除しますか？')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_category">
                    <input type="hidden" name="category_id" value="${categoryId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html> 