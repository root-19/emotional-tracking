<?php
require_once __DIR__ . '/../models/Notes.php';

class NotesController {
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notes = new Notes();
            $type = $_POST['type'] ?? '';
            $content = '';

            if ($type === 'text') {
                $content = trim($_POST['text_note'] ?? '');

            } elseif ($type === 'drawing') {
                $drawingData = $_POST['drawing_data'] ?? '';
                if (!empty($drawingData)) {
                    $uploadDirectory = __DIR__ . '/../../uploads/';
                    if (!is_dir($uploadDirectory)) {
                        mkdir($uploadDirectory, 0777, true);
                    }
                    $drawingData = str_replace('data:image/png;base64,', '', $drawingData);
                    $drawingData = base64_decode($drawingData);

                    $fileName = 'drawing_' . uniqid() . '.png';
                    $filePath = $uploadDirectory . $fileName;

                    if (file_put_contents($filePath, $drawingData)) {
                        $content = 'uploads/' . $fileName;
                    }
                }

            } elseif ($type === 'image') {
                if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                    $uploadDirectory = __DIR__ . '/../../uploads/';
                    if (!is_dir($uploadDirectory)) {
                        mkdir($uploadDirectory, 0777, true);
                    }
                    $fileName = 'image_' . uniqid() . '_' . basename($_FILES['image_file']['name']);
                    $uploadPath = $uploadDirectory . $fileName;

                    if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadPath)) {
                        $content = 'uploads/' . $fileName;
                    }
                }
            }

            if (!empty($content)) {
                if ($notes->saveNote($type, $content)) {
                    $uri = strtok($_SERVER['REQUEST_URI'], '?');
                    header("Location: " . $uri . "?success=1");
                    exit();
                } else {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
                }
                exit();
            } else {
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
                exit();
            }
        }
    }
}

// Execute saving logic if this controller is triggered
$controller = new NotesController();
$controller->save();
?>
