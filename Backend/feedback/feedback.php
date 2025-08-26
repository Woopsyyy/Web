<?php
// Start output buffering to prevent any unexpected output
ob_start();

require __DIR__ . '/../db.php';

// Get PDO connection
$pdo = get_pdo();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication required']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON data']);
        exit;
    }
    
    $type = $input['type'] ?? '';
    $rating = $input['rating'] ?? null;
    $message = $input['message'] ?? '';
    $user_id = $_SESSION['user_id'];
    
    if ($type === 'rating' && $rating !== null) {
        // Handle rating submission
        try {
            $stmt = $pdo->prepare('
                INSERT INTO feedback (user_id, type, rating, message, created_at) 
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
            ');
            $stmt->execute([$user_id, 'rating', $rating, $message]);
            
            echo json_encode(['success' => true, 'message' => 'Rating submitted successfully']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to submit rating: ' . $e->getMessage()]);
        }
    } elseif ($type === 'feedback' && !empty($message)) {
        // Handle feedback submission
        try {
            $stmt = $pdo->prepare('
                INSERT INTO feedback (user_id, type, rating, message, created_at) 
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
            ');
            $stmt->execute([$user_id, 'feedback', null, $message]);
            
            echo json_encode(['success' => true, 'message' => 'Feedback submitted successfully']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to submit feedback: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data provided']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

// Flush the output buffer
ob_end_flush();
