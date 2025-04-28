<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Emotion.php';
require_once __DIR__ . '/../controller/EmotionController.php';

// session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// Submit emotion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emotion'])) {
    $emotion = $_POST['emotion'];
    $controller = new EmotionController();
    $controller->store($user_id, $emotion);
}

// Get current week's dates (Monday to Sunday)
$currentMonday = isset($_GET['monday']) ? $_GET['monday'] : date('Y-m-d', strtotime('monday this week'));
$weekDates = [];
for ($i = 0; $i < 7; $i++) {
    $weekDates[] = date('Y-m-d', strtotime("$currentMonday +$i day"));
}

// Fetch emotions
$emotions = Emotion::getEmotionsByUser($user_id, $weekDates[0], $weekDates[6]);
$emotionData = [];
foreach ($emotions as $emotion) {
    $emotionData[$emotion['date']] = $emotion['emotion'];
}

// Count emotions
$emotionCounts = ['happy' => 0, 'sad' => 0, 'good' => 0, 'bad' => 0];
foreach ($emotionData as $emotion) {
    if (isset($emotionCounts[$emotion])) {
        $emotionCounts[$emotion]++;
    }
}

// Most frequent emotion this week
$mainEmotion = null;
if (!empty($emotionCounts)) {
    $mainEmotion = array_search(max($emotionCounts), $emotionCounts);
}

// Prev / Next week
$prevMonday = date('Y-m-d', strtotime("$currentMonday -7 days"));
$nextMonday = date('Y-m-d', strtotime("$currentMonday +7 days"));

// Emotions Images
$images = [
    'happy' => '../../resources/image/happy.png',
    'sad' => '../../resources/image/sad.png',
    'good' => '../../resources/image/inlove.png',
    'bad' => '../../resources/image/cry.png',
];


$weeklyMood = [
    'Monday' => 4,
    'Tuesday' => 5,
    'Wednesday' => 3,
    'Thursday' => 2,
    'Friday' => 4,
    'Saturday' => 5,
    'Sunday' => 3,
];

// Calculate overall average
$averageMood = array_sum($weeklyMood) / count($weeklyMood);

// Decide if good or bad week
$weekStatus = ($averageMood >= 3.5) ? 'Great' : 'Needs Improvement';

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Mood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .emotion-button { transition: all 0.3s ease; }
        .emotion-button:hover { transform: scale(1.1); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        tr:hover { background-color: #f1f5f9; transform: scale(1.02); transition: transform 0.3s ease, background-color 0.3s ease; }
        .emotion-img { transition: all 0.3s ease; }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
<div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl mt-8">

    <h1 class="text-4xl font-semibold text-center text-indigo-600 mb-8">Track Your Mood</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Left Side: Main Pet Display -->
        <div class="col-span-1 bg-indigo-50 rounded-lg p-6 flex flex-col items-center justify-center shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-indigo-600">Your Pet</h2>
            <?php if ($mainEmotion && isset($images[$mainEmotion])): 
                $baseSize = 100; // base size of image
                $increment = 20; // how much to increase per day
                $size = $baseSize + ($emotionCounts[$mainEmotion] * $increment);
                ?>
                <img src="<?= $images[$mainEmotion] ?>" alt="<?= ucfirst($mainEmotion) ?>" style="width: <?= $size ?>px; height: <?= $size ?>px;" class="emotion-img">
                <p class="mt-4 font-semibold text-lg text-indigo-700"><?= ucfirst($mainEmotion) ?></p>
                <p class="text-sm text-gray-500"><?= $emotionCounts[$mainEmotion] ?> days feeling <?= $mainEmotion ?></p>
            <?php else: ?>
                <p class="text-gray-500">No mood selected yet.</p>
            <?php endif; ?>
        </div>

        <!-- Right Side: Mood Tracker -->
        <div class="col-span-3">
            <!-- Emotion Buttons -->
            <form method="POST" class="grid grid-cols-2 sm:grid-cols-4 gap-6 mb-8">
                <?php $disabled = isset($emotionData[date('Y-m-d')]) ? 'disabled' : ''; ?>
                <?php
                $emotionsList = [
                    'happy' => 'yellow-400',
                    'sad' => 'blue-400',
                    'good' => 'green-500',
                    'bad' => 'red-500'
                ];
                ?>
                <?php foreach ($emotionsList as $emo => $color): ?>
                    <button type="submit" name="emotion" value="<?= $emo ?>" class="emotion-button flex flex-col items-center justify-center bg-<?= $color ?> hover:bg-<?= $color ?> text-white rounded-lg shadow-md w-28 h-32 <?= $disabled ?>" <?= $disabled ?>>
                        <img src="<?= $images[$emo] ?>" alt="<?= ucfirst($emo) ?>" class="w-16 h-16 object-contain">
                        <span class="mt-2 text-sm text-black"><?= ucfirst($emo) ?></span>
                    </button>
                <?php endforeach; ?>
            </form>

            <!-- Week Navigation -->
            <div class="flex justify-between items-center mb-6">
                <a href="?monday=<?= $prevMonday ?>" class="text-blue-600 hover:underline">← Previous</a>
                <span class="text-gray-700 font-semibold text-lg">Week of <?= date('M d', strtotime($currentMonday)) ?></span>
                <a href="?monday=<?= $nextMonday ?>" class="text-blue-600 hover:underline">Next →</a>
            </div>

            <!-- Emotions Table -->
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
                                <div class="flex items-center justify-center gap-3">
                                    <?php if (isset($emotionData[$date])): ?>
                                        <?php 
                                            $emo = $emotionData[$date];
                                            $img = $images[$emo] ?? '';
                                            $count = $emotionCounts[$emo];
                                            $daySize = min(24 + ($count * 8), 100);
                                        ?>
                                        <img src="<?= $img ?>" alt="<?= ucfirst($emo) ?>" class="emotion-img object-contain" style="width: <?= $daySize ?>px; height: <?= $daySize ?>px;">
                                        <span class="font-semibold"><?= ucfirst($emo) ?></span>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Mood Summary -->
            <div class="mt-8">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Mood Analysis for the Week</h2>
    
    <canvas id="weeklyMoodChart" width="400" height="200"></canvas>

    <p class="mt-4 text-xl font-bold <?= $weekStatus == 'Great' ? 'text-green-600' : 'text-red-600' ?>">
        Overall this week: <?= $weekStatus ?>
    </p>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('weeklyMoodChart').getContext('2d');

        const moodChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_keys($weeklyMood)) ?>,
                datasets: [{
                    label: 'Mood Level (1 Bad - 5 Great)',
                    data: <?= json_encode(array_values($weeklyMood)) ?>,
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',  // Green background (under line)
                    borderColor: 'rgba(34, 197, 94, 1)',         // Green line
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                    pointBorderColor: '#fff',
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                const moods = ['Bad', 'Poor', 'Average', 'Good', 'Great'];
                                return moods[value - 1] || value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const moods = ['Bad', 'Poor', 'Average', 'Good', 'Great'];
                                return 'Mood: ' + (moods[context.parsed.y - 1] || context.parsed.y);
                            }
                        }
                    }
                }
            }
        });
    </script>
</div>
        </div>
    </div>
</div>
</body>
</html>