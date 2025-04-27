<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - EmoTrack</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-tr from-pink-100 via-purple-100 to-blue-100 text-gray-800">

  <!-- Register Form Section -->
  <div class="flex items-center justify-center min-h-screen">
    <div class="bg-white w-full max-w-md p-10 rounded-xl shadow-2xl">
      <h2 class="text-4xl font-bold mb-6 text-center text-purple-700">Create Your Emotion Track Account</h2>

      <form action="/register" method="POST" class="space-y-5">
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
          <input type="text" name="username" id="username" required 
                 class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-300" />
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" name="email" id="email" required 
                 class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-300" />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" name="password" id="password" required 
                 class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-300" />
        </div>

        <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-full hover:bg-pink-600 transition text-lg font-semibold">
          Join EmoTrack
        </button>
      </form>

      <p class="text-center mt-6 text-sm text-gray-600">
        Already a member? <a href="/login" class="text-purple-600 hover:underline">Log in here</a>
      </p>
    </div>
  </div>

</body>
</html>
