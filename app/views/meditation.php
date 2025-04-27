<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en" class="bg-gray-900 text-gray-100">
<head>
  <meta charset="UTF-8">
  <title>Meditating System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    let currentAudio = null;

    function showMusic(time) {
      document.querySelectorAll('.music-list').forEach(list => list.classList.add('hidden'));
      document.getElementById(time).classList.remove('hidden');
      stopAllAudio();
    }

    function playAudio(audioElement, spinner, lyricsElement) {
      if (currentAudio && currentAudio !== audioElement) {
        currentAudio.pause();
        currentAudio.parentElement.querySelector('.spinner').classList.add('hidden');
        const prevLyrics = currentAudio.parentElement.querySelector('.lyrics');
        if (prevLyrics) prevLyrics.classList.add('hidden');
      }
      currentAudio = audioElement;
      currentAudio.play();
      spinner.classList.remove('hidden');
      lyricsElement.classList.remove('hidden');

      currentAudio.onpause = () => {
        spinner.classList.add('hidden');
        lyricsElement.classList.add('hidden');
      };
      currentAudio.onended = () => {
        spinner.classList.add('hidden');
        lyricsElement.classList.add('hidden');
      };
    }

    function stopAllAudio() {
      document.querySelectorAll('audio').forEach(audio => {
        audio.pause();
        audio.currentTime = 0;
        const spinner = audio.parentElement.querySelector('.spinner');
        if (spinner) spinner.classList.add('hidden');
        const lyrics = audio.parentElement.querySelector('.lyrics');
        if (lyrics) lyrics.classList.add('hidden');
      });
      currentAudio = null;
    }
  </script>
  <style>
    .spinner {
      border: 3px solid transparent;
      border-top: 3px solid #4f46e5;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>

<div class="min-h-screen flex flex-col items-center px-6 pt-10 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">

  <h1 class="text-5xl font-extrabold mb-12 animate-pulse text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-pink-500">
    Meditation Playlist
  </h1>

  <!-- Button Group -->
  <div class="flex gap-6 mb-10">
    <button onclick="showMusic('morning')" class="bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-black font-semibold py-2 px-8 rounded-full shadow-md hover:scale-110 transition-transform duration-300">
      Morning
    </button>
    <button onclick="showMusic('afternoon')" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-2 px-8 rounded-full shadow-md hover:scale-110 transition-transform duration-300">
      Afternoon
    </button>
    <button onclick="showMusic('evening')" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold py-2 px-8 rounded-full shadow-md hover:scale-110 transition-transform duration-300">
      Evening
    </button>
  </div>

  <!-- Music Lists -->

  <!-- MORNING -->
  <div id="morning" class="music-list hidden w-full max-w-7xl flex flex-wrap gap-6 justify-center">
    <?php 
      $morningMusic = [
        ["Peaceful Morning", "../../resources/image/491190616_680532184667655_8920155106455869347_n.jpg", "../../resources/video/491487075_23880665654892989_2742946353925652515_n.mp4", "Good morning, rise and shine... Feel the breeze, hear the sound."],
        ["Early Sunshine", "../../resources/image/491184681_692546199829525_7815917042717690953_n.jpg", "../../resources/video/491527310_29956379330620043_4358568605692076484_n.mp4", "Golden light through the sky... Dreams awaken, so do I."],
        ["Calm Awakening", "../../resources/image/491189936_1370598043984663_4990241857467273117_n.jpg", "../../resources/video/492720199_29900881109558351_5764059159114085970_n.mp4", "Soft whispers in the air... Peace flows everywhere."],
      ];
      foreach($morningMusic as $music): 
    ?>
    <div class="bg-gray-800 rounded-2xl w-80 overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-300 transform hover:scale-105 flex flex-col">
      <img src="<?= $music[1] ?>" class="w-full h-48 object-cover" alt="<?= $music[0] ?>">
      <div class="p-4 flex flex-col space-y-4">
        <div class="flex items-center gap-3">
          <div class="spinner hidden"></div>
          <h3 class="font-bold text-xl"><?= $music[0] ?></h3>
        </div>
        <audio onplay="playAudio(this, this.parentElement.querySelector('.spinner'), this.parentElement.querySelector('.lyrics'))" controls class="w-full">
          <source src="<?= $music[2] ?>" type="audio/mp3">
        </audio>
        <div class="lyrics hidden bg-gray-700 p-3 rounded-lg mt-2 text-sm text-indigo-300">
          <?= $music[3] ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- AFTERNOON -->
  <div id="afternoon" class="music-list hidden w-full max-w-7xl flex flex-wrap gap-6 justify-center">
    <?php 
      $afternoonMusic = [
        ["Bright Horizon", "../../resources/image/afternoon1.jpg", "../../resources/video/afternoon1.mp4", "Sun high in the sky, endless blue and wide..."],
        ["Midday Breeze", "../../resources/image/afternoon2.jpg", "../../resources/video/afternoon2.mp4", "Whispering winds carry dreams along..."],
        ["Lazy Noon", "../../resources/image/afternoon3.jpg", "../../resources/video/afternoon3.mp4", "Soft and slow, the day moves gently..."],
      ];
      foreach($afternoonMusic as $music): 
    ?>
    <div class="bg-gray-800 rounded-2xl w-80 overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-300 transform hover:scale-105 flex flex-col">
      <img src="<?= $music[1] ?>" class="w-full h-48 object-cover" alt="<?= $music[0] ?>">
      <div class="p-4 flex flex-col space-y-4">
        <div class="flex items-center gap-3">
          <div class="spinner hidden"></div>
          <h3 class="font-bold text-xl"><?= $music[0] ?></h3>
        </div>
        <audio onplay="playAudio(this, this.parentElement.querySelector('.spinner'), this.parentElement.querySelector('.lyrics'))" controls class="w-full">
          <source src="<?= $music[2] ?>" type="audio/mp3">
        </audio>
        <div class="lyrics hidden bg-gray-700 p-3 rounded-lg mt-2 text-sm text-orange-300">
          <?= $music[3] ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- EVENING -->
  <div id="evening" class="music-list hidden w-full max-w-7xl flex flex-wrap gap-6 justify-center">
    <?php 
      $eveningMusic = [
        ["Twilight Calm", "../../resources/image/evening1.jpg", "../../resources/video/evening1.mp4", "Twilight fades, the world slows down..."],
        ["Silent Nightfall", "../../resources/image/evening2.jpg", "../../resources/video/evening2.mp4", "Stars appear, night whispers near..."],
        ["Midnight Reflections", "../../resources/image/evening3.jpg", "../../resources/video/evening3.mp4", "Thoughts float under the midnight sky..."],
      ];
      foreach($eveningMusic as $music): 
    ?>
    <div class="bg-gray-800 rounded-2xl w-80 overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-300 transform hover:scale-105 flex flex-col">
      <img src="<?= $music[1] ?>" class="w-full h-48 object-cover" alt="<?= $music[0] ?>">
      <div class="p-4 flex flex-col space-y-4">
        <div class="flex items-center gap-3">
          <div class="spinner hidden"></div>
          <h3 class="font-bold text-xl"><?= $music[0] ?></h3>
        </div>
        <audio onplay="playAudio(this, this.parentElement.querySelector('.spinner'), this.parentElement.querySelector('.lyrics'))" controls class="w-full">
          <source src="<?= $music[2] ?>" type="audio/mp3">
        </audio>
        <div class="lyrics hidden bg-gray-700 p-3 rounded-lg mt-2 text-sm text-purple-300">
          <?= $music[3] ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

</body>
</html>
