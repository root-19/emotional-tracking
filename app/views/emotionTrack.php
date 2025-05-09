<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Emotion.php';
require_once __DIR__ . '/../controller/EmotionController.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 

// Check if an emotion is being submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emotion'])) {
    $emotion = $_POST['emotion'];
    $controller = new EmotionController();
    $controller->store($user_id, $emotion);
}

// Get the current week's dates (Monday to Sunday)
$currentMonday = isset($_GET['monday']) ? $_GET['monday'] : date('Y-m-d', strtotime('monday this week'));
$weekDates = [];
for ($i = 0; $i < 7; $i++) {
    $weekDates[] = date('Y-m-d', strtotime("$currentMonday +$i day"));
}

// Fetch emotions for the current week from the database
$emotions = Emotion::getEmotionsByUser($user_id, $weekDates[0], $weekDates[6]);

// Prepare data for chart display
$emotionData = [];
foreach ($emotions as $emotion) {
    $emotionData[$emotion['date']] = $emotion['emotion'];
}

// Count emotions for the week
$emotionCounts = [
    'happy' => 0,
    'sad' => 0,
    'good' => 0,
    'bad' => 0,
];

foreach ($emotionData as $emotion) {
    if (isset($emotionCounts[$emotion])) {
        $emotionCounts[$emotion]++;
    }
}

// Date navigation for previous and next week
$prevMonday = date('Y-m-d', strtotime("$currentMonday -7 days"));
$nextMonday = date('Y-m-d', strtotime("$currentMonday +7 days"));
include 'header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Mood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>

        .emotion-button {
            transition: all 0.3s ease;
        }

        .emotion-button:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        tr:hover {
            background-color: #f1f5f9;
            transform: scale(1.02);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen ">

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-xl transform transition-all hover:scale-105">
        <h1 class="text-4xl font-semibold text-center text-indigo-600 mb-8">Track Your Mood</h1>

        <form method="POST" class="grid grid-cols-2 sm:grid-cols-4 gap-6 mb-8">
            <?php 
            // Disable buttons if the emotion for the current day is already set
            $disabled = isset($emotionData[date('Y-m-d')]) ? 'disabled' : ''; 
            ?>
            <button type="submit" name="emotion" value="happy" class="emotion-button flex flex-col items-center bg-yellow-400 hover:bg-yellow-500 text-white p-5 rounded-lg shadow-md <?= $disabled ?>" <?= $disabled ?>>
                <span class="text-5xl">😊</span>
                <span class="mt-3 text-sm">Happy</span>
            </button>
            <button type="submit" name="emotion" value="sad" class="emotion-button flex flex-col items-center bg-blue-400 hover:bg-blue-500 text-white p-5 rounded-lg shadow-md <?= $disabled ?>" <?= $disabled ?>>
                <span class="text-5xl">😢</span>
                <span class="mt-3 text-sm">Sad</span>
            </button>
            <button type="submit" name="emotion" value="good" class="emotion-button flex flex-col items-center bg-green-500 hover:bg-green-600 text-white p-5 rounded-lg shadow-md <?= $disabled ?>" <?= $disabled ?>>
                <span class="text-5xl">👍</span>
                <span class="mt-3 text-sm">Good</span>
            </button>
            <button type="submit" name="emotion" value="bad" class="emotion-button flex flex-col items-center bg-red-500 hover:bg-red-600 text-white p-5 rounded-lg shadow-md <?= $disabled ?>" <?= $disabled ?>>
                <span class="text-5xl">👎</span>
                <span class="mt-3 text-sm">Bad</span>
            </button>
        </form>

        <div class="flex justify-between items-center mb-6">
            <a href="?monday=<?= $prevMonday ?>" class="text-blue-600 hover:underline">← Previous</a>
            <span class="text-gray-700 font-semibold text-lg">Week of <?= date('M d', strtotime($currentMonday)) ?></span>
            <a href="?monday=<?= $nextMonday ?>" class="text-blue-600 hover:underline">Next →</a>
        </div>

        <table class="w-full text-center border border-gray-300 rounded-lg mb-8">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-4 px-6 text-sm font-medium text-gray-700">Day</th>
                    <th class="py-4 px-6 text-sm font-medium text-gray-700">Emotion</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($weekDates as $date): ?>
                    <tr class="border-t">
                        <td class="py-4 px-6 text-sm font-medium"><?= date('l', strtotime($date)) ?></td>
                        <td class="py-4 px-6 text-sm">
                            <?php if (isset($emotionData[$date])): ?>
                                <span class="font-semibold"><?= ucfirst($emotionData[$date]) ?></span>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Display the mood analysis -->
        <div class="mt-8">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Mood Analysis for the Week</h2>
            <ul class="list-disc pl-5">
                <li><strong>Happy:</strong> <?= $emotionCounts['happy'] ?> days</li>
                <li><strong>Sad:</strong> <?= $emotionCounts['sad'] ?> days</li>
                <li><strong>Good:</strong> <?= $emotionCounts['good'] ?> days</li>
                <li><strong>Bad:</strong> <?= $emotionCounts['bad'] ?> days</li>
            </ul>
        </div>
    </div>

</body>
</html>
