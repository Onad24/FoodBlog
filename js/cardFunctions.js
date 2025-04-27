// Async function to handle the "like" button click
async function likePost(postId) {
  try {
    if (
      document.getElementById(`like-button-${postId}`).textContent == 'Liked'
    ) {
      liked = 0;
    } else {
      liked = 1;
    }

    const response = await fetch('./api/like.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ postId: postId, liked: liked }),
    });

    // Check if the response is ok (status 200-299)
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const text = await response.text();

    const result = JSON.parse(text);

    if (result.status === 'success') {
      // Update the UI elements on success
      document.getElementById(`like-count-${postId}`).textContent =
        result.likes;
      if (liked == 1) {
        document.getElementById(`like-button-${postId}`).textContent = 'Liked';
      } else {
        document.getElementById(
          `like-button-${postId}`
        ).innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6 mr-1" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>`;
      }
    } else {
      console.error('Error:', result.message);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}

async function submitComment(postId, commentText) {
  try {
    const response = await fetch('./api/comment.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ postId: postId, comment: commentText }),
    });

    const text = await response.text();

    const result = JSON.parse(text);

    if (result.status === 'success') {
      const commentsList = document.getElementById(`comments-list`);
      const commentElement = document.createElement('div');
      commentElement.classList.add('bg-gray-100', 'p-3', 'rounded-lg', 'mb-2');
      commentElement.innerHTML = `<p class="text-gray-700">${
        result.comment.feedback
      }</p>
                        <p class="text-xs text-gray-500">By ${
                          result.comment.name
                        } on ${new Date(
        result.comment.feedback_date
      ).toLocaleString()}</p>`;
      commentsList.appendChild(commentElement);

      document.getElementById(`comment-input`).value = '';
    } else {
      console.error('Error:', result.message);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}

function redirectToPost(postId) {
  // Navigate to the dedicated post page with the post ID as a URL parameter
  window.location.href = `blog.php?id=${postId}`;
}
