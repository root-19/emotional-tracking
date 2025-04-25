<?php

class EmotionController {



    // Method to handle the emotion submission (sad, happy, etc.)
    public function store($user_id, $emotion) {
        $date = date('Y-m-d'); 
        $result = Emotion::addEmotion($user_id, $emotion, $date);

        // if ($result) {
        //     echo "Emotion added successfully!";
        // } else {
        //     echo "Failed to add emotion.";
        // }
    }
}
