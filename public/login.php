<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome to EmoTrack</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .typewriter-text::after {
      content: '|';
      animation: blink 1s infinite;
    }

    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0; }
    }

    .full-height {
      height: 100vh;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-purple-200 via-pink-100 to-blue-100 text-gray-800">

  <!-- Welcome Section -->
  <div class="flex items-center justify-center full-height">
    <div class="text-center px-6">
      <h1 class="text-5xl font-bold mb-6 text-purple-800">
        Welcome to <span class="text-pink-600">Emotion Track</span>
      </h1>

      <p id="typewriter" class="text-lg text-gray-700 mb-10 typewriter-text"></p>

      <button onclick="goToLogin()" class="bg-pink-500 hover:bg-pink-600 text-white text-lg font-medium px-8 py-3 rounded-full transition">
        Start Tracking →
      </button>
    </div>
  </div>

  <!-- Login Form Section -->
  <div id="loginForm" class="hidden absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white w-full max-w-md p-10 rounded-lg shadow-lg">
    <div class="text-gray-800">
      <h2 class="text-3xl font-bold mb-4 text-center">Log In</h2>
      
      <?php if (isset($error)) { echo "<p class='text-red-500 text-center'>$error</p>"; } ?>

      <form method="POST" action="/login">
        <label class="block text-sm text-gray-700">Email</label>
        <input type="email" name="email" class="w-full p-3 mb-4 border border-gray-300 rounded" required>

        <label class="block text-sm text-gray-700">Password</label>
        <input type="password" name="password" class="w-full p-3 mb-6 border border-gray-300 rounded" required>

        <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-full hover:bg-purple-700 transition">
          Log In
        </button>

        <div class="text-center mt-5">
          <a href="/forget-password" class="text-sm text-blue-500 hover:underline">Forgot your password?</a>
        </div>
      </form>

      <p class="text-center mt-4">No account yet? 
        <a href="/register" class="text-purple-600 hover:underline">Sign up here</a>
      </p>
    </div>
  </div>

  <script>
    const text = "Track your emotions. Understand your patterns. Build a healthier you — one feeling at a time.";
    const element = document.getElementById("typewriter");
    let i = 0;

    function type() {
      if (i < text.length) {
        element.innerHTML += text.charAt(i);
        i++;
        setTimeout(type, 45);
      }
    }

    window.onload = type;

    function goToLogin() {
      document.querySelector('.text-center').classList.add('hidden');
      document.getElementById('loginForm').classList.remove('hidden');
    }
  </script>

</body>
</html>
