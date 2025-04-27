<?php
// createPost.php
session_start();
require_once './db.php';

// Check if the form was submitted and the image file was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $header = $_POST['header'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'] ?? null; // Make sure the user is logged in

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        exit();
    }

    // Set up image directory and file path
    $targetDir = "../uploads/images/";
    $fileName = basename($_FILES['image']['name']);
    $targetFilePath = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Validate the file type (accept only specific types)
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowedTypes)) {
        // Move uploaded file to the server
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            // Save the post data with the image path
            $stmt = $pdo->prepare('INSERT INTO posts (header, content, image_path, user_id) VALUES (:header, :content, :image_path, :user_id)');
            if ($stmt->execute([
                'header' => $header,
                'content' => $content,
                'image_path' => "uploads/images/$fileName",  // Store relative path
                'user_id' => $user_id,
            ])) {
                echo json_encode(['status' => 'success', 'message' => 'Post created successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save post data.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
