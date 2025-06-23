<?php
class Guide {
    private $conn;
    private $table_name = "guide";

    public $id;
    public $title;
    public $content;
    public $category;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Membaca semua guide
    public function readAll($onlyActive = false) {
        $query = "SELECT * FROM " . $this->table_name;
        if ($onlyActive) {
            $query .= " WHERE status = 'active'";
        }
        $query .= " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Membaca satu guide
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->title = $row['title'];
            $this->content = $row['content'];
            $this->category = $row['category'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    // Membuat guide baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (title, content, category, status) VALUES (:title, :content, :category, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":status", $this->status);
        return $stmt->execute();
    }

    // Update guide
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET title = :title, content = :content, category = :category, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    // Menghapus guide
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?> 