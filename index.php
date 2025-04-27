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
        async function loadData() {
            try {
                
                const response = await fetch('./api/getPosts.php');
                const data = await response.json();

                 // Create HTML with template literals
                let html = data.map(post => 
                        `<div class="border border-white relative max-w-sm w-full bg-white rounded-lg shadow-md m-2 hover:shadow-2xl hover:bg-gray-100 hover:cursor-pointer"
                            onclick="redirectToPost(${post.post_id})">
                            <!-- Post Image -->
                            <div class="w-full h-48 overflow-hidden rounded-t-lg">
                                ${post.image_path ? `<img src="./${post.image_path}" alt="Post Image" class="w-full h-full object-cover">` : ''}
                            </div>

                            <!-- Post Information -->
                            <div class="absolute bottom-10 left-0 bg-white opacity-50 w-full hover:opacity-80">
                                <p class="text-2xl mx-2 font-bold text-white-800">${post.header}</p>
                                <p class="text-xs mx-2 text-white-600 font-semibold">By: ${post.name}</p>
                            </div>
                            
                                <!-- Like and Comment Counts -->
                            <div class="flex items-center justify-between mt-2 mx-4 text-sm">
                                    <div class="flex items-center mb-2">
                                        <button id="like-button-${post.post_id}" onclick="likePost(${post.post_id}); event.stopPropagation();" class="flex items-center text-red-500 hover:text-red-600">
                                            ${post.contains_id == 1 ? "Liked" :`<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6 mr-1" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>`}
                                        </button>
                                        <span id="like-count-${post.post_id}" class="ml-2 text-gray-700">${post.likes ? post.likes : 0}</span>
                                        <span class="text-gray-600 ml-1">Likes</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-gray-700">${post.comments ? post.comments : 0 }</span>
                                        <span class="text-gray-600">Feedbacks</span>
                                    </div>
                            </div>
                        </div>
                        `).join('');

                    document.getElementById('postList').innerHTML = html;
                    document.getElementById('postList1').innerHTML = html;
                    document.getElementById('postList2').innerHTML = html;
                    document.getElementById('postList3').innerHTML = html;
         // Insert into the DOM
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // Call loadData on page load
        document.addEventListener('DOMContentLoaded', loadData);
    </script>
</head>
<body class="bg-[#F9DBBA] font-mono">
<?php include 'navbar.php'; ?>
<main class="my-16">
        <div>Most Liked Recipes</div>
        <div id="postList" class="flex flex-wrap justify-center"></div>
        <div>Breakfast Recipes</div>
        <div id="postList1" class="flex flex-wrap justify-center"></div>
        <div>Lunch Recipes</div>
        <div id="postList2" class="flex flex-wrap justify-center"></div>
        <div>Dinner Recipes</div>
        <div id="postList3" class="flex flex-wrap justify-center"></div>
</main>
</body>
</html>

