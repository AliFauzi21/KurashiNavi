<?php
session_start();
require_once 'models/db.php';

// Cek login
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    $_SESSION['join_message'] = 'ログインが必要です。先にログインしてください。';
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$type = isset($_POST['type']) ? $_POST['type'] : '';
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if (!$type || !$id) {
    $_SESSION['join_message'] = '不正なリクエストです。';
    header('Location: community.php');
    exit;
}

try {
    if ($type === 'event') {
        // Cek sudah join
        $stmt = $pdo->prepare('SELECT * FROM event_participants WHERE event_id = ? AND user_id = ?');
        $stmt->execute([$id, $user_id]);
        if ($stmt->fetch()) {
            $_SESSION['join_message'] = 'すでにこのイベントに参加しています。';
            header('Location: community.php');
            exit;
        }
        // Insert
        $stmt = $pdo->prepare('INSERT INTO event_participants (event_id, user_id, status) VALUES (?, ?, ?)');
        $stmt->execute([$id, $user_id, 'registered']);
        $_SESSION['join_message'] = 'イベントに参加しました！';
        header('Location: community.php');
        exit;
    } elseif ($type === 'group') {
        // Cek sudah join
        $stmt = $pdo->prepare('SELECT * FROM group_members WHERE group_id = ? AND user_id = ?');
        $stmt->execute([$id, $user_id]);
        if ($stmt->fetch()) {
            $_SESSION['join_message'] = 'すでにこのグループに参加しています。';
            header('Location: community.php');
            exit;
        }
        // Insert
        $stmt = $pdo->prepare('INSERT INTO group_members (group_id, user_id, role, status) VALUES (?, ?, ?, ?)');
        $stmt->execute([$id, $user_id, 'member', 'active']);
        $_SESSION['join_message'] = 'グループに参加しました！';
        header('Location: community.php');
        exit;
    } else {
        $_SESSION['join_message'] = '不正なリクエストです。';
        header('Location: community.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['join_message'] = 'エラーが発生しました: ' . $e->getMessage();
    header('Location: community.php');
    exit;
} 