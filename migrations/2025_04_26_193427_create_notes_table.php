<?php

class CreateNotesTable {
    public function up($pdo) {
        $query = "
            CREATE TABLE IF NOT EXISTS notes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                type ENUM('text', 'drawing', 'image') NOT NULL,
                content LONGTEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        $pdo->exec($query);
    }

    public function down($pdo) {
        $query = "DROP TABLE IF EXISTS notes;";
        $pdo->exec($query);
    }
}
?>
