<?php
session_start();
require_once '../models/db.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$error = '';
$success = '';

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                $contact_id = $_POST['contact_id'];
                try {
                    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
                    $stmt->execute([$contact_id]);
                    $success = 'お問い合わせが正常に削除されました。';
                } catch (PDOException $e) {
                    error_log("Delete contact error: " . $e->getMessage());
                    $error = 'エラーが発生しました。後でもう一度お試しください。';
                }
                break;

            case 'update_status':
                $contact_id = $_POST['contact_id'];
                $status = $_POST['status'];
                try {
                    $stmt = $pdo->prepare("UPDATE contacts SET status = ? WHERE id = ?");
                    $stmt->execute([$status, $contact_id]);
                    $success = 'ステータスが正常に更新されました。';
                } catch (PDOException $e) {
                    error_log("Update contact status error: " . $e->getMessage());
                    $error = 'エラーが発生しました。後でもう一度お試しください。';
                }
                break;
        }
    }
}

// Ambil data contacts
try {
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
    $contacts = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Fetch contacts error: " . $e->getMessage());
    $error = 'お問い合わせデータの取得中にエラーが発生しました。';
    $contacts = [];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ管理 - くらしナビ</title>
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
        .contacts-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px;
        }
        .contacts-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .contacts-table th {
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
        .contacts-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            color: #555;
            font-size: 14px;
            vertical-align: middle;
        }
        .contacts-table tr:last-child td {
            border-bottom: none;
        }
        .contacts-table tr:hover {
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
        .status-new {
            background: #e3f2fd;
            color: #1565c0;
            border: 1px solid #bbdefb;
        }
        .status-in-progress {
            background: #fff3e0;
            color: #ef6c00;
            border: 1px solid #ffe0b2;
        }
        .status-completed {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
        }
        .view-btn, .delete-btn {
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
        .view-btn {
            background: #e3f2fd;
            color: #1565c0;
        }
        .delete-btn {
            background: #ffebee;
            color: #c62828;
        }
        .view-btn:hover {
            background: #bbdefb;
            transform: translateY(-1px);
        }
        .delete-btn:hover {
            background: #ffcdd2;
            transform: translateY(-1px);
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }
        .table-actions {
            display: flex;
            gap: 10px;
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            padding: 8px 12px 8px 35px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            width: 250px;
            transition: all 0.3s ease;
        }
        .search-box input:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
            outline: none;
        }
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        .date-column {
            white-space: nowrap;
            color: #666;
            font-size: 13px;
        }
        .name-column {
            font-weight: 500;
            color: #333;
        }
        .email-column {
            color: #666;
        }
        .subject-column {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
            max-width: 600px;
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
        .contact-details {
            margin-bottom: 20px;
        }
        .contact-details p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .contact-details strong {
            color: #333;
            display: inline-block;
            width: 120px;
        }
        .modal-footer {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .submit-btn, .cancel-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .submit-btn {
            background: #1a73e8;
            color: white;
        }
        .cancel-btn {
            background: #f5f5f5;
            color: #333;
        }
        .submit-btn:hover {
            background: #1557b0;
        }
        .cancel-btn:hover {
            background: #e0e0e0;
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
                <h1>お問い合わせ管理</h1>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <div class="table-header">
                <div class="table-title">お問い合わせ一覧</div>
                <div class="table-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="検索..." onkeyup="searchTable()">
                    </div>
                </div>
            </div>

            <div class="contacts-table">
                <table>
                    <thead>
                        <tr>
                            <th>名前</th>
                            <th>メールアドレス</th>
                            <th>件名</th>
                            <th>ステータス</th>
                            <th>受信日時</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td class="name-column"><?php echo htmlspecialchars($contact['name']); ?></td>
                                <td class="email-column"><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td class="subject-column"><?php echo htmlspecialchars($contact['subject']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $contact['status']; ?>">
                                        <?php 
                                        switch($contact['status']) {
                                            case 'new':
                                                echo '新規';
                                                break;
                                            case 'in_progress':
                                                echo '対応中';
                                                break;
                                            case 'completed':
                                                echo '完了';
                                                break;
                                            default:
                                                echo '新規';
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td class="date-column"><?php echo date('Y/m/d H:i', strtotime($contact['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="view-btn" onclick="openViewModal(<?php echo htmlspecialchars(json_encode($contact)); ?>)" title="詳細を見る">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="delete-btn" onclick="confirmDelete(<?php echo $contact['id']; ?>)" title="削除">
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

    <!-- View Contact Modal -->
    <div id="viewContactModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeViewModal()">&times;</button>
            <div class="modal-header">
                <h2>お問い合わせ詳細</h2>
            </div>
            <div class="contact-details">
                <p><strong>名前:</strong> <span id="view_name"></span></p>
                <p><strong>メールアドレス:</strong> <span id="view_email"></span></p>
                <p><strong>件名:</strong> <span id="view_subject"></span></p>
                <p><strong>メッセージ:</strong></p>
                <p id="view_message" style="white-space: pre-wrap;"></p>
                <p><strong>受信日時:</strong> <span id="view_created_at"></span></p>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="contact_id" id="view_contact_id">
                <div class="form-group">
                    <label for="status">ステータス</label>
                    <select id="status" name="status" onchange="this.form.submit()">
                        <option value="new">新規</option>
                        <option value="in_progress">対応中</option>
                        <option value="completed">完了</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>お問い合わせ削除の確認</h2>
            </div>
            <p>このお問い合わせを削除してもよろしいですか？この操作は元に戻せません。</p>
            <form method="POST" action="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="contact_id" id="delete_contact_id">
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" onclick="closeDeleteModal()">キャンセル</button>
                    <button type="submit" class="submit-btn" style="background: #dc3545;">削除</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openViewModal(contact) {
            document.getElementById('view_name').textContent = contact.name;
            document.getElementById('view_email').textContent = contact.email;
            document.getElementById('view_subject').textContent = contact.subject;
            document.getElementById('view_message').textContent = contact.message;
            document.getElementById('view_created_at').textContent = new Date(contact.created_at).toLocaleString('ja-JP');
            document.getElementById('view_contact_id').value = contact.id;
            document.getElementById('status').value = contact.status;
            document.getElementById('viewContactModal').style.display = 'block';
        }

        function closeViewModal() {
            document.getElementById('viewContactModal').style.display = 'none';
        }

        function confirmDelete(contactId) {
            document.getElementById('delete_contact_id').value = contactId;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }

        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('.contacts-table table');
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
    </script>
</body>
</html> 