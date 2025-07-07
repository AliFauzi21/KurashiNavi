<?php
/**
 * Setup Community Database
 * Script untuk membuat tabel dan data awal untuk sistem community
 */

require_once 'models/db.php';

echo "<h2>🔧 Setup Database Community</h2>";

try {
    // Baca file SQL
    $sqlFile = 'models/community_database.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("File SQL tidak ditemukan: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split SQL statements
    $statements = explode(';', $sql);
    
    $successCount = 0;
    $errorCount = 0;
    
    echo "<div style='font-family: monospace; background: #f5f5f5; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            // Handle DELIMITER statements
            if (strpos($statement, 'DELIMITER') === 0) {
                continue;
            }
            
            // Execute statement
            $pdo->exec($statement);
            $successCount++;
            echo "<div style='color: green; margin: 5px 0;'>✅ Berhasil: " . substr($statement, 0, 50) . "...</div>";
            
        } catch (PDOException $e) {
            $errorCount++;
            echo "<div style='color: red; margin: 5px 0;'>❌ Error: " . $e->getMessage() . "</div>";
        }
    }
    
    echo "</div>";
    
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>📊 Hasil Setup:</h3>";
    echo "<p><strong>Berhasil:</strong> $successCount statements</p>";
    echo "<p><strong>Error:</strong> $errorCount statements</p>";
    echo "</div>";
    
    if ($errorCount === 0) {
        echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>🎉 Setup Database Community Berhasil!</h3>";
        echo "<p>Database community telah berhasil dibuat dengan tabel-tabel berikut:</p>";
        echo "<ul>";
        echo "<li><strong>community_events</strong> - Tabel untuk menyimpan data event</li>";
        echo "<li><strong>community_groups</strong> - Tabel untuk menyimpan data grup</li>";
        echo "<li><strong>forum_categories</strong> - Tabel untuk kategori forum</li>";
        echo "<li><strong>forum_topics</strong> - Tabel untuk topik forum</li>";
        echo "<li><strong>forum_replies</strong> - Tabel untuk balasan forum</li>";
        echo "<li><strong>event_participants</strong> - Tabel untuk peserta event</li>";
        echo "<li><strong>group_members</strong> - Tabel untuk anggota grup</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<div style='background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>📝 Langkah Selanjutnya:</h3>";
        echo "<ol>";
        echo "<li>Akses <a href='admin/manage_community.php' style='color: #856404;'>Panel Admin Community</a> untuk mengelola data</li>";
        echo "<li>Update halaman <a href='community.php' style='color: #856404;'>Community</a> untuk menggunakan data dari database</li>";
        echo "<li>Test fitur-fitur CRUD di panel admin</li>";
        echo "</ol>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>❌ Error Setup Database:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

// Test koneksi dan data
echo "<h3>🔍 Test Koneksi Database:</h3>";

try {
    // Test query untuk events
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM community_events");
    $eventCount = $stmt->fetch()['total'];
    echo "<p>✅ Events: $eventCount records</p>";
    
    // Test query untuk groups
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM community_groups");
    $groupCount = $stmt->fetch()['total'];
    echo "<p>✅ Groups: $groupCount records</p>";
    
    // Test query untuk forum categories
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM forum_categories");
    $categoryCount = $stmt->fetch()['total'];
    echo "<p>✅ Forum Categories: $categoryCount records</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error testing database: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='index.php' style='color: #1a73e8; text-decoration: none;'>← Kembali ke Beranda</a></p>";
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: #f8f9fa;
}

h2 {
    color: #1a73e8;
    border-bottom: 2px solid #1a73e8;
    padding-bottom: 10px;
}

h3 {
    color: #333;
    margin-top: 20px;
}

a {
    color: #1a73e8;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style> 