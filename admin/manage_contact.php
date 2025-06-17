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
                case 'reply':
                    $stmt = $conn->prepare("UPDATE contact_messages SET reply = ?, status = 'replied', replied_at = NOW() WHERE id = ?");
                    $stmt->execute([$_POST['reply'], $_POST['id']]);
                    break;
                case 'delete':
                    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    break;
            }
        } catch(PDOException $e) {
            $error = 'エラーが発生しました。';
        }
    }
}

// Ambil data pesan kontak
try {
    $stmt = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = 'データの取得中にエラーが発生しました。';
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
        .messages-table {
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .messages-table th,
        .messages-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .messages-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .messages-table tr:last-child td {
            border-bottom: none;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .reply-button,
        .delete-button {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .reply-button {
            background: #1a73e8;
            color: white;
        }
        .reply-button:hover {
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
        .message-details {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .message-details p {
            margin: 5px 0;
            color: #555;
        }
        .message-details strong {
            color: #333;
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
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-pending {
            background: #ffc107;
            color: #000;
        }
        .status-replied {
            background: #28a745;
            color: white;
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
                <li><a href="manage_guide.php">ガイド管理</a></li>
                <li><a href="manage_community.php">コミュニティ管理</a></li>
                <li><a href="manage_contact.php" class="active">お問い合わせ管理</a></li>
                <li><a href="manage_users.php">ユーザー管理</a></li>
                <li><a href="logout.php" class="logout-btn">ログアウト</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="content-header">
                <h1>お問い合わせ管理</h1>
            </div>
            
            <table class="messages-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名前</th>
                        <th>メール</th>
                        <th>件名</th>
                        <th>ステータス</th>
                        <th>受信日時</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($messages as $message): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($message['id']); ?></td>
                        <td><?php echo htmlspecialchars($message['name']); ?></td>
                        <td><?php echo htmlspecialchars($message['email']); ?></td>
                        <td><?php echo htmlspecialchars($message['subject']); ?></td>
                        <td>
                            <span class="status-badge <?php echo $message['status'] === 'pending' ? 'status-pending' : 'status-replied'; ?>">
                                <?php echo $message['status'] === 'pending' ? '未対応' : '対応済み'; ?>
                            </span>
                        </td>
                        <td><?php echo date('Y/m/d H:i', strtotime($message['created_at'])); ?></td>
                        <td class="action-buttons">
                            <button class="reply-button" onclick="showReplyModal(<?php echo htmlspecialchars(json_encode($message)); ?>)">返信</button>
                            <button class="delete-button" onclick="deleteMessage(<?php echo $message['id']; ?>)">削除</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk balas pesan -->
    <div id="replyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>メッセージへの返信</h2>
            </div>
            <form id="replyForm" method="POST">
                <input type="hidden" name="action" value="reply">
                <input type="hidden" name="id" id="messageId">
                
                <div class="message-details">
                    <p><strong>送信者:</strong> <span id="senderName"></span></p>
                    <p><strong>メール:</strong> <span id="senderEmail"></span></p>
                    <p><strong>件名:</strong> <span id="messageSubject"></span></p>
                    <p><strong>メッセージ:</strong></p>
                    <p id="messageContent"></p>
                </div>
                
                <div class="form-group">
                    <label for="reply">返信内容</label>
                    <textarea id="reply" name="reply" class="form-control" rows="6" required></textarea>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="close-button" onclick="closeModal()">キャンセル</button>
                    <button type="submit" class="submit-button">送信</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showReplyModal(message) {
            document.getElementById('messageId').value = message.id;
            document.getElementById('senderName').textContent = message.name;
            document.getElementById('senderEmail').textContent = message.email;
            document.getElementById('messageSubject').textContent = message.subject;
            document.getElementById('messageContent').textContent = message.message;
            document.getElementById('reply').value = message.reply || '';
            document.getElementById('replyModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('replyModal').style.display = 'none';
        }

        function deleteMessage(id) {
            if(confirm('このメッセージを削除してもよろしいですか？')) {
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
            const modal = document.getElementById('replyModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html> 