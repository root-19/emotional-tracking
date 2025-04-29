<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en" class="bg-gray-900 text-gray-100">
<head>
  <meta charset="UTF-8">
  <title>Meditating System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    let currentAudio = null;
    let countdownInterval = null;

    function showMusic(time) {
      document.querySelectorAll('.music-list').forEach(list => list.classList.add('hidden'));
      document.getElementById(time).classList.remove('hidden');
      stopAllAudio();
    }

    function playAudio(audioElement, spinner, lyricsElement) {
      if (currentAudio && currentAudio !== audioElement) {
        currentAudio.pause();
        resetPreviousAudio(currentAudio);
      }
      
      currentAudio = audioElement;
      const duration = Math.floor(audioElement.duration);
      startCountdown(duration);

      // Hide other music cards and increase size of current card
      const currentCard = audioElement.closest('.music-card');
      document.querySelectorAll('.music-card').forEach(card => {
        if (card !== currentCard) {
          card.classList.add('hidden');
        }
      });
      
      // Increase size of current card and image
      currentCard.classList.add('playing');
      const currentImage = currentCard.querySelector('img');
      currentImage.classList.add('playing-image');

      document.getElementById('button-group').classList.add('hidden');
      document.getElementById('main-title').classList.add('hidden');
      document.getElementById('countdown').classList.remove('hidden');

      spinner.classList.remove('hidden');
      lyricsElement.classList.remove('hidden');
      
      currentAudio.play();

      currentAudio.onpause = () => {
        stopCountdown();
        // Reset card and image size
        currentCard.classList.remove('playing');
        currentImage.classList.remove('playing-image');
      };
      
      currentAudio.onended = () => {
        stopCountdown();
        // Reset card and image size
        currentCard.classList.remove('playing');
        currentImage.classList.remove('playing-image');
        setTimeout(() => location.reload(), 1000);
      };
    }

    function stopAllAudio() {
      document.querySelectorAll('audio').forEach(audio => {
        audio.pause();
        audio.currentTime = 0;
        resetPreviousAudio(audio);
      });
      currentAudio = null;
      stopCountdown();
      
      // Show all cards and reset sizes
      document.querySelectorAll('.music-card').forEach(card => {
        card.classList.remove('hidden', 'playing');
        const image = card.querySelector('img');
        image.classList.remove('playing-image');
      });
    }

    function resetPreviousAudio(audio) {
      const spinner = audio.parentElement.querySelector('.spinner');
      const lyrics = audio.parentElement.querySelector('.lyrics');
      if (spinner) spinner.classList.add('hidden');
      if (lyrics) lyrics.classList.add('hidden');
    }

    function startCountdown(seconds) {
      let remaining = seconds;
      document.getElementById('countdown-timer').innerText = formatTime(remaining);

      countdownInterval = setInterval(() => {
        remaining--;
        if (remaining <= 0) {
          clearInterval(countdownInterval);
          location.reload();
        } else {
          document.getElementById('countdown-timer').innerText = formatTime(remaining);
        }
      }, 1000);
    }

    function stopCountdown() {
      if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
      }
      document.getElementById('countdown').classList.add('hidden');
    }

    function formatTime(seconds) {
      const mins = Math.floor(seconds / 60);
      const secs = seconds % 60;
      return `${mins}:${secs < 10 ? '0' + secs : secs}`;
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
    
    .music-card {
      transition: all 0.3s ease;
      margin: 1rem;
    }
    
    .music-card.playing {
      width: 90%;
      max-width: 800px;
      margin: 2rem auto;
      position: relative;
      z-index: 10;
    }
    
    .playing-image {
      height: 500px !important;
    }

    .music-list {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 2rem;
      padding: 1rem;
    }

    .music-list.hidden {
      display: none;
    }
  </style>
</head>
<body>

<div class="min-h-screen flex flex-col items-center px-6 pt-10 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">

  <!-- Main Title -->
  <h1 id="main-title" class="text-5xl font-extrabold mb-12 animate-pulse text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-pink-500">
    Meditation Playlist
  </h1>

  <!-- Countdown -->
  <div id="countdown" class="text-4xl font-bold text-indigo-400 mb-12 hidden">
    Time Remaining: <span id="countdown-timer"></span>
  </div>

  <!-- Button Group -->
  <div id="button-group" class="flex gap-6 mb-10">
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
    <div class="bg-gray-800 rounded-2xl w-80 overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-300 transform hover:scale-105 flex flex-col music-card">
      <img src="<?= $music[1] ?>" class="w-full h-48 object-cover transition-all duration-300" alt="<?= $music[0] ?>">
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
        ["Bright Horizon", "../../resources/image/491186488_1233494641673773_2842554514402736449_n.jpg", "../../resources/video/491506014_30347214391536018_6248472493198818994_n.mp4", "Sun high in the sky, endless blue and wide..."],
        ["Midday Breeze", "../../resources/image/491190010_1957635444974950_4260005271893533536_n.jpg", "../../resources/video/491897414_9734825196611097_6919508935587538169_n.mp4", "Whispering winds carry dreams along..."],
        ["Lazy Noon", "../../resources/image/491184684_1439375530377996_702826330490027647_n.jpg", "../../resources/video/494056757_9763477183698054_8223793322611642650_n.mp4", "Soft and slow, the day moves gently..."],
      ];
      foreach($afternoonMusic as $music): 
    ?>
    <div class="bg-gray-800 rounded-2xl w-80 overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-300 transform hover:scale-105 flex flex-col music-card">
      <img src="<?= $music[1] ?>" class="w-full h-48 object-cover transition-all duration-300" alt="<?= $music[0] ?>">
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
        ["Twilight Calm", "../../resources/image/491186428_1024909026245955_8793238593760314726_n.jpg", "../../resources/video/492156520_29454918080789980_8177381556537864474_n.mp4", "Twilight fades, the world slows down..."],
        ["Silent Nightfall", "../../resources/image/491186519_979648741035943_3778194296248305387_n.jpg", "../../resources/video/491527291_10041145832565108_6220701080957486383_n.mp4", "Stars appear, night whispers near..."],
        ["Midnight Reflections", "../../resources/image/491188981_1750562515870089_3760467549162254654_n.jpg", "../../resources/video/491949299_30162815653305525_8168772926024598170_n.mp4", "Thoughts float under the midnight sky..."],
      ];
      foreach($eveningMusic as $music): 
    ?>
    <div class="bg-gray-800 rounded-2xl w-80 overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-300 transform hover:scale-105 flex flex-col music-card">
      <img src="<?= $music[1] ?>" class="w-full h-48 object-cover transition-all duration-300" alt="<?= $music[0] ?>">
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

</div>

</body>
</html>
