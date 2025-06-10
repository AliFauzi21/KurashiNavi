<?php
// Include database dan object files
require_once 'config/database.php';
require_once 'models/Contact.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inisialisasi database
    $database = new Database();
    $db = $database->getConnection();

    // Inisialisasi object Contact
    $contact = new Contact($db);

    // Mengambil data dari form
    $contact->name = $_POST['name'] ?? '';
    $contact->email = $_POST['email'] ?? '';
    $contact->phone = $_POST['phone'] ?? '';
    $contact->subject = $_POST['subject'] ?? '';
    $contact->message = $_POST['message'] ?? '';

    // Validasi input
    $errors = [];
    
    if (empty($contact->name)) {
        $errors[] = "お名前は必須です。";
    }
    
    if (empty($contact->email)) {
        $errors[] = "メールアドレスは必須です。";
    } elseif (!filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "有効なメールアドレスを入力してください。";
    }
    
    if (empty($contact->subject)) {
        $errors[] = "件名は必須です。";
    }
    
    if (empty($contact->message)) {
        $errors[] = "メッセージは必須です。";
    }

    // Jika tidak ada error, simpan ke database dan kirim email
    if (empty($errors)) {
        // Simpan ke database
        if ($contact->create()) {
            // Kirim email
            $to = "info@kurashinavi.jp";
            $email_subject = "お問い合わせ: " . $contact->subject;
            
            $email_body = "以下の内容でお問い合わせがありました。\n\n";
            $email_body .= "お名前: " . $contact->name . "\n";
            $email_body .= "メールアドレス: " . $contact->email . "\n";
            $email_body .= "電話番号: " . $contact->phone . "\n";
            $email_body .= "件名: " . $contact->subject . "\n\n";
            $email_body .= "メッセージ:\n" . $contact->message;
            
            $headers = "From: " . $contact->email . "\r\n";
            $headers .= "Reply-To: " . $contact->email . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            if (mail($to, $email_subject, $email_body, $headers)) {
                header("Location: contact.php?status=success");
                exit();
            } else {
                $errors[] = "メールの送信に失敗しました。後ほど再度お試しください。";
            }
        } else {
            $errors[] = "データベースへの保存に失敗しました。後ほど再度お試しください。";
        }
    }

    // Jika ada error, kembali ke form dengan pesan error
    if (!empty($errors)) {
        session_start();
        $_SESSION['contact_errors'] = $errors;
        $_SESSION['contact_form_data'] = $_POST;
        header("Location: contact.php?status=error");
        exit();
    }
} else {
    // Jika bukan method POST, redirect ke halaman kontak
    header("Location: contact.php");
    exit();
}
?> 