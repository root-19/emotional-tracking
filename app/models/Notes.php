<?php
require_once __DIR__ . '/../../config/database.php';

class Notes {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    public function saveNote($type, $content) {
        try {
            $sql = "INSERT INTO notes (type, content) VALUES (:type, :content)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':content', $content);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error saving note: " . $e->getMessage());
            return false;
        }
    }
}
?>
