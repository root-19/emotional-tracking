<?php

require_once  __DIR__ . '/../controller/NotesController.php';

$controller  =new NotesController();
$controller->save();
include 'header.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notes</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function showInput(type) {
            document.getElementById('text_input').style.display = (type === 'text') ? 'block' : 'none';
            document.getElementById('draw_input').style.display = (type === 'drawing') ? 'block' : 'none';
            document.getElementById('image_input').style.display = (type === 'image') ? 'block' : 'none';
            document.getElementById('type').value = type;
        }

        window.onload = function() {
    const canvas = document.getElementById('drawCanvas');
    const ctx = canvas.getContext('2d');
    const penColorInput = document.getElementById('penColor');
    const penSizeInput = document.getElementById('penSize');

    let drawing = false;
    let selectedSticker = null;

    // Start drawing
    canvas.addEventListener('mousedown', () => drawing = true);
    canvas.addEventListener('mouseup', () => { 
        drawing = false; 
        ctx.beginPath(); 
    });

    // Mouse Move (Draw or Move Cursor)
    canvas.addEventListener('mousemove', function(e) {
        if (!drawing || selectedSticker) return;
        ctx.lineWidth = penSizeInput.value;
        ctx.lineCap = 'round';
        ctx.strokeStyle = penColorInput.value;
        ctx.lineTo(e.clientX - canvas.offsetLeft, e.clientY - canvas.offsetTop);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(e.clientX - canvas.offsetLeft, e.clientY - canvas.offsetTop);
    });

    // Add sticker
    canvas.addEventListener('click', function(e) {
        if (selectedSticker) {
            ctx.font = "40px Arial";
            ctx.fillText(selectedSticker, e.clientX - canvas.offsetLeft, e.clientY - canvas.offsetTop);
            selectedSticker = null; // Deselect after placing
        }
    });

    window.addSticker = function(sticker) {
        selectedSticker = sticker;
    }
}

// Save drawing data when form submits
function saveDrawing() {
    const canvas = document.getElementById('drawCanvas');
    document.getElementById('drawing_data').value = canvas.toDataURL();
}

    </script>
</head>
<div class=" min-h-screen flex items-center justify-center p-6">

<div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-2xl space-y-6">
    <h2 class="text-3xl font-bold text-gray-800 text-center">Create a Journal</h2>

    <div class="flex justify-center gap-4">
        <button onclick="showInput('text')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full">Journal</button>
        <button onclick="showInput('drawing')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-full">Draw</button>
        <button onclick="showInput('image')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-full">Send Image</button>
    </div>

    <form method="POST" enctype="multipart/form-data" onsubmit="saveDrawing()" action="" class="space-y-6">
        <input type="hidden" name="type" id="type">

        <!-- Text Note -->
        <div id="text_input" style="display:none;">
            <label class="block mb-2 text-lg font-semibold text-gray-700">Write your note:</label>
            <textarea name="text_note" rows="6" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
        </div>

        <!-- Drawing -->
     <!-- Drawing Section -->
<div id="draw_input" style="display:none;" class="space-y-6">
    <label class="block text-xl font-bold text-gray-800">Draw Your Note:</label>

    <!-- Controls -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-4 bg-gray-100 rounded-lg shadow-inner">
        <div class="flex items-center gap-4">
            <label class="flex items-center gap-2 text-gray-700 font-semibold">
                Pen Color:
                <input type="color" id="penColor" value="#1f2937" class="w-10 h-10 p-0 border-0 cursor-pointer rounded-full">
            </label>

            <label class="flex items-center gap-2 text-gray-700 font-semibold">
                Pen Size:
                <input type="range" id="penSize" min="1" max="20" value="5" class="w-32 accent-blue-600">
            </label>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-gray-700 font-semibold">Stickers:</span>
            <button type="button" onclick="addSticker('🎨')" class="text-2xl hover:scale-110 transition-transform">🎨</button>
            <button type="button" onclick="addSticker('🔥')" class="text-2xl hover:scale-110 transition-transform">🔥</button>
            <button type="button" onclick="addSticker('⭐')" class="text-2xl hover:scale-110 transition-transform">⭐</button>
            <button type="button" onclick="addSticker('🌈')" class="text-2xl hover:scale-110 transition-transform">🌈</button>
            <button type="button" onclick="addSticker('🚀')" class="text-2xl hover:scale-110 transition-transform">🚀</button>
        </div>
    </div>

    <!-- Canvas -->
    <div class="flex justify-center">
        <canvas id="drawCanvas" width="400" height="400" class="border-2 border-gray-400 rounded-lg shadow-lg bg-white cursor-crosshair"></canvas>
    </div>

    <input type="hidden" name="drawing_data" id="drawing_data">
</div>


        <!-- Image Upload -->
        <div id="image_input" style="display:none;">
            <label class="block mb-2 text-lg font-semibold text-gray-700">Upload an image:</label>
            <input type="file" name="image_file" accept="image/*" class="w-full bg-gray-100 p-3 rounded-lg border border-gray-300 file:bg-purple-600 file:text-white hover:file:bg-purple-700 file:rounded-full">
        </div>

        <div class="flex justify-center">
            <button type="submit" class="w-full md:w-1/2 bg-black hover:bg-gray-900 text-white text-lg py-3 rounded-full font-bold">Save Note</button>
        </div>
    </form>
</div>

<?php if (isset($_GET['success'])): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Note Saved!',
        text: 'Your note has been saved successfully!',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    }).then(() => window.location.href = window.location.pathname);
</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Something went wrong while saving your note!',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    }).then(() => window.location.href = window.location.pathname);
</script>
<?php endif; ?>

</body>
</html>
