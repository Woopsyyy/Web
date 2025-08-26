<?php
require __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

// Check admin authentication first
$user = current_user_or_null();
if (!$user || $user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Admin access required']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    $pdo = get_pdo();
    
    switch ($method) {
        case 'GET':
            // Get all menu items
            $stmt = $pdo->query('SELECT * FROM dishes ORDER BY category, title');
            $dishes = $stmt->fetchAll();
            echo json_encode(['success' => true, 'dishes' => $dishes]);
            break;
            
        case 'POST':
            // Create new menu item
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                json_response(['success' => false, 'error' => 'Invalid JSON data'], 400);
            }
            
            $required_fields = ['title', 'description', 'image', 'category', 'price', 'ingredients', 'prep_time'];
            foreach ($required_fields as $field) {
                if (empty($data[$field])) {
                    json_response(['success' => false, 'error' => "Missing required field: $field"], 400);
                }
            }
            
            // Try to insert with tags; fall back without tags if column is missing
            try {
                $stmt = $pdo->prepare('INSERT INTO dishes (title, description, image, category, price, ingredients, prep_time, tags) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([
                    $data['title'],
                    $data['description'],
                    $data['image'],
                    $data['category'],
                    $data['price'],
                    $data['ingredients'],
                    $data['prep_time'],
                    $data['tags'] ?? null
                ]);
            } catch (Throwable $t) {
                $stmt = $pdo->prepare('INSERT INTO dishes (title, description, image, category, price, ingredients, prep_time) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([
                    $data['title'],
                    $data['description'],
                    $data['image'],
                    $data['category'],
                    $data['price'],
                    $data['ingredients'],
                    $data['prep_time']
                ]);
            }
            
            $dish_id = $pdo->lastInsertId();
            echo json_encode(['success' => true, 'id' => $dish_id, 'message' => 'Menu item created successfully']);
            break;
            
        case 'PUT':
            // Update menu item
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || !isset($data['id'])) {
                json_response(['success' => false, 'error' => 'Invalid data or missing ID'], 400);
            }
            
            $required_fields = ['title', 'description', 'image', 'category', 'price', 'ingredients', 'prep_time'];
            foreach ($required_fields as $field) {
                if (empty($data[$field])) {
                    json_response(['success' => false, 'error' => "Missing required field: $field"], 400);
                }
            }
            
            // Try to update with tags; fall back without tags if column is missing
            try {
                $stmt = $pdo->prepare('UPDATE dishes SET title = ?, description = ?, image = ?, category = ?, price = ?, ingredients = ?, prep_time = ?, tags = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
                $stmt->execute([
                    $data['title'],
                    $data['description'],
                    $data['image'],
                    $data['category'],
                    $data['price'],
                    $data['ingredients'],
                    $data['prep_time'],
                    $data['tags'] ?? null,
                    $data['id']
                ]);
            } catch (Throwable $t) {
                $stmt = $pdo->prepare('UPDATE dishes SET title = ?, description = ?, image = ?, category = ?, price = ?, ingredients = ?, prep_time = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
                $stmt->execute([
                    $data['title'],
                    $data['description'],
                    $data['image'],
                    $data['category'],
                    $data['price'],
                    $data['ingredients'],
                    $data['prep_time'],
                    $data['id']
                ]);
            }
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Menu item updated successfully']);
            } else {
                json_response(['success' => false, 'error' => 'Menu item not found'], 404);
            }
            break;
            
        case 'DELETE':
            // Delete menu item
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                json_response(['success' => false, 'error' => 'Missing ID parameter'], 400);
            }
            
            $stmt = $pdo->prepare('DELETE FROM dishes WHERE id = ?');
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Menu item deleted successfully']);
            } else {
                json_response(['success' => false, 'error' => 'Menu item not found'], 404);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>
