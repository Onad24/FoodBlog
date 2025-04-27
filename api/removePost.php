<?php
// createPost.php
session_start();
require_once './db.php';

// Check if the form was submitted and the image file was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    $id = $data['id'];

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        exit();
    }


    $stmt = $pdo->prepare('UPDATE posts set archive = 1 where id = :id');
    if ($stmt->execute([
        'id' => $id,
    ])) {
        echo json_encode(['status' => 'success', 'message' => 'Post delete successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete post data.']);
    }
}
?>
