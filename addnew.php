<?php
// routes/index.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./js/cardFunctions.js" defer></script>
    <script>
        // Async function to load data

    </script>
</head>
<body class="bg-[#F9DBBA] font-mono">
<?php include 'navbar.php'; ?>

<main class="flex items-center justify-center min-h-screen">
    <form id="createPostForm" class="w-full" action="./api/createPost.php" method="POST" enctype="multipart/form-data">
    <div class="grid grid-cols-3 gap-2 min-h-screen w-full">
        <div class="pt-14 shadow-xl w-full flex flex-col items-center justify-between bg-white p-8 rounded-lg overflow-auto h-screen">
            <div class="w-full p-4 flex-none">
                <label for="header">Post Title:</label>
                <input class="border-2 border-black w-full rounded-md" type="text" id="header" name="header" required>
            </div>
            <div class="w-full p-4 flex-1">
                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <img id="preview" src="#" alt="Image Preview" class="mt-4 max-w-xs hidden rounded shadow-md">
            </div>
        </div>
        <div class="pt-18 shadow-xl w-full flex flex-col justify-center bg-white p-8 rounded-lg overflow-auto h-screen">
            <h2>Ingredients:</h2>
            <div id="ingredients">
            </div>
            <button class="block" onclick="return addNewIngredients()">Add Ingredient</button>
        </div>
        <div class="pt-18 shadow-xl w-full flex flex-col justify-center bg-white p-8 rounded-lg overflow-auto h-screen">
            <h2>Process:</h2>
            <div id="process">
            </div>
            <button class="block" onclick="return addNewProcess()">Add Process</button>
        </div>
    </div>
    <button type="submit" class="absolute bg-[#F9DBBA] p-4 rounded-xl shadow-xl bottom-5 right-5">Post</button>

</form>

</main>

    <script>

        let ingredients = [];
        let process = [];

        document.getElementById('createPostForm').onsubmit = async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            const jsonStr = JSON.stringify({ingredients: ingredients, process : process});

            formData.append("content", jsonStr);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert("Recipe Posted!")
                window.location.href = 'index.php';
            } else {
                alert(result.message);
            }
        };


        function addNewIngredients(){
            ingredients.push("");
            const ingredientDivElement = document.createElement('div');
            ingredientDivElement.classList = "w-full flex"

            const ingredientElement = document.createElement('input');
            ingredientElement.type = "text";
            ingredientElement.className="border-2 border-black rounded-md mb-2 w-fit flex-1"
            ingredientElement.name = "i-" + (ingredients.length + 1);
            ingredientElement.required = true;
            const index = ingredients.length -1;
            ingredientElement.addEventListener("change",()=>editIngredients(index, event));
            const ingDiv = document.getElementById("ingredients");

            const removeElement = document.createElement('button');
            removeElement.type = "button";
            removeElement.textContent = "-";
            removeElement.className = "flex-none px-2 hover:bg-gray";
            removeElement.addEventListener("click", () => removeIngredients(index, ingredientDivElement));

            ingredientDivElement.appendChild(ingredientElement);
            ingredientDivElement.appendChild(removeElement);
            ingDiv.appendChild(ingredientDivElement);
            
            return false;
        }

        function editIngredients(index,ingComponent){
            console.log(ingComponent.target.value);

            ingredients[index] = ingComponent.target.value;

        };

        function removeIngredients(index, component){
        
        ingredients.splice(index,1);

        };

       function addNewProcess(){
            process.push("");
            const processDivElement = document.createElement('div');
            processDivElement.classList = "w-full flex"

            const processElement = document.createElement('textarea');
            processElement.className="border-2 border-black rounded-md mb-2 w-fit flex-1"
            processElement.name = "p-" + (process.length + 1);
            processElement.required = true;
            const index = process.length -1;
            processElement.addEventListener("change",()=>editProcess(index, event));
            const ingDiv = document.getElementById("process");

            const removeElement = document.createElement('button');
            removeElement.type = "button";
            removeElement.textContent = "-";
            removeElement.className = "flex-none px-2 hover:bg-gray";
            removeElement.addEventListener("click", () => removeProcess(index, processDivElement));

            processDivElement.appendChild(processElement);
            processDivElement.appendChild(removeElement);
            ingDiv.appendChild(processDivElement);
            
            return false;
        }

        function editProcess(index,ingComponent){

            process[index] = ingComponent.target.value;

        };

        function removeProcess(index, component){
        
        process.splice(index,1);
        component.remove();

        };

        
        window.addEventListener('DOMContentLoaded', () => {
            addNewProcess();
            addNewIngredients();
        });
    </script>

    <script>
        const imageInput = document.getElementById('image');
        const preview = document.getElementById('preview');

        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result; // Set the image source to the file's data URL
                    preview.classList.remove('hidden'); // Display the preview
                };

                reader.readAsDataURL(file); // Read the file as a data URL
            } else {
                preview.src = '#'; // Reset if no file is selected
                preview.classList.add('hidden'); // Hide the preview
            }
        });
    </script>
</body>
</html>
