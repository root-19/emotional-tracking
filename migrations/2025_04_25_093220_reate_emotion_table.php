<?php

class CreateEmotionTable {
    // Method to create the 'emotions' table
    public function up($pdo) {
        $query = "
            CREATE TABLE IF NOT EXISTS emotions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                emotion ENUM('happy', 'sad', 'good', 'bad') NOT NULL,
                date DATE NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE(user_id, date) -- Ensure that each user can only have one emotion per day
            ) ENGINE=InnoDB;
        ";

        try {
            $pdo->exec($query);
            echo "Table 'emotions' created successfully!";
        } catch (PDOException $e) {
            echo "Error creating table: " . $e->getMessage();
        }
    }

    // Method to drop the 'emotions' table
    public function down($pdo) {
        $query = "DROP TABLE IF EXISTS emotions";
        
        try {
            $pdo->exec($query);
            echo "Table 'emotions' dropped successfully!";
        } catch (PDOException $e) {
            echo "Error dropping table: " . $e->getMessage();
        }
    }
}

?>
