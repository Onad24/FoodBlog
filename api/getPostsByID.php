<?php
// api/getPost.php
session_start();
require_once './db.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Query to fetch the post based on post_id

    $stmt = $pdo->prepare("SELECT p.id as post_id,p.image_path, u.name, p.content, p.header, l.like_count as likes, f.comment_count as comments FROM 
    (SELECT * FROM posts WHERE user_id = :userID and archive = 0) as p 
    inner join users as u on p.user_id = u.id
    left join (SELECT MIN(post_id) as post_id, COUNT(id) as like_count from likes GROUP BY post_id) as l on p.id = l.post_id
    left join (SELECT MIN(post_id) as post_id, COUNT(id) as comment_count from feedbacks GROUP BY post_id) as f on p.id=f.post_id");

    $stmt->execute(['userID' => $userId]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM (SELECT * FROM users where id = :user_id) as u inner join credentials as c on u.id = c.user_id");

    $stmt->execute(['user_id' => $userId]);
    $info = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode(['info' => $info, 'data' => $users]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid post ID']);
}
?>
