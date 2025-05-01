<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Emotion.php';
require_once __DIR__ . '/../controller/EmotionController.php';
require_once __DIR__ . '/../utils/TimeUtils.php';

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

// Calculate days without mood
$daysWithoutMood = 0;
$today = date('Y-m-d');
foreach ($weekDates as $date) {
    if (!isset($emotionData[$date]) && $date <= $today) {
        $daysWithoutMood++;
    }
}

// Calculate streak percentage
$streakPercentage = min(($emotionCounts[$mainEmotion] / 7) * 100, 100);

// Calculate current size based on streak
$baseSize = 80;
$maxSize = 200;
$currentSize = $baseSize + (($streakPercentage / 100) * ($maxSize - $baseSize));

// Get current hour for time-based suggestions
$currentHour = (int)date('H');
$timeOfDay = getTimeOfDay($currentHour);

// Meditation suggestions based on time and mood
$meditationSuggestions = [
    'sad' => [
        'title' => 'Feeling Down?',
        'message' => 'I see that you\'re feeling down right now. Here are some meditation sessions that might help:',
        'song' => '../../resources/audio/relaxing_song.mp3',
        'sessions' => [
            'morning' => [
                'title' => 'Morning Renewal Meditation',
                'description' => 'Start your day with positive energy',
                'video' => '../../resources/video/491527310_29956379330620043_4358568605692076484_n.mp4'
            ],
            'afternoon' => [
                'title' => 'Midday Mindfulness',
                'description' => 'Find balance in your day',
                'video' => '../../resources/video/491506014_30347214391536018_6248472493198818994_n.mp4'
            ],
            'evening' => [
                'title' => 'Evening Reflection',
                'description' => 'Release the day\'s stress',
                'video' => '../../resources/video/491527310_29956379330620043_4358568605692076484_n.mp4'
            ]
        ]
    ],
    'bad' => [
        'title' => 'Having a Tough Day?',
        'message' => 'I notice you\'re not feeling great. These meditation sessions might help:',
        'song' => '../../resources/audio/relaxing_song.mp3',
        'sessions' => [
            'morning' => [
                'title' => 'Morning Calm',
                'description' => 'Begin your day with peace',
                'video' => '../../resources/video/491506014_30347214391536018_6248472493198818994_n.mp4'
            ],
            'afternoon' => [
                'title' => 'Stress Relief Break',
                'description' => 'Take a moment to reset',
                'video' => '../../resources/video/491506014_30347214391536018_6248472493198818994_n.mp4'
            ],
            'evening' => [
                'title' => 'Nighttime Relaxation',
                'description' => 'Prepare for restful sleep',
                'video' => '../../resources/video/491897414_9734825196611097_6919508935587538169_n.mp4'
            ]
        ]
    ]
];

// Determine time of day
$hour = date('H');
if ($hour >= 5 && $hour < 12) {
    $timeOfDay = 'morning';
} elseif ($hour >= 12 && $hour < 18) {
    $timeOfDay = 'afternoon';
} else {
    $timeOfDay = 'evening';
}

