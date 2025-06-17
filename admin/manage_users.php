<?php
session_start();
require_once '../models/db.php';

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $username = trim($_POST['username']);
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
                $full_name = trim($_POST['full_name']);
                $role = isset($_POST['role']) ? $_POST['role'] : 'user';

                if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
                    $error = 'すべての必須フィールドを入力してください。';
                } else {
                    try {
                        // Cek username dan email yang sudah ada
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
                        $stmt->execute([$username, $email]);
                        if ($stmt->fetchColumn() > 0) {
                            $error = 'ユーザー名またはメールアドレスが既に使用されています。';
                        } else {
                            // Hash password
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            
                            // Insert user baru
                            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                            $stmt->execute([$username, $email, $hashed_password, $full_name, $role]);
                            $success = 'ユーザーが正常に追加されました。';
                        }
                    } catch (PDOException $e) {
                        error_log("Add user error: " . $e->getMessage());
                        $error = 'エラーが発生しました。後でもう一度お試しください。';
                    }
                }
                break;

            case 'edit':
                $user_id = $_POST['user_id'];
                $email = trim($_POST['email']);
                $full_name = trim($_POST['full_name']);
                $role = $_POST['role'];

                if (empty($email) || empty($full_name)) {
                    $error = 'すべての必須フィールドを入力してください。';
                } else {
                    try {
                        // Cek email yang sudah ada (kecuali untuk user yang sedang diedit)
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
                        $stmt->execute([$email, $user_id]);
                        if ($stmt->fetchColumn() > 0) {
                            $error = 'メールアドレスが既に使用されています。';
                        } else {
                            // Update user
                            $stmt = $pdo->prepare("UPDATE users SET email = ?, full_name = ?, role = ? WHERE id = ?");
                            $stmt->execute([$email, $full_name, $role, $user_id]);
                            
                            // Update password jika diisi
                            if (!empty($_POST['password'])) {
                                $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                                $stmt->execute([$hashed_password, $user_id]);
                            }
                            
                            $success = 'ユーザー情報が正常に更新されました。';
                        }
                    } catch (PDOException $e) {
                        error_log("Edit user error: " . $e->getMessage());
                        $error = 'エラーが発生しました。後でもう一度お試しください。';
                    }
                }
                break;

            case 'delete':
                $user_id = $_POST['user_id'];
                try {
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $success = 'ユーザーが正常に削除されました。';
                } catch (PDOException $e) {
                    error_log("Delete user error: " . $e->getMessage());
                    $error = 'エラーが発生しました。後でもう一度お試しください。';
                }
                break;
        }
    }
}

