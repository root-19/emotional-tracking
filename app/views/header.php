
 <!-- header.php -->
 <script src="https://cdn.jsdelivr.net/npm/@heroicons/react@1.0.6/outline/index.min.js"></script>

<header class="bg-white shadow p-4 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <!-- Logo & Brand -->
        <img src="../../resources/image/490998649_611840321893766_2785696449251422280_n.jpg" alt="Logo" class="w-10 h-10 rounded-full">
        <h1 class="text-xl font-bold text-purple-700">Emotion Track</h1>

        <!-- Navigation Links -->
        <nav class="flex gap-6 ml-6">
    <a href="/dashboard" class="text-gray-700 hover:text-purple-700 font-medium transition flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor" class="text-gray-500">
            <path fill-rule="evenodd" d="M10 18a8 8 0 10-8-8 8 8 0 008 8zm0 2a10 10 0 100-20 10 10 0 000 20zm-1-14a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1V6z" clip-rule="evenodd"/>
        </svg>
        Dashboard
    </a>
    <a href="/notes" class="text-gray-700 hover:text-purple-700 font-medium transition flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor" class="text-gray-500">
            <path fill-rule="evenodd" d="M13 3H7a2 2 0 00-2 2v10a2 2 0 002 2h6a2 2 0 002-2V5a2 2 0 00-2-2zm-1 12H8V5h4v10z" clip-rule="evenodd"/>
        </svg>
        Notes
    </a>
    <a href="/emotionTrack" class="text-gray-700 hover:text-purple-700 font-medium transition flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor" class="text-gray-500">
            <path fill-rule="evenodd" d="M9 2a7 7 0 110 14 7 7 0 010-14zM8 8.5a.5.5 0 011 0V9h.5a.5.5 0 010 1H9v.5a.5.5 0 01-1 0V10H7.5a.5.5 0 010-1H8V8.5z" clip-rule="evenodd"/>
        </svg>
        Emotion
    </a>
    <a href="/memories" class="text-gray-700 hover:text-purple-700 font-medium transition flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor" class="text-gray-500">
            <path fill-rule="evenodd" d="M16 4H4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2zm0 10H4V6h12v8z" clip-rule="evenodd"/>
        </svg>
        Memories
    </a>
    <!-- <a href="/logout" class="text-gray-700 hover:text-purple-700 font-medium transition flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor" class="text-gray-500">
            <path fill-rule="evenodd" d="M10 3a7 7 0 11-7 7 7 7 0 017-7zm0 2a5 5 0 100 10 5 5 0 000-10zm-1 2a1 1 0 011 1v2.5h2a1 1 0 011 1V11h-3V9a1 1 0 01-1-1H9v2a1 1 0 011 1V7z" clip-rule="evenodd"/>
        </svg>
        Logout
    </a> -->
</nav>

    </div>

    <!-- User Dropdown -->
    <div class="relative inline-block text-left">
        <button id="userMenuButton" onclick="toggleDropdown()" class="flex items-center gap-2 focus:outline-none hover:text-purple-700">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M5.121 17.804A4 4 0 0110 14h4a4 4 0 014.879 3.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span><?php echo $_SESSION['username']; ?></span>
            <svg class="w-4 h-4 mt-1 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-50">
            <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="inline-block w-4 h-4 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                </svg>
                Logout
            </a>
        </div>
    </div>
</header>



        <!-- Main content -->
        <div class="flex-2 bg-white p-6 ">
            
<script>
    function toggleDropdown() {
        const dropdown = document.getElementById("userDropdown");
        dropdown.classList.toggle("hidden");
    }

    window.addEventListener("click", function(event) {
        const button = document.getElementById("userMenuButton");
        const dropdown = document.getElementById("userDropdown");
        if (!button.contains(event.target)) {
            dropdown.classList.add("hidden");
        }
    });
</script>
