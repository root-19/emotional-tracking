<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Relax Your Thoughts</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Quicksand:wght@500&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      overflow: hidden;
      background-color: black;
      height: 100vh;
      width: 100vw;
      font-family: 'Poppins', 'Quicksand', sans-serif;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    /* Special style for May 2nd */
    body.may-second {
      background-color: rgba(255, 255, 255, 0.8);
      color: black;
    }

    #starCanvas {
      position: absolute;
      top: 0;
      left: 0;
      z-index: 0;
    }

    .message {
      position: absolute;
      top: 50px;
      width: 90%;
      max-width: 800px;
      text-align: center;
      font-size: 2.2rem;
      font-weight: 600;
      z-index: 2;
      opacity: 0;
      transition: opacity 2s;
      line-height: 1.5;
      letter-spacing: 1px;
    }

    .circle {
      width: 300px;
      height: 300px;
      background: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: black;
      font-size: 1.5rem;
      text-align: center;
      padding: 20px;
      word-wrap: break-word;
      overflow-wrap: break-word;
      transition: all 5s ease;
      z-index: 2;
    }

    .input-area {
      margin-top: 20px;
      z-index: 2;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    input[type="text"] {
      padding: 10px;
      width: 300px;
      border: none;
      border-radius: 20px;
      font-size: 1rem;
      outline: none;
      margin-bottom: 10px;
    }

    button {
      padding: 10px 20px;
      background-color: white;
      color: black;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: bold;
    }

    button:hover {
      background-color: #f1f1f1;
    }

    .hide {
      display: none;
    }

    /* Fade in animation for the video */
    #bgVideo.fade-in {
      visibility: visible;
      opacity: 1;
      transition: opacity 2s ease;
      pointer-events: auto;
    }
  </style>
</head>
<body>

<canvas id="starCanvas"></canvas>

<div class="message" id="messageArea"></div>

<div class="circle" id="circleText"></div>

<div class="input-area" id="inputContainer">
  <input type="text" id="thoughtInput" placeholder="Type your thought here...">
  <div style="display: flex; gap: 10px;">
    <button onclick="startMeditation()">Let it go</button>
    <button onclick="window.location.href='/login'" style="background-color: #f1f1f1;">Skip</button>
  </div>
</div>

<!-- Background Video -->
<video id="bgVideo" autoplay loop muted
  style="visibility: hidden; opacity: 0; pointer-events: none; position: absolute; top: 0; left: 0; z-index: -1; width: 100vw; height: 100vh;">
  <source src="../resources/video/491527291_10041145832565108_6220701080957486383_n.mp4" type="video/mp4">
</video>

<script>
  // Check if it's May 2nd
  const today = new Date();
  const currentMonth = today.getMonth();
  const currentDay = today.getDate();
  
  // // Apply white screen only on May 2nd
  // if (currentMonth === 4 && currentDay === 2) { // Month is 0-based, so 4 is May
  //   document.body.classList.add('may-second');
  // }

  // Stars animation
  const canvas = document.getElementById('starCanvas');
  const ctx = canvas.getContext('2d');
  let stars = [];

  function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  resizeCanvas();
  window.addEventListener('resize', resizeCanvas);

  function createStar() {
    return {
      x: Math.random() * canvas.width,
      y: canvas.height + Math.random() * 100,
      radius: Math.random() * 2,
      speed: Math.random() * 1 + 0.5
    };
  }

  for (let i = 0; i < 150; i++) {
    stars.push(createStar());
  }

  function drawStars() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = 'white';
    for (let star of stars) {
      ctx.beginPath();
      ctx.arc(star.x, star.y, star.radius, 0, 2 * Math.PI);
      ctx.fill();
      star.y -= star.speed;
      if (star.y < 0) {
        Object.assign(star, createStar());
      }
    }
    requestAnimationFrame(drawStars);
  }
  drawStars();

  // Meditation logic
  const messages = [
    "Relax and watch your thoughts",
    "Take a deep breath in...",
    "...and breathe out",
    "Everything is okay",
    "Your life is okay",
    "Life is much grander than this thought",
    "The universe is over 93 billion light years in distance",
    "Our galaxy is small",
    "Our sun is tiny",
    "The earth is miniscule",
    "Our cities are insignificant...",
    "...and you are microscopic",
    "This thought may not disappear easily, but it will...",
    "And life will go on...",
    "Hope you feel less stressed and more motivated."
  ];

  function startMeditation() {
    var thought = document.getElementById('thoughtInput').value.trim();
    if (thought === "") {
      alert("Please enter a thought!");
      return;
    }

    document.getElementById('inputContainer').classList.add('hide');
    var circleText = document.getElementById('circleText');
    circleText.innerText = thought;

  
    var bgVideo = document.getElementById('bgVideo');
    bgVideo.muted = false; 
    bgVideo.classList.add('fade-in'); 
    bgVideo.play().catch((error) => {
      console.log('Video play failed:', error);
    });

  
    let messageArea = document.getElementById('messageArea');
    let index = 0;

    function showNextMessage() {
      if (index < messages.length) {
        messageArea.innerText = messages[index];
        messageArea.style.opacity = 1;

        setTimeout(() => {
          messageArea.style.opacity = 0;
          index++;
          setTimeout(showNextMessage, 2000); // Next message after fade out
        }, 4000);
      } else {
        setTimeout(() => {
          window.location.href = "/login"
        }, 3000);
      }
    }
    showNextMessage();

    // Shrink the circle and text slowly
    setTimeout(() => {
      circleText.style.transform = "scale(0.8)";
    }, 3000);

    setTimeout(() => {
      circleText.style.transform = "scale(0.5)";
    }, 6000);

    setTimeout(() => {
      circleText.style.transform = "scale(0.3)";
    }, 9000);

    setTimeout(() => {
      circleText.style.transform = "scale(0.2)";
    }, 12000);
  }
</script>

</body>
</html>
