<?php
require __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $user = current_user_or_null();
    
    if (!$user) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Not authenticated'
        ]);
        exit;
    }
    
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Admin access required'
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'user' => $user
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error'
    ]);
}
?>
