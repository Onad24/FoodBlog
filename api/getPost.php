<?php
// api/getPost.php
session_start();
require_once './db.php';

if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];

    // Query to fetch the post based on post_id
    $stmt = $pdo->prepare('SELECT p.id, p.header, p.content, p.image_path, u.name AS author
                           FROM posts p
                           JOIN users u ON p.user_id = u.id
                           WHERE p.id = :post_id');
    $stmt->execute(['post_id' => $postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        // Fetch comments for the post if needed
        $stmt = $pdo->prepare('SELECT f.feedback, u.name, f.feedback_date FROM (SELECT * FROM feedbacks WHERE post_id = :post_id) as f 
                           JOIN users u ON f.react_id = u.id ORDER BY f.feedback_date DESC');
        $stmt->execute(['post_id' => $postId]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare('SELECT count(id) FROM likes WHERE post_id = :post_id');
        $stmt->execute(['post_id' => $postId]);
        $likeCount = $stmt->fetchColumn();

        // Send the post data with comments as a response
        echo json_encode([
            'status' => 'success',
            'likes' => $likeCount,
            'post' => $post,
            'comments' => $comments
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Post not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid post ID']);
}
?>
