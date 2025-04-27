
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v6m0 6h4a2 2 0 002-2v-4a2 2 0 00-2-2h-4a2 2 0 00-2 2v4a2 2 0 002 2z" />
                </svg>
                Dashboard
            </a>

            <a href="/notes" class="text-gray-700 hover:text-purple-700 font-medium transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8M8 12h8m-8-4h8M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Notes
            </a>

            <a href="/memories" class="text-gray-700 hover:text-purple-700 font-medium transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h2l3 3 3-3h2a2 2 0 002-2v-1M4 8v8M4 8h16M4 8l4-4m12 4l-4-4" />
                </svg>
                Memories
            </a>

            <!-- Added Meditation Link -->
            <a href="/meditation" class="text-gray-700 hover:text-purple-700 font-medium transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Meditation
            </a>

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
