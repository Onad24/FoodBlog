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
                const response = await fetch('./api/getMyPosts.php');
                const data = await response.json();
                 // Create HTML with template literals
                    document.getElementById('name').textContent = data.info[0].name;
                    document.getElementById('email').textContent = data.info[0].email;
                    document.getElementById('occupation').textContent = data.info[0].occupation;
                    document.getElementById('birthPlace').textContent = data.info[0].birthPlace;
                    document.getElementById('birthDate').textContent = data.info[0].birthDate;
                    document.getElementById('aboutMe').textContent = data.info[0].aboutMe;

                let html = data.data.map(post => 
                        `<div class="h-fit border border-white relative max-w-sm w-full bg-white rounded-lg shadow-md m-2 hover:shadow-2xl hover:bg-gray-100 hover:cursor-pointer"
                            onclick="redirectToPost(${post.post_id})">
                            <!-- Post Image -->
                             <button 
                                class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 z-20"
                                aria-label="Delete"
                                onclick="event.stopPropagation(); removePost(${post.post_id})"
                            >
                                <!-- Trashcan Icon -->
                                <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="w-5 h-5" 
                                viewBox="0 0 20 20" 
                                fill="currentColor"
                                >
                                <path 
                                    fill-rule="evenodd" 
                                    d="M8 4a1 1 0 00-1 1v1H5a1 1 0 000 2h10a1 1 0 100-2h-2V5a1 1 0 00-1-1H8zm3 2H9v1h2V6zM5 9a1 1 0 011-1h8a1 1 0 011 1v6a2 2 0 01-2 2H7a2 2 0 01-2-2V9zm2 2a1 1 0 102 0v3a1 1 0 10-2 0V11zm4 0a1 1 0 102 0v3a1 1 0 10-2 0V11z" 
                                    clip-rule="evenodd" 
                                />
                                </svg>
                            </button>
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
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6 mr-1" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
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
                        
                    // <!-- Comment Section -->
                    // <div class="border-t border-gray-300 pt-4">
                    //     <input type="text" id="comment-input-${post.post_id}" placeholder="Write a comment..." 
                    //         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" />
                    //     <button onclick="submitComment(${post.post_id}, document.getElementById('comment-input-${post.post_id}').value)" 
                    //             class="mt-2 w-full bg-green-500 text-white rounded-md px-3 py-1 hover:bg-green-600">
                    //         Submit
                    //     </button>
                    //     <div id="comments-list-${post.post_id}" class="mt-4 space-y-2">
                    //         <!-- Comments will be appended here -->
                    //     </div>
                    // </div>
                    
                    document.getElementById('postList').innerHTML = html;
         // Insert into the DOM
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // Call loadData on page load
        document.addEventListener('DOMContentLoaded', loadData);
    </script>
</head>
<body class="bg-[#F9DBBA] font-mono min-h-screen">
    <?php include 'navbar.php'; ?>
    <main class="flex min-h-screen">
        <!-- Fixed Sidebar -->
        <div class="flex-none bg-white px-4 pt-14 w-64 h-screen sticky top-0 overflow-auto">
            <img src="https://avatar.iran.liara.run/public" class="rounded-xl inline h-52 pb-4">
            <p>Name: <span id="name"></span></p>
            <p>Occupation: <span id="occupation"></span></p>
            <p>Email: <span id="email"></span></p>
            <p>Birth Date: <span id="birthDate"></span></p>
            <p>Location: <span id="birthPlace"></span></p>
            <p>About Me: <span id="aboutMe"></span></p>
        </div>

        <!-- Scrollable Content -->
        <div id="postList" class="flex-1 flex flex-wrap pt-14 justify-center overflow-auto h-screen">
            <!-- Dynamic Posts will be populated here -->
        </div>
    </main>

<script>
    async function removePost(id){
        if (confirm("Are you sure you want to delete this post?")) {
        // User clicked "OK" (Yes)
        const response = await fetch(
        `./api/removePost.php`,
        {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json',
            },
            body: JSON.stringify({id: id}),
        }
            );

        const result = await response.json();
        console.log(result);

        if (result.status === 'success') {
            window.location.href = 'profile.php';
        } else {
            alert(result.message);
        }
        }
    }
</script>
</body>
</html>
