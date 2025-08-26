<?php
require __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$identifier = trim((string)($_POST['identifier'] ?? ''));
$password = (string)($_POST['password'] ?? '');

if ($identifier === '' || $password === '') {
    json_response(['error' => 'Identifier and password are required'], 400);
}

try {
    $pdo = get_pdo();
    
    $stmt = $pdo->prepare('SELECT id, name, username, email, role, password_hash, created_at, updated_at FROM users WHERE username = ? OR email = ? LIMIT 1');
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if (!$user) {
        json_response(['error' => 'Invalid credentials'], 401);
    }
    
    if (!password_verify($password, $user['password_hash'])) {
        json_response(['error' => 'Invalid credentials'], 401);
    }

    $_SESSION['user_id'] = (int)$user['id'];

    unset($user['password_hash']);
    json_response(['user' => $user]);
} catch (Throwable $e) {
    json_response(['error' => 'Server error'], 500);
}