// Check if we should display the modal
$showModal = false;
$emotion = '';
if (isset($_POST['emotion']) && in_array($_POST['emotion'], ['sad', 'bad'])) {
    $showModal = true;
    $emotion = $_POST['emotion'];
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Mood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>

video {
            width: 100%;
            height: auto;
            max-height: 400px;
            display: block;
            background-color: #000; /* Black background to make video more visible */
            border-radius: 8px;
            object-fit: contain; /* Ensures video maintains aspect ratio */
        }
        
        /* Fix for video controls display */
        video::-webkit-media-controls {
            display: flex !important;
            visibility: visible !important;
        }
        
        /* Ensure video element has appropriate dimensions */
        .video-container {
            width: 100%;
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
        }
        
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .emotion-button {
            transition: all 0.3s ease;
        }
        .emotion-button:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        .emotion-img {
            transition: all 0.3s ease;
        }
        .emotion-img:hover {
            transform: scale(1.1);
        }
        tr:hover {
            background-color: #f8fafc;
            transform: scale(1.01);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        /* Enhanced Pet Animation Styles */
        .pet-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .pet-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transition: all 0.5s ease;
        }

        .pet-image.front {
            z-index: 3;
            animation: idle 3s infinite ease-in-out;
        }

        .pet-container:hover .pet-image.front {
            animation: jump 1s infinite ease-in-out;
        }

        .pet-image.left, .pet-image.right {
            opacity: 0;
            filter: brightness(0.9);
        }

        .pet-container.walking .pet-image.front {
            animation: walk 1s infinite ease-in-out;
        }

        .pet-container.walking .pet-image.left {
            opacity: 0.6;
            z-index: 2;
            transform: translateX(-20px) scaleX(-1);
            animation: walkLeft 1s infinite ease-in-out;
        }

        .pet-container.walking .pet-image.right {
            opacity: 0.6;
            z-index: 1;
            transform: translateX(20px);
            animation: walkRight 1s infinite ease-in-out;
        }

        @keyframes idle {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-2px) scale(1.02); }
        }

        @keyframes jump {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(0.95); }
        }

        @keyframes walk {
            0%, 100% { transform: translateX(0) rotate(0deg); }
            25% { transform: translateX(10px) rotate(5deg); }
            75% { transform: translateX(-10px) rotate(-5deg); }
        }

        @keyframes walkLeft {
            0%, 100% { transform: translateX(-20px) scaleX(-1); }
            50% { transform: translateX(-25px) scaleX(-1) translateY(-5px); }
        }

        @keyframes walkRight {
            0%, 100% { transform: translateX(20px); }
            50% { transform: translateX(25px) translateY(-5px); }
        }

        .pet-shadow {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 10px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            animation: shadowIdle 3s infinite ease-in-out;
        }

        .pet-container:hover .pet-shadow {
            animation: shadowJump 1s infinite ease-in-out;
        }

        .pet-container.walking .pet-shadow {
            animation: shadowWalk 1s infinite ease-in-out;
        }

        @keyframes shadowIdle {
            0%, 100% { transform: translateX(-50%) scale(1); opacity: 0.3; }
            50% { transform: translateX(-50%) scale(1.1); opacity: 0.2; }
        }

        @keyframes shadowJump {
            0%, 100% { transform: translateX(-50%) scale(1); opacity: 0.3; }
            50% { transform: translateX(-50%) scale(0.8); opacity: 0.1; }
        }

        @keyframes shadowWalk {
            0%, 100% { transform: translateX(-50%) scale(1); opacity: 0.3; }
            25% { transform: translateX(-40%) scale(1.1); opacity: 0.2; }
            75% { transform: translateX(-60%) scale(1.1); opacity: 0.2; }
        }

        .percentage-display {
            position: absolute;
            bottom: -30px; /* Position at bottom of pet */
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 10;
            text-align: center;
            min-width: 80px;
        }

        .percentage-display .percentage {
            font-size: 1rem;
            font-weight: 600;
            color: #4f46e5;
        }

        .percentage-display .warning {
            font-size: 0.75rem;
            color: #ef4444;
            margin-top: 2px;
        }

        .pet-container-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 30px; /* Add space for percentage display */
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
<div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl mt-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-4xl font-semibold text-indigo-600">Track Your Mood</h1>
        
        <!-- Pet Display moved to top -->
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 flex items-center gap-6 shadow-lg transform hover:scale-105 transition-all duration-300">
            <div class="pet-container-wrapper">
                <div class="relative" style="width: <?= $currentSize ?>px; height: <?= $currentSize ?>px;">
                    <div class="pet-container" id="petContainer">
                        <?php if ($mainEmotion && isset($images[$mainEmotion])): ?>
                            <img src="<?= $images[$mainEmotion] ?>" alt="<?= ucfirst($mainEmotion) ?>" 
                                 class="pet-image front">
                            <img src="<?= $images[$mainEmotion] ?>" alt="<?= ucfirst($mainEmotion) ?>" 
                                 class="pet-image left">
                            <img src="<?= $images[$mainEmotion] ?>" alt="<?= ucfirst($mainEmotion) ?>" 
                                 class="pet-image right">
                            <div class="pet-shadow"></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($mainEmotion): ?>
                        <div class="percentage-display">
                            <div class="percentage"><?= round($streakPercentage) ?>%</div>
                            <?php if ($daysWithoutMood > 0): ?>
                                <div class="warning">-<?= $daysWithoutMood ?> day<?= $daysWithoutMood > 1 ? 's' : '' ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="pet-info">
                    <h2 class="text-2xl font-bold text-indigo-600 mb-2">Your Pet</h2>
                    <?php if ($mainEmotion): ?>
                        <p class="font-semibold text-lg text-indigo-700"><?= ucfirst($mainEmotion) ?></p>
                        <p class="text-sm text-gray-500"><?= $emotionCounts[$mainEmotion] ?> days feeling <?= $mainEmotion ?></p>
                    <?php else: ?>
                        <p class="text-gray-500">No mood selected yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="space-y-8">
        <!-- Emotion Buttons -->
        <form method="POST" class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            <?php $disabled = isset($emotionData[date('Y-m-d')]) ? 'disabled' : ''; ?>
            <?php
            $emotionsList = [
                'happy' => ['color' => 'yellow-400', 'hover' => 'yellow-500', 'image' => $images['happy']],
                'sad' => ['color' => 'blue-400', 'hover' => 'blue-500', 'image' => $images['sad']],
                'good' => ['color' => 'green-500', 'hover' => 'green-600', 'image' => $images['good']],
                'bad' => ['color' => 'red-500', 'hover' => 'red-600', 'image' => $images['bad']]
            ];
            ?>
            <?php foreach ($emotionsList as $emo => $style): ?>
                <button type="submit" name="emotion" value="<?= $emo ?>" 
                        class="emotion-button flex flex-col items-center justify-center bg-<?= $style['color'] ?> hover:bg-<?= $style['hover'] ?> text-white rounded-xl shadow-md w-full h-32 transform hover:scale-105 transition-all duration-300 <?= $disabled ?>"
                        <?= $disabled ?>>
                    <img src="<?= $style['image'] ?>" alt="<?= ucfirst($emo) ?>" class="w-16 h-16 object-contain mb-2">
                    <span class="text-lg font-semibold"><?= ucfirst($emo) ?></span>
                </button>
            <?php endforeach; ?>
        </form>

        <!-- Week Navigation -->
        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
            <a href="?monday=<?= $prevMonday ?>" class="text-blue-600 hover:text-blue-800 font-medium transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous
            </a>
            <span class="text-gray-700 font-semibold text-lg">Week of <?= date('M d', strtotime($currentMonday)) ?></span>
            <a href="?monday=<?= $nextMonday ?>" class="text-blue-600 hover:text-blue-800 font-medium transition flex items-center">
                Next
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <!-- Emotions Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white">
                        <th class="py-4 px-6 text-left">Day</th>
                        <th class="py-4 px-6 text-left">Emotion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($weekDates as $date): ?>
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-4 px-6 text-gray-700 font-medium"><?= date('l', strtotime($date)) ?></td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <?php if (isset($emotionData[$date])): ?>
                                        <?php 
                                            $emo = $emotionData[$date];
                                            $img = $images[$emo] ?? '';
                                            $count = $emotionCounts[$emo];
                                            $daySize = min(24 + ($count * 8), 100);
                                        ?>
                                        <img src="<?= $img ?>" alt="<?= ucfirst($emo) ?>" 
                                             class="emotion-img object-contain transform hover:scale-110 transition-transform duration-300" 
                                             style="width: <?= $daySize ?>px; height: <?= $daySize ?>px;">
                                        <span class="font-semibold text-gray-800"><?= ucfirst($emo) ?></span>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mood Summary -->
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8 shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Mood Analysis for the Week</h2>
    
    <canvas id="weeklyMoodChart" width="400" height="200"></canvas>

    <?php
    // Calculate mood scores for the week
    $moodScores = [];
    foreach ($weekDates as $date) {
        $dayName = date('l', strtotime($date));
        if (isset($emotionData[$date])) {
            $emotion = $emotionData[$date];
            $score = [
                'happy' => 5,
                'good' => 4,
                'sad' => 2,
                'bad' => 1
            ][$emotion] ?? 3;
            $moodScores[$dayName] = $score;
        } else {
            $moodScores[$dayName] = 0; // No mood recorded
        }
    }

    // Filter out days with no recorded mood
    $validScores = array_filter($moodScores);

    if (count($validScores) >= 6):
        $weeklyAverage = array_sum($validScores) / count($validScores);
        $weekStatus = $weeklyAverage >= 3.5 ? 'Great' : 'Needs Improvement';
    ?>

    <div class="mt-6 p-4 bg-white rounded-xl shadow-sm">
        <p class="text-xl font-bold <?= $weekStatus == 'Great' ? 'text-green-600' : 'text-red-600' ?>">
            Overall this week: <?= $weekStatus ?>
        </p>
        <p class="text-gray-600 mt-2">Average mood score: <?= number_format($weeklyAverage, 1) ?> / 5.0</p>
    </div>

    <?php else: ?>
        <div class="mt-6 p-4 bg-yellow-100 text-yellow-800 rounded-xl shadow-sm">
            <p class="text-xl font-semibold">Not enough data yet!</p>
            <p class="mt-1">Mood analysis will be available once you’ve logged at least 6 days of moods.</p>
        </div>
    <?php endif; ?>
