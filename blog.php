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
    // Function to fetch and display a single post based on post_id
    async function fetchPost(postId) {
        try {
            const response = await fetch(`./api/getPost.php?post_id=${postId}`);
            const data = await response.json();
            if (data.status === 'success') {
                const post = data.post;
                const comments = data.comments;
                const likes = data.likes;

                // Update post information on the page
                document.getElementById('post-header').textContent = post.header;
                document.getElementById('post-author').textContent = `By ${post.author}`;
                let dataContent = JSON.parse(post.content);
                let ingContent = document.getElementById('ingContent');
                let prcContent = document.getElementById('prcContent');
               
                dataContent.ingredients.forEach((data)=>{
                    let ingElement = document.createElement("li");
                    ingElement.textContent = data;  
                    ingContent.appendChild(ingElement);
                });

                 dataContent.process.forEach((data)=>{
                    let prcElement = document.createElement("li");
                    prcElement.textContent = data;  
                    prcContent.appendChild(prcElement);
                });

                document.getElementById('post-likes').textContent = likes;
                document.getElementById('post-image').src = `./${post.image_path}`;

                const button = document.createElement("button");
                button.textContent = "Submit";
                button.className = "mt-2 w-full bg-green-500 text-white rounded-md px-3 py-1 hover:bg-green-600";
                button.addEventListener("click", () => {
                    const inputValue = document.getElementById(`comment-input`).value;
                    submitComment(postId, inputValue);
                });
                document.getElementById("buttonContainer").appendChild(button); // Replace `button-container` with the actual container ID.


                // Display comments
                const commentsList = document.getElementById('comments-list');
                commentsList.innerHTML = ''; // Clear previous comments
                comments.forEach(comment => {
                    const commentElement = document.createElement('div');
                    commentElement.classList.add('bg-gray-100', 'p-3', 'rounded-lg', 'mb-2');
                    commentElement.innerHTML = `<p class="text-gray-700">${comment.feedback}</p>
                        <p class="text-xs text-gray-500">By ${comment.name} on ${new Date(comment.feedback_date).toLocaleString()}</p>`;
                    commentsList.appendChild(commentElement);
                });
            } else {
                console.error(data.message);
            }
        } catch (error) {
            console.error('Error fetching post:', error);
        }
    }

    // Call the fetchPost function when the page loads, passing in the post_id (example: 1)
    window.onload = () => {
        const postId = new URLSearchParams(window.location.search).get('id');
        if (postId) {
            fetchPost(postId);
        } else {
            console.error('No post ID provided');
        }
    };
</script>
</head>
<body class="bg-[#F9DBBA] font-mono">
 <?php include 'navbar.php'; ?>

<main class="my-16 flex items-center justify-center min-h-screen">
    <div class="w-2/3 mx-auto p-4 bg-white rounded shadow-lg">
        <!-- Post Image -->
        <div>
            <img id="post-image" class="w-full h-96 object-cover rounded-t-lg" alt="Post Image">
        </div>
        <!-- Post Title, Author, and Content -->
        <div class="p-4">
            <h2 id="post-header" class="text-2xl font-semibold text-gray-800"></h2>
            <p id="post-author" class="text-sm text-gray-600"></p>
            <div id="post-content" class="text-gray-700 mt-2">
                <h3>Ingredients</h3>
                <ul id="ingContent" class="list-disc ml-8"></ul>
                <h3>Process</h3>
                <ol id="prcContent" class="list-decimal ml-8"></ol>
            </div>
        </div>
        <!-- Comments Section -->
        <div class="p-4">
            <div class="flex items-center">
                <span id="post-likes" class="text-gray-600 mr-1"></span> Likes
            </div>
            <div class="border-t border-gray-300 pt-4">
                <h3 class="text-lg font-semibold text-gray-800">Comments</h3>
                <input type="text" id="comment-input" placeholder="Write a comment..." 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" />
                <div id="buttonContainer">
                    <!-- button here -->
                </div>
                <div id="comments-list" class="mt-4 space-y-2">
                        <!-- Comments will be appended here -->
                </div>
            </div>
        </div>
    </div>
</main>
</html>





