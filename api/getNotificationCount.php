<?php
// CORS headers to allow cross-origin requests
header("Access-Control-Allow-Origin: *"); // Allows requests from any origin
header("Access-Control-Allow-Methods: GET, POST"); // Defines allowed methods
header("Access-Control-Allow-Headers: Content-Type"); // Allows specific headers


session_start();
require_once './db.php';


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$markAsRead = isset($_GET['markAsRead']) && $_GET['markAsRead'] === 'true';

// Get the user's last notification click time
$stmt = $pdo->prepare('SELECT last_notification_click FROM users WHERE id = :user_id');
$stmt->execute(['user_id' => $user_id]);
$lastClickTime = $stmt->fetchColumn();

// Fetch new notifications since the last click
if ($lastClickTime) {
    
    // $stmt = $pdo->prepare('SELECT COUNT(id) FROM posts WHERE date_added > :lastClickTime');

    $stmt = $pdo->prepare('SELECT p.post_count, u.name, u.id FROM (SELECT COUNT(id) as post_count, MIN(user_id) as user_id from posts where 
    date_added > :lastClicktime and user_id != :userID GROUP BY user_id) as p join users as u on p.user_id = u.id');
    $stmt->execute(['lastClicktime' => $lastClickTime, 'userID' => $user_id]);

    // $stmt->execute(['lastClickTime' => $lastClickTime]);
} else {
    // If no last click time, count all unread notifications
    $stmt = $pdo->prepare('SELECT p.post_count, u.name, u.id FROM (SELECT COUNT(id) as post_count, MIN(user_id) as user_id from posts where 
    user_id != :userID GROUP BY user_id) as p join users as u on p.user_id = u.id');
    $stmt->execute(['userID' => $user_id]);
}

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update the last notification click time
if ($markAsRead) {
$stmt = $pdo->prepare('UPDATE users SET last_notification_click = NOW() WHERE id = :user_id');
$stmt->execute(['user_id' => $user_id]);
}



echo json_encode($data);
?>
