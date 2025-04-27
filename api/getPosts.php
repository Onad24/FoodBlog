<?php
// api/getUsers.php
session_start();
require_once './db.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null; // Make sure the user is logged in

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        exit();
    }

$stmt = $pdo->prepare("SELECT p.id as post_id,p.image_path, u.name, p.content, p.header, l.like_count as likes, l.contains_id , f.comment_count as comments FROM 
(SELECT * FROM posts where archive = 0) as p 
inner join users as u on p.user_id = u.id
left join (SELECT MIN(post_id) as post_id, COUNT(id) as like_count, MAX(CASE WHEN reactor_id = :user_id THEN 1 ELSE 0 END) AS contains_id from likes GROUP BY post_id) as l on p.id = l.post_id
left join (SELECT MIN(post_id) as post_id, COUNT(id) as comment_count from feedbacks GROUP BY post_id) as f on p.id=f.post_id");

$stmt->execute(['user_id' => $user_id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo json_encode($users);
?>  