</div>
<?php if ($showModal && isset($meditationSuggestions[$emotion])): ?>
        <div id="meditationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-8 max-w-2xl w-full mx-4">
                <?php
                $sessionData = $meditationSuggestions[$emotion];
                $sessions = $sessionData['sessions'];
                $song = $sessionData['song'];
                ?>
                
                <h2 class="text-2xl font-bold text-indigo-600 mb-4"><?= $sessionData['title'] ?></h2>
                <p class="text-gray-600 mb-6"><?= $sessionData['message'] ?></p>
                
                <div class="space-y-4">
                    <?php if (isset($sessions[$timeOfDay])):
                        $currentSession = $sessions[$timeOfDay];
                    ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-indigo-50 transition-colors">
                            <h3 class="font-semibold text-lg text-indigo-700"><?= $currentSession['title'] ?></h3>
                            <p class="text-gray-600 mb-4"><?= $currentSession['description'] ?></p>
                            
                            <!-- Video container with proper CSS -->
                            <div class="video-container">
                                <video controls preload="metadata" playsinline>
                                    <source src="<?= htmlspecialchars($currentSession['video']) ?>" type="video/mp4">
                                    Your browser does not support the video element.
                                </video>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-600">No meditation session available for this time of day. Here's a relaxing song instead:</p>
                    <?php endif; ?>
                    
                    <!-- Audio player with distinct styling -->
                    <div class="mt-4">
                        <p class="text-gray-600 mb-2">Relaxing Music:</p>
                        <audio controls class="w-full">
                            <source src="<?= htmlspecialchars($song) ?>" type="audio/mp3">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end">
                    <button onclick="closeMeditationModal()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Add JavaScript for modal functionality -->
        <script>
            function closeMeditationModal() {
                document.getElementById('meditationModal').style.display = 'none';
                // Alternatively, redirect back to the main page
                // window.location.href = 'index.php';
            }
            
            // Ensure video loads properly
            document.addEventListener('DOMContentLoaded', function() {
                const videoElements = document.querySelectorAll('video');
                videoElements.forEach(video => {
                    // Force video element to refresh
                    video.load();
                    
                    // Check if video has loaded metadata
                    video.addEventListener('loadedmetadata', function() {
                        console.log('Video metadata loaded successfully');
                    });
                    
                    // Log any errors loading the video
                    video.addEventListener('error', function(e) {
                        console.error('Error loading video:', e);
                    });
                });
            });
        </script>
 </div>


        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
            <h2 class="text-2xl font-bold text-indigo-600 mb-4">How was your session?</h2>
            <p class="text-gray-600 mb-6">Do you feel better after the meditation?</p>
            
            <div class="flex justify-center space-x-8 mb-6">
                <button onclick="submitFeedback('up')" class="text-4xl hover:scale-110 transition-transform">👍</button>
                <button onclick="submitFeedback('down')" class="text-4xl hover:scale-110 transition-transform">👎</button>
            </div>

            <div class="mb-6">
                <textarea id="feedbackComment" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                          placeholder="Share your thoughts (optional)"></textarea>
            </div>

            <div class="flex justify-end">
                <button onclick="submitFeedbackWithComment()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                    Submit Feedback
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('weeklyMoodChart').getContext('2d');
    const moodScores = <?= json_encode($moodScores) ?>;

    const moodChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: Object.keys(moodScores),
            datasets: [{
                label: 'Mood Level (1 Bad - 5 Great)',
                data: Object.values(moodScores),
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(99, 102, 241, 1)',
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
                            const moods = ['', 'Bad', 'Sad', 'Neutral', 'Good', 'Happy'];
                            return moods[value] || value;
                        }
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const moods = ['No Mood', 'Bad', 'Sad', 'Neutral', 'Good', 'Happy'];
                            return 'Mood: ' + (moods[context.parsed.y] || context.parsed.y);
                        }
                    }
                }
            }
        }
    });

    function initPetBehavior(container) {
        let currentAction = 'idle';
        
        // Sequence: idle (2s) -> walk (2s) -> jump -> repeat
        function startSequence() {
            if (currentAction === 'idle') {
                currentAction = 'walk';
                container.classList.add('walking');
                setTimeout(() => {
                    currentAction = 'jump';
                    makeJump(container);
                }, 2000);
            } else if (currentAction === 'walk') {
                currentAction = 'jump';
                makeJump(container);
            } else {
                currentAction = 'idle';
                container.classList.remove('walking');
                setTimeout(() => {
                    currentAction = 'walk';
                    container.classList.add('walking');
                }, 2000);
            }
        }

        // Start the sequence every 2 seconds
        setInterval(startSequence, 2000);
    }

    function makeJump(container) {
        const wasWalking = container.classList.contains('walking');
        container.classList.remove('walking');
        
        const pet = container.querySelector('.pet-image.front');
        const shadow = container.querySelector('.pet-shadow');
        
        pet.style.animation = 'none';
        shadow.style.animation = 'none';
        
        pet.offsetHeight;
        shadow.offsetHeight;
        
        pet.style.animation = 'jump 1s ease-in-out';
        shadow.style.animation = 'shadowJump 1s ease-in-out';

        setTimeout(() => {
            pet.style.animation = '';
            shadow.style.animation = '';
            if (wasWalking) {
                container.classList.add('walking');
            }
        }, 1000);
    }

    // Initialize pet behavior when page loads
    window.addEventListener('load', () => {
        const petContainer = document.getElementById('petContainer');
        if (petContainer) {
            initPetBehavior(petContainer);
        }
    });

    function closeMeditationModal() {
        document.getElementById('meditationModal').style.display = 'none';
        document.getElementById('feedbackModal').style.display = 'flex';
    }

    function submitFeedback(type) {
        // Here you would typically send the feedback to your server
        console.log('Feedback submitted:', type);
        document.getElementById('feedbackModal').style.display = 'none';
    }

    function submitFeedbackWithComment() {
        const comment = document.getElementById('feedbackComment').value;
        // Here you would typically send the feedback and comment to your server
        console.log('Feedback submitted with comment:', comment);
        document.getElementById('feedbackModal').style.display = 'none';
    }

    // Show meditation modal when page loads if emotion was sad or bad
    window.addEventListener('load', () => {
        <?php if (isset($_POST['emotion']) && in_array($_POST['emotion'], ['sad', 'bad'])): ?>
            document.getElementById('meditationModal').style.display = 'flex';
        <?php endif; ?>
    });
</script>
</body>
</html>