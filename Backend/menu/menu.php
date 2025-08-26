<?php
require __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = get_pdo();

    // Get all dishes from database; be tolerant if 'tags' column does not exist
    try {
        $stmt = $pdo->query('SELECT id, title, description, image, category, price, ingredients, prep_time, tags FROM dishes ORDER BY category, title');
        $dishes = $stmt->fetchAll();
    } catch (Throwable $t) {
        // Fallback for older schema without tags column
        $stmt = $pdo->query('SELECT id, title, description, image, category, price, ingredients, prep_time FROM dishes ORDER BY category, title');
        $dishes = $stmt->fetchAll();
        foreach ($dishes as &$row) {
            if (!isset($row['tags'])) {
                $row['tags'] = null;
            }
        }
        unset($row);
    }

                // Group dishes by category
                $menu = [
                    'main' => [],
                    'appetizer' => [],
                    'dessert' => [],
                    'beverage' => []
                ];

                foreach ($dishes as $dish) {
                    // Add to appropriate category
                    $menu[$dish['category']][] = $dish;
                }
    
    echo json_encode([
        'success' => true,
        'menu' => $menu,
        'total_dishes' => count($dishes)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to load menu'
    ]);
}
?>
