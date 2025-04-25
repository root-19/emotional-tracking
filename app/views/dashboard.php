<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mood Pet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes spin-clockwise {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .animate-spin-slow {
            animation: spin-clockwise 5s linear infinite;
        }

        @keyframes wiggle {
            0%, 100% { transform: rotate(-5deg); }
            50% { transform: rotate(5deg); }
        }

        .wiggle {
            animation: wiggle 0.3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-white min-h-screen">

<?php include 'header.php'; ?>

<!-- Mood Pet Section -->
<div class="text-center mb-6 mt-8 flex flex-col items-center justify-center">
    <div id="octagonWrapper" class="relative w-32 h-32 mx-auto mb-4">
        <svg class="absolute inset-0 w-full h-full animate-spin-slow z-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="none" stroke="currentColor">
            <polygon points="50,5 61,35 95,35 68,57 79,90 50,70 21,90 32,57 5,35 39,35" stroke="purple" stroke-width="3" fill="none" />
        </svg>
        <div class="w-full h-full flex items-center justify-center z-10 relative">
            <img id="centerPetImg" src="" alt="Pet Mood" class="w-20 h-20 rounded-full shadow-md object-cover hidden">
        </div>
    </div>
    <p class="text-gray-600">What's your feeling now?</p>
</div>

<!-- Mood Input Form -->
<form id="moodForm" onsubmit="showPet(event)" class="w-full max-w-sm px-6 flex flex-col items-center justify-center ml-auto mr-auto">
    <input type="text" id="moodInput" placeholder="Type your mood..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 mb-4">
    <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-md hover:bg-purple-700 transition">Show Me a Pet</button>
</form>

<!-- Spinner -->
<div id="loadingSpinner" class="mt-10 hidden">
    <div class="w-24 h-24 border-8 border-black border-t-transparent rounded-full mx-auto animate-spin"></div>
</div>

<!-- Mood Result -->
<div id="petImage" class="mt-10 hidden text-center transition-opacity duration-700 opacity-0">
    <img id="petMoodImg" src="" alt="Pet Mood" class="w-48 h-48 mx-auto rounded-xl shadow-lg transform scale-90 hover:scale-100 transition-transform duration-500">
    <p id="petMoodText" class="mt-4 text-lg font-semibold text-gray-700 font-bol whitespace-pre-wrap leading-relaxed text-center px-4 sm:px-10 lg:px-20 text-xl"></p>

</div>

<!-- JS Script -->
<script>
    function typeText(element, text, delay = 50) {
        element.innerText = '';
        let index = 0;
        return new Promise(resolve => {
            const typing = setInterval(() => {
                element.innerText += text.charAt(index);
                index++;
                if (index >= text.length) {
                    clearInterval(typing);
                    resolve();
                }
            }, delay);
        });
    }

    function showPet(event) {
        event.preventDefault();

        const mood = document.getElementById("moodInput").value.toLowerCase();
        const spinner = document.getElementById("loadingSpinner");
        const petImage = document.getElementById("petImage");
        const petMoodImg = document.getElementById("petMoodImg");
        const petMoodText = document.getElementById("petMoodText");
        const centerPetImg = document.getElementById("centerPetImg");
        const octagonWrapper = document.getElementById("octagonWrapper");
        const form = event.target;

        // Reset view
        petImage.classList.add("hidden");
        petImage.classList.remove("opacity-100");
        spinner.classList.remove("hidden");
        centerPetImg.classList.add("hidden");
        octagonWrapper.classList.remove("hidden");
        form.style.display = "none";

        let moodType = "";
        let imageUrl = "";
        let comfortingText = "";

if (mood.includes("happy") || mood.includes("joy")) {
    moodType = "Happy Buddy 🐶";
    imageUrl = "../../resources/image/happy.jpg";
    comfortingText = "It's amazing to see your happiness shining! ✨\n\nKeep holding on to that joy and let it brighten not just your day, but everyone around you. You're a beacon of light, and the world needs more of that energy. 🌟";
} else if (mood.includes("sad") || mood.includes("lonely")) {
    moodType = "Sad Snuggler 🐱";
    imageUrl = "../../resources/image/sad.jpg";
    comfortingText = "Hey, it’s okay to feel a little down sometimes. 😔\n\nJust know you're never truly alone, and this feeling won't last forever. Take a deep breath, drink some water, and know that better days are coming. You've got a soft place here to rest for a bit. 💜";
} else if (mood.includes("broken") || mood.includes("heart")) {
    moodType = "Broken-Heart Bunny 🐰";
    imageUrl = "../../resources/image/cry.jpg";
    comfortingText = "I know your heart feels heavy right now 💔\n\nBut you are strong, even when it feels like you're breaking. Pain doesn't mean you're weak—it means you cared deeply. Take your time to heal. You are worthy of love and peace. 🕊️";
} else {
    moodType = "Confused Critter 🐾";
    imageUrl = "https://i.imgur.com/W3Qb7Rm.gif";
    comfortingText = "It’s okay to not have everything figured out. 🌧️\n\nConfusion is just a part of growing and learning. You’re doing better than you think. One step at a time. Breathe, reflect, and move gently. Everything will fall into place. 🌱";
}


        setTimeout(async () => {
            spinner.classList.add("hidden");

            petMoodImg.src = imageUrl;
            petImage.classList.remove("hidden");
            petImage.classList.add("opacity-100");
            petMoodImg.classList.add("wiggle");

            centerPetImg.src = imageUrl;
            centerPetImg.classList.remove("hidden");
            octagonWrapper.classList.add("hidden");

            setTimeout(() => petMoodImg.classList.remove("wiggle"), 3000);

            await typeText(petMoodText, comfortingText, 40);

            // Wait for 1 minute before resetting
            setTimeout(() => {
                petMoodText.innerText = '';
                form.style.display = "block";
                petImage.classList.add("hidden");
                centerPetImg.classList.add("hidden");
                octagonWrapper.classList.remove("hidden");
            }, 60000);

        }, 2000);
    }
</script>

</body>
</html>