// Ambil data users
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Fetch users error: " . $e->getMessage());
    $error = 'ユーザーデータの取得中にエラーが発生しました。';
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー管理 - くらしナビ</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .content-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a73e8;
            margin: 0;
        }
        .add-user-btn {
            background: #1a73e8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .add-user-btn:hover {
            background: #1557b0;
            transform: translateY(-1px);
        }
        .users-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px;
        }
        .users-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .users-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #e0e0e0;
        }
        .users-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            color: #555;
            font-size: 14px;
            vertical-align: middle;
        }
        .users-table tr:last-child td {
            border-bottom: none;
        }
        .users-table tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            min-width: 80px;
        }
        .status-active {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .status-inactive {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85em;
            font-weight: 500;
        }
        .role-admin {
            background-color: #4CAF50;
            color: white;
        }
        .role-user {
            background-color: #2196F3;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
        }
        .edit-btn, .delete-btn {
            padding: 8px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }
        .edit-btn {
            background: #e3f2fd;
            color: #1565c0;
        }
        .delete-btn {
            background: #ffebee;
            color: #c62828;
        }
        .edit-btn:hover {
            background: #bbdefb;
            transform: translateY(-1px);
        }
        .delete-btn:hover {
            background: #ffcdd2;
            transform: translateY(-1px);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .modal-header {
            margin-bottom: 20px;
        }
        .modal-header h2 {
            margin: 0;
            color: #1a73e8;
            font-size: 24px;
        }
        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
            outline: none;
        }
        .form-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color: white;
            transition: border-color 0.3s;
        }
        .form-select:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #eee;
        }
        .cancel-btn {
            background-color: #ffffff;
            color: #333;
            border: 1px solid #ddd;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            min-width: 100px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }
        .cancel-btn:hover {
            background-color: #f5f5f5;
            border-color: #ccc;
        }
        .submit-btn {
            background-color: #1a73e8;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            min-width: 80px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .submit-btn:hover {
            background-color: #1557b0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-actions {
            display: flex;
            gap: 16px;
            align-items: center;
        }
        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-box i {
            position: absolute;
            left: 12px;
            color: #666;
        }
        .search-box input {
            padding: 10px 12px 10px 36px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            width: 250px;
            transition: all 0.3s;
        }
        .search-box input:focus {
            border-color: #2196F3;
            outline: none;
            box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.1);
        }
        .add-btn {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .add-btn:hover {
            background-color: #1976D2;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .add-btn i {
            font-size: 14px;
        }
        .edit-btn, .delete-btn {
            padding: 6px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }
        .edit-btn {
            background-color: #2196F3;
            color: white;
            margin-right: 8px;
        }
        .edit-btn:hover {
            background-color: #1976D2;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>くらしナビ</h2>
                <div class="admin-info">
                    <span>管理者: <?php echo isset($_SESSION['admin_full_name']) ? htmlspecialchars($_SESSION['admin_full_name']) : htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i>ダッシュボード</a></li>
                <li><a href="manage_services.php"><i class="fas fa-concierge-bell"></i>サービス管理</a></li>
                <li><a href="manage_guide.php"><i class="fas fa-book"></i>ガイド管理</a></li>
                <li><a href="manage_community.php"><i class="fas fa-comments"></i>コミュニティ管理</a></li>
                <li><a href="manage_contact.php"><i class="fas fa-envelope"></i>お問い合わせ管理</a></li>
                <li><a href="manage_users.php" class="active"><i class="fas fa-users"></i>ユーザー管理</a></li>
                <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i>ログアウト</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="content-header">
                <h1>ユーザー管理</h1>
                <button class="add-user-btn" onclick="openAddModal()">
                    <i class="fas fa-plus"></i>新規ユーザー追加
                </button>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <div class="table-header">
                <div class="table-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="検索..." onkeyup="searchTable()">
                    </div>
                </div>
            </div>

            <div class="users-table">
                <table>
                    <thead>
                        <tr>
                            <th>ユーザー名</th>
                            <th>メールアドレス</th>
                            <th>名前</th>
                            <th>役割</th>
                            <th>登録日</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="name-column"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td class="email-column"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="name-column"><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td>
                                    <span class="role-badge role-<?php echo $user['role']; ?>">
                                        <?php echo $user['role'] === 'admin' ? '管理者' : '一般ユーザー'; ?>
                                    </span>
                                </td>
                                <td class="date-column"><?php echo date('Y/m/d H:i', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="edit-btn" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user)); ?>)" title="編集">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-btn" onclick="confirmDelete(<?php echo $user['id']; ?>)" title="削除">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeAddModal()">&times;</button>
            <div class="modal-header">
                <h2>新規ユーザー追加</h2>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="username">ユーザー名</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="full_name">名前</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="role">役割</label>
                    <select id="role" name="role" class="form-select">
                        <option value="user">一般ユーザー</option>
                        <option value="admin">管理者</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" onclick="closeAddModal()">キャンセル</button>
                    <button type="submit" class="submit-btn" style="background-color: #1a73e8;">追加</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeEditModal()">&times;</button>
            <div class="modal-header">
                <h2>ユーザー編集</h2>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="form-group">
                    <label for="edit_username">ユーザー名</label>
                    <input type="text" id="edit_username" class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label for="edit_email">メールアドレス</label>
                    <input type="email" id="edit_email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_password">パスワード（変更する場合のみ入力）</label>
                    <input type="password" id="edit_password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="edit_full_name">名前</label>
                    <input type="text" id="edit_full_name" name="full_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_role">役割</label>
                    <select id="edit_role" name="role" class="form-select">
                        <option value="user">一般ユーザー</option>
                        <option value="admin">管理者</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" onclick="closeEditModal()">キャンセル</button>
                    <button type="submit" class="submit-btn">更新</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>ユーザー削除の確認</h2>
            </div>
            <p>このユーザーを削除してもよろしいですか？この操作は元に戻せません。</p>
            <form method="POST" action="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="user_id" id="delete_user_id">
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" onclick="closeDeleteModal()">キャンセル</button>
                    <button type="submit" class="submit-btn" style="background: #dc3545;">削除</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addUserModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addUserModal').style.display = 'none';
        }

        function openEditModal(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_full_name').value = user.full_name;
            document.getElementById('edit_role').value = user.role;
            document.getElementById('editUserModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editUserModal').style.display = 'none';
        }

        function confirmDelete(userId) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('.users-table table');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < td.length - 1; j++) { // Exclude the last column (actions)
                    const cell = td[j];
                    if (cell) {
                        const text = cell.textContent || cell.innerText;
                        if (text.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                
                tr[i].style.display = found ? '' : 'none';
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html> 