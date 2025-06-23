<?php
// Script import data guide statis ke database
require_once '../models/database.php';
require_once '../models/Guide.php';

$db = new Database();
$conn = $db->getConnection();
$guideModel = new Guide($conn);

$defaultGuides = [
    // Housing
    [
        'title' => '必要な書類',
        'content' => "在留カード\nパスポート\n収入証明書\n連帯保証人",
        'category' => 'housing',
        'status' => 'active',
    ],
    [
        'title' => '費用',
        'content' => "敷金（2ヶ月分）\n礼金（1-2ヶ月分）\n仲介手数料（1ヶ月分）\n火災保険",
        'category' => 'housing',
        'status' => 'active',
    ],
    [
        'title' => '便利な情報',
        'content' => '物件探しは、駅からの距離や周辺施設も確認しましょう。また、契約前に内見することをお勧めします。',
        'category' => 'housing',
        'status' => 'active',
    ],
    // Healthcare
    [
        'title' => '健康保険',
        'content' => "国民健康保険\n社会保険\n保険証の取得方法",
        'category' => 'healthcare',
        'status' => 'active',
    ],
    [
        'title' => '病院の利用方法',
        'content' => "予約方法\n診察料金\n薬の処方",
        'category' => 'healthcare',
        'status' => 'active',
    ],
    [
        'title' => '緊急時の対応',
        'content' => '夜間や休日の急病の場合は、救急病院を利用できます。救急車は119番に電話してください。',
        'category' => 'healthcare',
        'status' => 'active',
    ],
    // Education
    [
        'title' => '学校の種類',
        'content' => "公立学校\n私立学校\nインターナショナルスクール",
        'category' => 'education',
        'status' => 'active',
    ],
    [
        'title' => '入学手続き',
        'content' => "必要な書類\n入学試験\n学費",
        'category' => 'education',
        'status' => 'active',
    ],
    [
        'title' => '学校選びのポイント',
        'content' => '学校の教育方針、通学時間、学費、言語サポート体制などを確認しましょう。',
        'category' => 'education',
        'status' => 'active',
    ],
    // Admin
    [
        'title' => '住民登録',
        'content' => "転入届\nマイナンバー\n住民票",
        'category' => 'admin',
        'status' => 'active',
    ],
    [
        'title' => '各種届出',
        'content' => "婚姻届\n出生届\n死亡届",
        'category' => 'admin',
        'status' => 'active',
    ],
    [
        'title' => '手続きの注意点',
        'content' => '手続きには本人確認書類が必要です。また、期限がある届出は早めに準備しましょう。',
        'category' => 'admin',
        'status' => 'active',
    ],
];

$count = 0;
foreach ($defaultGuides as $g) {
    $guideModel->title = $g['title'];
    $guideModel->content = $g['content'];
    $guideModel->category = $g['category'];
    $guideModel->status = $g['status'];
    if ($guideModel->create()) {
        $count++;
    }
}
echo "<h2>Import selesai!</h2>";
echo "<p>Berhasil menambahkan $count data guide ke database.</p>"; 