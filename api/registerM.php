<?php
// api/register.php
session_start();
require_once './db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $birthPlace = $_POST['birthPlace'] ?? '';
    $birthDate = $_POST['birthDate'] ?? '';
    $aboutMe = $_POST['aboutMe'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Username and password are required.']);
        exit();
    }

    // Check if email already exists
    $stmt = $pdo->prepare('SELECT * FROM credentials WHERE email = :email');
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Email Address is already taken.']);
        exit();
    }
    
    $stmt = $pdo->prepare('INSERT INTO users (name, occupation,birthPlace,birthDate,aboutMe) 
                            VALUES (:name, :occupation, :birthPlace, :birthDate, :aboutMe)');
    if ($stmt->execute(['name' => $name,
     'occupation' => $occupation,
      "birthPlace" => $birthPlace,
       "birthDate" => $birthDate,
        "aboutMe" => $aboutMe])) {
        $userId = $pdo->lastInsertId();

            // Insert new user with hashed password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO credentials (email, password, user_id) VALUES (:email, :password, :user_id)');

        if ($stmt->execute(['email' => $email, 'password' => $hashedPassword, 'user_id'=> $userId])) {
            $userId = $pdo->lastInsertId();

            echo json_encode(['status' => 'success', 'message' => 'Registration successful.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Registration failed.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed.']);
    }
}
?>
