<?php

class Emotion {
    // Method to add a new emotion to the database
    public static function addEmotion($user_id, $emotion, $date) {
        $pdo = Database::connect();

        $sql = "INSERT INTO emotions (user_id, emotion, date) VALUES (:user_id, :emotion, :date)
                ON DUPLICATE KEY UPDATE emotion = :emotion";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':emotion', $emotion);
        $stmt->bindParam(':date', $date);

        return $stmt->execute();
    }

    // Method to get all emotions for a given user
    public static function getEmotionsByUser($user_id, $start_date, $end_date) {
        $pdo = Database::connect();

        $sql = "SELECT * FROM emotions WHERE user_id = :user_id AND date BETWEEN :start_date AND :end_date ORDER BY date ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get today's emotion for a user
    public static function getEmotionForToday($user_id) {
        $pdo = Database::connect();

        $sql = "SELECT * FROM emotions WHERE user_id = :user_id AND date = CURDATE() LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
