<?php
header('Access-Control-Allow-Origin: *'); // Allow all origins, or specify your frontend domain
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
// api/like.php
session_start();
require_once './db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $userId = $_SESSION['user_id']  ?? 0;  // ID of the user who liked
    // $userId = 21;  // ID of the user who liked
    $postId = $_POST['postId'] ?? 0;  // ID of the liked post
    $liked = $_POST['liked'] ?? 0;  // ID of the liked post

    // Query to update the like count (example)
    
    echo json_encode(['status' => 'success', 'likes' => $liked]);
    return;
   if ($liked == 0 || $liked == '0') {
        // Remove the like from the database
        $stmt = $pdo->prepare('DELETE FROM likes WHERE post_id = :post_id AND reactor_id = :reactor_id');
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':reactor_id', $userId, PDO::PARAM_INT);
    } else {
        // Add the like to the database
        $stmt = $pdo->prepare('INSERT INTO likes (post_id, reactor_id) VALUES (:post_id, :reactor_id)');
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':reactor_id', $userId, PDO::PARAM_INT);
    }


    if ($stmt->execute()) {
        // Fetch updated like count
        $stmt = $pdo->prepare('SELECT COUNT(id) as like_count FROM likes WHERE post_id = :post_id');
        $stmt->execute(['post_id' => $postId]);
        $likeCount = $stmt->fetchColumn();

        echo json_encode(['status' => 'success', 'likes' => $likeCount]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unable to like post']);
    }
}
