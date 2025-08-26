<?php
require __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = get_pdo();
    
    // Check for duplicates by title
    $stmt = $pdo->query('
        SELECT title, COUNT(*) as count, GROUP_CONCAT(id) as ids
        FROM dishes 
        GROUP BY title 
        HAVING COUNT(*) > 1
        ORDER BY count DESC
    ');
    $duplicates = $stmt->fetchAll();
    
    // Get all dishes for reference
    $stmt = $pdo->query('SELECT id, title, category, price FROM dishes ORDER BY title');
    $allDishes = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'duplicates' => $duplicates,
        'total_dishes' => count($allDishes),
        'all_dishes' => $allDishes
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
?>
