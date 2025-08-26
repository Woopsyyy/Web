<?php
require __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$input = $_POST;

$name = trim($input['name'] ?? '');
$username = trim($input['username'] ?? '');
$email = trim($input['email'] ?? '');
$password = (string)($input['password'] ?? '');

if ($name === '' || $username === '' || $email === '' || $password === '') {
    json_response(['error' => 'All fields are required'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(['error' => 'Invalid email'], 400);
}

if (strlen($password) < 6) {
    json_response(['error' => 'Password must be at least 6 characters'], 400);
}

try {
    $pdo = get_pdo();

    // Check duplicates
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        json_response(['error' => 'Username or email already exists'], 409);
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO users (name, username, email, password_hash) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $username, $email, $passwordHash]);

    $userId = (int)$pdo->lastInsertId();
    $_SESSION['user_id'] = $userId;

    $stmt = $pdo->prepare('SELECT id, name, username, email, role, created_at, updated_at FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    json_response(['user' => $user]);
} catch (Throwable $e) {
    json_response(['error' => 'Server error'], 500);
}


