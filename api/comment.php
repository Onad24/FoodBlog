<?php
// api/comment.php
session_start();
require_once './db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'] ?? 0;  // Retrieve user ID from session
    $postId = $_POST['postId'] ?? 0;       // Post ID from the request
    $commentText = $_POST['comment'] ?? ''; // Comment text from the request

    if (empty($commentText)) {
        echo json_encode(['status' => 'error', 'message' => 'Comment cannot be empty']);
        exit();
    }

    // Insert the comment into the database
    $stmt = $pdo->prepare('INSERT INTO feedbacks (post_id, react_id, feedback) VALUES (:post_id, :react_id, :feedback)');
    if ($stmt->execute(['post_id' => $postId, 'react_id' => $userId, 'feedback' => $commentText])) {
        $commentId = $pdo->lastInsertId();
        $stmt = $pdo->prepare('SELECT f.id, u.name, f.feedback, f.feedback_date FROM feedbacks as f JOIN users as u on f.react_id = u.id WHERE f.id = :id');
        $stmt->execute(['id' => $commentId]);
        $newComment = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'comment' => $newComment]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add comment']);
    }
}
