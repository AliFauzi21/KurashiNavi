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

// Data guide kesehatan lengkap
$guides = [
    [
        'category' => 'insurance',
        'title' => '健康保険とは',
        'content' => '健康保険は、日本で生活するすべての人が加入することが義務付けられている公的な医療保険制度です。病気やけがをしたとき、医療費の一部を保険でカバーできます。主な種類は「国民健康保険」と「社会保険」です。',
    ],
    [
        'category' => 'insurance',
        'title' => '国民健康保険の概要',
        'content' => '国民健康保険は、自営業者や学生、退職者など、会社に勤めていない人が加入する保険です。市区町村の役所で手続きを行い、保険料は所得や世帯人数によって決まります。加入後は「保険証」が発行され、医療機関で提示することで医療費の自己負担が3割になります。',
    ],
    [
        'category' => 'insurance',
        'title' => '社会保険の概要',
        'content' => '社会保険は、会社員や公務員などが加入する保険です。会社が手続きを行い、保険料は給与から天引きされます。社会保険に加入していると、健康保険だけでなく、年金や雇用保険などもカバーされます。',
    ],
    [
        'category' => 'insurance',
        'title' => '保険証の取得方法',
        'content' => '国民健康保険の場合は、市区町村役所の窓口で申請します。必要書類は在留カード、住民票、パスポートなどです。社会保険の場合は、会社が手続きを行い、後日自宅に保険証が郵送されます。',
    ],
    [
        'category' => 'hospital',
        'title' => '病院の利用方法',
        'content' => '病院やクリニックを受診する際は、必ず保険証を持参してください。受付で保険証を提示し、問診票に記入します。初診の場合は「初診料」がかかります。診察後、必要に応じて検査や薬の処方が行われます。',
    ],
    [
        'category' => 'hospital',
        'title' => '予約方法',
        'content' => '多くの病院やクリニックでは、事前に電話やインターネットで予約が可能です。予約なしでも受診できますが、待ち時間が長くなる場合があります。大きな病院では紹介状が必要なこともあります。',
    ],
    [
        'category' => 'hospital',
        'title' => '診察料金',
        'content' => '保険証を使うと、医療費の自己負担は原則3割です。例えば、診察料が3,000円の場合、自己負担は約900円です。検査や薬代は別途かかります。保険証がない場合は全額自己負担となります。',
    ],
    [
        'category' => 'hospital',
        'title' => '薬の処方',
        'content' => '診察後、医師から「処方箋」が発行されます。処方箋を持って薬局（調剤薬局）に行き、薬を受け取ります。薬代も保険が適用され、自己負担は3割です。市販薬はドラッグストアで購入できます。',
    ],
    [
        'category' => 'emergency',
        'title' => '緊急時の対応',
        'content' => '夜間や休日に急病やけがをした場合は、救急病院や夜間診療所を利用できます。救急車が必要な場合は「119」に電話してください。日本語が難しい場合は、通訳サービスを利用できる自治体もあります。救急車は無料ですが、緊急時以外の利用は控えましょう。',
    ],
];

foreach ($guides as $g) {
    // Cek apakah sudah ada (berdasarkan title dan category)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM guide WHERE title = ? AND category = ?");
    $stmt->execute([$g['title'], $g['category']]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $conn->prepare("INSERT INTO guide (title, content, category, status) VALUES (?, ?, ?, 'active')");
        $stmt->execute([$g['title'], $g['content'], $g['category']]);
        echo "Inserted: " . $g['title'] . "<br>\n";
    } else {
        echo "Skipped (already exists): " . $g['title'] . "<br>\n";
    }
}
echo "<br>Import selesai."; 