<?php
require_once __DIR__ . '/../../config/database.php';

$pdo = Database::connect();
$stmt = $pdo->query("SELECT * FROM notes ORDER BY created_at DESC");
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include "header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
</head>
<div class="min-h-screen p-10">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-5xl font-extrabold text-center text-gray-800 mb-12">📚 My Memories</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($notes as $note): ?>
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden p-6 space-y-4 flex flex-col">
                    <?php if ($note['type'] == 'text'): ?>
                        <div class="flex items-center gap-2">
                            <span class="iconify text-blue-600" data-icon="tabler:notes" data-width="28"></span>
                            <h2 class="text-xl font-bold">Text Note</h2>
                        </div>
                        <p class="text-gray-700 text-lg"><?= nl2br(htmlspecialchars($note['content'])) ?></p>

                    <?php elseif ($note['type'] == 'drawing'): ?>
                        <div class="flex items-center gap-2">
                            <span class="iconify text-green-600" data-icon="tabler:pencil" data-width="28"></span>
                            <h2 class="text-xl font-bold">Drawing</h2>
                        </div>
                        <img src="<?= htmlspecialchars($note['content']) ?>" alt="Drawing" class="w-full h-64 object-contain bg-gray-50 rounded-lg border">

                    <?php elseif ($note['type'] == 'image'): ?>
                        <div class="flex items-center gap-2">
                            <span class="iconify text-purple-600" data-icon="tabler:photo" data-width="28"></span>
                            <h2 class="text-xl font-bold">Uploaded Image</h2>
                        </div>
                        <img src="<?= htmlspecialchars($note['content']) ?>" alt="Uploaded" class="w-full h-64 object-cover rounded-lg border">
                    <?php endif; ?>

                    <div class="text-right text-xs text-gray-400">
                        Saved on: <?= date('F j, Y, g:i a', strtotime($note['created_at'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($notes)): ?>
            <div class="text-center text-gray-600 mt-20 text-2xl">
                No notes yet. 🥲
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
