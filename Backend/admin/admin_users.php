<?php
// Start output buffering to prevent any unexpected output
ob_start();

require __DIR__ . '/../db.php';

// Get PDO connection
$pdo = get_pdo();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication required']);
    exit;
}

// Check if user is admin
try {
    $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user || $user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Admin access required']);
        exit;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all users
    try {
        $stmt = $pdo->prepare('
            SELECT id, name, username, email, role, created_at, updated_at
            FROM users
            ORDER BY created_at DESC
        ');
        $stmt->execute();
        $users = $stmt->fetchAll();
        
        echo json_encode(['success' => true, 'users' => $users]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch users: ' . $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update user role (promote/demote admin)
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON data']);
        exit;
    }
    
    $user_id = $input['user_id'] ?? null;
    $new_role = $input['role'] ?? null;
    
    if (!$user_id || !$new_role) {
        http_response_code(400);
        echo json_encode(['error' => 'User ID and role are required']);
        exit;
    }
    
    if (!in_array($new_role, ['user', 'admin'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid role']);
        exit;
    }
    
    // Prevent admin from demoting themselves
    if ($user_id == $_SESSION['user_id'] && $new_role === 'user') {
        http_response_code(400);
        echo json_encode(['error' => 'Cannot demote yourself from admin']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare('UPDATE users SET role = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
        $stmt->execute([$new_role, $user_id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'User role updated successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update user role: ' . $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete user
    $user_id = $_GET['id'] ?? null;
    
    if (!$user_id) {
        http_response_code(400);
        echo json_encode(['error' => 'User ID required']);
        exit;
    }
    
    // Prevent admin from deleting themselves
    if ($user_id == $_SESSION['user_id']) {
        http_response_code(400);
        echo json_encode(['error' => 'Cannot delete yourself']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete user: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

// Flush the output buffer
ob_end_flush();
