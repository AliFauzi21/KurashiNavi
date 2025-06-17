<?php
require_once '../includes/config.php';

// Cek apakah admin sudah login
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Proses form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        try {
            switch($_POST['action']) {
                case 'add':
                    $stmt = $conn->prepare("INSERT INTO guides (title, content, category, status) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$_POST['title'], $_POST['content'], $_POST['category'], $_POST['status']]);
                    break;
                case 'edit':
                    $stmt = $conn->prepare("UPDATE guides SET title = ?, content = ?, category = ?, status = ? WHERE id = ?");
                    $stmt->execute([$_POST['title'], $_POST['content'], $_POST['category'], $_POST['status'], $_POST['id']]);
                    break;
                case 'delete':
                    $stmt = $conn->prepare("DELETE FROM guides WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    break;
            }
        } catch(PDOException $e) {
            $error = 'エラーが発生しました。';
        }
    }
}

// Ambil data panduan
try {
    $stmt = $conn->query("SELECT * FROM guides ORDER BY created_at DESC");
    $guides = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = 'データの取得中にエラーが発生しました。';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ガイド管理 - くらしナビ</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
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
        .add-button {
            padding: 12px 24px;
            background: #1a73e8;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .add-button:hover {
            background: #1557b0;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.2);
        }
        .guides-table {
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .guides-table th,
        .guides-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .guides-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .guides-table tr:last-child td {
            border-bottom: none;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .edit-button,
        .delete-button {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .edit-button {
            background: #1a73e8;
            color: white;
        }
        .edit-button:hover {
            background: #1557b0;
        }
        .delete-button {
            background: #dc3545;
            color: white;
        }
        .delete-button:hover {
            background: #c82333;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .modal-header {
            margin-bottom: 20px;
        }
        .modal-header h2 {
            font-size: 24px;
            color: #333;
            margin: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #1a73e8;
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }
        .close-button {
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .close-button:hover {
            background: #5a6268;
        }
        .submit-button {
            padding: 10px 20px;
            background: #1a73e8;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .submit-button:hover {
            background: #1557b0;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>くらしナビ</h2>
                <div class="admin-info">
                    <span>管理者: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">ダッシュボード</a></li>
                <li><a href="manage_services.php">サービス管理</a></li>
                <li><a href="manage_guide.php" class="active">ガイド管理</a></li>
                <li><a href="manage_community.php">コミュニティ管理</a></li>
                <li><a href="manage_contact.php">お問い合わせ管理</a></li>
                <li><a href="manage_users.php">ユーザー管理</a></li>
                <li><a href="logout.php" class="logout-btn">ログアウト</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="content-header">
                <h1>ガイド管理</h1>
                <button class="add-button" onclick="showAddModal()">新規ガイド追加</button>
            </div>
            
            <table class="guides-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>タイトル</th>
                        <th>カテゴリー</th>
                        <th>ステータス</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($guides as $guide): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($guide['id']); ?></td>
                        <td><?php echo htmlspecialchars($guide['title']); ?></td>
                        <td><?php echo htmlspecialchars($guide['category']); ?></td>
                        <td><?php echo htmlspecialchars($guide['status']); ?></td>
                        <td class="action-buttons">
                            <button class="edit-button" onclick="showEditModal(<?php echo htmlspecialchars(json_encode($guide)); ?>)">編集</button>
                            <button class="delete-button" onclick="deleteGuide(<?php echo $guide['id']; ?>)">削除</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk tambah/edit panduan -->
    <div id="guideModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">新規ガイド追加</h2>
            </div>
            <form id="guideForm" method="POST">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="guideId">
                
                <div class="form-group">
                    <label for="title">タイトル</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="category">カテゴリー</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="housing">住居</option>
                        <option value="healthcare">医療</option>
                        <option value="education">教育</option>
                        <option value="work">仕事</option>
                        <option value="daily_life">日常生活</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="content">内容</label>
                    <textarea id="content" name="content" class="form-control" rows="8" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status">ステータス</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="active">有効</option>
                        <option value="inactive">無効</option>
                    </select>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="close-button" onclick="closeModal()">キャンセル</button>
                    <button type="submit" class="submit-button">保存</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddModal() {
            document.getElementById('modalTitle').textContent = '新規ガイド追加';
            document.getElementById('formAction').value = 'add';
            document.getElementById('guideForm').reset();
            document.getElementById('guideModal').style.display = 'block';
        }

        function showEditModal(guide) {
            document.getElementById('modalTitle').textContent = 'ガイド編集';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('guideId').value = guide.id;
            document.getElementById('title').value = guide.title;
            document.getElementById('category').value = guide.category;
            document.getElementById('content').value = guide.content;
            document.getElementById('status').value = guide.status;
            document.getElementById('guideModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('guideModal').style.display = 'none';
        }

        function deleteGuide(id) {
            if(confirm('このガイドを削除してもよろしいですか？')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Tutup modal jika klik di luar modal
        window.onclick = function(event) {
            const modal = document.getElementById('guideModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html> 